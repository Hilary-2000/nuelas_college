<div class="contents animate hide" id = "send_sms">
    <div class="titled">
        <h6>Broadcast Messages</h6>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Broadcast Messages</p>
        </div>
        <div class="middle1">
            <p class="hide" id="loading_my_sms_here"></p>
            <div class="setting_s pt-0">
                <div id="sms_loaders_window"></div>
                <div id="email_loaders_window"></div>
                <div class="bg-secondary mt-0">
                    <p class="text-center text-white">What you should know:</p>
                </div>
                <div class="conts p-1">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="conts">
                                <p>- At this window you are able to view your balances</p>
                                <p>- View the statistics of the messages you have sent over time and their delivery reports</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <u>Try Message Templates Now!</u>
                            <button type="button" id="message_templates_setup_btn">Message Templates</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting_s">
                <div class="bg-secondary mt-0">
                    <p class="text-center text-white">Send a message to a recipient</p>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-sm btn-primary active" id="rather_send_sms_btn" data-bs-toggle="tab" data-bs-target="#send_sms_window" type="button" role="tab" aria-controls="send_sms_window" aria-selected="true"><i class="fas fa-paper-plane"></i> Send SMS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-sm btn-primary" id="rather_send_email_btn" data-bs-toggle="tab" data-bs-target="#send_email_window" type="button" role="tab" aria-controls="send_email_window" aria-selected="false"><i class="fas fa-paper-plane"></i> Send Email</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active p-1 m-1" id="send_sms_window" role="tabpanel" aria-labelledby="rather_send_sms_btn">
                        <h5 class="title text-center"><u>Send SMS</u></h5>
                        <p>Start by selecting your recipient, you can either enter their phone number or select a staff.</p>
                        <label class="form-control-label" for="select_recipients1"> <br> Select recipients: </label><br>
                        <select class="form-control" name="select_recipients1" id="select_recipients1">
                            <option value="" hidden>Select recipient..</option>
                            <option value="phone_no">Enter Phone No.</option>
                            <option value="my_staff">My staff</option>
                        </select>

                        <div class="conts" id="sms_information">
                            <div class="hide" id="enter_phone">
                                <label class="form-control-label" for="staff_phones">Enter phone number: </label><br>
                                <input class="form-control" type="number" name="staff_phones" id="staff_phones" placeholder = "Phone number"> <br>
                            </div>

                            <div class="hide" id="select_tr">
                                <label class="form-control-label" for="select_staff_sms">Select staff below: <br></label>
                                <p id="my_staff_info"></p>
                            </div>

                            <label for="text_message">Message: <br></label>
                            <p style="color:gray;font-size:12px;" ><span id="char_count">0</span>/160 characters (One unit for 160 characters)</p>

                            <textarea class="form-control fx-12" name="text_message" id="text_message" cols="30" rows="5" maxlength="160"  placeholder = "Type your text message here"></textarea><br>

                            <p class="" id="out_put22"></p>
                            <p id="out_put223"></p>

                            <span class="btn btn-primary" type='button' id="send_sms_btns"><i class="fas fa-paper-plane"></i> Send</span>
                        </div>
                    </div>
                    <div class="tab-pane fade p-1 m-1" id="send_email_window" role="tabpanel" aria-labelledby="rather_send_email_btn">
                        <h5 class="title text-center"><u>Send E-Mail</u></h5>
                        <div class="tableme" id="my_advance_application_history">
                            <p>Start by selecting your recipient, you can either enter their email address or select a staff.</p>
                            <label class="form-control-label" for="email_recipient"> <br> Select recipients: </label><br>
                            <select class="form-control" name="email_recipient" id="email_recipient">
                                <option value="" hidden>Select recipient..</option>
                                <option value="email">Enter Email Address.</option>
                                <option value="my_staff">My staff</option>
                            </select>

                            <div class="conts" id="email_information">
                                <div class="hide my-0" id="enter_client_email_addr">
                                    <label class="form-control-label" for="staff_email_addressess">Enter E-Mail Address: </label><br>
                                    <input class="form-control my-0" type="text" name="staff_email_addressess" id="staff_email_addressess" placeholder = "Email Address"> <br>
                                </div>

                                <div class="hide" id="select_tr_email">
                                    <label class="form-control-label" for="select_staff_emails">Select staff below: <br></label>
                                    <p id="my_staff_emails"></p>
                                </div>
                                
                                <label for="carbon_copy1" class="form-control-label">CC.</label>
                                <input type="text" name="carbon_copy1" id="carbon_copy1" class="form-control" placeholder="Carbon Copy(optional)">
                                
                                <label for="blind_carbon_copy1" class="form-control-label">BCC.</label>
                                <input type="text" name="blind_carbon_copy1" id="blind_carbon_copy1" class="form-control" placeholder="Blind CC(optional)">

                                <label for="email_header" class="form-control-label">Message Subject.</label>
                                <input type="text" name="email_header" id="email_header" class="form-control" placeholder="E-Mail Subject">

                                <label for="email_messages">Message: <br></label>
                                <div id="email_messages" style="min-height:120px;"></div><br>
                                
                                <p class="hide" id="email_not_setup_notify"></p>
                                <span class="btn btn-primary" type='button' id="send_email_button"><i class="fas fa-paper-plane"></i> Send <span class="hide" id="load_email_sending"><img src="images/ajax_clock_small.gif"></span></span>
                                <br><span id="email_send_errors"></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact</div> -->
                </div>
            </div>
            <div class="setting_s">
                <div class="bg-secondary mt-0">
                    <p class="text-center text-white">Send broadcast message.</p>
                </div>
                <div class="conts p-1" id = "print_reminded">
                    <div class="instructions"><p><strong>Hint:</strong></p><p>- Broadcast messages will only allow you to send messages to either your <b>staff</b> or <b>Parent</b></p></div>
                    <label for="select_recipients2">Select recipients: <br></label><br>
                    <select class="form-control" name="select_recipients2" id="select_recipients2">
                        <option value="" hidden>Select recipient..</option>
                        <option value="my_staff">My staff</option>
                        <option value="parents">Parents and Students</option>
                    </select>
                    <div class="conts hide" id="staffs_list_ms">
                        <div class="instructions"><p><strong>Hint:</strong></p><p>- Select staff you want to send message to:</p></div>
                        <label for="">To My staff Except: <br></label>
                        <p id="staff_my_lists"></p>
                        <!--<div class="staff_list">
                            <div class="staff_dets">
                                <label>1.</label>
                                <label for="staff1">James st Patrick</label>
                                <input type="checkbox" name="staff1" id="staff1">
                            </div>
                            <div class="staff_dets">
                                <label>2.</label>
                                <label for="staff2">James st Patrick</label>
                                <input type="checkbox" name="staff2" id="staff2">
                            </div>
                            <div class="staff_dets">
                                <label>3.</label>
                                <label for="staff3">James st Patrick</label>
                                <input type="checkbox" name="staff3" id="staff3">
                            </div>
                            <div class="staff_dets">
                                <label>4.</label>
                                <label for="staff1">James st Patrick</label>
                                <input type="checkbox" name="staff1" id="staff1">
                            </div>
                            <div class="staff_dets">
                                <label>5.</label>
                                <label for="staff2">James st Patrick</label>
                                <input type="checkbox" name="staff2" id="staff2">
                            </div>
                        </div>-->
                    </div>
                    <div class="conts hide" id="students_parents">
                        <!-- Hidden holders -->
                        <p id="seleceted_class" class="hide"></p>
                        <p id="broadcast_filter_options_holder" class="hide"></p>

                        <!-- Stats -->
                        <div class="bc_stats">
                            <div class="bc_stat">
                                <span class="bc_stat_icon"><i class="fas fa-user-graduate"></i></span>
                                <div><b id="all_parents">0</b><small>Total students</small></div>
                            </div>
                            <div class="bc_stat">
                                <span class="bc_stat_icon"><i class="fas fa-filter"></i></span>
                                <div><b id="filtered_students_count">...</b><small>Showing after filters</small></div>
                            </div>
                            <div class="bc_stat bc_stat_selected">
                                <span class="bc_stat_icon"><i class="fas fa-check-circle"></i></span>
                                <div><b id="excempt_list">0</b><small>Selected to receive</small></div>
                            </div>
                        </div>

                        <!-- Filter Panel -->
                        <div class="bc_filters" id="broadcast_filter_panel">
                            <div class="bc_filters_head" id="filter_panel_toggle" role="button" tabindex="0">
                                <span class="bc_filters_icon"><i class="fas fa-sliders-h"></i></span>
                                <div class="bc_filters_title"><b>Filter Students</b><small>Narrow down who gets the message, then load the list</small></div>
                                <span class="bc_filters_badge" id="active_filter_count" style="display:none;"></span>
                                <i class="fas fa-chevron-up bc_filters_chevron" id="filter_panel_chevron"></i>
                            </div>
                            <div id="filter_panel_body" class="bc_filters_body">
                                <div class="bc_group">
                                    <div class="bc_group_title"><i class="fas fa-user-check"></i> Student</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label">Student Status</label>
                                            <div class="bc_seg" role="radiogroup" aria-label="Student status">
                                                <input type="radio" name="filter_status" id="fs_all" value="all" checked><label for="fs_all">All</label>
                                                <input type="radio" name="filter_status" id="fs_active" value="active"><label for="fs_active">Active</label>
                                                <input type="radio" name="filter_status" id="fs_inactive" value="inactive"><label for="fs_inactive">Inactive</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label">Gender</label>
                                            <div class="bc_seg" role="radiogroup" aria-label="Gender">
                                                <input type="radio" name="filter_gender" id="fg_all" value="all" checked><label for="fg_all">All</label>
                                                <input type="radio" name="filter_gender" id="fg_male" value="Male"><label for="fg_male">Male</label>
                                                <input type="radio" name="filter_gender" id="fg_female" value="Female"><label for="fg_female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bc_group">
                                    <div class="bc_group_title"><i class="fas fa-graduation-cap"></i> Course</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label">Course Level</label>
                                            <div id="cl_list_msg"></div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label">Course / Programme</label>
                                            <div id="course_list_sms_holder">
                                                <select class="form-control form-control-sm w-100" disabled><option>Select a course level first...</option></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bc_group">
                                    <div class="bc_group_title"><i class="fas fa-calendar-alt"></i> Intake and branch</div>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label class="bc_label" for="filter_branch">Branch</label>
                                            <select class="form-control form-control-sm w-100" id="filter_branch">
                                                <option value="all">All Branches</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="bc_label" for="filter_intake_year">Intake Year</label>
                                            <select class="form-control form-control-sm w-100" id="filter_intake_year">
                                                <option value="all">All Years</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="bc_label" for="filter_intake_month">Intake Month</label>
                                            <select class="form-control form-control-sm w-100" id="filter_intake_month">
                                                <option value="all">All Months</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="bc_group">
                                    <div class="bc_group_title"><i class="fas fa-coins"></i> Module and fees</div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label" for="filter_module_end_date">Module End Date <span>(exact date)</span></label>
                                            <input type="date" class="form-control form-control-sm w-100" id="filter_module_end_date">
                                            <small class="bc_help">Students whose active module ends exactly on this date.</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="bc_label" for="filter_balance_min">Fee Balance Range <span>(live filter)</span></label>
                                            <div class="bc_range">
                                                <span class="bc_range_cur">Kes</span>
                                                <input type="number" class="form-control form-control-sm" id="filter_balance_min" placeholder="Min, e.g. 1000" min="0">
                                                <span class="bc_range_to">to</span>
                                                <input type="number" class="form-control form-control-sm" id="filter_balance_max" placeholder="Max, e.g. 50000" min="0">
                                            </div>
                                            <small class="bc_help">Filters instantly after students load. Leave blank to ignore.</small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter actions -->
                                <div class="bc_filters_actions">
                                    <button type="button" class="bc_btn_ghost" id="broadcast_clear_filters">
                                        <i class="fas fa-undo"></i> Clear All
                                    </button>
                                    <button type="button" id="broadcast_apply_filters">
                                        <i class="fas fa-search"></i> Apply Filters &amp; Load Students
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Student List (revealed after Apply Filters) -->
                        <div id="broadcast_student_list_wrap" class="bc_list hide">
                            <div class="bc_list_head">
                                <div class="bc_list_title"><i class="fas fa-users"></i> Showing <b id="filtered_students_count2">0</b> students</div>
                                <div class="bc_list_btns">
                                    <button type="button" class="bc_btn_ghost" id="broadcast_select_all_btn">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="bc_btn_ghost" id="broadcast_deselect_all_btn">
                                        <i class="far fa-square"></i> Deselect All
                                    </button>
                                </div>
                            </div>
                            <div class="bc_search">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control form-control-sm w-100" id="search_student_sms" placeholder="Search by name or admission no...">
                            </div>
                            <div id="parents_lists_nm"></div>
                        </div>
                    </div>
                    <div class="conts hide" id="parent_selections">
                        <label for="send_to_whom" class="form-control-label">Send to whom?</label>
                        <select name="send_to_whom" id="send_to_whom" class="form-control">
                            <option value="" hidden >Select an option</option>
                            <option value="preferred_contact">Preferred Contact</option>
                            <option value="all_three">All Three</option>
                            <option value="both">Both Parents</option>
                            <option value="student_contact">Student Only</option>
                            <option value="primary">Primary Parent</option>
                            <option value="secondary">Secondary Parent</option>
                        </select>
                    </div>
                    <div class="instructions">
                        <strong>Hint:</strong> Compose separately for each channel below. Recipients whose preferred channel is SMS get the SMS Message tab's content; recipients whose preferred channel is Email get the Email Message tab's content. <b>Always check and preview the Email tab before sending</b> &mdash; it is easy to fill in only the SMS box and forget the Email one.
                    </div>
                    <ul class="nav nav-tabs" id="broadcastComposeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-sm btn-primary active" id="broadcast_sms_tab_btn" data-bs-toggle="tab" data-bs-target="#broadcast_sms_tab" type="button" role="tab" aria-controls="broadcast_sms_tab" aria-selected="true"><i class="fas fa-sms"></i> SMS Message</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-sm btn-primary" id="broadcast_email_tab_btn" data-bs-toggle="tab" data-bs-target="#broadcast_email_tab" type="button" role="tab" aria-controls="broadcast_email_tab" aria-selected="false"><i class="fas fa-envelope"></i> Email Message</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="broadcastComposeTabContent">
                        <div class="tab-pane fade show active p-1 m-1" id="broadcast_sms_tab" role="tabpanel" aria-labelledby="broadcast_sms_tab_btn">
                            <label for="text_message2"><br> Write Message: <br></label><br>
                            <p style="color:gray;font-size:12px;" ><span id="chr_counts_in">0</span>/160 characters (One unit for 160 characters)</p>
                            <textarea class="form-control" name="text_message2" id="text_message2" cols="30" rows="10" maxlength="160" placeholder="Type your SMS message here"></textarea>
                        </div>
                        <div class="tab-pane fade p-1 m-1" id="broadcast_email_tab" role="tabpanel" aria-labelledby="broadcast_email_tab_btn">
                            <div class="container w-100">
                                <label for="cc_email_bulk" class="form-control-label">CC</label>
                                <input type="text" class="form-control w-100" id="cc_email_bulk" placeholder="CC">
                            </div>
                            <div class="container w-100">
                                <label for="bcc_email_bulk" class="form-control-label">BCC</label>
                                <input type="text" class="form-control w-100" id="bcc_email_bulk" placeholder="BCC">
                            </div>
                            <div class="container w-100">
                                <label for="email_bulk_subject" class="form-control-label">Subject</label>
                                <input type="text" class="form-control w-100" id="email_bulk_subject" value="Email from <?php echo ucwords(strtolower($_SESSION['schname']))?>" placeholder="Subject">
                            </div>
                            <label for="email_editored"><br> Write Message: <br></label><br>
                            <div id="email_editored" style="min-height:200px;"></div>
                        </div>
                    </div>
                    <div class="my-2 mx-2 d-none" id="message_tags_window">
                        <div class="msg_tags">
                            <div class="msg_tags_head"><i class="fas fa-tags"></i> Message Tags</div>
                            <p class="msg_tags_hint">Tags are filled in with each recipient's details when the message is sent. Click a tag to add it to the message, or type <b>/</b> in the message box to search the tags, and check the sample before sending. Next module fees is the current balance plus the next module's fee.</p>
                            <div class="msg_tags_group">
                                <div class="msg_tags_group_title">Student</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag" id="insert_tag1" title="Click to insert {stud_fullname}">Full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag2" title="Click to insert {stud_first_name}">First name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag3" title="Click to insert {stud_class}">Class <code>{stud_class}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag4" title="Click to insert {stud_age}">Age <code>{stud_age}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag14" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag13" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="msg_tags_group">
                                <div class="msg_tags_group_title">Fees</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag" id="insert_tag5" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag6" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag7" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag15" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="msg_tags_group">
                                <div class="msg_tags_group_title">Parent</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag" id="insert_tag8" title="Click to insert {par_fullname}">Full name <code>{par_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag10" title="Click to insert {par_first_name}">First name <code>{par_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag11" title="Click to insert {title_1}">Mr / Mrs <code>{title_1}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag" id="insert_tag12" title="Click to insert {title_2}">Sir / Madam <code>{title_2}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="msg_tags_group">
                                <div class="msg_tags_group_title">Date</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag" id="insert_tag9" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="msg_preview msg_preview_wide my-2" data-channel="sms" id="broadcast_message_preview">
                        <div class="msg_preview_head"><i class="fas fa-eye"></i> Message Sample <span class="msg_preview_chip" id="broadcast_preview_chip"><i class="fas fa-sms"></i> SMS</span></div>
                        <div class="msg_preview_body"><div class="msg_bubble" id="message_samples" data-placeholder="The sample will appear here as you type."></div></div>
                        <div class="msg_preview_meta"></div>
                    </div>
                    <p id="err_hands_error"></p>
                    <button type='button' id="send_msg_btns"><i class="fas fa-paper-plane"></i> Send message <span class="hide" id="load_bulk_emails_sending"><img src="images/ajax_clock_small.gif"></span></button>
                    <p id="broadcast_sms_result"></p>
                    <p id="broadcast_email_result"></p>
                    <p id="out_put"></p>
                </div>
            </div>
            <div class="setting_s d-none">
                <h6 style='text-align:center;color:cadetblue;' ><u>Send staff notice/Message.</u> </h6>
                <div class="instructions">
                    <p><strong>Hint:</strong></p>
                    <p>The message you send will appear as a notice in the staff portal.</p>
                </div>
                <div class="conts">
                    <label class="form-control-label" for="select_staff_infors">Select staff: </label><br>
                    <p id="staffs_l_s"></p>
                </div>
                <div class="conts">
                    <label class="form-control-label" for="type_notice_here">Type message <br></label>
                    <p style="color:gray;font-size:12px;" ><span id="chr_counts_in1">0</span>/160 characters</p>
                    <textarea name="type_notice_here" id="type_notice_here" cols="30" rows="10" maxlength="160" placeholder = "Type your message here" ></textarea><br>
                    <p id='notice_errors'></p>
                    <button type='button' id="send_post" ><i class="fas fa-paper-plane"></i> Send Notice</button>
                </div>
            </div>
            <div class="setting_s">
                <div class="bg-secondary mt-0">
                    <p class="text-center text-white">View your Message history.</p>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-sm btn-primary active" id="rather_view_sms_history" data-bs-toggle="tab" data-bs-target="#show_sms_windows" type="button" role="tab" aria-controls="show_sms_windows" aria-selected="true"><i class="fas fa-history"></i> SMS History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="btn btn-sm btn-primary" id="rather_view_email_history" data-bs-toggle="tab" data-bs-target="#show_email_windows" type="button" role="tab" aria-controls="show_email_windows" aria-selected="false"><i class="fas fa-history"></i> Email History</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent1">
                    <div class="tab-pane fade show active p-1 m-1" id="show_sms_windows" role="tabpanel" aria-labelledby="rather_view_sms_history">
                        <h5 class="title text-center"><u>View SMS History</u></h5>
                        <div class="">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-control-label" for="from_msg_sent">From: <br></label>
                                    <input type="date" class="form-control" name="from_msg_sent" id="from_msg_sent" value = <?php echo date("Y-m-d", strtotime("-167 hour"))?>>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="to_msg_sent">To:</label>
                                    <input class="form-control" type="date" name="to_msg_sent" id="to_msg_sent" value = <?php echo date("Y-m-d")?>><br>
                                </div>
                                <div class="col-md-4">
                                    <button role="button" id="view_sms_history"><i class="fas fa-eye"></i> View SMS history</button>
                                </div>
                            </div>
                            <p id="sms_checker_evt_handlers"></p>
                            <div class="conts" id="histotysms"></div>
                            <div class="conts" id="">
                                <h6 class="text-center">Messages Table</h6>
                                <div class="row" id="search_option_sms">
                                    <div class="col-md-6 form-group row">
                                        <input type="text" name="search" id="searchkey_sms" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                                    </div>
                                </div>
                                <div class="table-responsive" id="transDataReciever_sms">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th title="Sort all descending"># <span id="sortall_sms"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by Reg No descending">Message Type <span id="sort_message_type"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by Amount descending">Content <span id="sort_content"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by date descending">Date Sent <span id="sortdate_sms"><i class="fas fa-caret-down"></i></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1 <span class="text-success" title="Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                            <tr>
                                                <td>2 <span class="text-secondary"  title="Not Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                            <tr>
                                                <td>3 <span class="text-success"  title="Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- <div class='displaydata'><img class='' src='images/error.png'></div>
                                    <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p> -->
                                </div>
                                <div class="row mt-5" id="tablefooter_sms">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="container-fluid">
                                            <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_sms">1 </span> to <span class="text-primary" id="finishNo_sms">10</span> of <span id="tot_records_sms"></span> Records.</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item first" id="datatable_first"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_sms">First</a></li>
                                                <li class="paginate_button page-item previous mx-1" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_sms">Prev</a></li>
                                                <li class="paginate_button page-item previous active mx-3" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_sms">1</a></li>
                                                <li class="paginate_button page-item next mx-1" id="datatable_next"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_sms">Next</a></li>
                                                <li class="paginate_button page-item last mx-1" id="datatable_last"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_sms">Last</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-1 m-1" id="show_email_windows" role="tabpanel" aria-labelledby="rather_view_email_history">
                        <h5 class="title text-center"><u>View E-Mail History</u></h5>
                        <div class="">
                            <p id="email_checker_evt_handlers"></p>
                            <div class="conts" id="histotyemail"></div>
                            <div class="conts" id="">
                                <h6 class="text-center">Email Table</h6>
                                <div class="row" id="search_option_email">
                                    <div class="col-md-6 form-group row">
                                        <input type="text" name="search" id="searchkey_email" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                                    </div>
                                </div>
                                <div class="table-responsive" id="transDataReciever_email">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th title="Sort all descending"># <span id="sortall_email"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by Reg No descending">Sent To <span id="sort_message_type"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by Amount descending">Content <span id="sort_content_email"><i class="fas fa-caret-down"></i></span></th>
                                                <th  title="Sort by date descending">Date Sent <span id="sortdate_email"><i class="fas fa-caret-down"></i></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1 <span class="text-success" title="Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>mail@ladybirdsmis.com <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                            <tr>
                                                <td>2 <span class="text-secondary"  title="Not Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>mail@ladybirdsmis. <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                            <tr>
                                                <td>3 <span class="text-success"  title="Charged"><i class="fas fa-coins"></i></span></td>
                                                <td>mail@ladybirdsmis. <span class="badge badge-success"> </span></td>
                                                <td>Hello Hillary see me at my office at 5 after classes</td>
                                                <td>14th June 2021</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- <div class='displaydata'><img class='' src='images/error.png'></div>
                                    <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p> -->
                                </div>
                                <div class="row mt-5" id="tablefooter_email">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="container-fluid">
                                            <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_email">1 </span> to <span class="text-primary" id="finishNo_email">10</span> of <span id="tot_records_email"></span> Records.</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate_email">
                                            <ul class="pagination">
                                                <li class="paginate_button page-item first" id="datatable_first_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_email">First</a></li>
                                                <li class="paginate_button page-item previous mx-1" id="datatable_previous_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_email">Prev</a></li>
                                                <li class="paginate_button page-item previous active mx-3" id="datatable_previous_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_email">1</a></li>
                                                <li class="paginate_button page-item next mx-1" id="datatable_next_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_email">Next</a></li>
                                                <li class="paginate_button page-item last mx-1" id="datatable_last_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_email">Last</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact</div> -->
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>