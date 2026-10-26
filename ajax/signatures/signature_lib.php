<?php
// Staff signatures and the school stamp.
//
// The Principal (auth 1) and the Deputy Principals (Academics 2, Administration 3)
// can store a signature; the college can store one stamp. Both are transparent PNGs kept in
// signatures_store/<school code>/, a folder blocked from direct web access, and the
// PDF generators read them straight from disk.
//
// Per-school choices live in the school DB `settings` table under
// sett = 'signature_stamp_config' as JSON:
//   stamp_file           relative path of the stamp PNG ('' when none)
//   stamp_documents      document keys (SIG_STAMP_DOCUMENTS) that print the stamp
//   print_signatures     "1" to print staff signatures on receipts and transcripts
//   signing_headteacher  user_id whose signature prints as the principal's
//   signing_deputy       user_id whose signature prints as the deputy principal's,
//                        and in place of the principal's when that one is missing
//   receipt_style        'new' (navy and gold) or 'classic' receipts

define('SIG_STORE_DIR', 'signatures_store');
define('SIG_MAX_BYTES', 2 * 1024 * 1024);
define('SIG_MAX_SIDE', 1600);
define('SIG_ELIGIBLE_ROLES', ['1', '2', '3']);
// roles that can be picked as the signing deputy principal
define('SIG_DEPUTY_ROLES', ['2', '3']);
// transcripts get the stamp tilted like a hand-pressed one
define('SIG_REPORT_CARD_STAMP_ANGLE', 20);

// Every document that can carry the school stamp, in the order the Settings table lists them.
const SIG_STAMP_DOCUMENTS = [
    'fees_receipt' => ['Fee payment receipts', 'Receipts printed after a payment, reprints, and receipts opened from the SMS link'],
    'transcript'   => ['Transcripts', 'Semester-wise and year-wise student transcripts'],
    'exam_marks'   => ['Exam and CAT mark sheets', 'Student exam and CAT performance sheets, and blank mark sheets'],
];

function sig_project_root() {
    return dirname(__DIR__, 2);
}

// Adds user_tbl.signature_loc on servers that have not had the column added yet.
function sig_ensure_column($conn) {
    static $done = false;
    if ($done) return;
    $result = $conn->query("SHOW COLUMNS FROM `user_tbl` LIKE 'signature_loc'");
    if ($result && $result->num_rows == 0) {
        $conn->query("ALTER TABLE `user_tbl` ADD COLUMN `signature_loc` varchar(500) DEFAULT NULL COMMENT 'Path to the staff signature PNG (principal, deputy principals)' AFTER `profile_loc`");
    }
    $done = true;
}

function sig_default_config() {
    return [
        'stamp_file' => '',
        'stamp_documents' => [],
        'print_signatures' => '1',
        'signing_headteacher' => '',
        'signing_deputy' => '',
        // receipt design: 'new' (navy and gold) or 'classic'; print windows may override it
        'receipt_style' => 'new',
    ];
}

// $fresh skips the per-request cache (the Settings endpoint reads, changes and saves).
function sig_get_config($conn2, $fresh = false) {
    static $cache = null;
    if ($cache !== null && !$fresh) return $cache;
    $config = sig_default_config();
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'signature_stamp_config' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $saved = json_decode($row['valued'], true);
        if (is_array($saved)) {
            $config = array_merge($config, array_intersect_key($saved, $config));
        }
    }
    if (!is_array($config['stamp_documents'])) $config['stamp_documents'] = [];
    $cache = $config;
    return $config;
}

function sig_save_config($conn2, $config) {
    $valued = json_encode(array_merge(sig_default_config(), $config));
    $stmt = $conn2->prepare("SELECT `id` FROM `settings` WHERE `sett` = 'signature_stamp_config' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->fetch_assoc()) {
        $stmt = $conn2->prepare("UPDATE `settings` SET `valued` = ? WHERE `sett` = 'signature_stamp_config'");
    } else {
        $stmt = $conn2->prepare("INSERT INTO `settings` (`sett`, `valued`) VALUES ('signature_stamp_config', ?)");
    }
    $stmt->bind_param("s", $valued);
    return $stmt->execute();
}

// Whoever can see a sidebar button can change everything in the window it opens.
// Mirrors allowed() in homepage.php: each built in role has a fixed list of
// buttons, and a custom role gets the buttons ticked for it in user_roles.
// (idleLockCanEditSettings() in ajax/login/idle_lock.php does the same for Settings.)
const SIG_ROLE_BUTTONS = [
    'manage_staff' => ['0', '1', '3', '4', '6'],
    'settings' => ['0', '1'],
];
function sig_menu_allows($conn2, $button) {
    $auth = (string) ($_SESSION['auth'] ?? '');
    if ($auth === '') return false;
    if (in_array($auth, ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], true)) {
        return in_array($auth, SIG_ROLE_BUTTONS[$button] ?? [], true);
    }
    $allowed = false;
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'user_roles' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $roles = json_decode($row['valued'] ?? '[]', true);
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ((string) ($role['name'] ?? '') !== $auth) continue;
                foreach (($role['roles'] ?? []) as $perm) {
                    if (($perm['name'] ?? '') === $button) $allowed = ($perm['Status'] ?? '') === 'yes';
                }
            }
        }
    }
    return $allowed;
}

// Anyone with the Manage Staff button can upload signatures for other staff members.
function sig_can_manage_staff_signatures($conn2) {
    return sig_menu_allows($conn2, 'manage_staff');
}

// Anyone with the Settings button manages the stamp, the stamped documents and
// whose principal/deputy principal signature prints.
function sig_can_edit_settings($conn2) {
    return sig_menu_allows($conn2, 'settings');
}

function sig_absolute_path($relative) {
    $relative = (string) $relative;
    if ($relative === '' || strpos($relative, '..') !== false) return null;
    if (strpos($relative, SIG_STORE_DIR . '/') !== 0) return null;
    $absolute = sig_project_root() . '/' . $relative;
    return is_file($absolute) ? $absolute : null;
}

function sig_school_dir($school_code) {
    $code = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $school_code);
    $root = sig_project_root() . '/' . SIG_STORE_DIR;
    if (!is_dir($root)) @mkdir($root, 0775, true);
    // keep the folder unreachable from the web even if the committed .htaccess is missing
    if (!is_file($root . '/.htaccess')) @file_put_contents($root . '/.htaccess', "Require all denied\n");
    $dir = $root . '/' . $code;
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    return [$dir, SIG_STORE_DIR . '/' . $code];
}

// Decodes a "data:image/png;base64,..." string from the browser editor, checks it
// is a real image of sane size, and re-encodes it with GD so only a clean PNG
// (alpha kept) is ever written. Returns [png bytes, null] or [null, error].
function sig_clean_png_from_data_url($data_url) {
    if (!preg_match('/^data:image\/png;base64,([A-Za-z0-9+\/=\r\n]+)$/', (string) $data_url, $m)) {
        return [null, 'The image was not received correctly. Please try again.'];
    }
    $raw = base64_decode($m[1], true);
    if ($raw === false || strlen($raw) == 0) return [null, 'The image was not received correctly. Please try again.'];
    if (strlen($raw) > SIG_MAX_BYTES) return [null, 'The image is too large. Crop it closer to the ink and try again.'];
    $info = @getimagesizefromstring($raw);
    if (!$info || $info[2] !== IMAGETYPE_PNG) return [null, 'Only PNG images from the editor are accepted.'];
    if ($info[0] < 10 || $info[1] < 10) return [null, 'The image is too small. Draw or crop a bigger area.'];
    if ($info[0] > SIG_MAX_SIDE || $info[1] > SIG_MAX_SIDE) return [null, 'The image is too big. Crop it closer to the ink and try again.'];
    if (!function_exists('imagecreatefromstring')) {
        // no GD on this server: keep only the chunks that carry the picture
        $png = sig_rebuild_png($raw);
        if ($png === null) return [null, 'The image could not be read. Please try again.'];
        return [$png, null];
    }
    $img = @imagecreatefromstring($raw);
    if (!$img) return [null, 'The image could not be read. Please try again.'];
    imagealphablending($img, false);
    imagesavealpha($img, true);
    ob_start();
    imagepng($img, null, 9);
    $png = ob_get_clean();
    imagedestroy($img);
    return [$png, null];
}

// Without GD the PNG cannot be re-encoded, so walk its chunks instead: every CRC must
// match, and anything besides the image data itself (text, metadata, trailing bytes)
// is dropped. Returns the rebuilt PNG, or null if the file is not a well-formed PNG.
function sig_rebuild_png($raw) {
    $signature = "\x89PNG\r\n\x1a\n";
    if (substr($raw, 0, 8) !== $signature) return null;
    $keep = ['IHDR', 'PLTE', 'tRNS', 'gAMA', 'sRGB', 'IDAT', 'IEND'];
    $out = $signature;
    $offset = 8;
    $length_total = strlen($raw);
    $seen_end = false;
    while ($offset + 12 <= $length_total) {
        $length = unpack('N', substr($raw, $offset, 4))[1];
        $type = substr($raw, $offset + 4, 4);
        if ($length > $length_total - $offset - 12) return null;
        $data = substr($raw, $offset + 8, $length);
        $crc = unpack('N', substr($raw, $offset + 8 + $length, 4))[1];
        if ((crc32($type . $data) & 0xFFFFFFFF) !== $crc) return null;
        if (in_array($type, $keep, true)) {
            $out .= substr($raw, $offset, 12 + $length);
        }
        $offset += 12 + $length;
        if ($type === 'IEND') { $seen_end = true; break; }
    }
    if (!$seen_end || substr($out, 12, 4) !== 'IHDR' || strpos($out, 'IDAT') === false) return null;
    return $out;
}

function sig_store_png($school_code, $prefix, $png) {
    list($dir, $relative_dir) = sig_school_dir($school_code);
    // a folder the web server cannot write to: say so instead of printing a PHP warning into the JSON reply
    if (!is_dir($dir) || !is_writable($dir)) return null;
    $name = $prefix . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.png';
    if (@file_put_contents($dir . '/' . $name, $png) === false) return null;
    return $relative_dir . '/' . $name;
}

function sig_delete_file($relative) {
    $absolute = sig_absolute_path($relative);
    if ($absolute) @unlink($absolute);
}

function sig_data_url($relative) {
    $absolute = sig_absolute_path($relative);
    if (!$absolute) return null;
    return 'data:image/png;base64,' . base64_encode(file_get_contents($absolute));
}

// ---------------------------------------------------------------------------
// Lookups used by the PDF generators. All return an absolute file path or null.
// ---------------------------------------------------------------------------

function sig_user_signature_file($conn, $user_id) {
    static $cache = [];
    if ($user_id === null || $user_id === '') return null;
    $user_id = (string) $user_id;
    if (array_key_exists($user_id, $cache)) return $cache[$user_id];
    $cache[$user_id] = null;
    sig_ensure_column($conn);
    $stmt = $conn->prepare("SELECT `signature_loc` FROM `user_tbl` WHERE `user_id` = ? LIMIT 1");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $cache[$user_id] = sig_absolute_path($row['signature_loc'] ?? '');
    }
    return $cache[$user_id];
}

function sig_signatures_enabled($conn2) {
    return sig_get_config($conn2)['print_signatures'] === '1';
}

function sig_deputy_signature_file($conn, $conn2) {
    if (!sig_signatures_enabled($conn2)) return null;
    return sig_user_signature_file($conn, sig_get_config($conn2)['signing_deputy']);
}

// The selected principal's signature, or the selected deputy principal's when
// the principal has none.
function sig_headteacher_signature_file($conn, $conn2) {
    if (!sig_signatures_enabled($conn2)) return null;
    $config = sig_get_config($conn2);
    $file = sig_user_signature_file($conn, $config['signing_headteacher']);
    if ($file) return $file;
    return sig_user_signature_file($conn, $config['signing_deputy']);
}

function sig_stamp_file($conn2, $document_key) {
    $config = sig_get_config($conn2);
    if (!in_array($document_key, $config['stamp_documents'], true)) return null;
    return sig_absolute_path($config['stamp_file']);
}

// ---------------------------------------------------------------------------
// PDF placement
// ---------------------------------------------------------------------------

// Draws an image as large as fits inside the box, keeping its proportions,
// bottom-aligned (so a signature sits on its line) and left-aligned.
function sig_pdf_image_in_box($pdf, $file, $x, $y, $box_w, $box_h, $center = false) {
    if (!$file) return;
    $size = @getimagesize($file);
    if (!$size || $size[0] == 0 || $size[1] == 0) return;
    $ratio = $size[0] / $size[1];
    $w = $box_w;
    $h = $w / $ratio;
    if ($h > $box_h) {
        $h = $box_h;
        $w = $h * $ratio;
    }
    if ($center) $x += ($box_w - $w) / 2;
    $pdf->Image($file, $x, $y + ($box_h - $h), $w, $h, 'PNG');
}

// Draws the stamp at the right side of the page, centred on the current line so it
// overlaps the end of the document the way a rubber stamp would, and kept inside
// the printable area. Pass $right_edge for half-width layouts, and $angle (degrees,
// counter-clockwise) to tilt it like a hand-pressed stamp.
function sig_pdf_stamp($pdf, $file, $width = 35, $right_edge = null, $y = null, $angle = 0) {
    if (!$file) return;
    $size = @getimagesize($file);
    if (!$size || $size[0] == 0) return;
    $height = $width * $size[1] / $size[0];
    // the space the tilted stamp takes up, used to keep it on the page
    $rad = deg2rad($angle);
    $box_w = abs($width * cos($rad)) + abs($height * sin($rad));
    $box_h = abs($width * sin($rad)) + abs($height * cos($rad));
    if ($right_edge === null) $right_edge = $pdf->GetPageWidth() - 10;
    $center_x = $right_edge - 5 - $box_w / 2;
    $center_y = ($y === null) ? $pdf->GetY() : $y + $height / 2;
    $max_y = $pdf->GetPageHeight() - 12 - $box_h / 2;
    if ($center_y > $max_y) $center_y = $max_y;
    if ($center_y < 10 + $box_h / 2) $center_y = 10 + $box_h / 2;
    $x = $center_x - $width / 2;
    $top = $center_y - $height / 2;
    if ($angle == 0) {
        $pdf->Image($file, $x, $top, $width, $height, 'PNG');
        return;
    }
    // FPDF has no rotation, so wrap the image in a PDF transform about its centre;
    // _out() is protected, hence the bound closure
    $draw = function () use ($file, $x, $top, $width, $height, $rad, $center_x, $center_y) {
        $cx = $center_x * $this->k;
        $cy = ($this->h - $center_y) * $this->k;
        $c = cos($rad);
        $s = sin($rad);
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        $this->Image($file, $x, $top, $width, $height, 'PNG');
        $this->_out('Q');
    };
    Closure::bind($draw, $pdf, get_class($pdf))();
}
