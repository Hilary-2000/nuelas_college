<div class="contents animate hide" id="findstudents">
    <div class="titled">
        <h2>Manage Students</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Manage student information</p>
            <p class="hide" id="studentinformation" ></p>
        </div>

        <!-- Student statistics -->
        <div id="manage_student_stats_holder" class="section_card stats_card">
            <p style="font-weight:700; font-size:15px; margin-bottom:12px;">
                <i class="fas fa-user-graduate" style="color:cadetblue;"></i>&nbsp; Student Enrolment
            </p>

            <!-- Stat cards -->
            <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:14px;">
                <div style="flex:1;min-width:140px;background:#e8f4fd;border-left:4px solid #2196F3;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#2196F3;line-height:1.1;" id="mss_total"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-users"></i>&nbsp;Total Enrolled</div>
                </div>
                <div style="flex:1;min-width:140px;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#4CAF50;line-height:1.1;" id="mss_active"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-check-circle"></i>&nbsp;Active <span id="mss_active_pct" style="color:#4CAF50;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#fff3e0;border-left:4px solid #FF9800;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#FF9800;line-height:1.1;" id="mss_inactive"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-pause-circle"></i>&nbsp;Inactive <span id="mss_inactive_pct" style="color:#FF9800;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#e3f2fd;border-left:4px solid #1565C0;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#1565C0;line-height:1.1;" id="mss_male"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-mars"></i>&nbsp;Male <span id="mss_male_pct" style="color:#1565C0;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#fce4ec;border-left:4px solid #E91E63;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#E91E63;line-height:1.1;" id="mss_female"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-venus"></i>&nbsp;Female <span id="mss_female_pct" style="color:#E91E63;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#fffde7;border-left:4px solid #FFC107;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#FFC107;line-height:1.1;" id="mss_new_this_month"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-calendar-plus"></i>&nbsp;New This Month</div>
                </div>
            </div>

            <!-- Progress bars -->
            <div style="background:#fff;border:1px solid #e8e8e8;border-radius:8px;padding:14px 18px;">
                <div style="margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;margin-bottom:4px;">
                        <span><i class="fas fa-check-circle" style="color:#4CAF50;"></i>&nbsp;Active</span>
                        <span id="mss_active_pct_bar" style="font-weight:600;">0%</span>
                    </div>
                    <div style="background:#eee;border-radius:10px;height:8px;overflow:hidden;">
                        <div id="mss_active_bar" style="background:#4CAF50;height:8px;border-radius:10px;width:0%;transition:width 0.7s ease;"></div>
                    </div>
                </div>
                <div style="margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;margin-bottom:4px;">
                        <span><i class="fas fa-mars" style="color:#1565C0;"></i>&nbsp;Male</span>
                        <span id="mss_male_pct_bar" style="font-weight:600;">0%</span>
                    </div>
                    <div style="background:#eee;border-radius:10px;height:8px;overflow:hidden;">
                        <div id="mss_male_bar" style="background:#1565C0;height:8px;border-radius:10px;width:0%;transition:width 0.7s ease;"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;margin-bottom:4px;">
                        <span><i class="fas fa-venus" style="color:#E91E63;"></i>&nbsp;Female</span>
                        <span id="mss_female_pct_bar" style="font-weight:600;">0%</span>
                    </div>
                    <div style="background:#eee;border-radius:10px;height:8px;overflow:hidden;">
                        <div id="mss_female_bar" style="background:#E91E63;height:8px;border-radius:10px;width:0%;transition:width 0.7s ease;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="topsearch2" id="alumni_candidates_banner">
                <div class="cont">
                    <p id="alumni_candidates_count_raw" class="hide"></p>
                    <button type="button" class="btn btn-primary btn-sm" id="check_alumni_candidates"><i class="fas fa-graduation-cap"></i> Move Completed Students to Alumni <img class="hide" src="images/ajax_clock_small.gif" id="alumni_candidates_loader"></button>
                    <span id="show_alumni_candidates_notice" class="hide pulse-notice text-success">
                        <span style="font-size: 20px;"><i class="fas fa-hand-point-left"></i></span>
                        <span id="alumni_candidates_notice_count">0</span> student(s) ready to move to Alumni
                    </span>
                </div>
            </div>
            <div class="topsearch section_card fs_card">
                <div class="topsearch1 instructions">
                    <p><strong>Hint:</strong></p>
                    <p>- Find a student here to view, change or delete their information.</p>
                    <p>- Searching by admission number gives the most accurate result.</p>
                </div>
                <div class="fs_head">
                    <span class="fs_icon"><i class="fas fa-search"></i></span>
                    <div class="fs_head_text"><b>Find Students</b><small>Choose how to search, fill in the details that appear, then click Search.</small></div>
                </div>
                <p id="errorSearch"></p>
                <div class="fs_filters">
                    <div class="fs_field fs_field_main">
                        <label class="fs_label" for="sach">Search by</label>
                            <select class="form-control" name="sach" id="sach" aria-label="Search by">
                                <option value="" hidden>Choose how to search..</option>
                                <option value="name">Name</option>
                                <option value="AdmNo">Admission No</option>
                                <option value="class">Course Levels</option>
                                <option value="bcno">ID Number</option>
                                <option value="allstuds" id ='alstuds'>All students</option>
                                <option value="regtoday" id='regtodays'>Registered today</option>
                                <option value="registered_in" id='registered_in'>Intake</option>
                                <option value="doa_range">Admission Date Range</option>
                                <option value="by_county">County</option>
                                <option value="by_heard">How They Heard About Us</option>
                                <option value="age_range">Age Range</option>
                                <option value="by_module">Module</option>
                            </select>
                    </div>
                    <div class="fs_field hide" id="named">
                        <label class="fs_label" for="name">Student name</label>
                        <div class="fs_input_icon"><i class="fas fa-font"></i><input class="form-control" type="text" name="name" id="name" placeholder="Type a name"></div>
                        <img src="images/ajax_clock_small.gif" class="hide" id="names_loaders_find">
                    </div>
                    <div class="fs_field hide" id="admnosd">
                        <label class="fs_label" for="admno">Admission no.</label>
                        <div class="fs_input_icon"><i class="fas fa-id-badge"></i><input class="form-control" type="text" name="admno" id="admno" placeholder="e.g. LC-2025-003"></div>
                        <img src="images/ajax_clock_small.gif" class="hide" id="admnos_loaders_find">
                    </div>
                    <div class="fs_field hide" id="classenroll">
                        <label class="fs_label" for="selclass">Course level</label>
                        <p id="stud_class_find"></p>
                    </div>
                    <div class="fs_field hide" id="course_lists_search_bar">
                        <label class="fs_label" for="course_chosen_search">Course <img class="hide" src="images/ajax_clock_small.gif" id="course_list_find_loader"></label>
                        <p id="get_student_class_list" class="fs_placeholder">Select a course level first</p>
                    </div>
                    <div class="fs_field hide" id="bcnos">
                        <label class="fs_label" for="bcnosd">National ID number</label>
                        <div class="fs_input_icon"><i class="fas fa-id-card"></i><input class="form-control" type="text" name="bcnosd" id="bcnosd" placeholder="Enter ID number"></div>
                    </div>
                    <div class="fs_field hide" id="intake_month_search_win">
                        <label class="fs_label" for="intake_month_search">Intake month</label>
                            <select name="" id="intake_month_search" class="form-control">
                                <option value="" hidden>Select intake month</option>
                                <option value="Jan">Jan</option>
                                <option value="Feb">Feb</option>
                                <option value="Mar">Mar</option>
                                <option value="Apr">Apr</option>
                                <option value="May">May</option>
                                <option value="Jun">Jun</option>
                                <option value="Jul">Jul</option>
                                <option value="Aug">Aug</option>
                                <option value="Sep">Sep</option>
                                <option value="Oct">Oct</option>
                                <option value="Nov">Nov</option>
                                <option value="Dec">Dec</option>
                            </select>
                    </div>
                    <div class="fs_field hide" id="intake_year_search_win">
                        <label class="fs_label" for="intake_year_search">Intake year</label>
                            <select name="intake_year_search" id="intake_year_search" class="form-control">
                                <option value="" hidden>Select an Option</option>
                                <?php for($index = date("Y"); $index > 2017; $index--):?>
                                    <option value="<?=$index?>"><?=$index?></option>
                                <?php endfor;?>
                            </select>
                    </div>
                    <div class="fs_field hide" id="student_status_window">
                        <label class="fs_label" for="student_status_search">Student status</label>
                            <select name="" id="student_status_search" class="form-control">
                                <option value="" hidden>Select student status</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                                <option value="both">Both</option>
                            </select>
                    </div>
                    <div class="fs_field hide" id="student_study_mode_search_win">
                        <label for="study_mode_search" class="fs_label">Study mode</label>
                            <select name="study_mode_search" id="study_mode_search" class="form-control">
                                <option value="" hidden>Select an Option</option>
                                <option value="fulltime">Fulltime Mode</option>
                                <option value="evening">Hybrid Mode</option>
                                <option value="weekend">Weekend Mode</option>
                                <option value="online">Online Mode</option>
                                <option value="all">All Modes</option>
                            </select>
                    </div>
                    <div class="fs_field hide" id="college_branch_search">
                        <label for="college_branch_search_student" class="fs_label">College branch</label>
                        <span id="get_college_branch_holder"></span>
                    </div>
                    <div class="fs_field fs_field_wide hide" id="doa_range_win">
                        <label class="fs_label" for="doa_from">Admitted between</label>
                        <div class="fs_range">
                            <input type="date" id="doa_from" class="form-control" aria-label="Admitted from">
                            <span>and</span>
                            <input type="date" id="doa_to" class="form-control" aria-label="Admitted to">
                        </div>
                    </div>
                    <div class="fs_field hide" id="county_search_win">
                        <label class="fs_label" for="county_search_filter">County</label>
                            <select id="county_search_filter" class="form-control">
                                <option value="">-- Select County --</option>
                                <option value="Mombasa">Mombasa</option>
                                <option value="Kwale">Kwale</option>
                                <option value="Kilifi">Kilifi</option>
                                <option value="Tana River">Tana River</option>
                                <option value="Lamu">Lamu</option>
                                <option value="Taita/Taveta">Taita/Taveta</option>
                                <option value="Garissa">Garissa</option>
                                <option value="Wajir">Wajir</option>
                                <option value="Mandera">Mandera</option>
                                <option value="Marsabit">Marsabit</option>
                                <option value="Isiolo">Isiolo</option>
                                <option value="Meru">Meru</option>
                                <option value="Tharaka-Nithi">Tharaka-Nithi</option>
                                <option value="Embu">Embu</option>
                                <option value="Kitui">Kitui</option>
                                <option value="Machakos">Machakos</option>
                                <option value="Makueni">Makueni</option>
                                <option value="Nyandarua">Nyandarua</option>
                                <option value="Nyeri">Nyeri</option>
                                <option value="Kirinyaga">Kirinyaga</option>
                                <option value="Murang'a">Murang'a</option>
                                <option value="Kiambu">Kiambu</option>
                                <option value="Turkana">Turkana</option>
                                <option value="West Pokot">West Pokot</option>
                                <option value="Samburu">Samburu</option>
                                <option value="Trans Nzoia">Trans Nzoia</option>
                                <option value="Uasin Gishu">Uasin Gishu</option>
                                <option value="Elgeyo/Marakwet">Elgeyo/Marakwet</option>
                                <option value="Nandi">Nandi</option>
                                <option value="Baringo">Baringo</option>
                                <option value="Laikipia">Laikipia</option>
                                <option value="Nakuru">Nakuru</option>
                                <option value="Narok">Narok</option>
                                <option value="Kajiado">Kajiado</option>
                                <option value="Kericho">Kericho</option>
                                <option value="Bomet">Bomet</option>
                                <option value="Kakamega">Kakamega</option>
                                <option value="Vihiga">Vihiga</option>
                                <option value="Bungoma">Bungoma</option>
                                <option value="Busia">Busia</option>
                                <option value="Siaya">Siaya</option>
                                <option value="Kisumu">Kisumu</option>
                                <option value="Homa Bay">Homa Bay</option>
                                <option value="Migori">Migori</option>
                                <option value="Kisii">Kisii</option>
                                <option value="Nyamira">Nyamira</option>
                                <option value="Nairobi">Nairobi</option>
                            </select>
                    </div>
                    <div class="fs_field hide" id="heard_search_win">
                        <label class="fs_label" for="heard_search_filter">How they heard about us</label>
                            <select id="heard_search_filter" class="form-control">
                                <option value="">-- Select Option --</option>
                                <option value="Radio">Radio</option>
                                <option value="TV">TV</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Tiktok">Tiktok</option>
                                <option value="Friends">Friends</option>
                                <option value="Referral">Referral</option>
                                <option value="Others">Others</option>
                            </select>
                    </div>
                    <div class="fs_field fs_field_wide hide" id="age_range_win">
                        <label class="fs_label" for="age_from">Age between (years)</label>
                        <div class="fs_range">
                            <input type="number" id="age_from" class="form-control" placeholder="e.g. 18" min="1" max="100" aria-label="Minimum age">
                            <span>and</span>
                            <input type="number" id="age_to" class="form-control" placeholder="e.g. 30" min="1" max="100" aria-label="Maximum age">
                        </div>
                    </div>
                    <div class="fs_field hide" id="module_search_win">
                        <label class="fs_label" for="module_search_filter">Module <img src="images/ajax_clock_small.gif" class="hide" id="module_search_loader"></label>
                            <select id="module_search_filter" class="form-control">
                                <option value="">-- Select Module --</option>
                            </select>
                    </div>
                    <div class="fs_field fs_field_btn">
                        <span id="findingstudents" class="fs_search_btn" role="button" tabindex="0"><i class="fas fa-search"></i> Search</span>
                    </div>
                </div>
                <p id="swindow"></p>
                <p id="topsearch2"></p>
                <div class="body1 hide" id="class_tr_search">
                        <div class="conts" id="">
                            <label for="class_assigned_tr">Class assigned: <br></label>
                            <input type="text" style="max-width:200px" name="class_assigned_tr" id="class_assigned_tr" placeholder="Class assigned" Readonly>
                            <button type="button" id="display_my_students"><i class="fas fa-search"></i> Display my students</button>
                        </div>
                </div>
            </div>
            <div class="">
                <div class="otherbtn section_card hide" id="resultsbody">
                </div>
                    <div class="back_button animate hide" id="back_btns" title="Click to dismis">
                        <button class = "my_back_button"  type="button" id="go_back_1" ><i class="fas fa-arrow-left"></i></button>
                    </div>
                <div class="staffinformed form-group rounded hide" id="viewinformation" >
                    <p id ="updateerrors"></p>
                    <div class="studentdetails">
                    <!-- profile header: photo, name (filled by admissions.js), boarding and delete -->
                    <div class="sp_hero">
                        <div class="sp_hero_photo">
                            <p id="dp_local_stud" class="hide"></p>
                            <div class="student_images">
                                <img id="student_image" style="cursor: pointer;" src="images/board.jpg" alt="Student photo" title="Click to view the photo">
                            </div>
                            <p class="sp_photo_actions">
                                <img class="hide" src="images/ajax_clock_small.gif" id="student_dp_loader">
                                <span id="change_student_profile_image" class="sp_photo_btn" role="button" tabindex="0"><i class="fas fa-camera"></i> Change</span>
                                <span id="delete_dp" class="sp_photo_btn sp_photo_btn_del" role="button" tabindex="0" title="Remove the photo"><i class="fas fa-trash-alt"></i></span>
                            </p>
                            <p id="dp_locale"></p>
                        </div>
                        <div class="sp_hero_main">
                            <div id="student_detail_identity" class="sp_identity"></div>
                            <div class="sp_hero_side">
                                <div class="sm-toggle-card">
                                    <div>
                                        <p class="sp_toggle_title"><i class="fas fa-bed"></i> Boarding Enrolled</p>
                                        <p class="sp_toggle_sub" id="boarding_toggle_label">Loading...</p>
                                    </div>
                                    <label class="sm-switch">
                                        <input type="checkbox" id="boarding_toggle">
                                        <span class="sm-slider"></span>
                                    </label>
                                </div>
                                <span id="boarding_toggle_feedback" class="sp_toggle_feedback"></span>
                                <p id="boarding_status_changer" class="sp_toggle_feedback"></p>
                                <img class="hide" src="images/ajax_clock_small.gif" id="boarding_status_load">
                                <span id="boarding_status" class="hide"></span>
                                <button type="button" class="sp_btn_danger" id="prompt_delete_student"><i class="fas fa-trash-alt"></i> Delete Student <img class="hide" src="images/ajax_clock_small.gif" id="delete_student_load"></button>
                            </div>
                        </div>
                    </div>
                    <!-- quick stats: fee balance, payments and attendance (admissions.js) -->
                    <div id="student_detail_stats" class="sp_stats"></div>
                    <!-- hidden elements kept for existing JS writes/reads -->
                    <span class="hide" id="current_term"></span>
                    <span class="hide" id="total_amount_to_pay"></span>
                    <span class="hide" id="fees_paid_this_term"></span>
                    <span class="hide" id="fees_balances"></span>
                    <span class="hide" id="total_paid_fees"></span>
                    <span class="hide" id="transport_enrolled_std_infor"></span>
                    <span class="hide" id="board_enrolled_std_infor"></span>
                    <span class="hide" id="attendance_this_term"></span>
                    <span class="hide" id="attendance_this_year"></span>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-wallet"></i></span>
                            <div class="sp_head_text"><b>Finance and Enrolment</b><small>Carried balance, voteheads, discount and one-off charges</small></div>
                        </div>
                        <div class="sp_facts">
                            <div class="sp_fact">
                                <span class="sp_fact_label">Balance carried forward</span>
                                <span class="sp_fact_value"><span id="lastyr_fees_balance">Kes 0</span><span class="sp_edit link <?php if ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0"){}else{echo "hide";}?>" id="edit_last_yr_academic_balance" title="Edit"><i class="fas fa-pen"></i></span></span>
                            </div>
                            <div class="sp_fact">
                                <span class="sp_fact_label">Votehead status <i class="fas fa-info-circle" title="The voteheads the student is paying for"></i></span>
                                <span class="sp_fact_value"><span id="view_votehead_status">-</span><span class="sp_edit link <?php if ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0"){}else{echo "hide";}?>" id="edit_votehead_status" title="Edit"><i class="fas fa-pen"></i></span></span>
                            </div>
                            <div class="sp_fact">
                                <span class="sp_fact_label">Fees discount <i class="fas fa-info-circle" title="The discount applies to regular and boarding fees only. Transport fees are not discounted."></i></span>
                                <span class="sp_fact_value"><span id="fees_discount">-</span><span class="sp_edit link <?php if ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0"){}else{echo "hide";}?>" id="edit_discounts" title="Edit"><i class="fas fa-pen"></i></span></span>
                            </div>
                            <div class="sp_fact">
                                <span class="sp_fact_label">Charged account</span>
                                <span class="sp_fact_value"><span id="charged_account_summary">Kes 0 - 0 item(s)</span><span class="sp_edit link" id="view_charged_account_btn" title="View or edit the charged account"><i class="fas fa-eye"></i></span></span>
                                <input type="hidden" id="charged_account_can_edit" value="<?php echo ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0") ? "1" : "0"; ?>">
                                <p id="charged_account_holder_raw" class="hide"></p>
                            </div>
                        </div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-user"></i></span>
                            <div class="sp_head_text"><b>Basic Information</b><small>Name, course, dates and contacts</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="sp_label" for="snamed_in">Surname</label>
                                <input type="text" class="form-control w-100" autocomplete="off" id="snamed_in" placeholder="Surname">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="fnamed_in">First name</label>
                                <input class="form-control w-100" type="text" autocomplete="off" id="fnamed_in" placeholder="First name">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="lnamed_in">Last name</label>
                                <input class="form-control w-100" type="text" autocomplete="off" id="lnamed_in" placeholder="Last name">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="classed">Course level <img src="images/ajax_clock_small.gif" class="hide" id="class_loaders_id_in"></label>
                                <div id="class_holders"></div>
                                <div id="course_level_error_window"></div>
                                <input type="hidden" name="" id="course_level_hidden">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="course_chosen_edit">Course <img src="images/ajax_clock_small.gif" class="hide" id="course_list_edit_loader"></label>
                                <div id="course_list_edit"></div>
                                <div id="course_chosen_error_window"></div>
                                <input type="hidden" name="" id="course_chosen_level_hidden">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="dobs">Date of birth</label>
                                <input class="form-control w-100" type="date" autocomplete="off" id="dobs">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="doas">Date of admission</label>
                                <input class="form-control w-100" type="date" autocomplete="off" id="doas">
                            </div>
                            <div class="col-md-12 hide" id="reason_for_leaving_window">
                                <label for="reason_for_leaving_desc" class="sp_label">Reason for leaving</label>
                                <textarea name="reason_for_leaving_desc" id="reason_for_leaving_desc" cols="30" rows="4" class="form-control" placeholder="Briefly describe why the student left"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="genders">Gender</label>
                                <select class="form-control w-100" id="genders">
                                    <option value="" hidden>Select gender..</option>
                                    <option id='Male' value="Male">Male</option>
                                    <option id='Female' value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="addressed">Address</label>
                                <input class="form-control w-100" type="text" autocomplete="off" id="addressed" placeholder="Area of residence">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_county" class="sp_label">County</label>
                                <select id="edit_county" class="form-control w-100">
                                    <option value="">-- Select County --</option>
                                        <option value="">-- Select County --</option>
                                        <option value="Mombasa">Mombasa</option>
                                        <option value="Kwale">Kwale</option>
                                        <option value="Kilifi">Kilifi</option>
                                        <option value="Tana River">Tana River</option>
                                        <option value="Lamu">Lamu</option>
                                        <option value="Taita/Taveta">Taita/Taveta</option>
                                        <option value="Garissa">Garissa</option>
                                        <option value="Wajir">Wajir</option>
                                        <option value="Mandera">Mandera</option>
                                        <option value="Marsabit">Marsabit</option>
                                        <option value="Isiolo">Isiolo</option>
                                        <option value="Meru">Meru</option>
                                        <option value="Tharaka-Nithi">Tharaka-Nithi</option>
                                        <option value="Embu">Embu</option>
                                        <option value="Kitui">Kitui</option>
                                        <option value="Machakos">Machakos</option>
                                        <option value="Makueni">Makueni</option>
                                        <option value="Nyandarua">Nyandarua</option>
                                        <option value="Nyeri">Nyeri</option>
                                        <option value="Kirinyaga">Kirinyaga</option>
                                        <option value="Murang'a">Murang'a</option>
                                        <option value="Kiambu">Kiambu</option>
                                        <option value="Turkana">Turkana</option>
                                        <option value="West Pokot">West Pokot</option>
                                        <option value="Samburu">Samburu</option>
                                        <option value="Trans Nzoia">Trans Nzoia</option>
                                        <option value="Uasin Gishu">Uasin Gishu</option>
                                        <option value="Elgeyo/Marakwet">Elgeyo/Marakwet</option>
                                        <option value="Nandi">Nandi</option>
                                        <option value="Baringo">Baringo</option>
                                        <option value="Laikipia">Laikipia</option>
                                        <option value="Nakuru">Nakuru</option>
                                        <option value="Narok">Narok</option>
                                        <option value="Kajiado">Kajiado</option>
                                        <option value="Kericho">Kericho</option>
                                        <option value="Bomet">Bomet</option>
                                        <option value="Kakamega">Kakamega</option>
                                        <option value="Vihiga">Vihiga</option>
                                        <option value="Bungoma">Bungoma</option>
                                        <option value="Busia">Busia</option>
                                        <option value="Siaya">Siaya</option>
                                        <option value="Kisumu">Kisumu</option>
                                        <option value="Homa Bay">Homa Bay</option>
                                        <option value="Migori">Migori</option>
                                        <option value="Kisii">Kisii</option>
                                        <option value="Nyamira">Nyamira</option>
                                        <option value="Nairobi">Nairobi</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="intake_month_edit" class="sp_label">Intake month</label>
                                <select name="intake_month_edit" id="intake_month_edit" class="form-control w-100">
                                        <option value="" hidden>Select an Option</option>
                                        <option value="JAN">JAN</option>
                                        <option value="FEB">FEB</option>
                                        <option value="MAR">MAR</option>
                                        <option value="APR">APR</option>
                                        <option value="MAY">MAY</option>
                                        <option value="JUN">JUN</option>
                                        <option value="JUL">JUL</option>
                                        <option value="AUG">AUG</option>
                                        <option value="SEP">SEP</option>
                                        <option value="OCT">OCT</option>
                                        <option value="NOV">NOV</option>
                                        <option value="DEC">DEC</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="intake_year_edit" class="sp_label">Intake year</label>
                                <select name="intake_year_edit" id="intake_year_edit" class="form-control w-100">
                                        <option value="" hidden>Select an Option</option>
                                        <?php for($index = date("Y"); $index > 2017; $index--):?>
                                            <option value="<?=$index?>"><?=$index?></option>
                                        <?php endfor;?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_study_mode" class="sp_label">Study mode</label>
                                <select name="edit_study_mode" id="edit_study_mode" class="form-control w-100">
                                        <option value="" hidden>Select an Option</option>
                                        <option selected value="fulltime">Fulltime Mode</option>
                                        <option value="evening">Hybrid Mode</option>
                                        <option value="weekend">Weekend Mode</option>
                                        <option value="online">Online Mode</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-none" id="study_mode_cost_holder">
                                <label class="sp_label"><span id="study_mode_cost_label">Module Cost</span></label>
                                <p class="sp_readonly">Kes <span id="study_mode_cost_value">0</span></p>
                            </div>
                            <div class="col-md-4">
                                <label for="course_progress" class="sp_label">Course progress</label>
                                <select name="course_progress" id="course_progress" class="form-control w-100">
                                    <option value="" hidden>Select how the student progresses</option>
                                    <option value="1">Automated</option>
                                    <option value="0">Manual</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="edit_student_contacts">Student phone</label>
                                <input type="number" class="form-control w-100" name="edit_student_contacts" id="edit_student_contacts" placeholder="Kenyan numbers only">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="edit_student_email">Student email</label>
                                <input type="email" class="form-control w-100" name="edit_student_email" id="edit_student_email" placeholder="Enter email">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="college_branch_edit">College branch <img src="images/ajax_clock_small.gif" class="hide" id="college_branch_loader_edit"></label>
                                <div id="college_branch_edit_holder"></div>
                            </div>
                        </div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-route"></i></span>
                            <div class="sp_head_text"><b>Course Progress</b><small>Set each module's status, dates and cost, then save the progress</small></div>
                        </div>
                        <div id="course_details_display" class="sp_course"></div>
                        <div class="sp_card_actions">
                            <span class="sp_btn_ghost" id="add_modules" role="button" tabindex="0"><i class="fa fa-plus"></i> Add Course Modules <img src="images/ajax_clock_small.gif" class="hide" id="add_course_modules_loader"></span>
                            <span class="sp_btn_primary" id="save_course_progress" role="button" tabindex="0"><i class="fa fa-save"></i> Save Course Progress <img src="images/ajax_clock_small.gif" class="hide" id="save_course_progress_loader"></span>
                        </div>
                        <div id="error_handler_course_progress"></div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-id-card"></i></span>
                            <div class="sp_head_text"><b>Admission Details</b><small>Identification numbers, admission items and clubs</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="sp_label" for="adminnos">Admission no. <span class="sp_tag">Read only</span></label>
                                <input class="w-100 form-control sp_locked" type="text" readonly id="adminnos" placeholder="Adm No.">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="indexnos">Index number</label>
                                <input class="w-100 form-control" type="text" id="indexnos" placeholder="Index number">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="bcnno">Birth certificate number</label>
                                <input class="w-100 form-control" type="text" id="bcnno" placeholder="Birth certificate number">
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label">Admission essentials</label>
                                <p id="admissionessentials_lists"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="select_clubs_sports" class="sp_label">Sports houses / clubs</label>
                                <p id="clubs_for_sports_in">
                                <select name='' class='form-control w-100' id='select_clubs_sports'><option value='' id='select_clubs_sports_def' hidden>Select an option</option></select>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-notes-medical"></i></span>
                            <div class="sp_head_text"><b>Health and Background</b><small>Disability, medical history and how the student found the college</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="sp_label" for="disableds">Disabled</label>
                                <select id="disableds" class="form-control w-100">
                                    <option value="" hidden>Select option..</option>
                                    <option id="Yes" value="Yes">Yes</option>
                                    <option id="No" value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="sp_label" for="descriptionsd">Disability description</label>
                                <textarea class="form-control w-100" placeholder="Describe the disability, if any" autocomplete="off" id="descriptionsd" cols="30" rows="3"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="medical_histry" class="sp_label">Medical history</label>
                                <textarea class="form-control" name="medical_histry" id="medical_histry" cols="30" rows="3" placeholder="Allergies, conditions or other medical notes"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="sp_label">How did they hear about us?</label>
                                <div class="sp_checks">
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_radio" value="Radio"> Radio</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_tv" value="TV"> TV</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_facebook" value="Facebook"> Facebook</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_instagram" value="Instagram"> Instagram</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_tiktok" value="Tiktok"> Tiktok</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_friends" value="Friends"> Friends</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_referral" value="Referral"> Referral</label>
                                    <label class="sp_check"><input class="edit_heard_about_checkbox" type="checkbox" id="edit_heard_others" value="Others"> Others</label>
                                </div>
                                <div class="row g-2 mt-1 hide" id="edit_referral_details_win">
                                    <div class="col-12"><span class="sp_hint">Referral: who referred them and their phone number</span></div>
                                    <div class="col-md-6"><input type="text" id="edit_referral_name" class="form-control" placeholder="Referral name"></div>
                                    <div class="col-md-6"><input type="text" id="edit_referral_phone" class="form-control" placeholder="Referral phone number"></div>
                                </div>
                                <div class="mt-2 hide" id="edit_heard_others_win">
                                    <span class="sp_hint">Others: please specify</span>
                                    <input type="text" id="edit_heard_others_specify" class="form-control" placeholder="Please specify">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-school"></i></span>
                            <div class="sp_head_text"><b>Previous Schools</b><small>Schools the student attended before joining</small></div>
                        </div>
                        <p class="hide" id="previous_school_json"></p>
                        <div class="tableme"><p id="prev_sch_list"></p></div>
                    </div>
                    </div>

                    <div class="parentsinformation">
                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-users"></i></span>
                            <div class="sp_head_text"><b>Parents and Guardians</b><small>Contacts the college reaches out to about the student</small></div>
                        </div>
                        <p class="sp_subhead"><span>1</span> First parent</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="sp_label" for="pnamed">Name</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pnamed' placeholder='Parent name'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="pcontacted">Phone</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pcontacted' placeholder='Parent phone number'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="paddressed">Residence</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='paddressed' placeholder='Parent residence'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="pemails">Email</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pemails' placeholder='Parent email'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="parrelationship">Relationship</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='parrelationship' placeholder='e.g. Mother'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="paroccupation1">Occupation</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='paroccupation1' placeholder='Parent occupation'>
                            </div>
                            <div class="col-md-4 hide">
                                <div class="call_option"><p class="btn btn-sm btn-success my-2" id="call_phone">Click to call parent</p></div>
                                <div class="call_option"><p class="btn btn-sm btn-success my-2" id="mail_to">Click to send the parent an email.</p></div>
                            </div>
                        </div>
                        <p class="sp_subhead"><span>2</span> Second parent</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="sp_label" for="pnamed2">Name</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pnamed2' placeholder='Parent name'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="pcontacted2">Phone</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pcontacted2' placeholder='Parent phone number'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="pemails2">Email</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='pemails2' placeholder='Parent email'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="parrelationship2">Relationship</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='parrelationship2' placeholder='e.g. Father'>
                            </div>
                            <div class="col-md-4">
                                <label class="sp_label" for="paroccupation2">Occupation</label>
                                <input class='form-control w-100' type='text' autocomplete='off' id='paroccupation2' placeholder='Parent occupation'>
                            </div>
                            <div class="col-md-4 hide">
                                <div class="call_option"><p class="btn btn-sm btn-success my-2" id="call_phone2">Click to call parent</p></div>
                                <div class="call_option"><p class="btn btn-sm btn-success my-2" id="mail_to2">Click to send the parent an email.</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="sp_card">
                        <div class="sp_head">
                            <span class="sp_icon"><i class="fas fa-comment-dots"></i></span>
                            <div class="sp_head_text"><b>Communication</b><small>Who receives messages about this student, and how</small></div>
                        </div>
                        <p class="sp_label">Preferred recipient</p>
                        <div class="sp_pills">
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_school_default" value="school_default" checked><span>College default</span></label>
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_primary" value="primary_parent"><span>Primary parent</span></label>
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_secondary" value="secondary_parent"><span>Secondary parent</span></label>
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_both" value="both_parents"><span>Both parents</span></label>
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_student" value="student"><span>Student</span></label>
                            <label class="sp_pill"><input type="radio" name="preferred_communication" id="pref_comm_all" value="all_three"><span>All three</span></label>
                        </div>
                        <p class="sp_label mt-3">Preferred channel</p>
                        <div class="sp_channels">
                            <div class="sp_channel">
                                <span class="sp_channel_who"><i class="fas fa-user-graduate"></i> Student</span>
                                <div class="sp_pills">
                                    <label class="sp_pill"><input type="radio" name="student_channel" id="student_channel_sms" value="sms" checked><span><i class="fas fa-sms"></i> SMS</span></label>
                                    <label class="sp_pill"><input type="radio" name="student_channel" id="student_channel_email" value="email"><span><i class="fas fa-envelope"></i> Email</span></label>
                                </div>
                            </div>
                            <div class="sp_channel">
                                <span class="sp_channel_who"><i class="fas fa-user"></i> Primary parent</span>
                                <div class="sp_pills">
                                    <label class="sp_pill"><input type="radio" name="primary_parent_channel" id="primary_parent_channel_sms" value="sms" checked><span><i class="fas fa-sms"></i> SMS</span></label>
                                    <label class="sp_pill"><input type="radio" name="primary_parent_channel" id="primary_parent_channel_email" value="email"><span><i class="fas fa-envelope"></i> Email</span></label>
                                </div>
                            </div>
                            <div class="sp_channel">
                                <span class="sp_channel_who"><i class="fas fa-user-friends"></i> Secondary parent</span>
                                <div class="sp_pills">
                                    <label class="sp_pill"><input type="radio" name="secondary_parent_channel" id="secondary_parent_channel_sms" value="sms" checked><span><i class="fas fa-sms"></i> SMS</span></label>
                                    <label class="sp_pill"><input type="radio" name="secondary_parent_channel" id="secondary_parent_channel_email" value="email"><span><i class="fas fa-envelope"></i> Email</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- stays at the bottom of the window while scrolling the profile -->
                    <div class="sp_actionbar">
                        <span class="sp_back link" id="returnfind" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to student list</span>
                        <p id="coppy_cat_err"></p>
                        <button type="button" id='updatestudinfor'><i class="fas fa-save"></i> Update student information</button>
                    </div>
                </div>
                <!-- <div class="conts" id="result_body_2">
                    <div class="view_informations row p-2">
                        <div class="dp_holder col-md-4 ">
                            <div class="dp_s">
                                <img src="images/dp.png" alt="dp">
                                <button>Change</button>
                            </div>
                        </div>
                        <div class=" row col-md-8 ">
                            <div class="col-md-5 shadow-lg border border-secondary p-1 rounded-sm m-2">
                                <div class="container ">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Basic Information</h6>
                                    <p><strong>✔ Student name: </strong> <span id="s_name">James st patrick</span></p>
                                    <p><strong>✔ Gender: </strong> <span id="gender_studs">male</span></p>
                                    <p><strong>✔ Class enrolled: </strong> <span id="students_classes">Class 3</span></p>
                                    <p><strong>✔ D.O.B: </strong> <span id="students_dobs">Jun 10<sup>th</sup> 2000</span></p>
                                    <p><strong>✔ Admission Date: </strong> <span id="student_doas">Jun 10<sup>th</sup> 2009</span></p>
                                    <p><strong>✔ Home Address: </strong> <span id="students_adds">Kisumu</span></p>
                                </div>
                                <div class="container">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Numbers</h6>
                                    <p><strong>✔ Admission No.</strong> <span id="students_adm_nos">202</span></p>
                                    <p><strong>✔ Student UPI: </strong> <span id="students_upisd">FM-1000</span></p>
                                    <p><strong>✔ Student BC No: </strong> <span id="students_bcs_no">09898978</span></p>
                                    <p><strong>✔ Student Index No: </strong> <span id="students_indexes_nos">1</span></p>
                                </div>
                                <div class="container">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Other Information</h6>
                                    <p><strong>✔ Disabled.</strong> <span id="students_disableds">Yes</span></p>
                                    <p><strong>✔ Disabled description:</strong> <span id="students_brocken_ankle" > Brocken ankle and stomach alergies.</span></p>
                                </div>
                            </div>
                            <div class="col-md-6 shadow-lg border border-secondary p-1 rounded-sm m-2 container">
                                <h6 class="text-bold" style="color: rgb(88, 104, 104);">Parents information</h6>
                                <div class="dp_s ">
                                    <img src="images/dp.png" alt="DP">
                                </div>
                                <div class="parent_data">
                                    <p><strong>✔ Parent name: </strong> <span id="pare_name">Cecilia Adongo</span></p>
                                    <p><strong>✔ Parent Relation</strong> <span id="pare_relations">Mother</span></p>
                                    <p><strong>✔ Parent Residence</strong> <span id="pare_residences">Kijabe, Kenya</span></p>
                                    <p><strong>✔ Parent Phone</strong>: <span id="pare_phoneids">0714151617</span> </p>
                                    <p><strong>✔ Parent Email</strong>: <span id="pare_maileds">parent@gmail.com</span></p>
                                </div>
                                <div class="call">
                                    <div class="call_option" id = "callers">
                                        <label><a href="tel:+254714152415" class = "link" >Call Cecilia Adongo  </a></label> <br>
                                        <img src="images/calls.png" alt="call" srcset="">
                                    </div>
                                    <div class="call_option" id="Mailersa">
                                        <label><a href="mailto:hilaryme452gmail.com" class = "link" >Mail Mrs Cecilia  </a></label>
                                        <img src="images/send_mail.png" alt="mail">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>