<?php
    session_start();
    require("connections/turnstile_config.php");

    $turnstile_bypassed = (TURNSTILE_BYPASS_IP !== '' && ($_SERVER['REMOTE_ADDR'] ?? '') === TURNSTILE_BYPASS_IP);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=yes">
    <link rel="stylesheet" href="assets/CSS/homepage.css">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="stylesheet" href="assets/CSS/login-modern.css">
    <link rel="stylesheet" href="assets/CSS/font-awesome/css/all.css">
    <link rel="shortcut icon" href="images/ladybird_white.png" type="image/x-icon">
    <title>Ladybird SMIS</title>
    <?php if (!$turnstile_bypassed): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-243578000-1"></script> -->
    <!-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-243578000-1');
    </script> -->

    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-K5H4YCK02K"></script> -->
    <!-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-K5H4YCK02K');
    </script> -->

</head>
<body>
    <div class="auth-shell">
        <div class="auth-brand">
            <div class="auth-logo" id="icon_age">
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
            <div class="auth-card">
                <h2>Sign in</h2>
                <p class="auth-sub">Enter your <strong>unique username</strong> to get started.</p>
                <div class="auth-errbox hide" id="err"></div>
                <div class="auth-field">
                    <i class="fas fa-user auth-field-icon"></i>
                    <input type="text" name="username" id="username" placeholder="Enter username" required>
                </div>
                <?php if (!$turnstile_bypassed): ?>
                <div class="auth-field">
                    <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars(TURNSTILE_SITE_KEY); ?>"></div>
                </div>
                <?php endif; ?>
                <p class="auth-note">Your username is private, please don't share it with anyone else.</p>
                <button class="auth-btn" id="submitUname" type="button"><i class="fas fa-arrow-right"></i> Submit</button>
                <p class="auth-links">Don`t have an account? <a href="#">Learn more</a></p>
            </div>
        </div>
    </div>
    <p class="auth-copyright">Copyright &copy; LadyBird School MIS 2020 - <?php echo date("Y");?></p>
    <div class="loadwindow hide" id="loadings">
        <div class="loadingcontents">
            <img src="images/load2.gif" alt="loading">
        </div>
    </div>
    <script src="assets/JS/functions.js"></script>
    <script>
        const turnstileBypassed = <?php echo $turnstile_bypassed ? 'true' : 'false'; ?>;
        cObj("submitUname").onclick = function () {
            let username = valObj("username");
            let turnstileToken = document.querySelector('[name="cf-turnstile-response"]') ? document.querySelector('[name="cf-turnstile-response"]').value : "";
            if(username.length>1 && !turnstileBypassed && turnstileToken.length==0){
                cObj("err").classList.remove("hide");
                cObj("err").innerHTML = "<p class='data' style='color:rgb(121, 19, 19);text-align:center;'>Please complete the verification checkbox to proceed!</p>"
                return;
            }
            if(username.length>1){
                grayBorder(cObj("username"));
                cObj("err").classList.add("hide");
                cObj("err").innerHTML = ""
                let datapass = "?log=true&username="+valObj("username")+"&domain="+(document.domain)+"&cf_token="+encodeURIComponent(turnstileToken);
                sendData1('GET','login/login.php',datapass,cObj("err"));
                 setTimeout(() => {
                     var timeout = 0;
                     var ids = setInterval(() => {
                         timeout++;
                         //after two minutes of slow connection the next process wont be executed
                         if (timeout==1200) {
                             stopInterval(ids);
                         }
                         if (cObj("loadings").classList.contains("hide")) {
                             if(cObj("err").innerText.length==0){
                                 redirect("loginsch.php");
                             }else{
                                 cObj("err").classList.remove("hide");
                             }
                             stopInterval(ids);
                         }
                     }, 100);
                 }, 200);
                 setTimeout(() => {
                 }, 100);
            }else{
                redBorder(cObj("username"));
                cObj("err").classList.remove("hide");
                cObj("err").innerHTML = "<p class='data' style='color:rgb(121, 19, 19);text-align:center;'>Enter your username to proceed!</p>"
            }
        }
        cObj("username").addEventListener("keydown", function (e) {
            if (e.key == "Enter") {
                cObj("submitUname").click();
            }
        });
        cObj("icon_age").onclick = function (){
            window.location = "https://ladybirdsmis.com/";
        }
    </script>
</body>

</html>