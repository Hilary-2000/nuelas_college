<div class="contents animate hide" id="update_school_profile_page">
    <div class="titled">
        <h2>College Profile</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Update College Profile</p>
        </div>
        <div class="middle1">
            <div class="pf_hero">
                <div class="pf_hero_cover"></div>
                <div class="pf_hero_body">
                    <div class="pf_avatar pf_avatar_logo school_logo">
                        <img src="images/board.jpg" id="sch_logos2" alt="College logo">
                        <span class="pf_avatar_edit" id="change_sch_dp" role="button" tabindex="0" title="Change the college logo"><i class="fa fa-camera"></i></span>
                    </div>
                    <div class="pf_hero_text">
                        <h3><?php echo htmlspecialchars($_SESSION['schoolname'] ?? '', ENT_QUOTES);?></h3>
                        <small>The college name, logo and contacts printed on receipts, reports and messages.</small>
                    </div>
                </div>
            </div>
            <p id="store_sch_information" class="hide"></p>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-university"></i> College Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-control-label" for="school_name_s">College Name <span class="pf_req">*</span></label>
                        <input class="form-control" name="school_name_s" id="school_name_s" placeholder="College Name">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="school_message_name">Message Name <span class="pf_req">*</span></label>
                        <input class="form-control" name="school_message_name" id="school_message_name" placeholder="Name used in SMS">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="school_codes">Institution Code <span class="pf_readonly">Read only</span></label>
                        <input class="form-control" name="school_codes" id="school_codes" readonly placeholder="Institution Code">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="school_motto_s">Motto <span class="pf_req">*</span></label>
                        <input class="form-control" name="school_motto_s" id="school_motto_s" placeholder="College Motto">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="school_vission">Vision <span class="pf_req">*</span></label>
                        <input class="form-control" name="school_vission" id="school_vission" placeholder="College Vision">
                    </div>
                </div>
            </div>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-map-marker-alt"></i> Location and Address</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-control-label" for="school_box_no">P.O Box <span class="pf_req">*</span></label>
                        <input class="form-control" name="school_box_no" id="school_box_no" placeholder="P.O Box">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="box_Code">Postal Code <span class="pf_req">*</span></label>
                        <input class="form-control" name="box_Code" id="box_Code" placeholder="Postal Code">
                    </div>
                    <div class="col-md-4">
                        <label class="form-control-label" for="sch_physical_address">Physical Address <span class="pf_req">*</span></label>
                        <input class="form-control" id="sch_physical_address" placeholder="Physical Address">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="sch_county">County <span class="pf_req">*</span></label>
                        <select class="form-control" name="sch_county" id="sch_county">
                        <option value="" hidden>Select County</option>
                        <option id="Mombasa" value="Mombasa">Mombasa</option>
                        <option id="Kwale" value="Kwale">Kwale</option>
                        <option id="Kilifi" value="Kilifi">Kilifi</option>
                        <option id="Tana River" value="Tana River">Tana River</option>
                        <option id="Lamu" value="Lamu">Lamu</option>
                        <option id="Taita/aveta" value="Taita/Taveta">Taita/Taveta</option>
                        <option id="Garissa" value="Garissa">Garissa</option>
                        <option id="Wajir" value="Wajir">Wajir</option>
                        <option id="Mandera" value="Mandera">Mandera</option>
                        <option id="Marsabit" value="Marsabit">Marsabit</option>
                        <option id="Isiolo" value="Isiolo">Isiolo</option>
                        <option id="Meru" value="Meru">Meru</option>
                        <option id="Tharaka-Nithi" value="Tharaka-Nithi">Tharaka-Nithi</option>
                        <option id="Embu" value="Embu">Embu</option>
                        <option id="Kitui" value="Kitui">Kitui</option>
                        <option id="Machakos" value="Machakos">Machakos</option>
                        <option id="Makueni" value="Makueni">Makueni</option>
                        <option id="Nyandarua" value="Nyandarua">Nyandarua</option>
                        <option id="Nyeri" value="Nyeri">Nyeri</option>
                        <option id="Kirinyaga" value="Kirinyaga">Kirinyaga</option>
                        <option id="Murang'a" value="Murang'a">Murang'a</option>
                        <option id="Kiambu" value="Kiambu">Kiambu</option>
                        <option id="Turkana" value="Turkana">Turkana</option>
                        <option id="West Pokot" value="West Pokot">West Pokot</option>
                        <option id="Samburu" value="Samburu">Samburu</option>
                        <option id="Trans Nzoia" value="Trans Nzoia">Trans Nzoia</option>
                        <option id="Uasin Gishu" value="Uasin Gishu">Uasin Gishu</option>
                        <option id="Elgeyo/Marakwet" value="Elgeyo/Marakwet">Elgeyo/Marakwet</option>
                        <option id="Nandi" value="Nandi">Nandi</option>
                        <option id="Baringo" value="Baringo">Baringo</option>
                        <option id="Laikipia" value="Laikipia">Laikipia</option>
                        <option id="Nakuru" value="Nakuru">Nakuru</option>
                        <option id="Narok" value="Narok">Narok</option>
                        <option id="Kajiado" value="Kajiado">Kajiado</option>
                        <option id="Kericho" value="Kericho">Kericho</option>
                        <option id="Bomet" value="Bomet">Bomet</option>
                        <option id="Kakamega" value="Kakamega">Kakamega</option>
                        <option id="Vihiga" value="Vihiga">Vihiga</option>
                        <option id="Bungoma" value="Bungoma">Bungoma</option>
                        <option id="Busia" value="Busia">Busia</option>
                        <option id="Siaya" value="Siaya">Siaya</option>
                        <option id="Kisumu" value="Kisumu">Kisumu</option>
                        <option id="Homa Bay" value="Homa Bay">Homa Bay</option>
                        <option id="Migori" value="Migori">Migori</option>
                        <option id="Kisii" value="Kisii">Kisii</option>
                        <option id="Nyamira" value="Nyamira">Nyamira</option>
                        <option id="Nairobi" value="Nairobi">Nairobi</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="sch_country">Country <span class="pf_req">*</span></label>
                        <select class="form-control" name="sch_country" id="sch_country">
                            <option value="" hidden>Select Country</option>
                            <option id="Kenya" value="Kenya">Kenya</option>
                            <option id="Uganda" value="Uganda">Uganda</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pf_card">
                <div class="pf_card_title"><i class="fas fa-address-book"></i> Administrator and Contacts</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-control-label" for="administrator_name">Administrator Name <span class="pf_req">*</span></label>
                        <input class="form-control" name="administrator_name" id="administrator_name" placeholder="Administrator Name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="administrator_contacts">College Phone <span class="pf_req">*</span></label>
                        <input class="form-control" name="administrator_contacts" id="administrator_contacts" placeholder="e.g. 0712345678">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="administrator_email">College Email</label>
                        <input class="form-control" name="administrator_email" id="administrator_email" placeholder="e.g. info@college.ac.ke">
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label" for="school_websites">Website <span class="pf_req">*</span></label>
                        <input type="text" class="form-control" id="school_websites" placeholder="e.g. www.ladybirdsmis.com">
                    </div>
                </div>
            </div>
            <div class="pf_actions">
                <p id="school_information_err_handler"></p>
                <button type='button' id='update_school_in4'><i class="fas fa-save"></i> Save College Profile</button>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>