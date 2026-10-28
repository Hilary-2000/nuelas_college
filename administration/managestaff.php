<div class="contents animate hide" id="managestaff">
    <div class="titled">
        <h2>Staff Information Management</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Staff management</p>
            <!--<div class="admin_special rotate_down">
            </div>-->
        </div>
        <div class="middle1">
            <div class="row">
                <div class="instructions col-md-9">
                    <p><strong>Hint:</strong></p>
                    <p>- Click <b>View</b> on a staff member to update or delete their information.</p>
                    <p>- From their details you can also activate or de-activate them as users and change their role.</p>
                    <select class="d-none" name="manage_tr_option" id="manage_tr_option">
                        <option value="" hidden>Select...</option>
                        <option value="viewstaffavailable" id="view_my_stf">View My staff</option>
                        <option value="assignclasses">Assign teacher a class</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <span id="manage_departments" class="block_btn"><i class="fas fa-cogs"></i> Manage Departments</span>
                </div>
            </div>
            <div class="contstable section_card hide" id="constable">
                <p id = 'stafferrors' ></p>
            </div>
            <?php include("empinfor.php")?>
            <?php include("assign_subjects.php")?>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>