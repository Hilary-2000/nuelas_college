<div class="contents animate hide" id="charged_accounts_win">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Charged Accounts</p>
        </div>
        <div class="middle1">
            <div class="instructions">
                <div class="notify">
                    <p><strong>What is a Charged Account?</strong></p>
                </div>
                <p>- Use this for irregular one-off fees that don't fit as a permanent votehead, e.g. lost uniform items, exam fees, or graduation fees.</p>
                <p>- A charge is applied to specific students or to a saved Group. It shows up as a single "Charged Account" line, alongside the normal fee voteheads, wherever a student's payable items are listed.</p>
            </div>

            <div class="tmpl_channel_switch ca_switch" id="chargedAccountsTab">
                <button type="button" class="tmpl_channel active" id="show_create_charge_btn" aria-pressed="true">
                    <span class="tmpl_channel_icon"><i class="fas fa-plus"></i></span>
                    <span class="tmpl_channel_text"><b>Create Charge</b><small>Bill students or a group a one-off fee</small></span>
                    <i class="fas fa-check-circle tmpl_channel_tick"></i>
                </button>
                <button type="button" class="tmpl_channel" id="show_charge_history_btn" aria-pressed="false">
                    <span class="tmpl_channel_icon"><i class="fas fa-history"></i></span>
                    <span class="tmpl_channel_text"><b>Charge History</b><small>Past charges and the students billed</small></span>
                    <i class="fas fa-check-circle tmpl_channel_tick"></i>
                </button>
            </div>

            <!-- ============== CREATE CHARGE PANEL ============== -->
            <div id="create_charge_panel" class="section_card">
                <p class="card_heading"><i class="fas fa-file-invoice-dollar"></i> Create Charge</p>
                <p id="charge_groups_holder" class="hide"></p>
                <p id="charge_lookup_holder" class="hide"></p>

                <div class="ca_step"><span>1</span> Who is being charged?</div>
                <div class="ca_targets">
                    <label class="ca_target" for="charge_target_students">
                        <input type="radio" name="charge_target_type" id="charge_target_students" value="students" checked>
                        <span class="ca_target_icon"><i class="fas fa-user-graduate"></i></span>
                        <span class="ca_target_text"><b>Specific Student(s)</b><small>Add students one by one by name or admission number</small></span>
                    </label>
                    <label class="ca_target" for="charge_target_group">
                        <input type="radio" name="charge_target_type" id="charge_target_group" value="group">
                        <span class="ca_target_icon"><i class="fas fa-users"></i></span>
                        <span class="ca_target_text"><b>Group</b><small>Charge every student in a saved group</small></span>
                    </label>
                </div>

                <!-- Students target -->
                <div id="charge_students_target_panel" class="ca_target_panel">
                    <label class="form-control-label" for="charge_admno_input"><b>Student Name or Admission Number</b></label>
                    <div class="ca_add_row">
                        <div class="autocomplete">
                            <input type="text" class="form-control w-100" id="charge_admno_input" placeholder="Type a name or admission number">
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="add_charge_admno_btn"><i class="fas fa-plus"></i> Add</button>
                    </div>
                    <p id="charge_admno_feedback"></p>
                    <div id="charge_admno_list" class="my-2"></div>
                </div>

                <!-- Group target -->
                <div id="charge_group_target_panel" class="ca_target_panel hide">
                    <label class="form-control-label" for="charge_group_select"><b>Select Group</b></label>
                    <select class="form-control w-100" id="charge_group_select">
                        <option value="" hidden>Select a group...</option>
                    </select>
                </div>

                <div class="ca_step"><span>2</span> What is the charge for?</div>
                <div class="row">
                    <div class="col-md-5 mb-2">
                        <label class="form-control-label" for="charge_description"><b>Description</b></label>
                        <input type="text" class="form-control w-100" id="charge_description" placeholder="e.g. Lost cardigan">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-control-label" for="charge_period"><b>Period</b></label>
                        <input type="text" class="form-control w-100" id="charge_period" placeholder="e.g. Term 2 2026">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-control-label" for="charge_amount"><b>Amount</b></label>
                        <div class="ca_amount">
                            <span>Kes</span>
                            <input type="number" class="form-control w-100" id="charge_amount" placeholder="e.g. 500" min="1">
                        </div>
                    </div>
                </div>
                <p id="create_charge_feedback"></p>
                <button type="button" class="btn btn-success" id="create_charge_btn"><i class="fas fa-save"></i> Create Charge</button>
            </div>

            <!-- ============== CHARGE HISTORY PANEL ============== -->
            <div id="charge_history_panel" class="section_card hide">
                <p class="card_heading"><i class="fas fa-history"></i> Charge History</p>
                <div id="charge_batches_list_holder"></div>
            </div>
        </div>
    </div>

</div>
