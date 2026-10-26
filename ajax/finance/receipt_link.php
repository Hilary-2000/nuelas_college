<?php
// Signed receipt links. The link texted to parents carries the receipt number, the
// college database and a signature of the two, so changing the number in one link
// does not open someone else's receipt. Each college has its own random secret,
// kept in its `settings` table (sett = 'receipt_link_secret') as
// {"secret": "...", "since": "Y-m-d H:i:s"}, created the first time it is needed.
//
// Links sent before signing started ("since") have no signature. They still open for
// payments made in the RECEIPT_LINK_UNSIGNED_DAYS days before that, until those
// payments are older than that, so after a month no unsigned link opens anything.

define('RECEIPT_LINK_UNSIGNED_DAYS', 30);

function receipt_link_config($conn2)
{
    static $cache = [];
    $key = spl_object_id($conn2);
    if (isset($cache[$key])) return $cache[$key];
    $stmt = $conn2->prepare("SELECT `valued` FROM `settings` WHERE `sett` = 'receipt_link_secret' LIMIT 1");
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $config = $row ? json_decode($row['valued'], true) : null;
    if (!is_array($config) || strlen($config['secret'] ?? '') < 32 || empty($config['since'])) {
        $config = ['secret' => bin2hex(random_bytes(32)), 'since' => date("Y-m-d H:i:s")];
        $valued = json_encode($config);
        if ($row) {
            $stmt = $conn2->prepare("UPDATE `settings` SET `valued` = ? WHERE `sett` = 'receipt_link_secret'");
        } else {
            $stmt = $conn2->prepare("INSERT INTO `settings` (`sett`, `valued`) VALUES ('receipt_link_secret', ?)");
        }
        $stmt->bind_param("s", $valued);
        $stmt->execute();
    }
    return $cache[$key] = $config;
}

function receipt_link_signature($conn2, $receipt_id, $database)
{
    return substr(hash_hmac('sha256', $receipt_id . "|" . $database, receipt_link_config($conn2)['secret']), 0, 32);
}

// Whether a link may open this receipt: a matching signature, or no signature on a
// payment made in the month before signing started (its SMS went out unsigned).
function receipt_link_allowed($conn2, $receipt_id, $database, $signature, $payment_date)
{
    if (strlen((string) $signature) > 0) {
        return hash_equals(receipt_link_signature($conn2, $receipt_id, $database), (string) $signature);
    }
    $paid = strtotime((string) $payment_date);
    $since = strtotime(receipt_link_config($conn2)['since']);
    $oldest = strtotime("-" . RECEIPT_LINK_UNSIGNED_DAYS . " days", strtotime(date("Y-m-d")));
    return $paid !== false && $paid <= $since && $paid >= $oldest;
}
