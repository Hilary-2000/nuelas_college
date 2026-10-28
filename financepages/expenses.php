<div class="contents animate hide" id="expenses_win">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Record expenses</p>
                </div>
                <div class="col-md-3">
                    <span id="record_expenses_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <div class="notify">
                    <p><strong>Hint:</strong></p>
                </div>
                <p>- At this window you are allowed to record expenses and liabilities from both operating and non-operating activities.</p>
                <p>- Please capture the information you enter correctly.</p>
            </div>
            <div class="section_card ex_card">
                <!-- options: the add form and the find block open in place of these -->
                <div class="ex_actions" id="exp_options">
                    <div class="ex_action" id="add_exp" role="button" tabindex="0">
                        <span class="ex_action_icon"><i class="fas fa-plus"></i></span>
                        <span class="ex_action_text"><b>Add Expense</b><small>Record an expense or make a payment request</small></span>
                    </div>
                    <div class="ex_action <?=allowed("allow_expenses_approval") == "d-none" && $_SESSION['authority'] != "1" ? "d-none" : ""?>" id="approve_payments" role="button" tabindex="0">
                        <span class="ex_action_icon"><i class="fas fa-check-double"></i></span>
                        <span class="ex_action_text"><b>Approve Payments</b><small>Accept or decline pending payment requests</small></span>
                    </div>
                    <button class="hide" id="find_exp_da"><i class="fas fa-search"></i> Find Expenses</button>
                </div>
                <div class="find_exp hide" id="find_exp_date">
                    <p class="card_subheading">Find Expense</p>
                    <div class="conts">
                        <label class="form-control-label" for="view-options-date">Select options: <br></label>
                        <select class="form-control" name="view-options-date" id="view-options-date">
                            <option value="" hidden>Select option..</option>
                            <option value="by-date">By date</option>
                            <option value="by-month">By Month</option>
                        </select>
                        <div class="bydate-view hide" id="bydates_viewings">
                            <label class="form-control-label" for="date_for_exp">Select date: <br></label>
                            <input class="form-control" type="date" name="date_for_exp" id="date_for_exp" max=<?php echo date("Y-m-d");?>>
                        </div>
                        <div class="bymonth-view hide" id="by_months_viewing">
                            <label class="form-control-label" for="sele-years">Select year: <br></label>
                            <select class="form-control" name="sele-years" id="sele-years">
                                <option value="" hidden>Select option..</option>
                                <?php
                                    $year = date("Y");
                                    for ($count=$year; $count > 2018; $count--) { 
                                        echo "<option value='$count'>$count</option>";
                                    }
                                ?>
                            </select>
                            <label class="form-control-label" for="month_for_exp"><br>Select Month: <br></label>
                            <select class="form-control" name="month_for_exp" id="month_for_exp">
                                <option value="" hidden>Select option..</option>
                                <option value="Jan">January</option>
                                <option value="Feb">February</option>
                                <option value="Mar">March</option>
                                <option value="Apr">April</option>
                                <option value="May">May</option>
                                <option value="Jun">June</option>
                                <option value="Jul">July</option>
                                <option value="Aug">August</option>
                                <option value="Sep">September</option>
                                <option value="Oct">October</option>
                                <option value="Nov">November</option>
                                <option value="Dec">December</option>
                            </select>
                        </div>
                        <p id="date_err"></p>
                    </div>
                    <div class="conts">
                        <button id="disp_btns"><i class="fas fa-search"></i> Display</button>
                        <button id='done_display_exp'><i class="fas fa-times"></i> Close</button>
                    </div>
                </div>
                <div class="hide" id="recordexp">
                    <div class="ex_head">
                        <span class="ex_head_icon"><i class="fas fa-file-invoice"></i></span>
                        <div><b>Add Expense</b><small>Expenses are paid once approved by the principal or an approver. Pick "Pending Approval" to send it as a payment request.</small></div>
                    </div>
                    <div class="ex_group">
                        <div class="ex_group_title"><i class="fas fa-tag"></i> What is it for?</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-control-label" for="exp_named">Expense Name</label>
                                <input class="form-control w-100" type="text" name="exp_named" id="exp_named" placeholder="e.g. Office stationery">
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="exp_cat">Expense Category <span class="hide" id="load_expense_categs"><img src="images/ajax_clock_small.gif"></span></label>
                                <div id="expense_categories_holders"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label">Expense Sub-Category <span class="hide" id="load_expense_sub_categs"><img src="images/ajax_clock_small.gif"></span></label>
                                <div id="expense_subcategory_display"><p class="ex_hint">Select an expense category to see its sub-categories.</p></div>
                            </div>
                            <div class="col-md-4">
                                <label for="expense_cash_activity" class="form-control-label">Expense Activity</label>
                                <select name="expense_cash_activity" id="expense_cash_activity" class="form-control w-100">
                                    <option value="" hidden>Select Option</option>
                                    <option value="1">Operating Activities</option>
                                    <option value="2">Investing Activities</option>
                                    <option value="3">Financing Activities</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="new_expense_description" class="form-control-label">Expense Description</label>
                                <textarea name="new_expense_description" id="new_expense_description" rows="2" class="form-control w-100" placeholder="Write expense description here.."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="ex_group">
                        <div class="ex_group_title"><i class="fas fa-money-bill-wave"></i> Amount and record</div>
                        <div class="row g-3">
                            <div class="col-md-4 hide">
                                <label class="form-control-label" for="exp_quant">Expense quantity</label>
                                <input class="form-control w-100" value="1" type="number" name="exp_quant" id="exp_quant" min="0" placeholder="Quantity">
                            </div>
                            <div class="col-md-4 hide">
                                <label class="form-control-label" for="exp_amnt">Expense unit cost</label>
                                <input class="form-control w-100" type="number" name="exp_amnt" id="exp_amnt" value="0" min="0" placeholder="Amount">
                            </div>
                            <div class="col-md-4 hide">
                                <label class="form-control-label" for="unit_name">Unit Name <small>(eg. kgs, litres)</small></label>
                                <input class="form-control w-100" type="text" name="unit_name" id="unit_name" placeholder="Unit Name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="exp_total_amt">Expense Amount</label>
                                <div class="ex_money">
                                    <span>Kes</span>
                                    <input class="form-control" type="number" name="exp_total_amt" id="exp_total_amt" min="0" placeholder="Amount">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="expense_record_date" class="form-control-label">Expense Record Date</label>
                                <input type="date" class="form-control w-100" id="expense_record_date" value="<?php echo date("Y-m-d");?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="document_number">Document Number</label>
                                <input class="form-control w-100" type="text" name="document_number" id="document_number" placeholder="Optional: receipt, invoice or cheque no.">
                            </div>
                            <div class="col-md-4 <?=allowed("allow_expenses_approval") == "d-none" && $_SESSION['authority'] != "1" ? "d-none" : ""?>">
                                <label for="expense_approval" class="form-control-label">Expense Approval Status</label>
                                <select name="expense_approval" id="expense_approval" class="form-control w-100">
                                    <option value="" hidden>Select an Option</option>
                                    <option <?=allowed("allow_expenses_approval") == "d-none" && $_SESSION['authority'] != "1" ? "" : "selected"?> value="1">Approve Expense</option>
                                    <option value="0">Pending Approval</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <p id='err_hndler_expenses'></p>
                    <div class="ex_form_actions">
                        <div id="error_message_expenses"><p class="ex_hint ex_hint_warn"><i class="fas fa-info-circle"></i> Select an expense category before you proceed.</p></div>
                        <button type="button" class="ex_btn_ghost" id='done_adding_exp'><i class="fas fa-times"></i> Close</button>
                        <button type="button" class="hide" id='add_expenseed'><i class="fas fa-paper-plane"></i> Save Expense</button>
                    </div>
                </div>
            </div>
            <div class="display_daily_expense">
                <!-- summary of the loaded expenses (expense.js showExpenseSummary) -->
                <div class="ex_stats" id="expense_summary"></div>
                <div id="window_expenses" class="section_card ex_card">
                    <div class="ex_head">
                        <span class="ex_head_icon"><i class="fas fa-receipt"></i></span>
                        <div><b>Expenses</b><small>The latest 1,000 expenses. Hover a status to see who requested and approved it.</small></div>
                    </div>
                    <div class="table-responsive" id="transDataReciever_expenses">
                        <p class="ex_hint">Loading expenses...</p>
                    </div>
                </div>
                <div class="section_card ex_card">
                    <div class="ex_head">
                        <span class="ex_head_icon"><i class="fas fa-chart-bar"></i></span>
                        <div><b>Expenses by Category</b><small>Approved and pending expenses grouped by category (declined ones are left out).</small></div>
                    </div>
                    <div class="ex_category" id="my_table"></div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>