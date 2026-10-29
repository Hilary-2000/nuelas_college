<div class="contents animate dash_page" id="tr_dash">

    <?php
    $dash_dp_id = "tr_dash_dp";
    $dash_tagline = "Here's your teaching overview. Use the navigation on the left to get started.";
    include("dashboard/dash_welcome.php");
    ?>

    <!-- Stats grid -->
    <div class="dash_stats">

        <div class="dash_stat dash_tone_purple">
            <div class="dash_stat_top">
                <span class="dash_stat_icon"><i class="fas fa-file-alt"></i></span>
                <div class="dash_stat_body">
                    <div class="dash_stat_value" id="active_examination">0</div>
                    <span>Active Exams</span>
                </div>
            </div>
            <div class="dash_stat_foot"><span class="dash_note">Ongoing examinations</span></div>
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
