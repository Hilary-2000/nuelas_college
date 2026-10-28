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
        <!-- M-Pesa statistics -->
        <div id="mpesa_stats_holder" class="section_card stats_card">
            <p style="font-weight:700;font-size:15px;margin-bottom:12px;">
                <i class="fas fa-mobile-alt" style="color:cadetblue;"></i>&nbsp; M-Pesa Transaction Overview (Last 30 days)
            </p>
            <!-- Stat cards -->
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
                <div style="flex:1;min-width:140px;background:#e8f4fd;border-left:4px solid #2196F3;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#2196F3;line-height:1.1;" id="mpesa_total"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-exchange-alt"></i>&nbsp;Total Transactions</div>
                </div>
                <div style="flex:1;min-width:140px;background:#e8f5e9;border-left:4px solid #4CAF50;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#4CAF50;line-height:1.1;" id="mpesa_assigned"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-check-circle"></i>&nbsp;Assigned <span id="mpesa_assigned_pct" style="color:#4CAF50;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#fce4ec;border-left:4px solid #E53935;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#E53935;line-height:1.1;" id="mpesa_unassigned"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-exclamation-circle"></i>&nbsp;Unassigned <span id="mpesa_unassigned_pct" style="color:#E53935;font-weight:600;"></span></div>
                </div>
                <div style="flex:1;min-width:140px;background:#e8f5e9;border-left:4px solid #00897B;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:1.4rem;font-weight:700;color:#00897B;line-height:1.1;" id="mpesa_total_amount"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-coins"></i>&nbsp;Last 30 Days (KES)</div>
                </div>
                <div style="flex:1;min-width:140px;background:#fffde7;border-left:4px solid #FFC107;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:2rem;font-weight:700;color:#FFC107;line-height:1.1;" id="mpesa_today_count"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-calendar-day"></i>&nbsp;Today's Transactions</div>
                </div>
                <div style="flex:1;min-width:140px;background:#fff3e0;border-left:4px solid #FF9800;border-radius:8px;padding:14px 16px;">
                    <div style="font-size:1.4rem;font-weight:700;color:#FF9800;line-height:1.1;" id="mpesa_today_amount"><img src="images/ajax_clock_small.gif"></div>
                    <div style="font-size:12px;color:#555;margin-top:4px;"><i class="fas fa-wallet"></i>&nbsp;Today's Amount (KES)</div>
                </div>
            </div>
            <!-- Progress bars: Assigned & Unassigned -->
            <div style="background:#fff;border:1px solid #e8e8e8;border-radius:8px;padding:14px 18px;">
                <small><b>Last 30 days</b></small>
                <div style="margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;margin-bottom:4px;">
                        <span><i class="fas fa-check-circle" style="color:#4CAF50;"></i>&nbsp;Assigned</span>
                        <span id="mpesa_assigned_pct_bar" style="font-weight:600;">0%</span>
                    </div>
                    <div style="background:#eee;border-radius:10px;height:8px;overflow:hidden;">
                        <div id="mpesa_assigned_bar" style="background:#4CAF50;height:8px;border-radius:10px;width:0%;transition:width 0.7s ease;"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#555;margin-bottom:4px;">
                        <span><i class="fas fa-exclamation-circle" style="color:#E53935;"></i>&nbsp;Unassigned</span>
                        <span id="mpesa_unassigned_pct_bar" style="font-weight:600;">0%</span>
                    </div>
                    <div style="background:#eee;border-radius:10px;height:8px;overflow:hidden;">
                        <div id="mpesa_unassigned_bar" style="background:#E53935;height:8px;border-radius:10px;width:0%;transition:width 0.7s ease;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <p><strong>Hint:</strong></p>
                <p>- At this window you will view the MPESA transactions recieved by the system</p>
                <p>- There are two types of payments at this window, The <strong>Assigned Payments</strong> and the <strong>Unassigned Payment</strong>.</p>
                <p>- The assigned payment are the payments that were correctly assigned to according to the student`s admission number.</p>
                <p>- The unassigned payments are the payments that were done but to the wrong admission numbers.</p>
                <p>- For the un-assigned payments there are actions that are to be done.</p>
            </div>
            <div class="my-2" id="mpesa_payment_tbl">
                <div class="section_card">
                    <div>
                        <p class="hide" id="student_done"><?php echo $student_done?></p>
                        <p class="sections">M-PESA Transaction Table</p>
                    </div>
                    <div>
                        <p class="hide" id="output"></p>
                        <div class="row m-0">
                            <div class="col-sm-7">
                                <!-- Add the loading element here -->
                                <div class="container d-flex align-content-center justify-content-left p-2 hide">
                                    <p id="data_error_holder"></p>
                                </div>
                            </div>
                            <div class="col sm-5">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive p-0" id="transDataReciever">
                            <p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No M-Pesa transactions has been captured yet!</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- the assign fees window -->
            <div class="container hide" id="payment_information">
                <div class="mt-2">
                    <p class="link" id="goback_link" style="text-align:left;font-weight:600;"><i class="fas fa-arrow-left"></i> Go back</p>
                </div>

                <!-- Transaction summary -->
                <div class="section_card my-3 mx-auto" style="max-width:760px;">
                    <p class="sections"><i class="fas fa-mobile-alt"></i> Assign M-Pesa Transaction</p>
                    <div>
                        <p class="hide" id="output_mpesa_transactions"></p>
                        <input type="hidden" name="" id="mpesa_payment_amount_holder" value="0">
                        <span class="d-none" id="msisdn"></span>
                        <div class="row m-0" style="row-gap:14px;">
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-hashtag"></i> Payment Id</div>
                                <div style="font-size:14px;font-weight:700;color:#333;" id="payment_id">...</div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-receipt"></i> Transaction Id</div>
                                <div style="font-size:14px;font-weight:700;color:#333;" id="mpesa_id">...</div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-money-bill-wave"></i> Amount</div>
                                <div style="font-size:16px;font-weight:700;color:#28a745;">Kes <span id="assign_payment_amount_paid">...</span></div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-exclamation-triangle"></i> Wrong Student Id</div>
                                <div><span style="display:inline-block;background:#fdecea;color:#c0392b;font-weight:700;font-size:13px;padding:3px 10px;border-radius:5px;" id="wrong_adm">...</span></div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-clock"></i> Date of Transaction</div>
                                <div style="font-size:13px;font-weight:700;color:#333;" id="trans_time">...</div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.4px;"><i class="fas fa-user"></i> Payer Name</div>
                                <div style="font-size:14px;font-weight:700;color:#333;" id="payer_name">...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Find the student and assign -->
                <div id="mpesa_student_assign_window" class="section_card mx-auto" style="max-width:760px;">
                    <p class="sections"><i class="fas fa-user-graduate"></i> Assign to Student</p>
                    <div class="form-group" style="max-width:400px;">
                        <label for="stud_admission_no" class="form-control-label"><b>Enter student admission number</b></label>
                        <div class="d-flex" style="gap:6px;">
                            <div class="autocomplete" style="flex:1;">
                                <input type="text" name="stud_admission_no" id="stud_admission_no" class="form-control w-100" placeholder="Admission No">
                            </div>
                            <button class="btn btn-primary btn-sm" id="find_student_assign"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                    <hr>
                    <div class="my-2 hide" id="result_holder">
                        <div class="container-fluid" id="student_results"></div>
                        <div class="conts my-2">
                            <label class="form-control-label"><b>Select what the payment is for</b></label>
                            <p id="payments_options"></p>
                        </div>
                        <div class="button">
                            <div style="background:#e3f2fd;border-left:4px solid #1565c0;border-radius:6px;padding:10px 14px;margin-bottom:10px;">
                                <p class="m-0" style="font-size:13px;color:#333;"><i class="fas fa-info-circle"></i> By clicking Assign Payment you are confirming transfer of MPESA ID: <strong id="mpesa_idds">...</strong> of Ksh <strong id="amount_to_transfer">...</strong> to <strong id="stud_name">...</strong>.</p>
                            </div>
                            <button class="btn btn-primary" id="assigne_payment_btn"><i class="fas fa-link"></i> Assign Payment</button>
                            <p id="error_handled"></p>
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