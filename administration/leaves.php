<div class="contents animate hide" id="leave_mgmt_staff">
    <div class="titled">
        <h2>Employees Leave Management</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Employees Leave Management</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <p><strong>Hint:</strong></p>
                <p>Welcome <?php echo ucwords(strtolower($_SESSION['fullnames']));?> to the Employees Leave management window, At this window you will be able to apply for leaves and also view your leave application history.</p>
            </div>
            <div class="section_card hide my_leaves_view al_card" id="apply_leaves_windows">
                <div class="al_back"><span class="link" id="back_to_list_emp_leave_list" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to my leave applications</span></div>
                <div class="al_head">
                    <span class="al_head_icon"><i class="fas fa-plane-departure"></i></span>
                    <div><b>Apply for Leave</b><small>Pick the leave type and your first and last day off. Weekends, non-working days and public holidays are not counted.</small></div>
                </div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="al_group">
                            <div class="al_group_title"><i class="fas fa-layer-group"></i> Leave type</div>
                            <label for="leave_type" class="form-control-label">Select leave <img class="hide" src="images/ajax_clock_small.gif" id="leave_loader_select"></label>
                            <span id="leave_category_select"></span>
                            <div class="al_balance_box">
                                <small>Your balance <img class="hide" src="images/ajax_clock_small.gif" id="leave_balance_loader"></small>
                                <div id="leave_balance_apply">Select leave category</div>
                            </div>
                        </div>
                        <div class="al_group">
                            <div class="al_group_title"><i class="fas fa-calendar-alt"></i> Dates</div>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label for="from_leave_date" class="form-control-label">First day of leave</label>
                                    <input class="form-control w-100" type="date" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="from_leave_date" id="from_leave_date">
                                </div>
                                <div class="col-sm-6">
                                    <label for="to_leaves_date" class="form-control-label">Last day of leave</label>
                                    <input class="form-control w-100" type="date" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="to_leaves_date" id="to_leaves_date">
                                </div>
                            </div>
                            <small class="al_help">Leave must be applied for at least a week ahead.</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="al_group">
                            <div class="al_group_title"><i class="fas fa-calculator"></i> Working days</div>
                            <div class="al_days">
                                <input type="number" readonly name="leave_duration" value="0" id="leave_duration" class="form-control">
                                <span>working day(s) <img class="hide" src="images/ajax_clock_small.gif" id="leave_duration_loader"></span>
                            </div>
                            <p class="hide" id="leave_days_holder"></p>
                            <div id="duration_day_errors"></div>
                        </div>
                        <div class="al_group">
                            <div class="al_group_title"><i class="fas fa-comment-alt"></i> Reason</div>
                            <label for="leave_comments" class="form-control-label">Leave Description</label>
                            <textarea name="leave_comments" id="leave_comments" rows="4" class="form-control w-100" placeholder="Describe why you are applying for the leave"></textarea>
                        </div>
                        <div class="al_actions">
                            <p id="application_error"></p>
                            <div class="al_btn" id="apply_leave" role="button" tabindex="0"><i class="fas fa-paper-plane"></i> Apply Now <img class="hide" src="images/ajax_clock_small.gif" id="apply_leave_loader"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section_card my_leaves_view al_card" id="display_my_applied_leaves">
                <div class="al_head">
                    <span class="al_head_icon"><i class="fas fa-calendar-check"></i></span>
                    <div><b>My Leave Applications <img class="hide" src="images/ajax_clock_small.gif" id="my_leave_list_loader"></b><small>Every leave you have applied for and whether it was approved.</small></div>
                    <div class="al_btn" id="self_apply_for_leave" role="button" tabindex="0"><i class="fas fa-plus"></i> Apply for Leave</div>
                </div>
                <div id="my_application_table_data">
                    <p class="al_hint">Loading your leave applications...</p>
                </div>
            </div>
            <div class="section_card hide my_leaves_view al_card" id="view_leave_details_emp">
                <div class="al_back"><span class="link" id="back_to_list_emp_leave_list_2" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to my leave applications</span></div>
                <div class="al_head">
                    <span class="al_head_icon"><i class="fas fa-file-alt"></i></span>
                    <div><b>Leave Application <img class="hide" src="images/ajax_clock_small.gif" id="load_leave_details"></b><small>The details of this application.</small></div>
                    <span id="my_leave_status"></span>
                </div>
                <p class="hide" id="leave_details_result"></p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="leave_name_applied" class="form-control-label">Leave</label>
                        <input type="text" readonly placeholder="Leave Title" id="leave_name_applied" class="form-control w-100">
                    </div>
                    <div class="col-md-6">
                        <label for="leave_apply_date_views" class="form-control-label">Applied On</label>
                        <input type="text" readonly placeholder="Leave Apply Date" id="leave_apply_date_views" class="form-control w-100">
                    </div>
                    <div class="col-md-4">
                        <label for="from_leave_date_my_view" class="form-control-label">First day of leave</label>
                        <input class="form-control w-100" readonly type="text" id="from_leave_date_my_view">
                    </div>
                    <div class="col-md-4">
                        <label for="to_leaves_date_my_view" class="form-control-label">Last day of leave</label>
                        <input class="form-control w-100" readonly type="text" id="to_leaves_date_my_view">
                    </div>
                    <div class="col-md-4">
                        <label for="leave_duration_days_view" class="form-control-label">Working days</label>
                        <input type="number" readonly value="0" id="leave_duration_days_view" class="form-control w-100">
                    </div>
                    <div class="col-12">
                        <label for="leave_comments_my_view" class="form-control-label">Leave Description</label>
                        <textarea id="leave_comments_my_view" rows="4" readonly class="form-control w-100" placeholder="No description given."></textarea>
                        <p id="application_error_view"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>