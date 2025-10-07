<div class="contents animate hide" id="staff_payroll_information">
    <div class="titled">
        <h2>Payslip & Advance Management</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Payslip & Advance</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
        </div>
        <div class="middle1">
            <p><b>Note:</b></p>
            <p>Welcome <b><?php echo ucwords(strtolower($_SESSION['fullnames']));?></b> to this window, 
            <br>You will be able to view advances you applied for and your payroll information.</p>
            <hr class="my-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary active" id="monthly-payslip-tab" data-bs-toggle="tab" data-bs-target="#monthly-payslip-window" type="button" role="tab" aria-controls="monthly-payslip-window" aria-selected="true">Monthly Payroll</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary" id="advance-history-tab" data-bs-toggle="tab" data-bs-target="#advance-window" type="button" role="tab" aria-controls="advance-window" aria-selected="false">Advance History</button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <button class="btn btn-sm btn-primary" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="monthly-payslip-window" role="tabpanel" aria-labelledby="monthly-payslip-tab">
                    <h5 class="title text-center"><u>Monthly Payslip</u></h5>
                    <p><b>Note:</b></p>
                    <ul>
                        <li>Hover your mouse over the deductions column to see the respective deductions.</li>
                    </ul>
                    <div class="tableme" id="monthly_payslip_holder">
                        
                    </div>
                </div>
                <div class="tab-pane fade" id="advance-window" role="tabpanel" aria-labelledby="advance-history-tab">
                    <h5 class="title text-center"><u>Advance History</u></h5>
                    <p><b>Note:</b></p>
                    <ul>
                        <li>Hover your mouse over the instalments column to see the payment history.</li>
                    </ul>
                    <div class="tableme" id="my_advance_application_history">
                        
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