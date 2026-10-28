<div class="staffinformed hide" id='informationwindow'>
    <!-- profile header: initials, name and quick facts (filled by admissions.js) -->
    <div class="sp_hero sf_hero">
        <div class="sp_hero_photo">
            <span class="sf_avatar" id="staff_avatar_initials"><i class="fas fa-user"></i></span>
        </div>
        <div class="sp_hero_main">
            <div class="sp_identity">
                <div id="staff_detail_identity"><h3>Employee</h3></div>
                <div class="sp_chips">
                    <span class="sp_chip sp_chip_main"><i class="fas fa-id-badge"></i> Staff ID <span id='staffid'></span></span>
                    <span class="sp_chip"><i class="fas fa-user-tag"></i> <span id="myauthorities"></span></span>
                    <span id="staff_detail_chips" class="sp_chips"></span>
                </div>
            </div>
            <div class="sp_hero_side sf_hero_side">
                <button type="button" class="sp_btn_ghost sf_back" id='back_one'><i class="fas fa-arrow-left"></i> Back to staff list</button>
                <button type="button" class="sp_btn_danger" id="delete_staff_permanently"><i class="fas fa-trash-alt"></i> Delete staff permanently</button>
            </div>
        </div>
    </div>
    <div class="instructions">
        <p><strong>Hint:</strong></p>
        <p>- Please be sure of the information you are updating.</p>
        <p>- Where possible, let staff update their own information from their portal.</p>
    </div>
    <!-- Signature card (assets/JS/signatures.js shows it for the principal and deputy principals) -->
    <div class="sp_card hide" id="staff_signature_card">
        <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-signature"></i></span>
            <div class="sp_head_text"><b>Signature</b><small>Printed on receipts and other documents that need this staff member's signature</small></div>
        </div>
        <div class="sf_signature">
            <div class="sig_card_preview" id="staff_signature_preview"><span class="sig_empty">No signature yet</span></div>
            <div>
                <p id="staff_signature_status" class="mb-2" style="font-size:12px;"></p>
                <button type="button" id="staff_signature_change" class="sp_btn_ghost"><i class="fas fa-signature"></i> Add / Change Signature</button>
                <button type="button" id="staff_signature_remove" class="sp_btn_danger hide"><i class="fas fa-trash"></i> Remove</button>
            </div>
        </div>
    </div>
    <div class="sp_card">
        <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-user"></i></span>
            <div class="sp_head_text"><b>Personal Information</b><small>Name, contacts and identification</small></div>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="sp_label" for="fullnamed">Full names</label>
                <input class="form-control w-100" type="text" name="fullnamed" id="fullnamed" autocomplete="off" placeholder="Full names">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="gende">Gender</label>
                <select class="form-control w-100" name="gende" id="gende">
                    <option value="" hidden>Select..</option>
                    <option class="genders" id='M' value="M">Male</option>
                    <option class="genders" id ='F' value="F">Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="dobd">Date of birth</label>
                <input class="form-control w-100" type="date" name="dobd" id="dobd" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="natids">National ID</label>
                <p id='nationalids'></p>
                <input class="form-control w-100" type="text" name="natids" id="natids" placeholder="National ID or passport" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="phonenumberd">Phone number</label>
                <p id='phoneerrord'></p>
                <input class="form-control w-100" type="text" name="phonenumberd" id="phonenumberd" placeholder="Phone number" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="addresdd">Address</label>
                <input class="form-control w-100" type="text" name="addresdd" id="addresdd" placeholder="Town or city" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="staffmail">Email</label>
                <p id='emailstaff'></p>
                <input class="form-control w-100" type="text" name="staffmail" id="staffmail" placeholder="Email" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="sp_card">
        <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-briefcase"></i></span>
            <div class="sp_head_text"><b>Human Resource Details</b><small>Employment dates, numbers and contract type</small></div>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="sp_label" for="d_o_e_input">Date of employment</label>
                <input class="form-control w-100" type="date" name="d_o_e_input" id="d_o_e_input" max="<?php echo date("Y-m-d"); ?>" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="employees_job_number">Personal file number <img class="hide" src="images/ajax_clock_small.gif" id="job_number_loader"></label>
                <p id="error_job_number"></p>
                <input class="form-control w-100" type="text" name="employees_job_number" id="employees_job_number" placeholder="Personal file number" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="employees_job_title">Job title</label>
                <input class="form-control w-100" type="text" name="employees_job_title" id="employees_job_title" placeholder="Job title" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="tscnosd">Employment number</label>
                <input class="form-control w-100" type="text" name="tscnosd" id="tscnosd" placeholder="Employment number" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="nssf_numbers">NSSF no.</label>
                <input class="form-control w-100" type="text" name="nssf_numbers" id="nssf_numbers" placeholder="NSSF number" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="nhif_numbers">SHIF no.</label>
                <input class="form-control w-100" type="text" name="nhif_numbers" id="nhif_numbers" placeholder="SHIF number" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="employees_type">Employee type</label>
                <select name="employees_type" id="employees_type" class="form-control w-100">
                    <option class="emp_infor_opt" value="" hidden>Select Employees Type</option>
                    <option class="emp_infor_opt" value="Intern"> Intern</option>
                    <option class="emp_infor_opt" value="Probationary"> Probationary</option>
                    <option class="emp_infor_opt" value="Regular (fixed-term)"> Regular (fixed-term)</option>
                    <option class="emp_infor_opt" value="Regular (open-ended)"> Regular (open-ended)</option>
                    <option class="emp_infor_opt" value="Casual"> Casual</option>
                    <option class="emp_infor_opt" value="Consultant"> Consultant</option>
                    <option class="emp_infor_opt" value="Non-teaching Staff"> Non-teaching Staff</option>
                    <option class="emp_infor_opt" value="BOG Staff"> BOG Staff</option>
                    <option class="emp_infor_opt" value="Teacher In Practice"> Teacher In Practice (T.P)</option>
                    <option class="emp_infor_opt" value="T.S.C Teacher"> T.S.C Teacher</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row g-3 sf_pair">
        <div class="col-lg-5">
            <div class="sp_card h-100">
                <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-key"></i></span>
            <div class="sp_head_text"><b>Login Credentials</b><small>How the staff member signs in to the system</small></div>
        </div>
                <label class="sp_label" for="usenames">Username <span class="sp_tag">Not case sensitive</span></label>
                <p id='usererrors'></p>
                <input class="form-control w-100" type="text" name="usenames" id="usenames" autocomplete="off">
                <p id='passworderrors2'></p>
                <button type='button' class="sp_btn_ghost mt-3" id ='changepwd'><i class="fas fa-lock"></i> Change password</button>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="sp_card h-100">
                <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-user-friends"></i></span>
            <div class="sp_head_text"><b>Next of Kin</b><small>Who to contact in an emergency</small></div>
        </div>
                <div class="row g-3">
                    <div class="col-md-6">
                <label class="sp_label" for="kin_fullnames">Full name</label>
                <input class="form-control w-100" placeholder="Next of kin name" type="text" name="kin_fullnames" id="kin_fullnames">
            </div>
                    <div class="col-md-6">
                <label class="sp_label" for="kin_relationship_edit">Relationship</label>
                <input class="form-control w-100" placeholder="e.g. Spouse" type="text" name="kin_relationship_edit" id="kin_relationship_edit">
            </div>
                    <div class="col-md-6">
                <label class="sp_label" for="kin_contacts_edit">Phone</label>
                <input class="form-control w-100" placeholder="Next of kin phone number" type="text" name="kin_contacts_edit" id="kin_contacts_edit">
            </div>
                    <div class="col-md-6">
                <label class="sp_label" for="kin_location_edit">Location</label>
                <input class="form-control w-100" placeholder="Town or city" type="text" name="kin_location_edit" id="kin_location_edit">
            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sp_card">
        <div class="sp_head">
            <span class="sp_icon"><i class="fas fa-user-shield"></i></span>
            <div class="sp_head_text"><b>Role and Account Status</b><small>What the staff member can do, and whether they can sign in</small></div>
        </div>
        <p class="hide" id="staff_detail_out"></p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="sp_label" for="auths">Role <span class="hide" id="load_roles43"><img src="images/ajax_clock_small.gif"></span></label>
                <span id="data_in_display"></span>
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="activated">Activated</label>
                <select class="form-control w-100" name="activated" id="activated">
                    <option value="" hidden>Select..</option>
                    <option id='act1' value="1">Yes</option>
                    <option id='act0' value="0">No</option>
                </select>
                <span class="sp_hint mt-1">A de-activated staff member still appears as staff but cannot sign in.</span>
            </div>
            <div class="col-md-4">
                <label class="sp_label" for="deleted">Deleted</label>
                <select class="form-control w-100" name="deleted" id="deleted">
                    <option value="" hidden>Select..</option>
                    <option id='del1' value="1">Yes</option>
                    <option id='del0' value="0">No</option>
                </select>
                <span class="sp_hint mt-1">A deleted staff member is left out of every staff list and cannot sign in.</span>
            </div>
            <div class="col-md-12 hide" id="reason_for_staff_inactive">
                <label class="sp_label" for="reason_inactive">Reason for de-activating</label>
                <textarea name="reason_inactive" id="reason_inactive" cols="30" rows="3" class="w-100 form-control" placeholder="Why is this staff member de-activated?"></textarea>
            </div>
        </div>
    </div>
    <!-- stays at the bottom of the window while scrolling the details -->
    <div class="sp_actionbar">
        <button type="button" class="sp_btn_ghost" id='backtostaff'><i class="fas fa-arrow-left"></i> Back</button>
        <p id='updateerror'></p>
        <button type="button" id='updatestaff'><i class="fas fa-save"></i> Update staff information</button>
    </div>
</div>