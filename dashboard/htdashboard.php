<div class="contents animate dash_page" id="htdash">

    <?php
    $dash_dp_id = "ht_dp_img";
    $dash_tagline = "Here's your school at a glance. Use the navigation on the left to get started.";
    include("dashboard/dash_welcome.php");
    ?>

    <!-- Stats grid -->
    <div class="dash_stats">

        <div class="dash_stat dash_tone_blue">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-users"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="studentscount">0</div>
                    <span>Active Students</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="totalstuds" class="dash_link">View all students <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_red">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-user-slash"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="inactive_students_count">0</div>
                    <span>Inactive Students</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Deactivated accounts</span></div>
        </div>

        <div class="dash_stat dash_tone_green">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-user-plus"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="studentscounttoday">0</div>
                    <span>Registered Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="regtoday" class="dash_link">View registrations <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_teal">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-clipboard-check"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="studpresenttoday">0</div>
                    <span>Present Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="prestoday" class="dash_link">View attendance <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_red">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-user-times"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="absentstuds">0</div>
                    <span>Students Absent</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#regs" id="studentabs" class="dash_link">View absent list <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_amber">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-exchange-alt"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="transfered_studs">0</div>
                    <span>Transferred Students</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Transfer records</span></div>
        </div>

        <div class="dash_stat dash_tone_purple">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-graduation-cap"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="alumnis_number">0</div>
                    <span>Alumni</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Former students</span></div>
        </div>

        <div class="dash_stat dash_tone_green finance_graphs">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_money">
                        <span id="schoolfeesrecieved">Ksh xxx</span>
                        <button type="button" id="showfees" class="dash_eye" title="Show or hide the amount">
                            <span id="se_e"><i class="fa fa-eye"></i></span>
                            <span class="hide" id="unse_e"><i class="fa fa-eye-slash"></i></span>
                        </button>
                    </div>
                    <span>Fees Received Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><button type="button" id="schoolfee" class="dash_link">View details <i class="fas fa-arrow-right"></i></button></div>
        </div>

    </div><!-- end stats grid -->

    <?php include("dashboard/dash_charts.php"); ?>

</div>
