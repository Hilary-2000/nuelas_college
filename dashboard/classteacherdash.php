<div class="contents animate dash_page" id="ctdash">

    <?php
    $dash_dp_id = "ct_admin_dp";
    $dash_tagline = "Here's your class at a glance. Use the navigation on the left to get started.";
    include("dashboard/dash_welcome.php");
    ?>

    <!-- Stats grid -->
    <div class="dash_stats">

        <div class="dash_stat dash_tone_blue">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-users"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text" id="studclass">0</div>
                    <span>Students in My Class</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="my_students_populate" class="dash_link">View class list <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_green">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-user-plus"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text" id="reg_tod_mine">0</div>
                    <span>Registered Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">New admissions today</span></div>
        </div>

        <div class="dash_stat dash_tone_teal">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-clipboard-check"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text" id="my_att_clas">0</div>
                    <span>Present Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Roll call attendance</span></div>
        </div>

        <div class="dash_stat dash_tone_red">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-user-times"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text" id="my_absent_list">0</div>
                    <span>Absent Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Absent students</span></div>
        </div>

        <div class="dash_stat dash_tone_amber">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-tasks"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text">Roll Call</div>
                    <span>Mark today's attendance</span>
                </div>
            </div>
            <div class="dash_stat_foot"><button type="button" id="callrollcall" class="dash_link">Register now <i class="fas fa-arrow-right"></i></button></div>
        </div>

        <div class="dash_stat dash_tone_purple">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-calendar-week"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value dash_stat_text">My Timetable</div>
                    <span>View your schedule</span>
                </div>
            </div>
            <div class="dash_stat_foot"><button type="button" id="view_my_tt" class="dash_link">View timetable <i class="fas fa-arrow-right"></i></button></div>
        </div>

        <div class="dash_stat dash_tone_indigo">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-book"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="my_subjects">0</div>
                    <span>Subjects I Teach</span>
                </div>
            </div>
            <div class="dash_stat_foot"><p id="view_my_subs"><a href="#" class="dash_link">View subjects <i class="fas fa-arrow-right"></i></a></p></div>
        </div>

    </div><!-- end stats grid -->

    <?php include("dashboard/dash_charts.php"); ?>

</div>
