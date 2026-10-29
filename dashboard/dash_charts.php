<?php
// Shared charts for every dashboard. dashboardajax.js (load_dash_graphs) fills them by id, and
// admissions.js hides the .finance_graphs cards for users without finance access.
?>
    <div class="dash_section_head">
        <b>Insights</b>
        <small>Live figures from today's records</small>
    </div>

    <div class="dash_charts">
        <div class="dash_chart dash_span_6">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-users"></i></span>
                <div><b>Student Population</b><small>By course level, active and inactive</small></div>
            </div>
            <p class="d-none" id="student_population_data"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="student_population_loader"><img src="images/ajax_clock_small.gif"> Loading...</span>
                <canvas id="studentPopulationChart"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_6">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-clipboard-check"></i></span>
                <div><b>Attendance Today</b><small>Present and absent by course level</small></div>
            </div>
            <p class="d-none" id="student_attendance_data_stats"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="student_attendance_data_loader"><img src="images/ajax_clock_small.gif"> Loading...</span>
                <canvas id="student_attendance_data_chart"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_6 finance_graphs">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-wallet"></i></span>
                <div><b>Fees Collection</b><small>By mode of payment, this month</small></div>
            </div>
            <p class="d-none" id="fees_collection_modeofpay"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="fees_collection_modeofpay_loader"><img src="images/ajax_clock_small.gif"> Loading...</span>
                <canvas id="fees_collection_modeofpay_chart"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_6 finance_graphs">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-balance-scale"></i></span>
                <div><b>Fees Balances</b><small>Paid against balance by course level, today</small></div>
            </div>
            <p class="d-none" id="student_fees_balance_data"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="fees_balance_data_loader"><img src="images/ajax_clock_small.gif"> Loading...</span>
                <canvas id="studentFeesBalanceData"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_4 finance_graphs">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-chart-pie"></i></span>
                <div><b>Income and Expenses</b><small>Fees and revenue against expenses, today</small></div>
            </div>
            <p class="d-none" id="income_and_expense_pie"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="income_and_expense_pie_loader"><img src="images/ajax_clock_small.gif"></span>
                <canvas id="studentIncomeDataPie"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_4 finance_graphs">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-percentage"></i></span>
                <div><b>Fees Paid and Balances</b><small>School-wide totals, today</small></div>
            </div>
            <p class="d-none" id="student_fees_balance_pie"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="fees_balance_data_loader_pie"><img src="images/ajax_clock_small.gif"></span>
                <canvas id="studentFeesBalanceDataPie"></canvas>
            </div>
        </div>
        <div class="dash_chart dash_span_4 dash_gender">
            <div class="dash_chart_head">
                <span class="dash_chart_icon"><i class="fas fa-venus-mars"></i></span>
                <div><b>Gender Population</b><small>Male and female students</small></div>
            </div>
            <p class="d-none" id="gender_population_data_holder"></p>
            <div class="dash_chart_body">
                <span class="hide dash_chart_loader" id="gender_population_pie_loader"><img src="images/ajax_clock_small.gif"></span>
                <canvas id="gender_population_pie"></canvas>
            </div>
        </div>
    </div>
