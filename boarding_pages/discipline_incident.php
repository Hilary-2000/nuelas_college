<div class="contents animate hide" id="discipline_incident_mgt_window">
    <div class="titled">
        <h2>Discipline & Incidents</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Discipline & Incidents Management</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
        </div>
        <div class="middle1">
            <p><b>Note:</b></p>
            <p>In this window you`ll be able to manage students misconducts and incidents.
            <br>You will be able to record, update and delete misconducts and incidents flowlessly.
            <br>Example:
            <ul>
                <li>Student writes grafitti on hostel walls → Staff records it as a misconducts and warning is issued.</li>
                <li>Students laptops stolen from dormitories → records it under incidents with high security threat</li>
                <li>Student suffers minor injury during sports → their ankle was dislocated and they were taken to the school clinic.</li>
            </ul>
            </p>
            <hr class="my-1">
            <ul class="nav nav-tabs" id="discipline_incident_tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary active" id="discipline_tab" data-bs-toggle="tab" data-bs-target="#discipline_window" type="button" role="tab" aria-controls="discipline_window" aria-selected="true">Discipline/Incidents Management</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary" id="warning_tab" data-bs-toggle="tab" data-bs-target="#warning_window" type="button" role="tab" aria-controls="warning_window" aria-selected="false">Student Warning Management</button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="discipline_window" role="tabpanel" aria-labelledby="discipline_tab">
                    <h5 class="title text-center mt-2">Discipline/Miscounduct & Incidents Management</h5>
                    <button class="btn btn-sm btn-primary" id="report_new_incidents" type="button"><i class="fa fa-plus"></i> Report New Incident</button>
                    <span id="incident_error_holder"></span>
                    <div class="tableme my-2" id="incident_table_holder">
                        
                    </div>
                </div>
                <div class="tab-pane fade" id="warning_window" role="tabpanel" aria-labelledby="warning_tab">
                    <h5 class="title text-center mt-2">Student Warning Management</h5>
                    <button class="btn btn-sm btn-primary" id="record_new_warning" type="button"><i class="fa fa-plus"></i> Issue Warning</button>
                    <span id="warning_error_holder"></span>
                    <div class="tableme my-2" id="warning_table_holder">
                        
                    </div>
                </div>
                <!-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact</div> -->
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>