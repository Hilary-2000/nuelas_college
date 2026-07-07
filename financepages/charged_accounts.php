<div class="contents animate hide" id="charged_accounts_win">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Charged Accounts</p>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>What is a Charged Account?</strong></p>
                </div>
                <p>- Use this for irregular one-off fees that don't fit as a permanent votehead, e.g. lost uniform items, exam fees, or graduation fees.</p>
                <p>- A charge is applied to specific students or to a saved Group. It shows up as a single "Charged Account" line, alongside the normal fee voteheads, wherever a student's payable items are listed.</p>
            </div>

            <div class="d-flex" style="gap:8px;margin-bottom:12px;">
                <button type="button" class="btn btn-sm btn-primary" id="show_create_charge_btn"><i class="fas fa-plus"></i> Create Charge</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="show_charge_history_btn"><i class="fas fa-history"></i> Charge History</button>
            </div>

            <!-- ============== CREATE CHARGE PANEL ============== -->
            <div id="create_charge_panel" class="border border-secondary rounded p-3">
                <p id="charge_groups_holder" class="hide"></p>
                <p id="charge_lookup_holder" class="hide"></p>
                <div class="mb-3">
                    <label class="form-control-label"><b>Apply this charge to</b></label>
                    <div class="d-flex mt-1" style="gap:24px;">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="charge_target_type" id="charge_target_students" value="students" checked>
                            <label class="form-check-label" for="charge_target_students">Specific Student(s)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="charge_target_type" id="charge_target_group" value="group">
                            <label class="form-check-label" for="charge_target_group">Group</label>
                        </div>
                    </div>
                </div>

                <!-- Students target -->
                <div id="charge_students_target_panel" class="mb-3">
                    <label class="form-control-label"><b>Student Name or Admission Number</b></label>
                    <div class="d-flex" style="gap:8px;">
                        <div class="autocomplete" style="max-width:400px;">
                            <input type="text" class="form-control" id="charge_admno_input" placeholder="Type a name or admission number">
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="add_charge_admno_btn">Add</button>
                    </div>
                    <p id="charge_admno_feedback"></p>
                    <div id="charge_admno_list" class="my-2"></div>
                </div>

                <!-- Group target -->
                <div id="charge_group_target_panel" class="hide mb-3">
                    <label class="form-control-label"><b>Select Group</b></label>
                    <select class="form-control" id="charge_group_select">
                        <option value="" hidden>Select a group...</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-control-label"><b>Description</b></label>
                        <input type="text" class="form-control" id="charge_description" placeholder="e.g. Lost cardigan">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-control-label"><b>Period</b></label>
                        <input type="text" class="form-control" id="charge_period" placeholder="e.g. Term 2 2026">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-control-label"><b>Amount (Kes)</b></label>
                        <input type="number" class="form-control" id="charge_amount" placeholder="e.g. 500" min="1">
                    </div>
                </div>
                <p id="create_charge_feedback"></p>
                <button type="button" class="btn btn-success" id="create_charge_btn"><i class="fas fa-save"></i> Create Charge</button>
            </div>

            <!-- ============== CHARGE HISTORY PANEL ============== -->
            <div id="charge_history_panel" class="hide">
                <div id="charge_batches_list_holder"></div>
            </div>
        </div>
    </div>

    <!-- ============== VIEW BATCH STUDENTS MODAL ============== -->
    <div class="confirmpaymentwindow hide" id="view_batch_students_modal">
        <div class="window_lg animate">
            <div class="conts">
                <p class="funga" id="close_view_batch_students_modal">&times;</p>
                <h5 class="text-center"><b>Students Charged</b></h5>
            </div>
            <input type="text" class="form-control form-control-sm mb-2" id="batch_students_search_box" placeholder="Search by name or admission number...">
            <div id="batch_students_holder"></div>
            <div class="btns">
                <button type="button" id="close_view_batch_students_btn">Close</button>
            </div>
        </div>
    </div>

</div>
