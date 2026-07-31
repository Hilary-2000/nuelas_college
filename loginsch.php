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
                <img src="images/ladybird.png" alt="icon">
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
                <button class="auth-btn" id="subpwd" type="button"><i class="fas fa-sign-in-alt"></i> Login</button>
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
                cObj("errors").classList.add("hide");
                cObj("errors").innerHTML = ""
                grayBorder(cObj("pass"));
                let datapas = "?password="+passwds+"&usernames="+valObj("uname")+"&domain="+(document.domain);
                sendData('GET','login/login.php',datapas,cObj("errors"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout==1200) {
                            stopInterval(ids);
                        }
                        //set data recieved from the database
                        let fback = cObj("errors").innerText;
                        let substrs = fback.substr(0,7);
                        if(fback.length>0){
                            cObj("errors").classList.remove("hide");
                        }
                        if(substrs=="Correct"){
                            let datapassed = "?newvalues=true";
                            sendData2("GET","login/changevalues.php",datapassed,cObj("subpwd"),cObj("anonymus"));
                            setTimeout(() => {
                                var timeout = 0;
                                var ids2 = setInterval(() => {
                                    timeout++;
                                    //after two minutes of slow connection the next process wont be executed
                                    if (timeout==1200) {
                                        stopInterval(ids2);                        
                                    }
                                    if (cObj("anonymus").classList.contains("hide")) {
                                        redirect("homepage.php");
                                        stopInterval(ids2);
                                    }
                                }, 100);
                            }, 200);
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