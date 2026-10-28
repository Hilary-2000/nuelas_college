<?php
    session_start();
    date_default_timezone_set('Africa/Nairobi');

    // Landing here with the Back button after logging in shouldn't look like a
    // logout, so a session that is already open gets a short notice instead.
    $already_logged_in = isset($_SESSION['databasename']) && $_SESSION['databasename'] !== '0' && trim((string)$_SESSION['databasename']) !== '';

    // nothing to verify unless the password step just sent a code to this session
    if (!$already_logged_in && !isset($_SESSION['otp_user_id'])) {
        header('Location: login.php');
        exit;
    }

    $salute = "";
    if (isset($_SESSION['gender']) && $_SESSION['gender'] == 'M') {
        $salute = 'Mr. ';
    } elseif (isset($_SESSION['gender']) && $_SESSION['gender'] == 'F') {
        $salute = 'Mrs. ';
    }
    $first_name = isset($_SESSION['username']) ? explode(" ", $_SESSION['username'])[0] : "there";
    $school_name = isset($_SESSION['schname']) ? $_SESSION['schname'] : "School name";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $already_logged_in ? "Already logged in" : "Verify login"; ?> || <?php echo htmlspecialchars($school_name); ?></title>
    <link rel="stylesheet" href="assets/CSS/homepage.css">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="stylesheet" href="assets/CSS/login-modern.css">
    <link rel="stylesheet" href="assets/CSS/font-awesome/css/all.css">
    <link rel="icon" href="images/brand/favicon.ico" sizes="48x48">
    <link rel="icon" type="image/png" sizes="32x32" href="images/brand/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="images/brand/favicon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/brand/apple-touch-icon.png">
</head>
<body>
    <div class="auth-shell">
        <div class="auth-brand">
            <div class="auth-logo">
                <img src="images/ladybird_white.png" alt="icon">
                <h3>Ladybird School MIS</h3>
            </div>
            <h1>Welcome to LadyBird School MIS</h1>
            <p class="lead">An all-in-one school management information system with a suite of portals for parents, students and staff, giving your school full control of all academic, finance, wellbeing, and administrative information.</p>
            <div class="auth-contacts">
                <a class="chip" href="tel://+254743551250" title="Click to call us"><i class="fas fa-phone"></i> +254743551250</a>
                <a class="chip" href="tel://+254783840449" title="Click to call us"><i class="fas fa-phone"></i> +254783840449</a>
                <a class="chip" href="mailto:ladybirdsmis@gmail.com"><i class="fas fa-envelope"></i> ladybirdsmis@gmail.com</a>
            </div>
        </div>
        <div class="auth-formside">
            <?php if ($already_logged_in) { ?>
            <div class="auth-card">
                <img class="auth-school-logo" src='<?php echo $_SESSION['school_profile_image']; ?>' alt="school logo">
                <h2><?php echo htmlspecialchars($school_name); ?></h2>
                <p class="auth-sub">You're already logged in, <strong><?php echo htmlspecialchars($salute.$first_name); ?></strong>.</p>
                <p class="auth-sub">Once inside, use the system's own menu to move around. The browser's <strong>Back</strong> button is not part of the system's navigation and can bring you to pages like this one.</p>
                <button class="auth-btn" type="button" id="back_to_system"><i class="fas fa-arrow-left"></i> Back to System</button>
                <button class="auth-btn" type="button" id="landing_logout" style="margin-top:10px;background:none !important;color:#8c9a9b !important;box-shadow:none !important;"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </div>
            <?php } else { ?>
            <div class="auth-card" id="loginwin">
                <img class="auth-school-logo" src='<?php echo $_SESSION['school_profile_image']; ?>' alt="school logo">
                <h2><?php echo htmlspecialchars($school_name); ?></h2>
                <p class="auth-sub">Enter the verification code sent to you, <?php echo htmlspecialchars($salute.$first_name); ?>.</p>
                <div class="auth-errbox" id="errors">
                    <p style='color:green;'>The code expires in 5 minutes.</p>
                </div>
                <div class="otp-boxes" id="otp_boxes">
                    <input type="text" inputmode="text" maxlength="1" class="otp-box" data-index="0" autocomplete="one-time-code">
                    <input type="text" inputmode="text" maxlength="1" class="otp-box" data-index="1">
                    <input type="text" inputmode="text" maxlength="1" class="otp-box" data-index="2">
                    <input type="text" inputmode="text" maxlength="1" class="otp-box" data-index="3">
                </div>
                <input type="hidden" name="verification_code" id="verification_code">
                <button class="auth-btn" type="button" id="submit_verification_code"><i class="fas fa-sign-in-alt"></i> Verify &amp; Login <span class="hide" id="loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                <p class="auth-resend"><a href="#" id="resend_verification_code">Re-send code</a></p>
                <p class="auth-links"><a href="login.php">Re-enter username</a></p>
            </div>
            <?php } ?>
        </div>
    </div>
    <p class="auth-copyright">Copyright &copy; LadyBird School MIS 2020 - <?php echo date("Y");?></p>
    <div class="anonymus hide" id="anonymus" title="Click to dismis">
    </div>
    <script src="assets/JS/functions.js"></script>
    <script>
    <?php if ($already_logged_in) { ?>
        cObj("back_to_system").onclick = function () {
            redirect("homepage.php");
        };
        cObj("landing_logout").onclick = function () {
            let datapass = "?changeval=true";
            sendData2("GET", "login/changevalues.php", datapass, cObj("anonymus"), cObj("anonymus"), function () {
                redirect("login.php");
            });
        };
    <?php } else { ?>
        var otpBoxes = document.querySelectorAll("#otp_boxes .otp-box");
        var counter = 45;

        // the resend link stays greyed out until the countdown runs out
        function startResendCountdown(seconds) {
            counter = seconds;
            cObj("resend_verification_code").setAttribute("disabled", "disabled");
            cObj("resend_verification_code").innerHTML = "Re-send code in "+toMinutesAndSeconds(counter);
            var ids = setInterval(() => {
                counter--;
                cObj("resend_verification_code").innerHTML = "Re-send code in "+toMinutesAndSeconds(counter);
                if (counter <= 0) {
                    cObj("resend_verification_code").removeAttribute("disabled");
                    cObj("resend_verification_code").innerHTML = "Re-send code";
                    stopInterval(ids);
                }
            }, 1000);
        }
        startResendCountdown(45);

        cObj("submit_verification_code").onclick = function () {
            let verification_code = valObj("verification_code");
            if(verification_code.length == otpBoxes.length){
                // disable button to prevent double click
                this.disabled = true;
                otpBoxes.forEach(function (box) { box.classList.remove("otp-error"); });
                let datapas = "?verification_code="+encodeURIComponent(verification_code);
                sendData2('GET','login/login.php',datapas,cObj("errors"), cObj("loader"), () => {
                    if(cObj("confirmed_code") != null){
                        // the code is confirmed, open the session up and go in
                        let datapassed = "?newvalues=true";
                        sendData2("GET","login/changevalues.php",datapassed,cObj("anonymus"),cObj("anonymus"), function () {
                            redirect("homepage.php");
                        });
                    }else{
                        // wrong or expired code, flag the boxes and let the user retype
                        this.disabled = false;
                        otpBoxes.forEach(function (box) { box.classList.add("otp-error"); box.value = ""; });
                        cObj("verification_code").value = "";
                        otpBoxes[0].focus();
                    }
                });
            }else{
                cObj("errors").innerHTML = "<p style='color:red;'>Enter the full verification code sent to you!</p>";
                otpBoxes.forEach(function (box) { box.classList.add("otp-error"); });
            }
        }

        // keep the hidden #verification_code input in sync with the boxes
        function syncVerificationCode() {
            var code = "";
            otpBoxes.forEach(function (box) { code += box.value; });
            cObj("verification_code").value = code;
            if (code.length == otpBoxes.length && !cObj("submit_verification_code").disabled) {
                cObj("submit_verification_code").click();
            }
        }
        otpBoxes.forEach(function (box, index) {
            box.addEventListener("input", function () {
                this.value = this.value.slice(-1);
                otpBoxes.forEach(function (b) { b.classList.remove("otp-error"); });
                if (this.value.length == 1 && index < otpBoxes.length - 1) {
                    otpBoxes[index + 1].focus();
                }
                syncVerificationCode();
            });
            box.addEventListener("keydown", function (e) {
                if (e.key == "Enter") {
                    cObj("submit_verification_code").click();
                } else if (e.key == "Backspace" && this.value.length == 0 && index > 0) {
                    otpBoxes[index - 1].focus();
                }
            });
            box.addEventListener("paste", function (e) {
                e.preventDefault();
                var pasted = (e.clipboardData || window.clipboardData).getData("text").trim();
                for (var i = 0; i < otpBoxes.length; i++) {
                    otpBoxes[i].value = pasted[i] || "";
                }
                syncVerificationCode();
                otpBoxes[Math.max(Math.min(pasted.length, otpBoxes.length) - 1, 0)].focus();
            });
        });
        otpBoxes[0].focus();

        cObj("resend_verification_code").onclick = function (e) {
            e.preventDefault();
            if (counter > 0) {
                return;
            }
            otpBoxes.forEach(function (box) { box.classList.remove("otp-error"); box.value = ""; });
            cObj("verification_code").value = "";
            sendData2('GET','login/login.php',"?resend_code=true",cObj("errors"), cObj("loader"));
            startResendCountdown(240);
        }

        function toMinutesAndSeconds(totalSeconds) {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    <?php } ?>
    </script>
</body>
</html>
