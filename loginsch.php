<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login || <?php if(isset($_SESSION['schname'])){ echo $_SESSION['schname']; }else{  
        echo "School name";
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: login.php');
        ;}?></title>
    <link rel="stylesheet" href="assets/CSS/homepage.css">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="stylesheet" href="assets/CSS/login-modern.css">
    <link rel="stylesheet" href="assets/CSS/font-awesome/css/all.css">
    <link rel="shortcut icon" href="images/ladybird_white.png" type="image/x-icon">
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
            <div class="auth-card" id="loginwin">
                <input type="text" name="uname" id="uname" hidden value= <?php if(isset($_SESSION['unames'])){ echo $_SESSION['unames']; }else{ echo "null";} ?>>
                <div class="auth-school-block">
                    <img class="auth-school-logo" src='<?php echo $_SESSION['school_profile_image']; ?>' alt="school logo">
                    <div class="auth-school-info">
                        <p class="auth-school-name"><?php if(isset($_SESSION['schname'])){ echo $_SESSION['schname'];}else{ echo "School name";}?></p>
                        <p class="auth-school-motto"><?php if(isset($_SESSION['smotto'])){ echo $_SESSION['smotto']; }else{ echo "School name";}?></p>
                    </div>
                </div>
                <h2><?php
                    $date = date("H");
                    if($date<=10){
                        echo "Good morning";
                    }elseif ($date>10 && $date<=16) {
                        echo "Hello";
                    }elseif ($date > 16) {
                        echo "Good evening";
                    }
                ?> <?php if(isset($_SESSION['username'])){
                    $salute = "";
                    if($_SESSION['gender']=='M'){
                        $salute = 'Mr. ';
                    }elseif ($_SESSION['gender'] == 'F') {
                        $salute = 'Mrs. ';
                    }else{
                        $salute = "";
                    }
                    $named = explode(" ",$_SESSION['username']);
                    echo $salute.$named[0];
                }else {
                    echo "there";
                }?></h2>
                <p class="auth-sub">Proceed if that's your correct name and school. If not, <a href="login.php">re-enter username</a>.</p>
                <div class="auth-errbox hide" id="errors"></div>
                <div class="auth-field">
                    <i class="fas fa-lock auth-field-icon"></i>
                    <input type="password" name="pass" id="pass" placeholder="Enter password">
                    <i class="fas fa-eye auth-toggle-pass" id="toggle_pass"></i>
                </div>
                <div class="auth-channels">
                    <label class="auth-channels-label">Send verification code via</label>
                    <?php
                        // email and whatsapp only show up on servers that have their config file
                        $otp_channels = ['sms' => '<i class="fas fa-sms"></i> SMS'];
                        if (file_exists(__DIR__."/connections/mailConn.php")) {
                            $otp_channels['email'] = '<i class="fas fa-envelope"></i> Email';
                        }
                        if (file_exists(__DIR__."/connections/whatsappConn.php")) {
                            $otp_channels['whatsapp'] = '<i class="fab fa-whatsapp"></i> WhatsApp';
                        }
                        $otp_pref = isset($_SESSION['otp_channel_pref']) && isset($otp_channels[$_SESSION['otp_channel_pref']]) ? $_SESSION['otp_channel_pref'] : 'sms';
                    ?>
                    <div class="auth-channel-opts">
                        <?php foreach ($otp_channels as $channel => $channel_label) { ?>
                        <label class="auth-channel-opt">
                            <input type="radio" name="otp_channel" value="<?php echo $channel; ?>" <?php if($otp_pref == $channel){ echo "checked"; } ?>>
                            <span><?php echo $channel_label; ?></span>
                        </label>
                        <?php } ?>
                    </div>
                </div>
                <button class="auth-btn" id="subpwd" type="button"><i class="fas fa-sign-in-alt"></i> Login <span class="hide" id="loader"><i class="fa fa-spinner fa-spin"></i></span></button>
            </div>
        </div>
    </div>
    <p class="auth-copyright">Copyright &copy; LadyBird School MIS 2020 - <?php echo date("Y");?></p>
    <div class="anonymus hide" id="anonymus" title="Click to dismis">
    </div>
    <script src="assets/JS/functions.js"></script>
    <script>
        cObj("subpwd").onclick = function () {
            let passwds = valObj("pass");
            if(passwds.length>1){
                // disable button to prevent a double click sending two codes
                this.disabled = true;
                cObj("errors").classList.add("hide");
                cObj("errors").innerHTML = ""
                grayBorder(cObj("pass"));
                let otp_channel = document.querySelector('input[name="otp_channel"]:checked').value;
                let datapas = "?password="+passwds+"&usernames="+valObj("uname")+"&domain="+(document.domain)+"&otp_channel="+otp_channel;
                sendData2('GET','login/login.php',datapas,cObj("errors"), cObj("loader"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout==1200) {
                            this.disabled = false;
                            stopInterval(ids);
                        }
                        if (cObj("loader").classList.contains("hide")) {
                            this.disabled = false;
                            cObj("errors").classList.remove("hide");
                            // the code has been sent, go and enter it
                            if(cObj("correct_credentials") != null){
                                redirect("verification_code.php");
                            }
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            }else{
                cObj("errors").classList.remove("hide");
                cObj("errors").innerHTML = "<p class='data' style='color:red;'>Enter your password to proceed!</p>";
                redBorder(cObj("pass"));
            }
        }
        cObj("pass").addEventListener("keydown", function (e) {
            if (e.key == "Enter") {
                cObj("subpwd").click();
            }
        });
        cObj("toggle_pass").onclick = function () {
            let isHidden = cObj("pass").type == "password";
            cObj("pass").type = isHidden ? "text" : "password";
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        }

    </script>
</body>
</html>