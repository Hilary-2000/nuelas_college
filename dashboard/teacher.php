<div class="contents animate " id="tr_dash">
    <div class="welcome">
        <div class="name_n_icons">        
            <h2>Welcome back  <?php if(isset($_SESSION['fullnames'])){
                $salute = "";
                if($_SESSION['gen']=='M'){
                    $salute = 'Mr. ';
                }elseif ($_SESSION['gen'] == 'F') {
                    $salute = 'Mrs. ';
                }else{
                    $salute = "";
                }
                $named = explode(" ",$_SESSION['fullnames']);
                echo $named[0];
            }else {
                            echo "Username ";
                        }?> </h2>
            <span><img class="images" src="images/dp.png" id="tr_dash_dp" alt="userimg"></span>
        </div>

        <div class="contedd">
            <p>You are logged in as 
                <?php 
                    if(isset($_SESSION['auth'])){
                        $auth = $_SESSION['auth'];
                        $data = "";
                        if ($auth == 0) {
                            $data .= "<b>". "System Administrator"."</b>";
                        }else if ($auth == "1"){
                            $data .= "<b>". "Principal"."</b>";
                        }else if ($auth == "2"){
                            $data .= "<b>". "Deputy Principal Academics"."</b>";
                        }else if ($auth == "3"){
                            $data .= "<b>". "Deputy Principal Administration"."</b>";
                        }else if ($auth == "4"){
                            $data .= "<b>". "Dean of Students"."</b>";
                        }else if ($auth == "5"){
                            $data .= "<b>". "Finance Office"."</b>";
                        }else if ($auth == "6"){
                            $data .= "<b>". "Human Resource Officer"."</b>";
                        }else if ($auth == "7"){
                            $data .= "<b>". "Head of Department"."</b>";
                        }else if ($auth == "8"){
                            $data .= "<b>". "Trainer/Lecturer"."</b>";
                        }else if ($auth == "9"){
                            $data .= "<b>". "Admissions"."</b>";
                        }else {
                            $data .= "<b>". ucwords(strtolower($auth))."</b>";
                        }
                        echo $data;
                    }else{
                        echo "Login to proceed";
                    }
                ?>
            </p>
            <p>Welcome to your dashboard <br>Use the navigation bar on your left to select a task you want to carry out!</p>
            <p>Below I have summarized infomation of what you might need to know</p>
        </div>
    </div>
    <div class="cardholder">
        
        <div class="cards">
            <div class="conted">
                <p><strong>Active exams:</strong></p>
            </div>
            <div class="conted">
                <p><span id = "active_examination">0</span> : exam(s)</p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Subjects I teach :</strong></p>
            </div>
            <div class="conted">
                <p><span id = "my_subjects">1</span> : Subject(s)</p>
            </div>    
            <div class="conted">
                <p id="view_my_subs"><a href="#my_information_inner">More..</a></p>
            </div>    
        </div>
        
        <!--<div class="cards">
            <div class="conted">
                <p><strong>Todays activities:</strong></p>
            </div>
            <div class="conted">
                <p><ul>
                    <p>Scout camping</p>
                    <p>Drama training</p>
                    <p>Music training</p>
                </ul></p>
            </div>    
            <div class="conted">
                <p><a href="#">More..</a></p>
            </div>    
        </div>-->
    </div>
    <hr class="w-75 mx-auto">
    <div class="row p-1 w-100 mx-auto">
        <div class="col-md-6 my-1" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="student_population_data"></p>
                <h5 class="text-center"><span class="hide" id="student_population_loader"><img src="images/ajax_clock_small.gif" id=""> Loading Charts...</span></h5>
                <canvas id="studentPopulationChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 my-1" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="student_attendance_data_stats"></p>
                <h5 class="text-center"><span class="hide" id="student_attendance_data_loader"><img src="images/ajax_clock_small.gif" id=""> Loading Charts...</span></h5>
                <canvas id="student_attendance_data_chart"></canvas>
            </div>
        </div>
        <div class="col-md-6 my-1 finance_graphs" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="fees_collection_modeofpay"></p>
                <h5 class="text-center"><span class="hide" id="fees_collection_modeofpay_loader"><img src="images/ajax_clock_small.gif" id=""> Loading Charts...</span></h5>
                <canvas id="fees_collection_modeofpay_chart"></canvas>
            </div>
        </div>
        <div class="col-md-6 my-1 finance_graphs" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="student_fees_balance_data"></p>
                <h5 class="text-center"><span class="hide" id="fees_balance_data_loader"><img src="images/ajax_clock_small.gif" id=""> Loading Charts...</span></h5>
                <canvas id="studentFeesBalanceData"></canvas>
            </div>
        </div>
        <div class="col-md-4 mt-2 finance_graphs" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="student_fees_balance_pie"></p>
                <h5 class="text-center"><span class="hide" id="fees_balance_data_loader_pie"><img src="images/ajax_clock_small.gif" id=""></span></h5>
                <canvas id="studentIncomeDataPie"></canvas>
            </div>
        </div>
        <div class="col-md-4 mt-2 finance_graphs" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="income_and_expense_pie"></p>
                <h5 class="text-center"><span class="hide" id="income_and_expense_pie_loader"><img src="images/ajax_clock_small.gif" id=""></span></h5>
                <canvas id="studentFeesBalanceDataPie"></canvas>
            </div>
        </div>
        <div class="col-md-4 mt-2" style="max-height: 45vh;">
            <div class="container bg-white rounded">
                <p class="d-none" id="gender_population_data_holder"></p>
                <h5 class="text-center"><span class="hide" id="gender_population_pie_loader"><img src="images/ajax_clock_small.gif" id=""></span></h5>
                <canvas id="gender_population_pie"></canvas>
            </div>
        </div>
    </div>
</div>