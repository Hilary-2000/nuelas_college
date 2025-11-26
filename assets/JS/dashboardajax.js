/*******start of dashboard ajax******* */
var auth = cObj("authoriti").value;
if (auth == '1') {
    cObj("sch_logos").onclick = function () {
        cObj("update_school_profile").click();
    }
    //get number of students
    var datapass = "?getStudentCount=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
        }
    }, 900000);

    //get number of students registerd today
    var datapass = "?studentscounttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?studentscounttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
        }
    }, 900000);

    //get number of students present in school today
    var datapass = "?studentspresenttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));

    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
        }
    }, 900000);

    //get number off students absent

    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var total = cObj("studentscount").innerText.split(" ");
            var present = cObj("studpresenttoday").innerText.split(" ");
            var total1 = total[0];
            var present1 = present[0];
            if (present1!=0) {
                cObj("absentstuds").innerText = (total1-present1)+" Student(s)";
            }else{
                cObj("absentstuds").innerText = "Roll call not taken.";
            }
        }
    }, 900000);

    //number of active users
    var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
    sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 300000);

    //number of school fees recieved
    var datapass = "?schoolfeesrecieved=true";
    sendData("GET","administration/admissions.php",datapass,cObj("schoolfeesrecieved"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?schoolfeesrecieved=true";
            sendData("GET","administration/admissions.php",datapass,cObj("schoolfeesrecieved"));
        }
    }, 300000);

    //number of transfered students
    var datapass = "?transfered_students=true";
    sendData("GET","administration/admissions.php",datapass,cObj("transfered_studs"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?transfered_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("transfered_studs"));
        }
    }, 900000);
    //number of alumnis students
    var datapass = "?alumnis_number=true";
    sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?alumnis_number=true";
            sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number"));
        }
    }, 900000);


        
    //head teacher dashboard
    cObj("totalstuds").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("regtoday").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("regtodays").selected = true;
        cObj("findingstudents").click();
    }

    cObj("prestoday").onclick = function () {
        cObj("callregister").click();
        cObj("view_atts").selected = true;
        cObj("optd").click();
        cObj("display_attendance_class").click();
    }
    cObj("studentabs").onclick = function () {
        cObj("callregister").click();
        cObj("prestoday").click();
    }
    cObj("schoolfee").onclick = function () {
        cObj("findtrans").click();
        cObj("todayfees").selected = true;
        cObj("allstudents").selected = true;
        cObj("searchtransaction").click();
    }

    //get the logs
    var datapass = "?get_loggers=true";
    sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //get the active exams
    var datapass = "?active_exams_lts=true";
    sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 60000);

    //view active exams
    cObj("view_active_exams").onclick = function () {
        cObj("viewexams").click();
        cObj("all_active").selected = true;
        cObj("examanagement").click();
        cObj("displaysubjects").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }
    
cObj("showfees").onclick = function () {
    cObj("hidefees").classList.toggle("hide");

    if(!cObj("se_e").classList.contains("hide")){

        cObj("hidefees").classList.add("hide");
        cObj("se_e").classList.add("hide");
        cObj("unse_e").classList.remove("hide");

    }else if (!cObj("unse_e").classList.contains("hide")) {

        cObj("hidefees").classList.remove("hide");
        cObj("se_e").classList.remove("hide");
        cObj("unse_e").classList.add("hide");

    }
}

//head teacher dashboard end
}
//deputy prncipal
if (auth == 3) {
    cObj("sch_logos").onclick = function () {
        cObj("update_school_profile").click();
    }
    //get number of students
    var datapass = "?getStudentCount=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
        }
    }, 900000);

    //get number of students registerd today
    var datapass = "?studentscounttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?studentscounttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
        }
    }, 900000);

    //get number of students present in school today
    var datapass = "?studentspresenttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
        }
    }, 900000);

    //get number off students absent

    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var total = cObj("studentscount").innerText.split(" ");
            var present = cObj("studpresenttoday").innerText.split(" ");
            var total1 = total[0];
            var present1 = present[0];
            if (present1!=0) {
                cObj("absentstuds").innerText = (total1-present1)+" Student(s)";
            }else{
                cObj("absentstuds").innerText = "Roll call not taken.";
            }
        }
    }, 900000);

    //number of active users
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 900000);
        
    //deputy head teacher dashboard
    cObj("totalstuds").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("regtoday").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("regtodays").selected = true;
        cObj("findingstudents").click();
    }

    cObj("prestoday").onclick = function () {
        cObj("callregister").click();
        cObj("view_atts").selected = true;
        cObj("optd").click();
        cObj("display_attendance_class").click();
    }
    cObj("studentabs").onclick = function () {
        cObj("callregister").click();
        cObj("prestoday").click();
    }

    //get the logs
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //get the active exams
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 900000);

    //view active exams
    cObj("view_active_exams").onclick = function () {
        cObj("viewexams").click();
        cObj("all_active").selected = true;
        cObj("examanagement").click();
        cObj("displaysubjects").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }
    //end of the deputy principal
}

//administrator dashboard = 0
if (auth == 0) {
    //get number of students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("students"));
        }
    }, 900000);

    //get number of users present in school
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?totaluserspresent=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"))
        }
    }, 900000);
    
    //number of active users
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 900000);
    
    //get number of students present in school today
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("rollcalnumber"))
        }
    }, 900000);

    cObj("admin_students").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("my_employees").onclick = function () {
        cObj("managestaf").click();
        cObj("view_my_stf").selected = true;
        viewstaffavailablebtn();
    }
    cObj("view_logs").onclick = function () {
        hideWindow();
        cObj("loggers_page").classList.remove("hide");
    }
    //get the logs
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //number of transfered students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?transfered_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("transfered_stud2"));
        }
    }, 900000);
    //number of alumnis students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            // console.log("WE ARE HERE");
            var datapass = "?alumnis_number=true";
            sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number2"));
        }
    }, 900000);
}
//classteacher dashboard = 5
if (auth == 5) {
    //get total number of students in my class
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?number_of_me_studnets=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studclass"));
        }
    }, 900000);
    //get total number of students regestered today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?reg_today_my_class=true";
            sendData("GET","administration/admissions.php",datapass,cObj("reg_tod_mine"));
        }
    }, 900000);
    //get total number of students present in school today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?today_attendance=true";
            sendData("GET","administration/admissions.php",datapass,cObj("my_att_clas"));
        }
    }, 900000);
    //get total number of students present in school today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?absent_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("my_absent_list"));
        }
    }, 900000);
    cObj("view_my_tt").onclick = function () {
        cObj("generate_tt_btn").click();
    }
    cObj("my_students_populate").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("display_my_students").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }

}
//the teachers` dashboard
if (auth == 2) {
    //get the active exams
    setInterval(() => {
        if (!cObj("tr_dash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 900000);
    //my subjects
    setInterval(() => {
        if (!cObj("tr_dash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }

}

//tracks a user if they are active
setInterval(() => {
    var datapass = "?activeuser=true&userid="+cObj("useriddds").value;
    sendData("GET","administration/admissions.php",datapass,cObj("nulled"));
}, 60000);

//check for notifications
setInterval(() => {
    var datapass = "?notices=true";
    sendData("GET","notices/notices.php",datapass,cObj("note_2"));
}, 60000);

let studentPopulationChartInstance = null;
let attendanceChartInstance = null;
let modeOfPayChartInstance = null;
let feesBalanceChartInstance = null;
let incomePieInstance = null;
let expensePieInstance = null;
let genderPopulationPieInstance = null;
function load_dash_graphs() {

    if (studentPopulationChartInstance !== null) {
        studentPopulationChartInstance.destroy();
    }
    if (attendanceChartInstance !== null) {
        attendanceChartInstance.destroy();
    }
    if (modeOfPayChartInstance !== null) {
        modeOfPayChartInstance.destroy();
    }
    if (feesBalanceChartInstance !== null) {
        feesBalanceChartInstance.destroy();
    }
    if (incomePieInstance !== null) {
        incomePieInstance.destroy();
    }
    if (expensePieInstance !== null) {
        expensePieInstance.destroy();
    }
    if (genderPopulationPieInstance !== null) {
        genderPopulationPieInstance.destroy();
    }

    if(!cObj("student_population_loader").classList.contains("hide") || !cObj("fees_balance_data_loader").classList.contains("hide") || !cObj("income_and_expense_pie_loader").classList.contains("hide") || !cObj("student_attendance_data_loader").classList.contains("hide") || !cObj("fees_collection_modeofpay_loader").classList.contains("hide")){
        return;
    }

    // student population chart
    if (cObj("studentPopulationChart") != undefined) {
        // get the student population
        var datapass = "?get_student_population=true&by_gender=true&by_class=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("student_population_data"), cObj("student_population_loader"), function () {
            // create the chart
            var student_population = cObj("student_population_data").innerText;
            if (hasJsonStructure(student_population)) {
                var student_pop = JSON.parse(student_population);
                const labels = student_pop.map(item => item.class);

                const maleActive = student_pop.map(item => item.male_active);
                const maleInactive = student_pop.map(item => item.male_inactive);

                const femaleActive = student_pop.map(item => item.female_active);
                const femaleInactive = student_pop.map(item => item.female_inactive);


                const ctx = document.getElementById('studentPopulationChart').getContext('2d');
                studentPopulationChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            // MALE STACK
                            {
                                label: 'Male Active',
                                data: maleActive,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                stack: 'male'
                            },
                            {
                                label: 'Male Inactive',
                                data: maleInactive,
                                backgroundColor: 'rgba(54, 162, 235, 0.3)',
                                stack: 'male'
                            },

                            // FEMALE STACK
                            {
                                label: 'Female Active',
                                data: femaleActive,
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                stack: 'female'
                            },
                            {
                                label: 'Female Inactive',
                                data: femaleInactive,
                                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                                stack: 'female'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Student Population by Course Level',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });


                // get male and female population
                const male = student_pop.map(item => item.male);
                const female = student_pop.map(item => item.female);
                var male_pop = 0;
                var female_pop = 0;
                for (let index = 0; index < male.length; index++) {
                    const element = male[index];
                    male_pop+=element;
                }
                for (let index = 0; index < female.length; index++) {
                    const element = female[index];
                    female_pop+=element;
                }
                var pieLabels = ["Male Students", "Female Students"];
                const ctx2 = document.getElementById('gender_population_pie').getContext('2d');

                genderPopulationPieInstance = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            label:"Gender Population",
                            data: [male_pop, female_pop],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Gender Population',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    // get the student fees by class

    if(cObj("studentFeesBalanceData") != undefined){
        // get the student fees balance
        var datapass = "?get_student_fees=true&by_gender=true&by_class=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("student_fees_balance_data"), cObj("fees_balance_data_loader"), function () {
            // create the chart
            var student_population = cObj("student_fees_balance_data").innerText;
            if (hasJsonStructure(student_population)) {
                var student_pop = JSON.parse(student_population);
                const labels = student_pop.map(item => item.class);
                const amount_paid = student_pop.map(item => item.amount_paid);
                const balance = student_pop.map(item => item.balance);
                const ctx = document.getElementById('studentFeesBalanceData').getContext('2d');

                feesBalanceChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Fees Paid',
                                data: amount_paid,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            },
                            {
                                label: 'Balance',
                                data: balance,
                                backgroundColor: 'rgba(255, 99, 132, 0.7)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Student Fees Balances by Course Level',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });

                // pie chart
                var fullBalance = 0;
                var fullAmountPaid = 0;
                for (let index = 0; index < balance.length; index++) {
                    const element = balance[index];
                    fullBalance+=(element*1);
                }
                for (let index = 0; index < amount_paid.length; index++) {
                    const element = amount_paid[index];
                    fullAmountPaid+=(element*1);
                }

                var pieLabels = ["Fees Paid", "Balance"];
                const ctx2 = document.getElementById('studentFeesBalanceDataPie').getContext('2d');

                incomePieInstance = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            label:"Payment Details",
                            data: [fullAmountPaid, fullBalance],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Fees Paid against Fees Balances',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    if(cObj("studentIncomeDataPie") != undefined){
        // get the term expense and income comparison in a pie chart
        var datapass = "?get_expense_income_pie=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("income_and_expense_pie"), cObj("income_and_expense_pie_loader"), function () {
            var income_and_expense_pie = cObj("income_and_expense_pie").innerText;
            if (hasJsonStructure(income_and_expense_pie)){
                var income_expense = JSON.parse(income_and_expense_pie);
                const labels = ["Fees & Revenue", "Expenses"];
                const income = income_expense.income;
                const expense = income_expense.expense;
                const ctx = document.getElementById('studentIncomeDataPie').getContext('2d');
                expensePieInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label:"Income & Expense",
                            data: [income, expense],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Income against Expenses',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    if(cObj("student_attendance_data_chart") != undefined){
        // get the student fees balance
        var datapass = "?student_attendance_statistics=true&by_gender=true&by_class=true";
            sendData2("GET", "administration/admissions.php", datapass, cObj("student_attendance_data_stats"), cObj("student_attendance_data_loader"), function () {
                // create the chart
                var student_population = cObj("student_attendance_data_stats").innerText;
                if (hasJsonStructure(student_population)) {
                    var student_pop = JSON.parse(student_population);
                    const labels = student_pop.map(item => item.class);
                    const present = student_pop.map(item => item.present);
                    const absent = student_pop.map(item => item.absent);
                    const ctx = document.getElementById('student_attendance_data_chart').getContext('2d');

                    attendanceChartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Present',
                                    data: present,
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                                },
                                {
                                    label: 'Absent',
                                    data: absent,
                                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Student attendance by Course Level (Today)',
                                    font: {
                                        family: 'Nunito',
                                        size: 15,
                                        weight: '700'
                                    },
                                    padding: { top: 10, bottom: 20 }
                                },
                                legend: {
                                    labels: {
                                        font: {
                                            family: 'Nunito'
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        font: {
                                            family: 'Nunito'
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            family: 'Nunito'
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
    }

    if(cObj("fees_collection_modeofpay_chart") != undefined){
        // get the student fees balance
        var datapass = "?get_income_per_mode_of_pay=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("fees_collection_modeofpay"), cObj("fees_collection_modeofpay_loader"), function () {
            // create the chart
            var mode_of_pay_data = cObj("fees_collection_modeofpay").innerText;
            if (hasJsonStructure(mode_of_pay_data)) {
                var mode_of_pay = JSON.parse(mode_of_pay_data);
                const labels = mode_of_pay.map(item => item.mode_of_pay);
                const amount = mode_of_pay.map(item => item.amount);
                const ctx = document.getElementById('fees_collection_modeofpay_chart').getContext('2d');

                modeOfPayChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Fees Paid',
                                data: amount,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Fees Collection by Mode of Pay',
                                font: {
                                    family: 'Nunito',
                                    size: 15,
                                    weight: '700'
                                },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        family: 'Nunito'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }
}