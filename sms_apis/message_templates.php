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
                    <div class="conts border border-secondary rounded m-2 p-2">
                        <p><b>Tags<img src="images/ajax_clock_small.gif" class="hide" id="class_loader_tags"></b></p>
                        <p>- Tags are message placeholders for dynamic data used by the system when sending bulk messages.</p>
                        <div class="row">
                            <div class="col-md-3"><p><b>Student Full Name</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_fullname}</i></span></div>
                            <div class="col-md-3"><p><b>Student First Name</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_first_name}</i></span></div>
                        </div>
                        <div class="row d-none">
                            <div class="col-md-3"><p><b>Student Class</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_class}</i></span></div>
                            <div class="col-md-3"><p><b>Student Age</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_age}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Student Fees Balance</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_fees_balance}</i></span></div>
                            <div class="col-md-3"><p><b>Student Fees To Pay</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_fees_to_pay}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Student Fees Paid</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_fees_paid}</i></span></div>
                            <div class="col-md-3"><p><b>Student Noun</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_noun}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Parent Fullname</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{par_fullname}</i></span></div>
                            <div class="col-md-3"><p><b>Parent First Name</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{par_first_name}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Parent title 1</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{title_1}</i></span></div>
                            <div class="col-md-3"><p><b>Parent title 2</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{title_2}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Today</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{today}</i></span></div>
                            <div class="col-md-3"><p><b>Student Admission No.</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{stud_adm}</i></span></div>
                            <div class="col-md-3"><p><b>School Name.</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{school_name}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>School Contacts</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{school_contact}</i></span></div>
                            <div class="col-md-3"><p><b>School Email.</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{school_email}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Fees Paid</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{amount_paid}</i></span></div>
                            <div class="col-md-3"><p><b>Time Now.</b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{time}</i></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><p><b>Receipt Link: </b></p></div>
                            <div class="col-md-3"><span><i class="text-danger">{receipt_url}</i></span></div>
                            <div class="col-md-3 d-none"><p><b>Parent Children: </b></p></div>
                            <div class="col-md-3 d-none"><span><i class="text-danger">{children}</i></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting_s">
                <div class="bg-secondary mt-0 rounded">
                    <p class="text-center text-white">Set Student welcome message</p>
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
                    <p class="text-center text-white">Set Parent Welcome message</p>
                </div>
                <div class="conts p-2">
                    <p class="hide" id="messages_holder_templates"></p>
                    <p class="hide" id="school_information_holder"></p>
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
                    <p class="text-center text-white">Student Account Confirmation Message</p>
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
                    <p class="text-center text-white">Parent Account Confirmation Message</p>
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
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>