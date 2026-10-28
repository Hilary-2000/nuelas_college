<div class="contents animate hide" id = "sms_templates_window">
    <div class="titled">
        <h6>Message Templates</h6>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Message Templates</p>
        </div>
        <div class="middle1">
            <div class="setting_s">
                <div class="bg-secondary mt-0 rounded">
                    <p class="text-center text-white">What you should know</p>
                </div>
                <div class="p-2 container">
                    <div class="conts">
                        <p>- At this window you are able to format message templates for different usages in the system.</p>
                        <p>- Instead of manually typing the message every time you need it, set it up and send at will</p>
                        <button type="button" id="back_to_message_dash"><i class="fas fa-arrow-left"></i> back</button>
                    </div>
                    <div class="msg_tags m-2" data-boxes="student_welcome_message_editor,welcome_message_editor,confirmation_message_editor,parent_confirmation_message_editor,module_progression_message_editor,student_module_progression_message_editor,student_welcome_message_editor_email,welcome_message_editor_email,confirmation_message_editor_email,parent_confirmation_message_editor_email,module_progression_message_editor_email,student_module_progression_message_editor_email">
                        <div class="msg_tags_head"><i class="fas fa-tags"></i> Message Tags <img src="images/ajax_clock_small.gif" class="hide" id="class_loader_tags"></div>
                        <p class="msg_tags_hint">Tags are placeholders the system fills in with each recipient's details when it sends a message. Click a tag to insert it into whichever message box you last clicked into, or type <b>/</b> in any message box to search the tags right where you are typing. Next module fees is the current fee balance plus the cost of the module the student is being moved into.</p>
                        <div class="msg_tags_group">
                            <div class="msg_tags_group_title">Student</div>
                            <div class="msg_tags_list">
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_fullname}" title="Click to insert {stud_fullname}">Full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_first_name}" title="Click to insert {stud_first_name}">First name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_adm}" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_noun}" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                            </div>
                        </div>
                        <div class="msg_tags_group">
                            <div class="msg_tags_group_title">Fees</div>
                            <div class="msg_tags_list">
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_fees_balance}" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_fees_to_pay}" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{stud_fees_paid}" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{amount_paid}" title="Click to insert {amount_paid}">Amount just paid <code>{amount_paid}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{next_module_fees}" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{receipt_url}" title="Click to insert {receipt_url}">Receipt link <code>{receipt_url}</code> <i class="fas fa-plus"></i></span>
                            </div>
                        </div>
                        <div class="msg_tags_group">
                            <div class="msg_tags_group_title">Parent</div>
                            <div class="msg_tags_list">
                                <span class="msg_tag tmpl_tag_click" data-tag="{par_fullname}" title="Click to insert {par_fullname}">Full name <code>{par_fullname}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{par_first_name}" title="Click to insert {par_first_name}">First name <code>{par_first_name}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{title_1}" title="Click to insert {title_1}">Mr / Mrs <code>{title_1}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{title_2}" title="Click to insert {title_2}">Sir / Madam <code>{title_2}</code> <i class="fas fa-plus"></i></span>
                            </div>
                        </div>
                        <div class="msg_tags_group">
                            <div class="msg_tags_group_title">College and time</div>
                            <div class="msg_tags_list">
                                <span class="msg_tag tmpl_tag_click" data-tag="{school_name}" title="Click to insert {school_name}">College name <code>{school_name}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{school_contact}" title="Click to insert {school_contact}">College contacts <code>{school_contact}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{school_email}" title="Click to insert {school_email}">College email <code>{school_email}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{today}" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                <span class="msg_tag tmpl_tag_click" data-tag="{time}" title="Click to insert {time}">Time now <code>{time}</code> <i class="fas fa-plus"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="hide" id="messages_holder_templates"></p>
            <p class="hide" id="school_information_holder"></p>
            <div class="tmpl_channel_area">
            <div class="nav tmpl_channel_switch" id="templateChannelTab" role="tablist">
                <button class="tmpl_channel tmpl_channel_sms active" id="rather_sms_templates_btn" data-bs-toggle="tab" data-bs-target="#sms_templates_tab" type="button" role="tab" aria-controls="sms_templates_tab" aria-selected="true">
                    <span class="tmpl_channel_icon"><i class="fas fa-sms"></i></span>
                    <span class="tmpl_channel_text"><b>SMS Templates</b><small>Text messages sent to phones</small></span>
                    <span class="tmpl_channel_count" id="sms_templates_count"></span>
                    <i class="fas fa-check-circle tmpl_channel_tick"></i>
                </button>
                <button class="tmpl_channel tmpl_channel_email" id="rather_email_templates_btn" data-bs-toggle="tab" data-bs-target="#email_templates_tab" type="button" role="tab" aria-controls="email_templates_tab" aria-selected="false">
                    <span class="tmpl_channel_icon"><i class="fas fa-envelope"></i></span>
                    <span class="tmpl_channel_text"><b>Email Templates</b><small>Emails with a subject line</small></span>
                    <span class="tmpl_channel_count" id="email_templates_count"></span>
                    <i class="fas fa-check-circle tmpl_channel_tick"></i>
                </button>
            </div>
            <div class="tab-content" id="templateChannelTabContent">
                <div class="tab-pane fade show active p-1 m-1" id="sms_templates_tab" role="tabpanel" aria-labelledby="rather_sms_templates_btn">
                    <div class="tmpl_channel_banner tmpl_channel_banner_sms">
                        <i class="fas fa-sms"></i>
                        <div><b>You are editing SMS templates</b><span>These are sent as text messages to the phone number on file. Anyone whose preferred communication channel is Email gets the email version instead.</span></div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Set Student welcome message <span class="template_status_dot" id="student_welcome_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to students when they are being registered!</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="student_welcome_message_editor" id="student_welcome_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your welcome message here!'>Hello {title_1} {stud_fullname}, Welcome to {school_name}. Your admission number is {stud_adm}, welcome to the home of Fashion.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="student_welcome_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="student_save_welcome_message"><i class="fas fa-save"></i> Save</span>
                                <p id="student_welcome_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Set Parent Welcome message <span class="template_status_dot" id="welcome_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to students when they are being registered!</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="welcome_message_editor" id="welcome_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your welcome message here!'>Hello {title_1} {par_fullname}, Welcome to {school_name}. Your {stud_noun}, {stud_fullname} has been successfully registered with admission number {stud_adm}.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="welcome_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_welcome_message"><i class="fas fa-save"></i> Save</span>
                                <p id="welcome_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded rounded">
                            <p class="text-center text-white">Student Confirmation Message <span class="template_status_dot" id="confirmation_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to parents when they pay their fees!</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="confirmation_message_editor" id="confirmation_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your fees confirmation message here!'>Confirmed Kes {amount_paid} has been successfully paid for {stud_fullname} - {stud_adm} - {stud_class}, New fee balance is Kes {stud_fees_balance} as at {time} on {today}.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="confirmation_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_confirmation_message"><i class="fas fa-save"></i> Save</span>
                                <p id="confirmation_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Parent Confirmation Message <span class="template_status_dot" id="parent_confirmation_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to parents when they pay via their parent account!</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="parent_confirmation_message_editor" id="parent_confirmation_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your fees confirmation message here!'>Confirmed we have received Kes {amount_paid} paid for your {children} children. Your new fee balance is Kes {stud_fees_balance} as at {time} on {today}.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="parent_confirmation_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_parent_confirmation_message"><i class="fas fa-save"></i> Save</span>
                                <p id="parent_confirmation_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Module Progression Message <span class="template_status_dot" id="module_progression_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to parents when the system automatically moves their child to the next module!</p>
                            <div class="msg_tags_inline">
                                <div class="msg_tags_inline_title"><i class="fas fa-tags"></i> Only these tags work here. College details and receipt tags are not available for this message.</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fullname}" data-target="module_progression_message_editor" title="Click to insert {stud_fullname}">Student full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_first_name}" data-target="module_progression_message_editor" title="Click to insert {stud_first_name}">Student first name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_class}" data-target="module_progression_message_editor" title="Click to insert {stud_class}">Class <code>{stud_class}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_age}" data-target="module_progression_message_editor" title="Click to insert {stud_age}">Age <code>{stud_age}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_adm}" data-target="module_progression_message_editor" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_noun}" data-target="module_progression_message_editor" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_balance}" data-target="module_progression_message_editor" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_to_pay}" data-target="module_progression_message_editor" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_paid}" data-target="module_progression_message_editor" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{next_module_fees}" data-target="module_progression_message_editor" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{par_fullname}" data-target="module_progression_message_editor" title="Click to insert {par_fullname}">Parent full name <code>{par_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{par_first_name}" data-target="module_progression_message_editor" title="Click to insert {par_first_name}">Parent first name <code>{par_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{title_1}" data-target="module_progression_message_editor" title="Click to insert {title_1}">Mr / Mrs <code>{title_1}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{title_2}" data-target="module_progression_message_editor" title="Click to insert {title_2}">Sir / Madam <code>{title_2}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{today}" data-target="module_progression_message_editor" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="module_progression_message_editor" id="module_progression_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your module progression message here!'>Dear {title_1} {par_fullname}, {stud_fullname} ({stud_adm}) has now been moved to the next module. Their new fee balance is Kes {next_module_fees}. Thank you.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="module_progression_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_module_progression_message"><i class="fas fa-save"></i> Save</span>
                                <p id="module_progression_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Student Module Progression Message <span class="template_status_dot" id="student_module_progression_message_status" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This message is sent to the student themselves when the system automatically moves them to the next module!</p>
                            <div class="msg_tags_inline">
                                <div class="msg_tags_inline_title"><i class="fas fa-tags"></i> Only these tags work here. Parent, college details and receipt tags are not available for this message.</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fullname}" data-target="student_module_progression_message_editor" title="Click to insert {stud_fullname}">Student full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_first_name}" data-target="student_module_progression_message_editor" title="Click to insert {stud_first_name}">Student first name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_class}" data-target="student_module_progression_message_editor" title="Click to insert {stud_class}">Class <code>{stud_class}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_age}" data-target="student_module_progression_message_editor" title="Click to insert {stud_age}">Age <code>{stud_age}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_adm}" data-target="student_module_progression_message_editor" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_noun}" data-target="student_module_progression_message_editor" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_balance}" data-target="student_module_progression_message_editor" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_to_pay}" data-target="student_module_progression_message_editor" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_paid}" data-target="student_module_progression_message_editor" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{next_module_fees}" data-target="student_module_progression_message_editor" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{today}" data-target="student_module_progression_message_editor" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <textarea name="student_module_progression_message_editor" id="student_module_progression_message_editor" cols="30" rows="10" class="form-control" placeholder='Type your student module progression message here!'>Hello {stud_first_name}, you have now been moved to the next module. Your new fee balance is Kes {next_module_fees}. Thank you.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="student_module_progression_message_viewer">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_student_module_progression_message"><i class="fas fa-save"></i> Save</span>
                                <p id="student_module_progression_message_template_holder"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade p-1 m-1" id="email_templates_tab" role="tabpanel" aria-labelledby="rather_email_templates_btn">
                    <div class="tmpl_channel_banner tmpl_channel_banner_email">
                        <i class="fas fa-envelope"></i>
                        <div><b>You are editing Email templates</b><span>An email is only sent instead of an SMS for a student, primary parent, or secondary parent whose preferred communication channel is set to Email. Leave a message here blank to skip sending an email for that event.</span></div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Set Student welcome message (Email) <span class="template_status_dot" id="student_welcome_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to students when they are being registered, if their channel is Email!</p>
                            <label class="form-control-label" for="student_welcome_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="student_welcome_message_subject_email" id="student_welcome_message_subject_email" placeholder="e.g. Welcome to {school_name}!" value="Welcome to {school_name}!">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="student_welcome_message_editor_email" style="min-height:200px;">Dear {stud_fullname},<br><br>Welcome to {school_name}. We are delighted to have you join us.<br><br>Your admission number is <b>{stud_adm}</b>. Please keep this for your records.<br><br>Warm regards,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="student_welcome_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="student_save_welcome_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="student_welcome_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Set Parent Welcome message (Email) <span class="template_status_dot" id="welcome_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to a parent when their student is being registered, if their channel is Email!</p>
                            <label class="form-control-label" for="welcome_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="welcome_message_subject_email" id="welcome_message_subject_email" placeholder="e.g. {stud_fullname} has been registered at {school_name}" value="{stud_fullname} has been registered at {school_name}">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="welcome_message_editor_email" style="min-height:200px;">Dear {title_1} {par_fullname},<br><br>Welcome to {school_name}. We are pleased to inform you that your {stud_noun}, {stud_fullname}, has been successfully registered with us.<br><br>Admission Number: <b>{stud_adm}</b><br><br>We look forward to a great journey together.<br><br>Warm regards,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="welcome_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_welcome_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="welcome_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded rounded">
                            <p class="text-center text-white">Student Confirmation Message (Email) <span class="template_status_dot" id="confirmation_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to a student when they pay their fees, if their channel is Email!</p>
                            <label class="form-control-label" for="confirmation_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="confirmation_message_subject_email" id="confirmation_message_subject_email" placeholder="e.g. Payment Confirmation for {stud_fullname}" value="Payment Confirmation - {stud_adm}">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="confirmation_message_editor_email" style="min-height:200px;">Dear {stud_fullname},<br><br>This is to confirm that a payment of <b>Kes {amount_paid}</b> has been received for your account ({stud_adm} - {stud_class}).<br><br>Your updated fee balance is <b>Kes {stud_fees_balance}</b> as at {time} on {today}.<br><br>Thank you,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="confirmation_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_confirmation_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="confirmation_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Parent Confirmation Message (Email) <span class="template_status_dot" id="parent_confirmation_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to a parent when they pay via their parent account, if their channel is Email!</p>
                            <label class="form-control-label" for="parent_confirmation_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="parent_confirmation_message_subject_email" id="parent_confirmation_message_subject_email" placeholder="e.g. Payment Received on {today}" value="Payment Confirmation">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="parent_confirmation_message_editor_email" style="min-height:200px;">Dear Parent,<br><br>This is to confirm that we have received a payment of <b>Kes {amount_paid}</b>.<br><br>Your new fee balance is <b>Kes {stud_fees_balance}</b> as at {time} on {today}.<br><br>Thank you,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="parent_confirmation_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_parent_confirmation_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="parent_confirmation_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Module Progression Message (Email) <span class="template_status_dot" id="module_progression_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to a parent when the system automatically moves their child to the next module, if their channel is Email!</p>
                            <div class="msg_tags_inline">
                                <div class="msg_tags_inline_title"><i class="fas fa-tags"></i> Only these tags work here. College details and receipt tags are not available for this message.</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fullname}" data-target="module_progression_message_editor_email" title="Click to insert {stud_fullname}">Student full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_first_name}" data-target="module_progression_message_editor_email" title="Click to insert {stud_first_name}">Student first name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_class}" data-target="module_progression_message_editor_email" title="Click to insert {stud_class}">Class <code>{stud_class}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_age}" data-target="module_progression_message_editor_email" title="Click to insert {stud_age}">Age <code>{stud_age}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_adm}" data-target="module_progression_message_editor_email" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_noun}" data-target="module_progression_message_editor_email" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_balance}" data-target="module_progression_message_editor_email" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_to_pay}" data-target="module_progression_message_editor_email" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_paid}" data-target="module_progression_message_editor_email" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{next_module_fees}" data-target="module_progression_message_editor_email" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{par_fullname}" data-target="module_progression_message_editor_email" title="Click to insert {par_fullname}">Parent full name <code>{par_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{par_first_name}" data-target="module_progression_message_editor_email" title="Click to insert {par_first_name}">Parent first name <code>{par_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{title_1}" data-target="module_progression_message_editor_email" title="Click to insert {title_1}">Mr / Mrs <code>{title_1}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{title_2}" data-target="module_progression_message_editor_email" title="Click to insert {title_2}">Sir / Madam <code>{title_2}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{today}" data-target="module_progression_message_editor_email" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <label class="form-control-label" for="module_progression_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="module_progression_message_subject_email" id="module_progression_message_subject_email" placeholder="e.g. Module Update for {stud_fullname}" value="{stud_fullname} has progressed to the next module">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="module_progression_message_editor_email" style="min-height:200px;">Dear {title_1} {par_fullname},<br><br>We are pleased to inform you that {stud_fullname} ({stud_adm}) has now progressed to the next module.<br><br>Their new fee balance is <b>Kes {next_module_fees}</b>.<br><br>Thank you,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="module_progression_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_module_progression_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="module_progression_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_s">
                        <div class="bg-secondary mt-0 rounded">
                            <p class="text-center text-white">Student Module Progression Message (Email) <span class="template_status_dot" id="student_module_progression_message_status_email" title="Not set"><i class="fas fa-circle text-secondary"></i></span></p>
                        </div>
                        <div class="conts p-2">
                            <p>- This email is sent to the student themselves when the system automatically moves them to the next module, if their channel is Email!</p>
                            <div class="msg_tags_inline">
                                <div class="msg_tags_inline_title"><i class="fas fa-tags"></i> Only these tags work here. Parent, college details and receipt tags are not available for this message.</div>
                                <div class="msg_tags_list">
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fullname}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_fullname}">Student full name <code>{stud_fullname}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_first_name}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_first_name}">Student first name <code>{stud_first_name}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_class}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_class}">Class <code>{stud_class}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_age}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_age}">Age <code>{stud_age}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_adm}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_adm}">Admission no. <code>{stud_adm}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_noun}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_noun}">Son / daughter <code>{stud_noun}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_balance}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_fees_balance}">Balance <code>{stud_fees_balance}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_to_pay}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_fees_to_pay}">Fees to pay <code>{stud_fees_to_pay}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{stud_fees_paid}" data-target="student_module_progression_message_editor_email" title="Click to insert {stud_fees_paid}">Fees paid <code>{stud_fees_paid}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{next_module_fees}" data-target="student_module_progression_message_editor_email" title="Click to insert {next_module_fees}">Next module fees <code>{next_module_fees}</code> <i class="fas fa-plus"></i></span>
                                    <span class="msg_tag msg_tag_compact" data-tag="{today}" data-target="student_module_progression_message_editor_email" title="Click to insert {today}">Today <code>{today}</code> <i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <label class="form-control-label" for="student_module_progression_message_subject_email">Subject</label>
                            <input type="text" class="form-control" name="student_module_progression_message_subject_email" id="student_module_progression_message_subject_email" placeholder="e.g. Your Module Update" value="You have progressed to the next module">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b class="text-primary">Compose Message</b></p>
                                    <div id="student_module_progression_message_editor_email" style="min-height:200px;">Dear {stud_first_name},<br><br>Congratulations! You have now progressed to the next module.<br><br>Your new fee balance is <b>Kes {next_module_fees}</b>.<br><br>Thank you,<br>{school_name}</div>
                                </div>
                                <div class="col-md-6">
                                    <p><b class="text-primary">Sample Message</b></p>
                                    <small id="student_module_progression_message_viewer_email">Message Sample will appear here</small>
                                </div>
                                <span class="my-2 mx-auto btn btn-sm w-75 btn-primary" id="save_student_module_progression_message_email"><i class="fas fa-save"></i> Save</span>
                                <p id="student_module_progression_message_template_holder_email"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>