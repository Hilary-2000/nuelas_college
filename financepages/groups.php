<div class="contents animate hide" id="student_groups_win">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Student Groups</p>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>What is a Group?</strong></p>
                </div>
                <p>- A Group is a saved, named list of students built by applying filters (gender, course, admission date, etc). It spans classes, so it's useful for cohorts like "Hostel students Jan&ndash;April" that a class list can't capture.</p>
                <p>- Once saved, a Group's membership is fixed. It will not silently change later if a student's details change. Groups can be reused elsewhere, e.g. Charged Accounts.</p>
            </div>

            <div class="d-flex" style="gap:8px;margin-bottom:12px;">
                <button type="button" class="btn btn-sm btn-primary" id="show_create_group_btn"><i class="fas fa-plus"></i> Create New Group</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="show_manage_groups_btn"><i class="fas fa-list"></i> Manage Groups</button>
            </div>

            <!-- ============== CREATE GROUP PANEL ============== -->
            <div id="create_group_panel">
                <p id="group_filter_options_holder" class="hide"></p>

                <div class="d-flex flex-wrap align-items-center gap-3 my-2 p-2 bg-light border rounded" style="font-size:13px;">
                    <span class="text-secondary">Showing after filters: <strong id="group_filtered_count">-</strong></span>
                    <span class="text-muted">|</span>
                    <span class="text-secondary">Selected: <strong class="text-primary" id="group_selected_count">0</strong></span>
                </div>

                <!-- Filter Panel -->
                <div class="border border-primary rounded my-2" id="group_filter_panel">
                    <div class="bg-primary text-white px-3 py-2 d-flex align-items-center justify-content-between" style="cursor:pointer;border-radius:4px 4px 0 0;" id="group_filter_panel_toggle">
                        <span><i class="fas fa-filter mr-2"></i><strong>Filter Students</strong></span>
                        <i class="fas fa-chevron-up" id="group_filter_panel_chevron"></i>
                    </div>
                    <div id="group_filter_panel_body" class="p-3">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Gender</label>
                                <select class="form-control form-control-sm w-100" id="gf_gender">
                                    <option value="all">All</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Course Level</label>
                                <select class="form-control form-control-sm w-100" id="gf_course_level">
                                    <option value="all">All Levels</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Course / Programme</label>
                                <select class="form-control form-control-sm w-100" id="gf_course">
                                    <option value="all">All Courses</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Study Mode</label>
                                <select class="form-control form-control-sm w-100" id="gf_study_mode">
                                    <option value="all">All Modes</option>
                                    <option value="fulltime">Fulltime</option>
                                    <option value="evening">Hybrid</option>
                                    <option value="weekend">Weekend</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Branch</label>
                                <select class="form-control form-control-sm w-100" id="gf_branch">
                                    <option value="all">All Branches</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Boarding Status</label>
                                <select class="form-control form-control-sm w-100" id="gf_boarding_status">
                                    <option value="all">All</option>
                                    <option value="enrolled">Enrolled (Boarding)</option>
                                    <option value="enroll">Pending Enrollment</option>
                                    <option value="none">Day Scholar</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Dormitory</label>
                                <select class="form-control form-control-sm w-100" id="gf_dormitory">
                                    <option value="all">All Dormitories</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Intake Year</label>
                                <select class="form-control form-control-sm w-100" id="gf_intake_year">
                                    <option value="all">All Years</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Intake Month</label>
                                <select class="form-control form-control-sm w-100" id="gf_intake_month">
                                    <option value="all">All Months</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Admission Date Range</label>
                                <div class="d-flex" style="gap:8px;">
                                    <input type="date" class="form-control form-control-sm w-100" id="gf_doa_from">
                                    <input type="date" class="form-control form-control-sm w-100" id="gf_doa_to">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-control-label" style="font-weight:600;font-size:13px;">Student Status</label>
                                <select class="form-control form-control-sm w-100" id="gf_student_status">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="group_clear_filters">
                                <i class="fas fa-times mr-1"></i> Clear All
                            </button>
                            <button type="button" class="btn btn-sm btn-primary" id="group_apply_filters">
                                <i class="fas fa-search mr-1"></i> Apply Filters &amp; Load Students
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Matching students -->
                <div id="group_student_list_wrap" class="hide">
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                        <span class="text-primary" style="font-weight:600;font-size:13px;">Select the students to include in this group</span>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary mr-1" id="group_select_all_btn">
                                <i class="fas fa-check-square mr-1"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="group_deselect_all_btn">
                                <i class="fas fa-square mr-1"></i> Deselect All
                            </button>
                        </div>
                    </div>
                    <input type="text" class="form-control form-control-sm mb-2" id="group_search_box" placeholder="Search this list by name or admission number...">
                    <div id="group_students_holder" style="max-height:400px;overflow-y:auto;border:1px solid #eee;border-radius:6px;"></div>

                    <hr class="my-2">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-control-label"><b>Group Name</b></label>
                            <input type="text" class="form-control" id="new_group_name" placeholder="e.g. Hostel students Jan-April">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-control-label"><b>Description (optional)</b></label>
                            <textarea class="form-control" id="new_group_description" rows="5" placeholder="Short note about this group"></textarea>
                        </div>
                    </div>
                    <p id="group_save_feedback"></p>
                    <button type="button" class="btn btn-success" id="save_group_btn"><i class="fas fa-save"></i> Save Group</button>
                </div>
            </div>

            <!-- ============== MANAGE GROUPS PANEL ============== -->
            <div id="manage_groups_panel" class="hide">
                <p id="manage_groups_feedback"></p>
                <div id="student_groups_list_holder"></div>
            </div>
        </div>
    </div>

    <!-- ============== EDIT GROUP MODAL ============== -->
    <div class="confirmpaymentwindow hide" id="edit_group_modal">
        <div class="changesubwindow animate">
            <div class="conts">
                <p class="funga" id="close_edit_group_modal">&times;</p>
                <h5 class="text-center"><b>Edit Group</b></h5>
            </div>
            <input type="hidden" id="edit_group_id">
            <div class="mb-2">
                <label class="form-control-label"><b>Group Name</b></label>
                <input type="text" class="form-control" id="edit_group_name">
            </div>
            <div class="mb-2">
                <label class="form-control-label"><b>Description</b></label>
                <textarea class="form-control" id="edit_group_description" rows="5"></textarea>
            </div>
            <p id="edit_group_feedback"></p>
            <div class="btns">
                <button type="button" id="save_edit_group_btn">Save Changes</button>
                <button type="button" id="cancel_edit_group_btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- ============== DELETE GROUP MODAL ============== -->
    <div class="confirmpaymentwindow hide" id="delete_group_modal">
        <div class="confirmpayment animate">
            <h6 class="text-center">Delete Group</h6>
            <p>Are you sure you want to delete <b id="delete_group_name_label"></b>? This cannot be undone.</p>
            <input type="hidden" id="delete_group_id">
            <div class="btns">
                <button type="button" id="confirm_delete_group_btn">Yes</button>
                <button type="button" id="cancel_delete_group_btn">No</button>
            </div>
        </div>
    </div>

    <!-- ============== MANAGE MEMBERS MODAL ============== -->
    <div class="confirmpaymentwindow hide" style="overflow:auto;" id="manage_members_modal">
        <div class="window_lg animate">
            <div class="conts">
                <p class="funga" id="close_manage_members_modal">&times;</p>
                <h5 class="text-center"><b>Manage Students: <span id="manage_members_group_name"></span></b></h5>
            </div>
            <input type="hidden" id="manage_members_group_id">

            <h6>Current Members (<span id="manage_members_count">0</span>)</h6>
            <input type="text" class="form-control form-control-sm mb-2" id="mm_current_members_search_box" placeholder="Search current members by name or admission number...">
            <div id="manage_members_current_holder" style="max-height:220px;overflow-y:auto;border:1px solid #eee;border-radius:6px;" class="mb-3"></div>

            <hr>
            <div class="border border-primary rounded my-2" id="mm_filter_panel">
                <div class="bg-primary text-white px-3 py-2 d-flex align-items-center justify-content-between" style="cursor:pointer;border-radius:4px 4px 0 0;" id="mm_filter_panel_toggle">
                    <span><i class="fas fa-filter mr-2"></i><strong>Add Students</strong></span>
                    <i class="fas fa-chevron-up" id="mm_filter_panel_chevron"></i>
                </div>
                <div id="mm_filter_panel_body" class="p-3">
                    <p class="text-muted" style="font-size:12px;"><i class="fas fa-info-circle"></i> Students already in this group are not shown in the results below; see Current Members above.</p>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Gender</label>
                            <select class="form-control form-control-sm" id="mm_gender">
                                <option value="all">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Course Level</label>
                            <select class="form-control form-control-sm" id="mm_course_level">
                                <option value="all">All Levels</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Course</label>
                            <select class="form-control form-control-sm" id="mm_course">
                                <option value="all">All Courses</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Boarding Status</label>
                            <select class="form-control form-control-sm" id="mm_boarding_status">
                                <option value="all">All</option>
                                <option value="enrolled">Enrolled (Boarding)</option>
                                <option value="enroll">Pending Enrollment</option>
                                <option value="none">Day Scholar</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Dormitory</label>
                            <select class="form-control form-control-sm" id="mm_dormitory">
                                <option value="all">All Dormitories</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Intake Year</label>
                            <select class="form-control form-control-sm" id="mm_intake_year">
                                <option value="all">All Years</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Intake Month</label>
                            <select class="form-control form-control-sm" id="mm_intake_month">
                                <option value="all">All Months</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-control-label" style="font-size:12px;font-weight:600;">Admission Date Range</label>
                            <div class="d-flex" style="gap:8px;">
                                <input type="date" class="form-control form-control-sm w-100" id="mm_doa_from">
                                <input type="date" class="form-control form-control-sm w-100" id="mm_doa_to">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-2" id="mm_search_btn"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>

            <div id="mm_search_results_wrap" class="hide">
                <input type="text" class="form-control form-control-sm mb-2" id="mm_results_search_box" placeholder="Search this list by name or admission number...">
                <div id="manage_members_search_results" style="max-height:250px;overflow-y:auto;border:1px solid #eee;border-radius:6px;"></div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-sm btn-success" id="mm_add_selected_btn"><i class="fas fa-plus"></i> Add Selected</button>
                </div>
            </div>
            <p id="manage_members_feedback"></p>

            <div class="btns">
                <button type="button" id="close_manage_members_btn">Close</button>
            </div>
        </div>
    </div>
</div>
