<div class="contents animate hide" id="human_resource_windows">
    <div class="titled">
        <h2>Human Resource</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Leave Management</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
            <!-- <input type="text"  id="myname" hidden value = <?php if(isset($_SESSION['username'])){ echo $_SESSION['username']; } ?> > -->
        </div>
        <div class="middle1">
            <div class="instructions">
                <div class="notify">
                    <p><strong>Hint:</strong></p>
                </div>
                <p>- Manage your Employees Leave Information.</p>
                <p>- Manage your Employees employment information.</p>
            </div>
            <!-- Manage Leaves is the only option, so these selects stay hidden; the
                 tab cards below drive leaves_options (admissions.js selectLeaveTab) -->
            <select name="hr_options" id="hr_options" class="d-none">
                <option value="" hidden>Select an option</option>
                <option value="manage_leaves" selected>Manage Leaves</option>
            </select>
            <div class="section_card lv_card" id="leave_management_window">
                <select name="leaves_options" id="leaves_options" class="d-none">
                    <option value="" hidden>Select an action</option>
                    <option value="view leave application">View Leave Applications</option>
                    <option value="view leave categories">Manage Leave Categories</option>
                </select>
                <div class="lv_tabs" id="leave_tabs">
                    <div class="lv_tab" data-option="view leave application" role="button" tabindex="0">
                        <span class="lv_tab_icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="lv_tab_text"><b>Leave Applications</b><small>Review, accept or reject staff leave requests</small></span>
                    </div>
                    <div class="lv_tab" data-option="view leave categories" role="button" tabindex="0">
                        <span class="lv_tab_icon"><i class="fas fa-layer-group"></i></span>
                        <span class="lv_tab_text"><b>Leave Categories</b><small>Annual, sick and other leave types and their days</small></span>
                    </div>
                </div>
                <!-- manage leave categories -->
                <div class="leave_displays my-2 animate hide" id="leave_diplay_windows">
                    <div class="lv_head">
                        <span class="lv_head_icon"><i class="fas fa-layer-group"></i></span>
                        <div><b>Leave Categories <img class="hide" src="images/ajax_clock_small.gif" id="load_leaves_table"></b><small>The leave types staff can apply for and how many days each allows.</small></div>
                        <div class="lv_btn" id="add_leave_category" role="button" tabindex="0"><i class="fas fa-plus"></i> Add Leave Category</div>
                    </div>
                    <span id="leave_tables_display"></span>
                    <!-- <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Accrued</th>
                            <th>Genders</th>
                            <th>Max Days</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Annual Leave</td>
                            <td>Monthly</td>
                            <td>Male</td>
                            <td>21</td>
                            <td><small><span class="link"><i class="fas fa-pen-fancy"></i> Edit</span>  <span class="link"><i class="fas fa-trash-alt"></i> Delete</span></small></td>
                        </tr>
                    </table>
                    <p>Showing 1 to 6 of 6 records</p> -->
                </div>
                <div class="leave_displays my-2 animate hide" id="add_leave_cat_window">
                    <div class="lv_back"><span class="link" id="go_back_leave_list" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to leave categories</span></div>
                    <div class="lv_head">
                        <span class="lv_head_icon"><i class="fas fa-plus"></i></span>
                        <div><b>Add Leave Category</b><small>Set who can take it, how many days it allows and how the days build up.</small></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_title_name" class="form-control-label">Leave Title/Name</label>
                                <input type="text" id="leave_title_name" class="form-control w-100" placeholder="Leave Name">
                            </div>
                            <div class="form-group">
                                <label for="gender_eligible" class="form-control-label">Gender Eligible</label>
                                <select name="" id="gender_eligible" class="form-control w-100">
                                    <option value="" hidden>Select Gender</option>
                                    <option value="All">All</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_per_yr" class="form-control-label">Maximum Days per Year</label>
                                <input type="number" name="" id="max_days_per_yr" class="form-control w-100" placeholder="Max Days">
                            </div>
                            <div class="form-group">
                                <label for="leave_status" class="form-control-label">Leave Status</label>
                                <select name="" id="leave_status" class="form-control w-100">
                                    <option value="" id="p_8" hidden>Select Period</option>
                                    <option value="1">Active</option>
                                    <option value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_year_start" class="form-control-label">Leaves Year Starts</label>
                                <select name="" id="leave_year_start" class="form-control w-100">
                                    <option value="" id="p_7" hidden>Select Period</option>
                                    <option value="Start Of Academic Yr">Start Of Academic Yr</option>
                                    <option value="Start of january">Start Of January</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="days_accrued" class="form-control-label">Days are accrued?</label>
                                <select name="" id="days_accrued" class="form-control w-100">
                                    <option id="p_6" value="" hidden>Select Period</option>
                                    <option value="Yearly">Yearly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Weekly">Weekly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="period_to_accrued" class="form-control-label">Period accrued?</label>
                                <select name="" id="period_to_accrued" class="form-control w-100">
                                    <option id="p_0" value="" hidden>Select Period</option>
                                    <option id="p_1" value="Start Of Year">Start Of Year</option>
                                    <option id="p_2" value="Start Of Month">Start Of Month</option>
                                    <option id="p_3" value="End Of Month">End Of Month</option>
                                    <option id="p_4" value="Start Of Week">Start Of Week</option>
                                    <option id="p_5" value="End Of Week">End Of Week</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_carry_forward" class="form-control-label">Max Days Carry Forward ?</label>
                                <input type="number" name="" id="max_days_carry_forward" class="form-control w-100" placeholder="Max Days Carry Forward">
                            </div>
                        </div>
                    </div>
                    <p id="save_leave_cat"></p>
                    <div class="lv_actions"><div class="lv_btn" id="save_leave_category" role="button" tabindex="0"><i class="fas fa-save"></i> Save Leave Category <img class="hide" src="images/ajax_clock_small.gif" id="save_leave_cat_loader"></div></div>
                </div>
                <div class="leave_displays my-2 animate hide" id="edit_leave_cat_window">
                    <div class="lv_back"><span class="link" id="go_back_leave_list2" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to leave categories</span></div>
                    <div class="lv_head">
                        <span class="lv_head_icon"><i class="fas fa-pen"></i></span>
                        <div><b>Edit Leave Category</b><small>Change what is needed and save.</small></div>
                    </div>
                    <p class="hide" id="leave_data_holder"></p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="" id="leaves_id">
                                <label for="leave_title_name2" class="form-control-label">Leave Title/Name</label>
                                <input type="text" id="leave_title_name2" class="form-control w-100" placeholder="Leave Name">
                            </div>
                            <div class="form-group">
                                <label for="gender_eligible2" class="form-control-label">Gender Eligible</label>
                                <select name="" id="gender_eligible2" class="form-control w-100">
                                    <option id="p_19_19" value="" hidden>Select Gender</option>
                                    <option id="p_16_16" value="All">All</option>
                                    <option id="p_17_17" value="Male">Male</option>
                                    <option id="p_18_18" value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_per_yr2" class="form-control-label">Maximum Days per Year</label>
                                <input type="number" name="" id="max_days_per_yr2" class="form-control w-100" placeholder="Max Days">
                            </div>
                            <div class="form-group">
                                <label for="leave_status2" class="form-control-label">Leave Status</label>
                                <select name="" id="leave_status2" class="form-control w-100">
                                    <option value="" id="p_13_13" hidden>Select Period</option>
                                    <option id="p_14_14" value="1">Active</option>
                                    <option id="p_15_15" value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_year_start2" class="form-control-label">Leaves Year Starts</label>
                                <select name="" id="leave_year_start2" class="form-control w-100">
                                    <option value="" id="p_7_7" hidden>Select Period</option>
                                    <option id="p_8_8" value="Start Of Academic Yr">Start Of Academic Yr</option>
                                    <option id="p_9_9" value="Start of january">Start Of January</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="days_accrued2" class="form-control-label">Days are accrued?</label>
                                <select name="" id="days_accrued2" class="form-control w-100">
                                    <option id="p_6_6" value="" hidden>Select Period</option>
                                    <option id="p_10_10" value="Yearly">Yearly</option>
                                    <option id="p_11_11" value="Monthly">Monthly</option>
                                    <option id="p_12_12" value="Weekly">Weekly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="period_to_accrued2" class="form-control-label">Period accrued?</label>
                                <select name="" id="period_to_accrued2" class="form-control w-100">
                                    <option id="p_0_0" value="" hidden>Select Period</option>
                                    <option id="p_1_1" value="Start Of Year">Start Of Year</option>
                                    <option id="p_2_2" value="Start Of Month">Start Of Month</option>
                                    <option id="p_3_3" value="End Of Month">End Of Month</option>
                                    <option id="p_4_4" value="Start Of Week">Start Of Week</option>
                                    <option id="p_5_5" value="End Of Week">End Of Week</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_carry_forward2" class="form-control-label">Max Days Carry Forward ?</label>
                                <input type="number" name="" id="max_days_carry_forward2" class="form-control w-100" placeholder="Max Days Carry Forward">
                            </div>
                        </div>
                    </div>
                    <p id="save_leave_cat2"></p>
                    <div class="lv_actions"><div class="lv_btn" id="update_leave_category" role="button" tabindex="0"><i class="fas fa-save"></i> Update Leave Category <img class="hide" src="images/ajax_clock_small.gif" id="save_leave_cat_loader2"></div></div>
                </div>
                <div class="leave_displays my-2 animate hide" id="all_leaves_application">
                    <div class="lv_head">
                        <span class="lv_head_icon"><i class="fas fa-calendar-check"></i></span>
                        <div><b>Leave Applications <img class="hide" src="images/ajax_clock_small.gif" id="leaves_application_loaders"></b><small>Pending applications can be accepted or rejected; view any application for its details.</small></div>
                    </div>
                    <p class="hide" id="my_leaves_application"></p>
                    <div class="lv_filters" id="search_option_leaves">
                        <div class="lv_search">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" id="searchkey_leaves" class="form-control w-100" placeholder="Search by applicant, leave type or date...">
                        </div>
                        <div>
                            <select name="choose_status" id="choose_leave_status" class="form-control w-100">
                                <option value="">All statuses</option>
                                <option value="0">Pending</option>
                                <option value="1">Accepted</option>
                                <option value="2">Declined</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive" id="transDataReciever_leave_apply">
                        <p class="lv_hint">Loading leave applications...</p>
                    </div>
                    <div class="row mt-5" id="tablefooter_leave_apply">
                        <div class="col-sm-12 col-md-5">
                            <div class="container-fluid">
                                <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_leave_apply">1 </span> to <span class="text-primary" id="finishNo_leave_apply">10</span> of <span id="tot_records_leave_apply"></span> Records.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item first" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_leave_apply">First</a></li>
                                    <li class="paginate_button page-item previous mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_leave_apply">Prev</a></li>
                                    <li class="paginate_button page-item previous active mx-3" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_leave_apply">1</a></li>
                                    <li class="paginate_button page-item next mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_leave_apply">Next</a></li>
                                    <li class="paginate_button page-item last mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_leave_apply">Last</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="leave_displays my-2 animate hide" id="view_applied_leaves_windows">
                    <div class="lv_back"><span class="link" id="back_to_leave_list" role="button" tabindex="0"><i class="fas fa-arrow-left"></i> Back to leave applications</span></div>
                    <div class="lv_head">
                        <span class="lv_head_icon"><i class="fas fa-file-alt"></i></span>
                        <div><b>Leave Application <img class="hide" src="images/ajax_clock_small.gif" id="view_leaves"></b><small>The details of this application. Change its status below.</small></div>
                        <input type="hidden" id="unussual_id">
                        <span class="lv_btn_delete" id="delete_this_application" role="button" tabindex="0" title="Delete this application"><i class="fas fa-trash"></i> Delete</span>
                    </div>
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_applicant_names_view" class="form-control-label">Applicant Names</label>
                                    <input type="text" name="" placeholder="Applicant Name" readonly id="leave_applicant_names_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_title_view" class="form-control-label">Leave Title</label>
                                    <input type="text" name="" placeholder="Leave Title" readonly id="leave_title_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leaf_duration_view" class="form-control-label">Leave Duration</label>
                                    <input type="text" name="" placeholder="Leave Duration" readonly id="leaf_duration_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_from_date_view" class="form-control-label">Leave Starting Date</label>
                                    <input type="text" date="" placeholder="Leave Started On" readonly id="leave_from_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_to_date_view" class="form-control-label">Leave Ending Date</label>
                                    <input type="text" date="" placeholder="Leaves Ends On" readonly id="leave_to_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_application_date_view" class="form-control-label">Leave Application Date</label>
                                    <input type="text" name="" placeholder="Leave Application Date" readonly id="leave_application_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" id="leaves_view_id">
                                    <label for="leaves_status" class="form-control-label">Leave Status</label>
                                    <select name="choose_status" id="choose_leave_status2" class="form-control w-100">
                                        <option id="p_101" value="">Select Leave status</option>
                                        <option id="p_102" value="0">Pending</option>
                                        <option id="p_103" value="1">Accepted</option>
                                        <option id="p_104" value="2">Declined</option>
                                    </select>
                                    <span id="leaves_status_displayer"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_description_view" class="form-control-label">Leave Description</label>
                                    <textarea name="" id="leave_description_view" placeholder="Leave Description" cols="30" rows="10" class="form-control" placeholder="Description for leave application will appear here.."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="leaves_options_views">
                                    <label class="form-control-label">Decision</label>
                                    <div class="lv_decide">
                                        <span id="accept_leave_applications" class="lv_mbtn lv_mbtn_ok" role="button" tabindex="0"><i class="fas fa-check"></i> Accept</span>
                                        <span id="decline_leave_applications" class="lv_mbtn lv_mbtn_bad" role="button" tabindex="0"><i class="fas fa-times"></i> Reject</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hide" id="employees_management_window"></div>
            <div class="hide" id="error_hr_selection"></div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>