<div class="contents animate hide" id="mpesa_trans">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>M-Pesa Transactions</p>
                </div>
                <div class="col-md-3">
                    <span id="mpesa_trans_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- At this window you will view the MPESA transactions recieved by the system</p>
                <p>- There are two types of payments at this window, The <strong>Assigned Payments</strong> and the <strong>Unassigned Payment</strong>.</p>
                <p>- The assigned payment are the payments that were correctly assigned to according to the student`s admission number.</p>
                <p>- The unassigned payments are the payments that were done but to the wrong admission numbers.</p>
                <p>- For the un-assigned payments there are actions that are to be done.</p>
            </div>
            <div class="container shadow-lg my-2 " id="mpesa_payment_tbl">
                <div class="card mb-4">
                    <div class="card-header">
                        <p class="hide" id="student_done"><?php echo $student_done?></p>
                        <div class="card-title">
                            <h5 class="text-primary">M-PESA Transaction Table.</h5>
                        </div>
                    </div>
                    <div class="card-header">
                        <p class="hide" id="output"></p>
                        <div class="row m-0">
                            <div class="col-sm-7">
                                <!-- Add the loading element here -->
                                <div class="container d-flex align-content-center justify-content-left p-2 hide">
                                    <p  id="completedTransHolder" >Loading <i class="fas fa-star fa-spin"></i><i class="fas fa-star fa-spin"></i><i class="fas fa-star fa-spin"></i></p>
                                    <p id="data_error_holder"></p>
                                </div>
                            </div>
                            <div class="col sm-5">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0" id="transDataReciever">
                            <p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No M-Pesa transactions has been captured yet!</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- the assigne fees window -->
            <div class="container hide" id="payment_information">
                <!-- display the payment information -->
                <div class="back mt-2">
                    <p class="link" id="goback_link" style="text-align: left;"><i class="fas fa-arrow-left"></i> Go back</p>
                </div>
                <div class="row rounded-25 border border-secondary p-2 my-2 mx-auto">
                    <h5 class="text-center"><u>Assign Transaction</u></h5>
                    <div class="col-md-6">
                        <h6 class="text-primary">Transaction information</h6>
                        <p class="hide" id="output_mpesa_transactions"></p>
                        <p>
                            <span class="text-dark text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Payment Id: </strong><span id="payment_id">PHJHJHGHJH</span></span><br>
                            <span class="text-dark text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Transaction Id: </strong><span id="mpesa_id">PHJHJHGHJH</span></span><br>
                            <span class="text-dark text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Transaction Amount: </strong> Kes <span id="assign_payment_amount_paid">1000,</span></span><br>
                            <input type="hidden" name="" id="mpesa_payment_amount_holder" value="0">
                            <span class="text-danger text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Wrong Student Id: </strong><span id="wrong_adm">12</span></span><br>
                            <span class="text-dark text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Date of transaction: </strong><span id="trans_time"> 12th Aug 2022 10:00:00</span></span><br>
                            <span class="text-dark text-uppercase text-xxs"><strong> <i class="fas fa-check"></i> Payer Name: </strong><span id="payer_name">ADALA HILLARY NGIGE</span></span><br>
                            <span class="text-dark text-uppercase text-xxs d-none"><strong> <i class="fas fa-check"></i> MSISDN: </strong><span id="msisdn">254743551250</span></span><br>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Find Student</h6>
                        <div class="form-group float-right" style="width: fit-content;">
                            <label for="stud_admission_no" class="form-control-label">Enter student admission to associate with the payment:</label>
                            <div class="form-group">
                                <div class="autocomplete">
                                    <input type="text" name="stud_admission_no" id="stud_admission_no" class="" placeholder="Admission No">
                                </div>
                                <button class="btn btn-primary btn-sm" id="find_student_assign">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="container my-2 hide" id="result_holder">
                    <div class="container-fluid" id="student_results">
                    </div>
                    <div class="conts my-2">
                        <label for="payment_for_option" class="form-control-label"><b>Select what the payment is for</b></label>
                        <p id="payments_options"></p>
                    </div>
                    <div class="button">
                        <div class="bg-white p-1">
                            <p class="text-bolder">By clicking Assign Payment you are confirming transfer of MPESA ID: <strong id="mpesa_idds">PHJHJHBHN</strong> of Ksh <strong id="amount_to_transfer">1000</strong> to <strong id="stud_name">Student name</strong>.</p>
                        </div>
                        <button class="btn btn-primary " id="assigne_payment_btn">Assign Payment</button>
                        <p id="error_handled"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>