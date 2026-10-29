<div class="contents animate dash_page" id='adminsdash'>

    <?php
    $dash_dp_id = "admin_admin_dp";
    $dash_tagline = "Here's your school at a glance. Use the navigation on the left to get started.";
    include("dashboard/dash_welcome.php");
    ?>

    <!-- Stats grid -->
    <div class="dash_stats">

        <div class="dash_stat dash_tone_blue">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-users"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="students">0</div>
                    <span>Active Students</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="admin_students" class="dash_link">View all students <i class="fas fa-arrow-right"></i></a></div>
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
                <span class="dash_stat_icon"><i class="fas fa-id-badge"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="studpresenttoday">0</div>
                    <span>Registered Users</span>
                </div>
            </div>
            <div class="dash_stat_foot"><a href="#" id="my_employees" class="dash_link">Manage users <i class="fas fa-arrow-right"></i></a></div>
        </div>

        <div class="dash_stat dash_tone_slate">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-signal"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="activeusers">0</div>
                    <span>Active Users Now</span>
                </div>
            </div>
            <div class="dash_stat_foot"><button type="button" id="view_logs" class="dash_link">View logs <i class="fas fa-arrow-right"></i></button></div>
        </div>

        <div class="dash_stat dash_tone_amber">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-exchange-alt"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="transfered_stud2">0</div>
                    <span>Transferred Students</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Transfer records</span></div>
        </div>

        <div class="dash_stat dash_tone_purple">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-graduation-cap"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="alumnis_number2">0</div>
                    <span>Alumni</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Former students</span></div>
        </div>

        <div class="dash_stat dash_tone_teal">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-clipboard-check"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="rollcalnumber">0</div>
                    <span>Present Today</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Roll call attendance</span></div>
        </div>

    </div><!-- end stats grid -->

    <?php include("dashboard/dash_charts.php"); ?>

</div>
