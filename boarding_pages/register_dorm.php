<div class="contents animate hide" id="dorm_registration">
    <div class="titled">
        <h2>Boarding</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Register dormitory</p>
        </div>
        <div class="middle1">
            <div class="conts" style="border-bottom:1px dashed black;">
                <div class="conts">
                    <p><strong>Information:</strong></p>
                    <p>- At this window you are previledged to register dormitories that are present in school and view their information including students who inhibit the dorms.</p>
                </div>
                <div class="conts">
                    <p>Start by doing either of the following:</p>
                    <button type="button" id="register_exams" >Register New Dormitory</button>
                    <button type="button" id="refresh_dorm_list" >Refresh</button>
                    <!--<p><a href="tel://+254713620727">Call me ?</a></p>
                    <p><a href="mailto://hilaryme45@gmail.com">Mail me.</a></p>-->
                </div>
            </div>
            <div class="conts">
                <p id="dorm_list_messenger"></p>
                <p id="dormitory_list">
                    <!--<table>
                        <tr>
                            <th>No. </th>
                            <th>House Name</th>
                            <th>House Captain</th>
                            <th>Capacity</th>
                            <th>Occupied</th>
                            <th>Available</th>
                            <th>Option</th>
                        </tr>
                        <tr>
                            <td>1. </td>
                            <td>Mt Sinai Dormitory</td>
                            <td>Mr Hilary</td>
                            <td>100</td>
                            <td>10</td>
                            <td>90</td>
                            <td><button style='margin:0' type='button'>Edit</button></td>
                        </tr>
                    </table>-->
                </p>
                <p class = "hide pt-2" id="dorm_occupancy_details">
                </p>
                <div class="container hide" id="room_lists_window">
                    <h6 class="pt-2 text-center">Room List for "<span id="hostel_name_for_room">Unknown</span>"</h6>
                    <button type="button" id="add_new_room"> <i class="fas fa-plus"></i> Add Room</button>
                    <p id="error_handler_room_list"></p>
                    <p id="room_lists_and_mgmt"></p>
                    <div class="btns">
                        <button type="button" id="back_to_dormlist_from_rooms"><i class="fas fa-arrow-left"></i> Back</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>