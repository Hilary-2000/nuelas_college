<div class="contents animate hide" id="classregister">
    <div class="titled">
        <h2>Student Attendance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Call register</p>
                </div>
                <div class="col-md-3">
                    <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
            <input type="text"  id="myname" hidden value = <?php if(isset($_SESSION['username'])){ echo $_SESSION['username']; } ?> >
        </div>
        <!-- Attendance statistics (loadAttendanceStats in admissions.js) -->
        <div id="attendance_stats_holder" class="at_overview">
            <div class="at_head">
                <span class="at_icon"><i class="fas fa-clipboard-list"></i></span>
                <div class="at_head_text"><b>Today's Attendance Overview</b><small>Roll call figures for <?php echo date("l, jS F Y"); ?></small></div>
            </div>
            <div class="at_stats">
                <div class="at_stat at_tone_teal">
                    <span class="at_stat_icon"><i class="fas fa-users"></i></span>
                    <div class="at_stat_body"><b id="att_total"><img src="images/ajax_clock_small.gif"></b><span>Active students</span></div>
                </div>
                <div class="at_stat at_tone_good">
                    <span class="at_stat_icon"><i class="fas fa-check-circle"></i></span>
                    <div class="at_stat_body"><b id="att_present"><img src="images/ajax_clock_small.gif"></b><span>Present today <span id="att_present_pct" class="at_stat_pct"></span></span></div>
                </div>
                <div class="at_stat at_tone_bad">
                    <span class="at_stat_icon"><i class="fas fa-times-circle"></i></span>
                    <div class="at_stat_body"><b id="att_absent"><img src="images/ajax_clock_small.gif"></b><span>Absent today <span id="att_absent_pct" class="at_stat_pct"></span></span></div>
                </div>
                <div class="at_stat at_tone_blue">
                    <span class="at_stat_icon"><i class="fas fa-mars"></i></span>
                    <div class="at_stat_body"><b id="att_male"><img src="images/ajax_clock_small.gif"></b><span>Male <span id="att_male_pct" class="at_stat_pct"></span></span></div>
                </div>
                <div class="at_stat at_tone_pink">
                    <span class="at_stat_icon"><i class="fas fa-venus"></i></span>
                    <div class="at_stat_body"><b id="att_female"><img src="images/ajax_clock_small.gif"></b><span>Female <span id="att_female_pct" class="at_stat_pct"></span></span></div>
                </div>
                <div class="at_stat at_tone_amber">
                    <span class="at_stat_icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <div class="at_stat_body"><b id="att_classes_today"><img src="images/ajax_clock_small.gif"></b><span>Classes registered</span></div>
                </div>
            </div>
            <div class="at_bars">
                <div class="at_bar_row">
                    <span class="at_bar_label"><i class="fas fa-check-circle"></i> Present</span>
                    <div class="at_bar at_bar_good"><span id="att_present_bar" style="width:0%"></span></div>
                    <b id="att_present_pct_bar">0%</b>
                </div>
                <div class="at_bar_row">
                    <span class="at_bar_label"><i class="fas fa-times-circle"></i> Absent</span>
                    <div class="at_bar at_bar_bad"><span id="att_absent_bar" style="width:0%"></span></div>
                    <b id="att_absent_pct_bar">0%</b>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <p><strong>Hint:</strong></p>
                <p>- Always confirm the students you have ticked as present, and the date, before submitting.</p>
                <p>- After submitting the register you can view the attendance list.</p>
            </div>
            <?php
                // who is signed in, and what they can do on this page
                $role_name = "";
                $role_note = "";
                $my_class = "0";
                if (isset($_SESSION['auth'])) {
                    $authority = $_SESSION['auth'];
                    if ($authority == 0) {
                        $role_name = "Admin";
                        $role_note = "You can select any class to call register.";
                    } elseif ($authority == 1) {
                        $role_name = "Headteacher";
                        $role_note = "You can select any class to call register.";
                    } elseif ($authority == 2) {
                        $role_name = "Teacher";
                        $role_note = "You can call register once a class teacher assigns you a class.";
                    } elseif ($authority == 3) {
                        $role_name = "Deputy principal";
                        $role_note = "You can select any class to call register.";
                    } elseif ($authority == 4) {
                        $role_name = "Staff";
                    } elseif ($authority == 5) {
                        $role_name = "Class teacher";
                        $class_label = "Nan";
                        if (isset($_SESSION['class_taughts'])) {
                            $my_class = $_SESSION['class_taughts'];
                            $class_label = strlen($_SESSION['class_taughts']) > 1 ? $_SESSION['class_taughts'] : "Class ".$_SESSION['class_taughts'];
                        }
                        $role_note = "Your class is <b>".htmlspecialchars($class_label)."</b>. Select the date and click Display to show your students.";
                    } elseif ($authority == 6) {
                        $role_name = "Student";
                    }
                }
            ?>
            <div class="register" id='mains'>
                <div class="registerbodytop at_card">
                    <input type="hidden" name="" id="hidden_course_level_selected">
                    <input type="hidden" name="" id="hidden_course_name_selected">
                    <div class="at_head">
                        <span class="at_icon"><i class="fas fa-user-check"></i></span>
                        <div class="at_head_text">
                            <b>Attendance Register</b>
                            <small><?php if (isset($_SESSION['auth'])) { ?>Signed in as <b><?php echo $role_name; ?></b>. <?php echo $role_note; ?><?php } else { echo "Login to proceed"; } ?></small>
                        </div>
                    </div>
                    <div class="conts">
                        <div class="option1 hide" id="class_tr_onl">
                            <label for="class_register_dates_cltr" class="at_label">Select date</label>
                            <input type="date" class="form-control" id="class_register_dates_cltr" value="<?php echo date("Y-m-d")?>" max="<?php echo date("Y-m-d")?>">
                            <input type="hidden" class="hide" id="classselected" value="<?php echo $my_class;?>" placeholder="Class assigned" readonly>
                            <button type='button' id="show_class_att"><i class="fas fa-search"></i> Display</button>
                        </div>
                    </div>
                    <div class="classoptions" id="class_tr_only">
                        <!-- the cards pick an option in #optd, which the attendance code (and the dashboard shortcuts) use -->
                        <div class="at_actions" id="attendance_option_cards">
                            <div class="at_action" data-option="callreg" role="button" tabindex="0">
                                <span class="at_action_icon"><i class="fas fa-clipboard-check"></i></span>
                                <span class="at_action_text"><b>Call Register</b><small>Mark who is present in a course on a date</small></span>
                            </div>
                            <div class="at_action" data-option="view_attendance" role="button" tabindex="0">
                                <span class="at_action_icon"><i class="fas fa-calendar-day"></i></span>
                                <span class="at_action_text"><b>View Attendance</b><small>Present and absent counts per course for a day</small></span>
                            </div>
                            <div class="at_action" data-option="specific_student" role="button" tabindex="0">
                                <span class="at_action_icon"><i class="fas fa-user-clock"></i></span>
                                <span class="at_action_text"><b>Student Attendance</b><small>A month calendar for one student</small></span>
                            </div>
                        </div>
                        <div class="options2 d-none">
                            <select class="form-control" name="optd" id="optd" aria-label="Attendance option">
                                <option value="" hidden>Select...</option>
                                <option value="callreg">Call registers</option>
                                <option value="view_attendance" id="view_atts">View attendance</option>
                                <option value="specific_student" id="view_specific">View attendance specific student</option>
                            </select>
                        </div>
                        <div class="option2 at_filters hide" id="moreopt">
                            <input type="hidden" name="" id="attendance_statistics_holder">
                            <div class="at_field">
                                <label for="class_register_dates" class="at_label">Date</label>
                                <input type="date" class="form-control" id="class_register_dates" value="<?php echo date("Y-m-d")?>" max="<?php echo date("Y-m-d")?>">
                            </div>
                            <div class="at_field">
                                <label class="at_label" for="selectclass">Course level</label>
                                <p id="class_register_class"></p>
                            </div>
                            <div class="at_field">
                                <label for="course_list_attendance" class="at_label">Course</label>
                                <p id="course_list_attendance_holder"><span class="at_placeholder">Select a course level first</span></p>
                            </div>
                            <div class="at_field">
                                <label for="register_branch_list" class="at_label">Branch</label>
                                <p id="register_branch_list_holder"><span class="at_placeholder">Branches appear here</span></p>
                            </div>
                            <div class="at_field at_field_btn">
                                <button class="at_btn" id="display_student_attendances" type="button"><i class="fas fa-search"></i> Display</button>
                            </div>
                        </div>
                        <div class="option2 at_filters hide" id="moreopt2">
                            <div class="at_field">
                                <label class="at_label" for="sel_att_date">Date</label>
                                <input class="form-control" type="date" id="sel_att_date" value="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d");?>">
                            </div>
                            <div class="at_field at_field_btn">
                                <button type="button" class="at_btn" id="display_attendance_class"><i class="fas fa-search"></i> Display</button>
                            </div>
                            <p id="err_date_handled"></p>
                        </div>
                        <div class="option2 at_filters hide" id="moreopt3">
                            <div class="at_field at_field_wide">
                                <label class="at_label" for="students_admnos_in">Student reg no.</label>
                                <div class="autocomplete">
                                    <input class="form-control" type="text" id="students_admnos_in" placeholder="Type a name or reg no.">
                                </div>
                            </div>
                            <div class="at_field">
                                <label class="at_label" for="select_months_attendance">Month</label>
                                <input class="form-control" value="<?php echo date("Y-m");?>" type="month" id="select_months_attendance">
                            </div>
                            <div class="at_field at_field_btn">
                                <img src="images/ajax_clock_small.gif" class="hide" id="select_student_clock">
                                <button type="button" class="at_btn" id="display_attendance_class_specific"><i class="fas fa-search"></i> Display</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="registerbody section_card" id='tableinformation'>

                </div>
                <div class="hide" id="register_btns">
                    <div class="at_actionbar">
                        <p id="error_handler_attendance"></p>
                        <button type='button' class="at_btn_ghost" id='viewpresent'><i class="fas fa-eye"></i> View attendance</button>
                        <button type='button' id='submitclasspresent'><i class="fas fa-save"></i> Submit register</button>
                    </div>
                </div>
            </div>
            <div class="hide" id="attendance_register_one_student">
                <div class="section_card" id="display_student_attendance">
                </div>
            </div>
            <div class="view hide" id="view_attendances">
                <div class="conts at_card">
                    <div class="at_head">
                        <span class="at_icon"><i class="fas fa-calendar-check"></i></span>
                        <div class="at_head_text"><b>Attendance by Day</b><small>Pick a date to list the students present and the students absent.</small></div>
                        <button type="button" class="at_btn_ghost at_head_btn" id='backtosearch'><i class="fas fa-arrow-left"></i> Back</button>
                    </div>
                    <div class="at_filters">
                        <div class="at_field">
                            <label class="at_label" for="date_selected">Date</label>
                            <input type="date" class="form-control" name="date_selected" id="date_selected" value="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d");?>">
                        </div>
                        <div class="at_field at_field_btn">
                            <button type="button" class="at_btn" id="display_attendance"><i class="fas fa-search"></i> Display</button>
                        </div>
                    </div>
                </div>
                <div class="informationtoview section_card" id="atendanceinfor">
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>