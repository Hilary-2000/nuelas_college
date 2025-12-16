<div class="contents animate hide" id="academic_reports_window">
    <div class="titled">
        <h2>Academics</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Academic Reports</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p><strong>Information:</strong></p>
                <p>- At this window you will be able to generate students academic report cards.</p>
                <ul>
                    <li>Unit perfomance reports</li>
                    <li>Students Semester wise transcripts</li>
                    <li>Students Yearly Transcripts</li>
                </ul>
            </div>
            <div class="body4">
                <p>Use the filters below:</p>
                <div class="row">
                    <div class="col-md-4">
                        <label for="filter_option" class="form-control-label"><b>Select an option</b></label>
                        <select name="filter_option" id="filter_option" class="form-control">
                            <option value="" hidden>Select an option</option>
                            <option value="student_perfomance">Student Exam Perfomance.</option>
                            <option value="student_cat_perfomance">Student C.A.T Perfomance.</option>
                            <option value="transcripts">Semeter-Wise Transcripts.</option>
                            <option value="report_card">Year-wise Transcripts. </option>
                        </select>
                    </div>
                    <div class="col-md-4" id='exam_list_option_filter_holder'>
                        <span id=""><p class="text-success p-1 my-2 border border-success rounded">Exam List appear here!</p></span>
                    </div>
                    <div class="col-md-4">
                        <label for="course_level_option" class="form-control-label">Course Level</label>
                        <span id="course_level_filter_holder"><p class="text-success p-1 my-2 border border-success rounded">Course Level Will appear here!</p></span>
                    </div>
                    <div class="col-md-4">
                        <label for="course_list_option" class="form-control-label">Course List</label>
                        <span id="course_list_filter_holder"><p class="text-success p-1 my-2 border border-success rounded">Course List Will appear here!</p></span>
                    </div>
                    <div class="col-md-4">
                        <label for="module_list_option" class="form-control-label">Module List</label>
                        <span id="module_list_filter_holder"><p class="text-success p-1 my-2 border border-success rounded">Module List Will appear here!</p></span>
                    </div>
                    <div class="col-md-4" id="unit_list_holder_window">
                        <label for="unit_list_option" class="form-control-label">Unit List</label>
                        <span id="unit_list_filter_holder"><p class="text-success p-1 my-2 border border-success rounded">Unit List Will appear here!</p></span>
                    </div>
                    <div class="col-md-4 d-none" id="cat_list_option_window">
                        <label for="cat_list_option" class="form-control-label">CAT List</label>
                        <span id="cat_list_filter_holder"><p class="text-success p-1 my-2 border border-success rounded">CAT List Will appear here!</p></span>
                    </div>
                    <div class="col-md-12">
                        <button type="button" id="display_course_reports"><i class="fas fa-eye"></i> Display</button>
                        <button type="submit" id="print_course_reports"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
                <div class="container" id="unit_report_display_holder"></div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>