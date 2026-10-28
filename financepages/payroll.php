<div class="contents animate hide" id="payrolled_win">
    <div class="titled">
        <h2>Payroll System</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Payroll System</p>
                </div>
                <div class="col-md-3">
                    <span id="payroll_sys_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <div class="notify">
                    <p><strong>Hint:</strong></p>
                </div>
                <p>- Pay your staff using the payroll system.</p>
                <p>- Select different option to accomplish your task.</p>
            </div>
            <div class="conts">
                <!-- payroll menu: the active option is highlighted by finance.js (setPayrollNav) -->
                <div class="pr_nav" id="payroll_nav">
                    <div class="pr_nav_main">
                        <div class="pr_nav_btn" id="enroll_staff_btn" role="button" tabindex="0">
                            <span class="pr_nav_icon"><i class="fas fa-user-plus"></i></span>
                            <span class="pr_nav_text"><b>Enroll Staff</b><small>Add a staff member to the payroll</small></span>
                        </div>
                        <div class="pr_nav_btn" id="see_enrolled" role="button" tabindex="0">
                            <span class="pr_nav_icon"><i class="fas fa-users"></i></span>
                            <span class="pr_nav_text"><b>Enrolled Staff</b><small>Edit salaries, view history and pay</small></span>
                        </div>
                        <div class="pr_nav_btn" id="advance_pay_view" role="button" tabindex="0">
                            <span class="pr_nav_icon"><i class="fas fa-hand-holding-usd"></i></span>
                            <span class="pr_nav_text"><b>Manage Advances</b><small>Salary advances and installments</small></span>
                        </div>
                    </div>
                    <div class="pr_nav_reports">
                        <span class="pr_nav_reports_title"><i class="fas fa-file-alt"></i> Compliance reports</span>
                        <div class="pr_nav_chip" id="kra_reports" role="button" tabindex="0">KRA</div>
                        <div class="pr_nav_chip" id="nssf_reports" role="button" tabindex="0">NSSF</div>
                        <div class="pr_nav_chip" id="nhif_reports" role="button" tabindex="0">SHIF</div>
                        <div class="pr_nav_chip" id="nita_reports" role="button" tabindex="0">NITA</div>
                    </div>
                </div>
                <div class="staff_information">
                    <!-- enroll staff -->
                    <form class="enroll_staf hide" id="payroll_enroll">
                        <div class="pr_view_head">
                            <span class="pr_view_icon"><i class="fas fa-user-plus"></i></span>
                            <div><b>Enroll Staff</b><small>Pick a staff member, set their salary details and check the net pay before enrolling.</small></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="pr_card">
                                    <div class="pr_card_title"><i class="fas fa-id-badge"></i> Staff Details</div>
                                    <div class="information_window pr_help">
                                        <h6 id="head_infor">Who am I</h6>
                                        <p id="para_infor">Click <span class="more_infor">Info</span> to see more information about the field.</p>
                                    </div>
                                    <div class="pr_field">
                                        <label class="form-control-label" for="staff_l">Select staff to enroll <span class="more_infor" title="Click to show more information about the field" id="staff_en">Info</span></label>
                                        <p id="staff_li"></p>
                                    </div>
                                    <div class="pr_field">
                                        <label class="form-control-label" for="amount_to_pay">Salary amount <span class="pr_readonly">Read only</span> <span class="more_infor" title="Click to show more information about the field" id="staff_salo">Info</span></label>
                                        <input class="form-control" type="number" readonly name="amount_to_pay" id="amount_to_pay" placeholder="Salary amount" value="1" min="1">
                                    </div>
                                    <div class="pr_field">
                                        <label class="form-control-label" for="effect_from">Current month <span class="more_infor" title="Click to show more information about the field" id="staff_currMon">Info</span></label>
                                        <select class="form-control" name="effect_from" id="effect_from">
                                            <option value="" hidden>Select Month</option>
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
                                        <p id="tell_pay"></p>
                                    </div>
                                    <div class="pr_field">
                                        <label class="form-control-label" for="effect_year">Current year <span class="more_infor" title="Click to show more information about the field" id="staff_currYear">Info</span></label>
                                        <select class="form-control" name="effect_year" id="effect_year">
                                            <option value="" hidden>Select Year</option>
                                            <?php
                                            $year = date("Y");
                                            for ($count = $year; $count > 2020; $count--) {
                                                echo "<option value='$count'>$count</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="pr_field">
                                        <label class="form-control-label" for="balances">Balance <span class="more_infor" title="Click to show more information about the field" id="staff_accruedbal">Info</span></label>
                                        <input class="form-control" type="number" name="balances" id="balances" placeholder="Balances" value="1" min="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="pr_card pr_calc">
                                    <div id="payrollOverlay" class="pr_overlay">
                                        <div class="pr_overlay_box">
                                            <span class="pr_overlay_icon"><i class="fas fa-lock"></i></span>
                                            <p class="mb-1"><strong>Complete these fields to unlock the Net Pay Calculator:</strong></p>
                                            <p class="mb-0" id="payrollOverlayMissing">Select Staff, Current Month, Current Year, Balance</p>
                                        </div>
                                    </div>
                                    <div class="pr_card_title"><i class="fas fa-calculator"></i> Net Pay Calculator <span class="pr_card_note">Based on Finance Act 2023</span></div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="pr_block">
                                                <label for="gross_salary" class="pr_block_title">Gross Salary</label>
                                                <div class="pr_money">
                                                    <span>Kes</span>
                                                    <input type="number" name="gross_salary" id="gross_salary" value="100" class="form-control" placeholder="Gross salary">
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <p class="hide" id="allowance_holder"></p>
                                                <div class="pr_block_title">Allowances and Bonuses <span class="pr_add" id="add_allowances_in" role="button" tabindex="0"><i class="fa fa-plus"></i> Add</span></div>
                                                <div class="pr_items" id="allowances_and_bonuses">
                                                    <p class='pr_empty'>No allowances to display at the moment.</p>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <p class="hide" id="deductions_holder_1"></p>
                                                <div class="pr_block_title">Deductions <span class="pr_add" id="add_deductions_1" role="button" tabindex="0"><i class="fa fa-plus"></i> Add</span></div>
                                                <div class="pr_items" id="deductions_windoww_1">
                                                    <p class='pr_empty'>No deductions to display at the moment.</p>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <div class="pr_block_title">Statutory Deductions</div>
                                                <label class="form-control-label" for="nssf_rates">NSSF Rates</label>
                                                <select name="nssf_rates" id="nssf_rates" class="form-control">
                                                    <option value="" hidden>Select Rates</option>
                                                    <option value="teir_1">Teir 1</option>
                                                    <option selected value="teir_1_2">Teir 1 & 2</option>
                                                    <option value="teir_old">Old Rates</option>
                                                    <option value="none">None</option>
                                                </select>
                                                <label class="pr_toggle" for="deduct_paye"><span>Deduct P.A.Y.E</span><input type="checkbox" checked name="deduct_paye" id="deduct_paye"></label>
                                                <label class="pr_toggle" for="deduct_NHIF"><span>Deduct SHIF</span><input type="checkbox" checked name="deduct_NHIF" id="deduct_NHIF"></label>
                                                <div class="deduct_housing_levy">
                                                    <label class="pr_toggle" for="deduct_nhds"><span>Deduct Housing Levy</span><input type="checkbox" checked name="deduct_nhds" id="deduct_nhds"></label>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <div class="pr_block_title">Reliefs</div>
                                                <label class="pr_toggle" for="personal_relief"><span>Personal Relief</span><input type="checkbox" checked name="personal_relief" id="personal_relief"></label>
                                                <label class="pr_toggle" for="NHIF_relief"><span>SHIF Relief</span><input type="checkbox" name="NHIF_relief" id="NHIF_relief"></label>
                                                <label class="pr_toggle" for="AHL_relief_checker"><span>AHL Relief</span><input type="checkbox" name="AHL_relief_checker" id="AHL_relief_checker"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="pr_slip">
                                                <div class="pr_slip_head"><i class="fas fa-receipt"></i> Calculation Breakdown</div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Gross Salary</div>
                                                    <div class="pr_line"><span>Basic Salary</span><p id="gros_salo_rec">Ksh 0</p></div>
                                                    <div class="pr_line"><span>Total Allowances</span><p id="all_allowances">Ksh 0</p></div>
                                                    <div class="pr_line pr_line_total"><span>Gross Salary</span><p id="total_salary">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Contributions Before Tax</div>
                                                    <div class="row pr_dyn" id="contributions_before_tax">
                                                        <div class="col-md-12"><p class="pr_dyn_empty">Contributions before tax will appear here</p></div>
                                                    </div>
                                                    <div class="pr_line pr_line_total"><span>Total Contributions</span><p id="contribution_before_tax_totals">Kes 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Taxable Income</div>
                                                    <div class="pr_line"><span>Taxable Income</span><p id="taxable_income_records">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Tax / P.A.Y.E</div>
                                                    <div class="pr_line"><span>Income Tax</span><p id="incomeTaxRecord">Ksh 0</p></div>
                                                    <div class="pr_line"><span>Income Tax Relief</span><p id="personal_relief_records">Ksh 0</p></div>
                                                    <div class="pr_line pr_line_total"><span>Final Income Tax</span><p id="final_income_taxe">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Income After Tax</div>
                                                    <div class="pr_line"><span>Income After Tax</span><p id="income_after_tax">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Contributions After Tax</div>
                                                    <div class="row pr_dyn" id="payments_after_tax">
                                                        <div class="col-md-12"><p class="pr_dyn_empty">Contributions after tax will appear here</p></div>
                                                    </div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Reliefs</div>
                                                    <div class="pr_line"><span>SHIF Relief</span><p id="nhif_relief_record">Ksh 0</p></div>
                                                    <div class="pr_line"><span>AHL Relief</span><p id="ahl_relief_record">Ksh 0</p></div>
                                                    <div class="pr_line pr_line_total"><span>Total Reliefs</span><p id="total_relief">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_slip_section">
                                                    <div class="pr_slip_title">Deductions</div>
                                                    <div class="pr_line"><span>Total Deductions</span><p id="deductions_calculate">Ksh 0</p></div>
                                                </div>
                                                <div class="pr_net"><span><i class="fas fa-wallet"></i> Net Salary</span><b id="net_salary_record">Ksh 0</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pr_actions">
                            <p id="enroll_err_handler"></p>
                            <button type='button' id="enrol_staf_btn"><i class="fa fa-save"></i> Enroll Staff</button>
                        </div>
                    </form>
                    <!-- view staff enrolled -->
                    <div class="enroll_staf section_card hide" id="viewEnrolledPay">
                        <div class="pr_view_head">
                            <span class="pr_view_icon"><i class="fas fa-users"></i></span>
                            <div><b>Enrolled Staff</b><small>Edit a salary, view the payment history or pay a staff member.</small></div>
                        </div>
                        <div class="table_holders">
                            <p id="my_enrolled_staff"></p>
                        </div>
                    </div>
                    <div class="body3 section_card hide" id="pay_salary_staff">
                        <div class="pr_back"><p class="link" id="back_to_payroll"><i class="fas fa-arrow-left"></i> Back to enrolled staff</p></div>
                        <div class="pr_view_head">
                            <span class="pr_view_icon"><i class="fas fa-money-check-alt"></i></span>
                            <div><b>Pay Staff</b><small>Record a salary payment for this staff member.</small></div>
                            <button type="button" class="pr_btn_ghost" id="refresh_paydets"><i class="fas fa-sync"></i> Refresh</button>
                        </div>
                        <div class="pr_stats">
                            <div class="pr_stat"><small>Staff id</small><b id="stf_ids_pay">unknown</b></div>
                            <div class="pr_stat"><small>Last paid</small><b id="last_paid_time">unknown</b></div>
                            <div class="pr_stat"><small>Latest month</small><b><?php echo date("M-Y", strtotime("-1 month")); ?></b></div>
                            <div class="pr_stat"><small>Monthly salary</small><b id="monthly_salo">unknown</b></div>
                            <div class="pr_stat"><small>Balance</small><b id="salary_balances">unknown</b></div>
                            <div class="pr_stat pr_stat_main"><small>Total balance</small><b id="tot_bal">unknown</b></div>
                        </div>
                        <div class="pr_card pr_pay_form">
                            <div class="pr_field">
                                <label for="staff_name" class="form-control-label">Staff name</label>
                                <input type="text" class="form-control" name="staff_name" id="staff_name" placeholder="Staff name" readonly>
                            </div>
                            <div class="pr_field">
                                <label class='form-control-label' for="pay_mode">Payment mode</label>
                                <select class='form-control' name="pay_mode" id="pay_mode">
                                    <option value="" id="def_opt" hidden>Select an option</option>
                                    <option value="m-pesa">M-pesa</option>
                                    <option value="bank">Bank</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>
                            <div class="pr_field hide" id="mpesa_salary">
                                <label class='form-control-label' for="mpesa_code">M-pesa code</label>
                                <input type="text" class='form-control' name="mpesa_code" id="mpesa_code" placeholder="Mpesa code">
                            </div>
                            <div class="pr_field hide" id="banks_sal">
                                <label class='form-control-label' for="bank_code">Bank code</label>
                                <input type="text" class='form-control' name="bank_code" id="bank_code" placeholder="Bank code">
                            </div>
                            <div class="pr_field hide" id="amount_sal">
                                <label class='form-control-label' for="amount_salary">Salary amount</label>
                                <div class="pr_money">
                                    <span>Kes</span>
                                    <input type="number" class='form-control' name="amount_salary" id="amount_salary" placeholder="Salary Amount">
                                </div>
                            </div>
                            <div class="pr_field hide" id="sal_pay_btns">
                                <button type="button" id="salary_pays_btns"><i class="fas fa-save"></i> Process Payment</button>
                                <p id="err_handler_in"></p>
                            </div>
                        </div>
                    </div>
                    <div class="body5 section_card hide" id="salary_infor">
                        <div class="pr_back"><p class="link" id="back_to_payrolls12"><i class="fas fa-arrow-left"></i> Back to enrolled staff</p></div>
                        <div class="pr_view_head">
                            <span class="pr_view_icon"><i class="fas fa-user-edit"></i></span>
                            <div><b>Edit Salary Information</b><small>Change the salary details and check the new net pay before updating.</small></div>
                            <p class="hide" id="salary_infor_br"></p>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-7">
                                <div class="pr_card">
                                    <div class="pr_card_title"><i class="fas fa-id-badge"></i> Staff <span class="pr_card_note">Staff id: <span id="stf_id_sal">10</span></span></div>
                                    <div class="row">
                                        <div class="col-md-6 pr_field">
                                            <label class='form-control-label' for="staff_name_ids_sal">Staff name</label>
                                            <input type="text" class='form-control' id="staff_name_ids_sal" placeholder="Staff name" readonly>
                                        </div>
                                        <div class="col-md-6 pr_field">
                                            <label class='form-control-label' for="change_salary">Net salary <span class="pr_card_note">Current: <span id="old_salary"></span></span></label>
                                            <input type="number" readonly class='form-control' name="change_salary" id="change_salary" placeholder="Salary" min=0>
                                        </div>
                                    </div>
                                    <p class="hide" id="old_salo"></p>
                                    <p id="err_handler_F"></p>
                                    <div class="pr_btn_row">
                                        <button type="button" id="changes_salary_btn"><i class="fas fa-upload"></i> Update</button>
                                        <button type="button" class="pr_btn_danger" id="unenroll_staff_salary"><i class="fas fa-trash"></i> Un-enroll</button>
                                    </div>
                                </div>
                                <div class="pr_card">
                                    <div class="pr_card_title"><i class="fas fa-calculator"></i> Net Pay Calculator <span class="pr_card_note">Based on Finance Act 2023</span></div>
                                    <p id="error_calaculator"></p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="pr_block">
                                                <label for="gross_salary_edit" class="pr_block_title">Gross Salary <span class="pr_card_note">Current: <span id="gross_sa"></span></span></label>
                                                <div class="pr_money">
                                                    <span>Kes</span>
                                                    <input type="number" value="100" class="form-control" id="gross_salary_edit" placeholder="Gross salary">
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <div class="pr_block_title">Effective From</div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label for="effect_month_payee" class="form-control-label">Month</label>
                                                        <select name="effect_month_payee" id="effect_month_payee" class="form-control">
                                                            <option value="" hidden>Select Month</option>
                                                            <option value="Jan">Jan</option>
                                                            <option value="Feb">Feb</option>
                                                            <option value="Mar">Mar</option>
                                                            <option value="Apr">Apr</option>
                                                            <option value="May">May</option>
                                                            <option value="Jun">Jun</option>
                                                            <option value="Jul">Jul</option>
                                                            <option value="Aug">Aug</option>
                                                            <option value="Sep">Sep</option>
                                                            <option value="Oct">Oct</option>
                                                            <option value="Nov">Nov</option>
                                                            <option value="Dec">Dec</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="year_of_effect_paye" class="form-control-label">Year</label>
                                                        <select name="year_of_effect_paye" id="year_of_effect_paye" class="form-control">
                                                            <option value="" hidden>Select year of effect</option>
                                                            <?php
                                                            $year = date("Y");
                                                            for ($count = $year; $count > 2020; $count--) {
                                                                $selected = "";
                                                                if ($count == $year) {
                                                                    $selected = "selected";
                                                                }
                                                                echo "<option id='yr_".$count."' " . $selected . " value='$count'>$count</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <p class="hide" id="allowance_holder_edit"></p>
                                                <div class="pr_block_title">Allowances <span class="pr_add" id="edit_allowances" role="button" tabindex="0"><i class="fa fa-plus"></i> Add</span></div>
                                                <div class="pr_items" id="allowance_html">
                                                    <p class='pr_empty'>No allowances to display at the moment.</p>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <p class="hide" id="deductions_holder"></p>
                                                <div class="pr_block_title">Deductions <span class="pr_add" id="add_deductions" role="button" tabindex="0"><i class="fa fa-plus"></i> Add</span></div>
                                                <div class="pr_items" id="deduction_windows">
                                                    <p class='pr_empty'>No deductions to display at the moment.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="pr_block">
                                                <div class="pr_block_title">Statutory Deductions</div>
                                                <label class="form-control-label" for="nssf_rates_edit">NSSF Rates</label>
                                                <select name="nssf_rates_edit" id="nssf_rates_edit" class="form-control">
                                                    <option value="" hidden>Select Rates</option>
                                                    <option id="teir_1" value="teir_1">Teir 1</option>
                                                    <option id="teir_1_2" value="teir_1_2">Teir 1 & 2</option>
                                                    <option id="teir_old" value="teir_old">Old Rates</option>
                                                    <option id="none" value="none">None</option>
                                                </select>
                                                <label class="pr_toggle" for="dedcut_paye_edit"><span>Deduct P.A.Y.E</span><input type="checkbox" name="dedcut_paye_edit" id="dedcut_paye_edit"></label>
                                                <label class="pr_toggle" for="dedcut_nhif_edit"><span>Deduct SHIF</span><input type="checkbox" name="dedcut_nhif_edit" id="dedcut_nhif_edit"></label>
                                                <div class="edit_housing_fund">
                                                    <label class="pr_toggle" for="edit_deduct_nhds"><span>Deduct Housing Levy</span><input type="checkbox" name="edit_deduct_nhds" id="edit_deduct_nhds"></label>
                                                </div>
                                            </div>
                                            <div class="pr_block">
                                                <div class="pr_block_title">Reliefs</div>
                                                <label class="pr_toggle" for="personal_relief_accept"><span>Personal Relief</span><input type="checkbox" name="personal_relief_accept" id="personal_relief_accept"></label>
                                                <label class="pr_toggle" for="nhif_relief_accept"><span>SHIF Relief</span><input type="checkbox" name="nhif_relief_accept" id="nhif_relief_accept"></label>
                                                <label class="pr_toggle" for="accept_ahl_relief"><span>AHL Relief</span><input type="checkbox" name="accept_ahl_relief" id="accept_ahl_relief"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <!-- the breakdown of the payment -->
                                <div class="pr_slip pr_slip_sticky">
                                    <div class="pr_slip_head"><i class="fas fa-receipt"></i> Calculation Breakdown</div>
                                    <p id="max_month_holders" class="d-none">[Jan:2018]</p>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Gross Salary</div>
                                        <div class="pr_line"><span>Gross Salary</span><p id="gros_salo_rec_edit">Ksh 0</p></div>
                                        <div class="pr_line"><span>Total Allowances</span><p id="all_allowances_edit">Ksh 0</p></div>
                                        <div class="pr_line pr_line_total"><span>Gross Salary Total</span><p id="gross_salary_total_edit">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Contributions Before Tax</div>
                                        <div class="row pr_dyn" id="contributions_before_tax_edit">
                                            <div class="col-md-12"><p class="pr_dyn_empty">Contributions before tax will appear here</p></div>
                                        </div>
                                        <div class="pr_line pr_line_total"><span>Total Contribution</span><p id="total_contributions_edit">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Taxable Income</div>
                                        <div class="pr_line"><span>Taxable Income</span><p id="taxable_income_records_edit">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Tax / P.A.Y.E</div>
                                        <div class="pr_line"><span>Income Tax</span><p id="incomeTaxRecord_edit">Ksh 0</p></div>
                                        <div class="pr_line"><span>Income Tax Relief</span><p id="personal_relief_records_edit">Ksh 0</p></div>
                                        <div class="pr_line pr_line_total"><span>Final Income Tax</span><p id="final_income_tax">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Reliefs</div>
                                        <div class="pr_line"><span>SHIF Relief</span><p id="nhif_relief_record_edit">Ksh 0</p></div>
                                        <div class="pr_line"><span>AHL Relief</span><p id="AHL_relief_edit">Ksh 0</p></div>
                                        <div class="pr_line pr_line_total"><span>Total Reliefs</span><p id="total_reliefs">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Contributions After Tax</div>
                                        <div class="row pr_dyn" id="payments_after_tax_edit">
                                            <div class="col-md-12"><p class="pr_dyn_empty">Contributions after tax will appear here</p></div>
                                        </div>
                                    </div>
                                    <div class="pr_slip_section">
                                        <div class="pr_slip_title">Deductions</div>
                                        <div class="pr_line"><span>Total Deductions</span><p id="all_deductions_edit">Ksh 0</p></div>
                                    </div>
                                    <div class="pr_net"><span><i class="fas fa-wallet"></i> Net Salary</span><b id="net_salary_record_edit">Ksh 0</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="body5 section_card hide" id="view_payment_history">
                        <div class="pr_back"><p class="link" id="back2_to_payroll123"><i class="fas fa-arrow-left"></i> Back to enrolled staff</p></div>
                        <div class="pr_view_head">
                            <span class="pr_view_icon"><i class="fas fa-history"></i></span>
                            <div><b>Payment History</b><small>Salary paid each month of the selected year.</small></div>
                            <div class="pr_year">
                                <label for="sel_yrs">Year</label>
                                <select class='form-control' id="sel_yrs">
                                    <option value="" hidden>Select year</option>
                                    <?php
                                    $year = date("Y",strtotime("1 year"));
                                    for ($count = $year; $count > 2018; $count--) {
                                        echo "<option value='$count'>$count</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" id="userPayId">
                        </div>
                        <div class="pr_legend">
                            <span>Payment modes:</span>
                            <span><b class="green_notice">-c</b> Cash</span>
                            <span><b class="green_notice">-b</b> Bank</span>
                            <span><b class="green_notice">-m</b> M-pesa</span>
                        </div>
                        <div class="pr_history" id="getmysalohistory">
                            <p class="pr_empty">Select a year to see the payment history.</p>
                        </div>
                        <div class="pr_back"><p class="link" id="back_to_payroll123"><i class="fas fa-arrow-left"></i> Back to enrolled staff</p></div>
                    </div>
                    <div class="section_card hide" id="advance_management">
                        <div class="container advances" id="view_all_advances_window">
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-hand-holding-usd"></i></span>
                                <div><b>Advances Registered <img class="hide" src="images/ajax_clock_small.gif" id="advance_registers_loaders"></b><small>Salary advances given to staff and how they are being repaid.</small></div>
                                <div class="pr_btn_tile" id="define_advance_pay" role="button" tabindex="0"><i class="fas fa-plus"></i> Add Advance</div>
                            </div>
                            <div class="pr_search">
                                <i class="fas fa-search"></i>
                                <input type="text" name="search_advances" id="search_advances" class="form-control w-100" placeholder="Search advances by name, amount or date...">
                            </div>
                            <p class="hide" id="data_advances_holder"></p>
                            <div class="table-responsive p-0" id="transDataReciever_advances">
                                <p class="pr_empty">Loading advances...</p>
                            </div>
                            <div class="row mt-3" id="tablefooter_advances">
                                <div class="col-sm-12 col-md-5">
                                    <p class="pr_page_info">Showing <span class="text-primary" id="startNo_advances">1 </span> to <span class="text-primary" id="finishNo_advances">10</span> of <span id="tot_records_advances"></span> Records.</p>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate_advances">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_advances">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_advances">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_advances">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_advances">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_advances">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container advances hide" id="define_advance_window">
                            <div class="pr_back"><p class="link" id="back_to_advance_list"><i class="fas fa-arrow-left"></i> Back to list</p></div>
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-plus"></i></span>
                                <div><b>Define New Advance</b><small>The advance is recovered from the salary in equal monthly installments.</small></div>
                            </div>
                            <div class="pr_card">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="employees_id_advances" class="form-control-label">Employee's Name <img class="hide" src="images/ajax_clock_small.gif" id="employees_data_loaders"></label>
                                        <p id="employees_data"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_amount" class="form-control-label">Advance Amount</label>
                                        <div class="pr_money">
                                            <span>Kes</span>
                                            <input type="number" class="form-control" id="advance_amount" placeholder="Advance Amounts">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="month_effects" class="form-control-label">Effect Month</label>
                                        <input min="<?php echo date("Y-m");?>" class="w-100 form-control" type="month" name="month_effects" id="month_effects" placeholder="Effect Month">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_installments" class="form-control-label">Advance Installments</label>
                                        <input type="number" class="w-100 form-control" id="advance_installments" value="1" min="1" placeholder="Pay in how many months eg(2 months)">
                                        <p id="advance_installments_price"></p>
                                    </div>
                                </div>
                                <div class="pr_actions">
                                    <p id="add_leave_error_handler"></p>
                                    <div class="pr_btn_tile" id="define_advances" role="button" tabindex="0"><i class="fas fa-plus"></i> Add Advance <img class="hide" src="images/ajax_clock_small.gif" id="add_advance_loadings"></div>
                                </div>
                            </div>
                        </div>
                        <div class="container advances hide" id="view_advance_window">
                            <input type="hidden" value="0" id="hold_advance_payment_id">
                            <div class="pr_back"><p class="link" id="back_to_view_advance_list"><i class="fas fa-arrow-left"></i> Back to list</p></div>
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-eye"></i></span>
                                <div><b>Advance Details</b><small>Read only. Delete the advance to change it.</small></div>
                                <span class="pr_btn_delete" id="delete_advance" role="button" tabindex="0" title="Delete this advance"><i class="fa fa-trash"></i> Delete</span>
                            </div>
                            <div id="error_payroll_holder"></div>
                            <div class="pr_card">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="employees_name_view" class="form-control-label">Employee's Name</label>
                                        <input type="text" readonly class="form-control w-100" id="employees_name_view" placeholder="Advance Amounts">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_amount_view" class="form-control-label">Advance Amount</label>
                                        <input type="text" readonly class="form-control w-100" id="advance_amount_view" placeholder="Advance Amounts">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="month_effects_view" class="form-control-label">Effect Month</label>
                                        <input readonly class="form-control w-100" type="month" name="month_effects_view" id="month_effects_view" placeholder="Effect Month">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_installments_view" class="form-control-label">Advance Installments</label>
                                        <input type="text" readonly class="form-control w-100" id="advance_installments_view" value="1" min="1" placeholder="Advance Installments">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_date_taken" class="form-control-label">Date Taken</label>
                                        <input type="text" readonly class="form-control w-100" id="advance_date_taken" placeholder="Advance Amounts">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="advance_balance" class="form-control-label">Advance Balance</label>
                                        <input type="text" readonly class="form-control w-100" id="advance_balance" placeholder="Advance Amounts">
                                    </div>
                                </div>
                            </div>
                            <div class="pr_card">
                                <div class="pr_card_title"><i class="fas fa-list-ol"></i> Payment Installments</div>
                                <p id="payment_installments_advanced"></p>
                            </div>
                        </div>
                    </div>
                    <div class="section_card hide" id="payroll_reports_window">
                        <div class="my_reports hide" id="nssf_reports_window">
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-shield-alt"></i></span>
                                <div><b>NSSF Reports</b><small>Employer and employee NSSF contributions for the month.</small></div>
                                <div class="pr_report_pick">
                                    <label for="select_nssf_months">Month</label>
                                    <input type="month" name="select_nssf_months" id="select_nssf_months" class="form-control" value="<?php echo date('Y-m');?>">
                                    <div class="pr_btn_tile" id="display_nssf_reports" role="button" tabindex="0"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_nssf_reports_loader"></div>
                                </div>
                            </div>
                            <div class="table_holders" id="display_nssf_reports_windows">
                                <p class="pr_empty">Loading the report...</p>
                            </div>
                        </div>
                        <div class="my_reports hide" id="nhif_reports_window">
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-heartbeat"></i></span>
                                <div><b>SHIF Reports</b><small>Employee health insurance contributions for the month.</small></div>
                                <div class="pr_report_pick">
                                    <label for="select_nhif_months">Month</label>
                                    <input type="month" name="select_nhif_months" id="select_nhif_months" class="form-control" value="<?php echo date('Y-m');?>">
                                    <div class="pr_btn_tile" id="display_nhif_reports" role="button" tabindex="0"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_nhif_reports_loader"></div>
                                </div>
                            </div>
                            <div class="table_holders" id="display_nhif_reports_windows">
                                <p class="pr_empty">Loading the report...</p>
                            </div>
                        </div>
                        <div class="my_reports hide" id="nita_reports_window">
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-tools"></i></span>
                                <div><b>NITA Reports</b><small>Industrial Training Levy the employer pays for each employee for the month.</small></div>
                                <div class="pr_report_pick">
                                    <label for="select_nita_months">Month</label>
                                    <input type="month" name="select_nita_months" id="select_nita_months" class="form-control" value="<?php echo date('Y-m');?>">
                                    <div class="pr_btn_tile" id="display_nita_reports" role="button" tabindex="0"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_nita_reports_loader"></div>
                                </div>
                            </div>
                            <div class="table_holders" id="display_nita_reports_windows">
                                <p class="pr_empty">Loading the report...</p>
                            </div>
                        </div>
                        <div class="my_reports hide" id="kra_reports_window">
                            <div class="pr_view_head">
                                <span class="pr_view_icon"><i class="fas fa-landmark"></i></span>
                                <div><b>KRA Reports</b><small>P.A.Y.E deducted from each staff member for the month.</small></div>
                                <div class="pr_report_pick">
                                    <label for="select_kra_months">Month</label>
                                    <input type="month" name="select_kra_months" id="select_kra_months" class="form-control" value="<?php echo date('Y-m');?>">
                                    <div class="pr_btn_tile" id="display_kra_reports" role="button" tabindex="0"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_kra_reports_loader"></div>
                                </div>
                            </div>
                            <div class="table_holders" style="font-size: 12px;" id="display_kra_reports_windows">
                                <p class="pr_empty">Loading the report...</p>
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
