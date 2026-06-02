<div class="contents animate" id="ctdash">

    <!-- Welcome banner -->
    <div class="welcome">
        <div class="name_n_icons">
            <h2>Welcome back <?php
                if (isset($_SESSION['fullnames'])) {
                    $salute = '';
                    if ($_SESSION['gen'] == 'M') { $salute = 'Mr. '; }
                    elseif ($_SESSION['gen'] == 'F') { $salute = 'Mrs. '; }
                    $named = explode(" ", $_SESSION['fullnames']);
                    echo $salute . $named[0];
                } else { echo "there"; }
            ?></h2>
            <span><img class="images" src="images/dp.png" id="ct_admin_dp" alt="userimg"></span>
        </div>
        <div class="contedd">
            <p>Logged in as <b><?php
                if (isset($_SESSION['auth'])) {
                    $auth = $_SESSION['auth'];
                    $roles = [
                        0 => 'System Administrator', 1 => 'Principal',
                        2 => 'Deputy Principal Academics', 3 => 'Deputy Principal Administration',
                        4 => 'Dean of Students', 5 => 'Finance Office',
                        6 => 'Human Resource Officer', 7 => 'Head of Department',
                        8 => 'Trainer/Lecturer', 9 => 'Admissions',
                    ];
                    echo isset($roles[$auth]) ? $roles[$auth] : ucwords(strtolower($auth));
                } else { echo 'User'; }
            ?></b> &mdash; <?php echo date("l, d M Y"); ?></p>
            <p>Here's your class at a glance. Use the navigation on the left to get started.</p>
        </div>
    </div>

    <!-- Stats grid -->
    <div class="row px-2 mt-3">

        <!-- Students in My Class -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#1565c0;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#e3f0fb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-users" style="color:#1565c0;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#1a1a2e;line-height:1;" id="studclass">0</div>
                        <div style="font-size:11px;color:#666;margin-top:2px;text-transform:uppercase;letter-spacing:.5px;">Students in My Class</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <a href="#" id="my_students_populate" style="font-size:12px;color:#1565c0;text-decoration:none;"><i class="fas fa-arrow-right" style="font-size:10px;"></i> View class list</a>
                </div>
            </div>
        </div>

        <!-- Registered Today -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#2e7d32;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#e8f5e9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-user-plus" style="color:#2e7d32;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#1a1a2e;line-height:1;" id="reg_tod_mine">0</div>
                        <div style="font-size:11px;color:#666;margin-top:2px;text-transform:uppercase;letter-spacing:.5px;">Registered Today</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <span style="font-size:12px;color:#aaa;">New admissions today</span>
                </div>
            </div>
        </div>

        <!-- Present Today -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#00695c;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#e0f2f1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-clipboard-check" style="color:#00695c;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#1a1a2e;line-height:1;" id="my_att_clas">0</div>
                        <div style="font-size:11px;color:#666;margin-top:2px;text-transform:uppercase;letter-spacing:.5px;">Present Today</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <span style="font-size:12px;color:#aaa;">Roll call attendance</span>
                </div>
            </div>
        </div>

        <!-- Students Absent -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#c62828;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#ffebee;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-user-times" style="color:#c62828;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#1a1a2e;line-height:1;" id="my_absent_list">0</div>
                        <div style="font-size:11px;color:#666;margin-top:2px;text-transform:uppercase;letter-spacing:.5px;">Absent Today</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <span style="font-size:12px;color:#aaa;">Absent students</span>
                </div>
            </div>
        </div>

        <!-- Register Roll Call -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#e65100;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#fff3e0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-tasks" style="color:#e65100;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:#1a1a2e;line-height:1.2;">Register Roll Call</div>
                        <div style="font-size:11px;color:#666;margin-top:4px;">Mark today's attendance</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <button type="button" id="callrollcall" style="background:none;border:none;padding:0;font-size:12px;color:#e65100;cursor:pointer;"><i class="fas fa-arrow-right" style="font-size:10px;"></i> Register now</button>
                </div>
            </div>
        </div>

        <!-- My Timetable -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#5e35b1;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#ede7f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-calendar-week" style="color:#5e35b1;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:#1a1a2e;line-height:1.2;">My Timetable</div>
                        <div style="font-size:11px;color:#666;margin-top:4px;">View your schedule</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <button type="button" id="view_my_tt" style="background:none;border:none;padding:0;font-size:12px;color:#5e35b1;cursor:pointer;"><i class="fas fa-arrow-right" style="font-size:10px;"></i> View timetable</button>
                </div>
            </div>
        </div>

        <!-- Subjects I Teach -->
        <div class="col-6 col-md-4 mb-3">
            <div style="background:#fff;border-radius:12px;padding:18px 16px 14px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:#283593;border-radius:12px 12px 0 0;"></div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                    <div style="width:46px;height:46px;border-radius:10px;background:#e8eaf6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-book" style="color:#283593;font-size:20px;"></i>
                    </div>
                    <div>
                        <div style="font-size:28px;font-weight:700;color:#1a1a2e;line-height:1;" id="my_subjects">0</div>
                        <div style="font-size:11px;color:#666;margin-top:2px;text-transform:uppercase;letter-spacing:.5px;">Subjects I Teach</div>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:8px;border-top:1px solid #f0f0f0;">
                    <p id="view_my_subs" style="margin:0;"><a href="#" style="font-size:12px;color:#283593;text-decoration:none;"><i class="fas fa-arrow-right" style="font-size:10px;"></i> View subjects</a></p>
                </div>
            </div>
        </div>

    </div><!-- end stats grid -->

    <hr class="w-75 mx-auto">

    <!-- Charts -->
    <div class="row px-2">
        <div class="col-md-6 mb-3" style="max-height:45vh;">
            <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;">
                <p class="d-none" id="student_population_data"></p>
                <div class="text-center"><span class="hide" id="student_population_loader"><img src="images/ajax_clock_small.gif"> Loading...</span></div>
                <canvas id="studentPopulationChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-3" style="max-height:45vh;">
            <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;">
                <p class="d-none" id="student_attendance_data_stats"></p>
                <div class="text-center"><span class="hide" id="student_attendance_data_loader"><img src="images/ajax_clock_small.gif"> Loading...</span></div>
                <canvas id="student_attendance_data_chart"></canvas>
            </div>
        </div>
        <div class="col-md-4 mb-3" style="max-height:45vh;">
            <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,0.07);height:100%;">
                <p class="d-none" id="gender_population_data_holder"></p>
                <div class="text-center"><span class="hide" id="gender_population_pie_loader"><img src="images/ajax_clock_small.gif"></span></div>
                <canvas id="gender_population_pie"></canvas>
            </div>
        </div>
    </div>

</div>
