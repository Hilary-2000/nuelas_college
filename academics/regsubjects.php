<div class="contents animate hide" id="regsubjects">
    <div class="titled">
        <h2>Register Course Units</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Register a Course Units</p>
        </div>
        <div class="middle1">
            <div class="tops" style='padding: 10px 0;'>
                <div class="conts">
                    <p><strong>Note:</strong></p>
                    <p>- At this window you will be able to register a Course Units.</p>
                    <p>- Fill all the fields required correctly.</p>
                    <p>- A Course Units name can be used twice but the Course Unit id cant be used twice.</p>
                    <p>- When registering a Course Units its recomended that the Courses that share the same course units be checked instead of adding the same unit over & over for different courses.</p>
                </div>
            </div>
            <div class="body1">
                <div class="conts" style='padding:10px 0;'>
                    <h5 style="text-align:center;">Register Course Unit</h5>
                </div>
                <div class="body3">
                    <form class="left" id="formpay">
                        <div class="conts my-3">
                            <label class="form-control-label" for="subname"><b>Enter Unit Name: </b><small>Eg. 'Communication Skills Diploma'</small> <span class="text-danger">(Unique only)</span> <br></label>
                            <p id='subnameerr'></p>
                            <input class="form-control w-100" style="margin-left: 0px !important;" type="text" name="subname" id="subname" placeholder = 'eg. Communication Skills Diploma'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="subject_display_name"><b>Enter Unit Display Name: </b> eg 'Communication Skills'<br></label>
                            <input  class="form-control w-100" style="margin-left: 0px !important;" type="text" name="Course Units display name" id="subject_display_name" placeholder = 'eg. Communication Skills'>
                        </div>
                        <div class="conts my-3">
                            <label  class="form-control-label" for="sundids"><b>Enter Unit Timetable Name: </b><small>Eg. 'COM001' for 'Communication Skills'</small> <br></label>
                            <input class="form-control w-100" style="margin-left: 0px !important;"  type="text" name="sundids" id="sundids" placeholder = 'e.g, COM001'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="submarks"><b>Enter Unit Maximum Marks: </b><br></label>
                            <input  class="form-control w-100" style="margin-left: 0px !important;" type="number" max=100 min=0 name="submarks" id="submarks" placeholder = 'Unit Marks'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="select_course_level"><b>Select Course Level: </b><br></label>
                            <select name="select_course_level w-100" style="margin-left: 0px !important;" id="select_course_level" class="form-control">
                                <option value="">Select Course Level</option>
                            </select>
                        </div>
                        <div class="conts my-3" style='margin:10px 0 0 0'>
                            <label class="form-control-label" for="selectsubs"><b>Select Course: </b><br></label>
                            <p id='subjectlist'><img src="images/load2.gif" alt="loading"></p>                            
                        </div>
                        <div class="cont my-3">
                            <label for="set_grades" class="form-control-label"><b>Set Grades</b></label><br>
                            <p class="block_btn" id="set_grades_display_btn">Set Grades</p>
                            <p class="my-2 hide" id="set_my_grades_list"></p>
                            <p class="my-2" id="display_tables_list"></p>
                        </div>
                        <div class="conts my-3" style='margin:20px 0 0 0;display:flex;flex-direction:row-reverse;'>
                            <button type='button' id='registersub'>Register</button>
                        </div>
                        <div class="conts my-3">
                            <p id='errregsub'></p>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>