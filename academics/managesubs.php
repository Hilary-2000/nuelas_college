<div class="contents animate hide" id="managesubjects">
    <div class="titled">
        <h2>Manage Course Units</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Manage Course Units</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p><strong>Information</strong></p>
                <p>- Update and delete Course Units information at this window</p>
                <p>- Changes done at the Course Units will cause major effects to how the system works including teachers access, exams and system timetable</p>
                <p>- When changes are done a new timetable needs to be generated!</p>
            </div>
            <div class="conts hide">
                <p id='subinform'></p>
            </div>
            <div class="body4">
                <label class="form-control-label d-none" for="subjects_option">Start by either <br></label>
                <select class="form-control d-none" name="subjects_option" id="subjects_option">
                    <option value="" hidden>Select an option</option>
                    <option value="search_subjects">Searching the Units</option>
                    <option value="display_subjects">Display all Units</option>
                </select>
                <div class="boddy form-group d-none">
                    <div class="conts hide" id='seachsub'>
                        <div class="conts">
                            <label  class="form-control-label"  for="serchby">Search by: <br></label>
                            <select  class="form-control" name="serchby" id="serchby">
                                <option value="" hidden>Select..</option>
                                <option value="byname">By name:</option>
                                <option value="byclass">By class taught:</option>
                            </select>
                        </div>
                        <div class="conts hide" id="byname">
                            <label  class="form-control-label" for="subnamed">Enter Unit name: <br></label>
                            <input  class="form-control" type="text" name="subnamed" id="subnamed" placeholder="Enter Unit name">
                        </div>
                        <div class="conts hide" id="classtaught">
                            <label class="form-control-label" for="classtaughts">Select Course Level:<br></label>
                            <p id="subjClass"><img src="images/load2.gif" alt="loading"></p>
                        </div>
                        <div class="btns">
                            <button type='button' id='finder' >Find</button>
                        </div>
                        <div class="conts" id="seachsubd">
                            <p id='errorhand'></p>
                        </div>
                    </div>
                </div>
                <div class="container border border-secondary my-2 row p-1 rounded">
                    <div class="col-md-4">
                        <label for="course_level_unit_filter" class="form-control-label"><b>Course Level</b></label>
                        <div id="course_level_unit_filter_holder"><span class="p-1 border border-success rounded my-1 text-success">Course Level List will appear here</span></div>
                    </div>
                    <div class="col-md-4">
                        <label for="course_list_unit_filter" class="form-control-label"><b>Course List</b></label>
                        <div id="course_list_unit_filter_holder"><span class="p-1 border border-success rounded my-1 text-success">Course List will appear here</span></div>
                    </div>
                    <div class="col-md-4">
                        <button id="search_unit_btn"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                <div class="boddy1">
                    <p id="resulthold"></p>
                    <form class="boddy3 hide" id ='subjectdets'>
                        <div class="conts">
                            <h3 style='text-align:center;' >Unit Details</h3>
                        </div>
                        <div class="delete-sub">
                            <p  class="funga" id="delete-subject" ><i class="fa fa-trash-alt"></i></p>
                        </div>
                        <div class="conts d-none">
                            <label for=""><b>Unit id</b>: <span id='unit_id_edit'></span> <br></label>
                        </div>
                        <div class="conts my-3">
                            <label  class="form-control-label"  for="subject_name_edit"><b>Unit name: Eg. 'Communication Skills Diploma'</b><br></label>
                            <input class="form-control w-100"  type="text" name="subject_name_edit" id="subject_name_edit" placeholder = 'Unit name'>
                        </div>
                        <div class="conts my-3">
                            <label  class="form-control-label"  for="sub_display_name_edit"><b>Unit Display Name: eg 'Communication Skills' </b><br></label>
                            <input class="form-control w-100"  type="text" name="sub_display_name_edit" id="sub_display_name_edit" placeholder = 'Unit Display Name'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="unit_code_edit"><b>Enter Unit Code: </b><small>Eg. 'COM001' for 'Communication Skills'</small> <br></label>
                            <input  class="form-control w-100" type="text" name="unit_code_edit" id="unit_code_edit" placeholder = 'Unit IDs'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="subject_max_marks_edit"><b>Unit Maximum marks: </b><br></label>
                            <input class="form-control w-100" type="number" name="subject_max_marks_edit" id="subject_max_marks_edit" placeholder = 'Unit maximum marks'>
                        </div>
                        <div class="conts my-3">
                            <label class="form-control-label" for="unit_year_of_study_edit"><b>Year Of Study: </b><br></label>
                            <select name="unit_year_of_study_edit" id="unit_year_of_study_edit" class="form-control">
                                <option value="" hidden>Select Year of Study</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                                <option value="5">Year 5</option>
                                <option value="6">Year 6</option>
                                <option value="7">Year 7</option>
                                <option value="8">Year 8</option>
                            </select>
                        </div>
                        <div class="conts my-3">
                            <input type="hidden" id="hold_course_selected_edit" value="[]">
                            <label class="form-control-label" for="select_course_level_unit_edit"><b>Select Course Level: </b><br></label>
                            <div id="select_course_level_list_holder_edit"></div>
                        </div>
                        <div class="conts my-3" style='margin:10px 0 0 0'>
                            <label class="form-control-label" for="selectsubs_edit"><b>Select Course: <small id="selected_course_counter">Selected Course : 1</small> <span class="hide" id="course_list_loader_edit"><img src="images/load2.gif" alt="loading"></span></b><br></label>
                            <p id='course_list_unit_holder_edit'><span class="border border-success my-2 rounded text-success p-1">Course lists will appear here!</span></p>                            
                        </div>
                        <hr>
                        <div class="conts my-3">
                            <p class="hide" id="subjects_grades_hidden"></p>
                            <label for="grading_lists" class="form-control-label"><b>Grading Lists</b><span id="edit_grading_subject" class="block_btn mx-2" style="padding:2px;border-radius: 3px;"><small>Edit Grades</small></span></label>
                            <p id="my_grade_lists_subject"></p>
                        </div>
                        <div class="btns">
                            <button type='button' id='updatesubs'>Update</button>
                            <button type='button' id='cancelsubs'><i class="fa fa-undo-alt"></i> Back</button>
                        </div>
                        <div class="conts">
                            <p id="errhandlers"></p>
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