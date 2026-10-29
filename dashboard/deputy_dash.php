<div class="contents animate dash_page" id="dp_dash">

    <?php
    $dash_dp_id = "dp_dash_dp";
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

        <div class="dash_stat dash_tone_slate">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-signal"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="activeusers">0</div>
                    <span>Active Users Now</span>
                </div>
            </div>
            <div class="dash_stat_foot"><p id="check_logs"><a href="#" class="dash_link">Check logs <i class="fas fa-arrow-right"></i></a></p></div>
        </div>

        <div class="dash_stat dash_tone_purple">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-file-alt"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="active_examination">0</div>
                    <span>Active Exams</span>
                </div>
            </div>
            <div class="dash_stat_foot"><p id="view_active_exams"><a href="#" class="dash_link">View exams <i class="fas fa-arrow-right"></i></a></p></div>
        </div>

        <div class="dash_stat dash_tone_indigo">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-book"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="my_subjects">0</div>
                    <span>Subjects I Teach</span>
                </div>
            </div>
            <div class="dash_stat_foot"><p id="view_my_subs"><a href="#my_information_inner" class="dash_link">View subjects <i class="fas fa-arrow-right"></i></a></p></div>
        </div>

    </div><!-- end stats grid -->

    <?php include("dashboard/dash_charts.php"); ?>

</div>
