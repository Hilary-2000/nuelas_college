<div class="contents animate hide" id="personal_profile_page">
    <div class="titled">
        <h2>My Profile</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>My Profile</p>
        </div>
        <div class="middle1">
            <div class="pf_hero">
                <div class="pf_hero_cover"></div>
                <div class="pf_hero_body">
                    <div class="pf_avatar school_logo">
                        <img src="images/dp.png" id="dpimage-sett" alt="My photo">
                        <span class="pf_avatar_edit" id="change_dp_btns" role="button" tabindex="0" title="Change my photo"><i class="fas fa-camera"></i></span>
                    </div>
                    <div class="pf_hero_text">
                        <h3><?php echo htmlspecialchars($_SESSION['fullnames'] ?? '', ENT_QUOTES);?></h3>
                        <small>Your details, photo, signature and password.</small>
                        <p id="dp_err_handler"></p>
                    </div>
                </div>
            </div>
            <p id="my_information_inner"></p>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-user"></i> Personal Details</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_full_name">Full Name <span class="pf_req">*</span></label>
                        <input class="form-control" type="text" name="my_full_name" id="my_full_name" placeholder="Full name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-control-label" for="my_dob">Date of Birth <span class="pf_req">*</span></label>
                        <input class="form-control" type="date" name="my_dob" id="my_dob">
                    </div>
                    <div class="col-md-3">
                        <label class="form-control-label" for="my_gender">Gender <span class="pf_req">*</span></label>
                        <select class="form-control" name="my_gender" id="my_gender">
                            <option value="" hidden>Select...</option>
                            <option id="M12" value="M">Male</option>
                            <option id="F12" value="F">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_nat_id">National ID / Passport No. <span class="pf_req">*</span></label>
                        <input class="form-control" type="number" name="my_nat_id" id="my_nat_id" placeholder="National ID">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_tsc_code">TSC Code</label>
                        <input class="form-control" type="text" name="my_tsc_code" id="my_tsc_code" placeholder="TSC Code (optional)">
                    </div>
                </div>
            </div>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-address-book"></i> Contact and Sign-in</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_phone_no">Phone Number <span class="pf_req">*</span></label>
                        <input class="form-control" type="number" name="my_phone_no" id="my_phone_no" placeholder="e.g. 0712345678">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_mail">Email <span class="pf_req">*</span></label>
                        <input class="form-control" type="email" name="my_mail" id="my_mail" placeholder="My email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="my_address">Address <span class="pf_req">*</span></label>
                        <input class="form-control" type="text" name="my_address" id="my_address" placeholder="Address">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="sys_username">Username <span class="pf_req">*</span> <img src="images/ajax_clock_small.gif" class="hide" id="ch_uname_clock"></label>
                        <input class="form-control" type="text" name="sys_username" id="sys_username" placeholder="Username">
                        <p id="check_me_username"></p>
                    </div>
                </div>
                <div class="pf_actions">
                    <p id="update_my_infor"></p>
                    <button type='button' id="change_my_information"><i class="fas fa-save"></i> Save My Details</button>
                </div>
            </div>
                <?php if (in_array((string) ($_SESSION['auth'] ?? ''), ['1', '2', '3'], true)): ?>
                <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-signature"></i> My Signature</div>
                <p class="pf_help">Your signature is printed on receipts, transcripts and other documents that need it, in place of signing each one by hand.</p>
                <div class="sig_card_preview" id="my_signature_preview"><span class="sig_empty">No signature yet</span></div>
                <p id="my_signature_status" style="font-size:13px;"></p>
                <div class="btns">
                    <button type="button" id="my_signature_change"><i class="fas fa-signature"></i> Add / Change Signature</button>
                    <button type="button" class="hide" id="my_signature_remove"><i class="fas fa-trash"></i> Remove</button>
                </div>
                </div>
                <?php endif; ?>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-lock"></i> Change Password</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-control-label" for="old_pass">Current Password <span class="pf_req">*</span></label>
                        <input class="form-control" type="password" name="old_pass" id="old_pass" placeholder="Current password" autocomplete="current-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="new_pass">New Password <span class="pf_req">*</span></label>
                        <input class="form-control" type="password" name="new_pass" id="new_pass" placeholder="New password" autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="repeat_pass">Repeat New Password <span class="pf_req">*</span></label>
                        <input class="form-control" type="password" name="repeat_pass" id="repeat_pass" placeholder="Repeat new password" autocomplete="new-password">
                    </div>
                </div>
                <div class="pf_actions">
                    <p id="update_credential_infor"></p>
                    <button type='button' id="change_my_pass"><i class="fas fa-key"></i> Change Password</button>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>