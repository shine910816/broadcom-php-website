{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^if $course_type eq "1"^}
  <input type="hidden" name="student_id" value="{^$student_id^}" />
{^else^}
  <input type="hidden" name="order_item_id" value="{^$order_item_id^}" />
{^/if^}
  <div class="main-table pb_15">
    <h2>学员信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">年级</div>
        <div class="table-item-value">{^$student_info["grade_name"]|escape^}</div>
      </div>
    </div>
  </div>
  <div class="main-table pb_15">
    <h2>课程信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^if !empty($order_item_info)^}{^$order_item_info["item_name"]|escape^}{^else^}试听课{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^if !empty($order_item_info)^}{^$item_method_list[$order_item_info["item_method"]]^}{^else^}一对多{^/if^}</div>
      </div>
    </div>
{^if $hint_context|strlen gt 0^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name"></div>
        <div class="table-item-value"><span class="error-hint">{^$hint_context^}</span></div>
      </div>
    </div>
{^/if^}
  </div>
{^if !empty($set_course_info)^}
  <div class="main-table">
    <h2>已排课程</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:200px;">方式</th>
          <th style="width:200px;">时间</th>
          <th style="width:200px;">教室</th>
          <th style="width:200px;">教师</th>
          <th style="width:200px;">学科</th>
          <th style="width:200px;">消课</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$set_course_info item=course_item^}
        <tr>
          <td>{^$course_type_list[$course_type]^}</td>
          <td>{^$course_item["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_item["course_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^$room_list[$course_item["room_id"]]^}</td>
          <td>{^$teacher_info[$course_item["teacher_member_id"]]["m_name"]^}</td>
          <td>{^$subject_list[$course_item["subject_id"]]^}</td>
          <td>{^if $course_item["reset_examine_flg"]^}已返课{^else^}{^if $course_item["reset_flg"]^}待返课审核{^else^}{^if $course_item["confirm_flg"]^}已消课{^else^}未消课{^/if^}{^/if^}{^/if^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
  <div class="main-table{^if !$create_able_flg or $course_type eq "4"^} pb_15{^/if^}">
    <h2>{^$course_type_list[$course_type]^}排课</h2>
{^if !$create_able_flg^}
    <p>无法排课</p>
{^else^}
{^if $course_type eq "4"^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">班课课表选择</div>
        <div class="table-item-value">
{^if empty($schedule_list)^}
          <a href="./?menu=education&act=schedule_create&do_select=1&selected_item_id={^$item_id^}" class="text-link">尚未创建课表</a>
{^else^}
          <select name="course_info[schedule_id]" class="text-field">
{^foreach from=$schedule_list item=schedule_item^}
            <option value="{^$schedule_item["schedule_id"]^}">{^$schedule_item["schedule_start_date"]|date_format:"%m-%d"^}~{^$schedule_item["schedule_expire_date"]|date_format:"%m-%d"^}</option>
{^/foreach^}
          </select>
{^/if^}
        </div>
      </div>
    </div>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
{^if $course_type neq "2"^}
          <th style="width:230px;">参考</th>
{^/if^}
          <th style="width:315px;">时间</th>
          <th style="width:200px;">时长</th>
          <th style="width:200px;">教室</th>
          <th style="width:200px;">学科教师</th>
        </tr>
      </thead>
      <tbody>
{^for $course_idx=1 to 10^}
        <tr>
{^if $course_type neq "2"^}
          <td>
            <select name="course_info[{^$course_idx^}][refer]" class="table-text-field" style="width:220px;">
              <option value="0">自定义课程安排</option>
{^if !empty($others_course_info)^}
{^foreach from=$others_course_info item=others_course_item^}
              <option value="{^$others_course_item["course_id"]^}">{^$others_course_item["course_start_date"]|date_format:"%m-%d %H:%M"^}~{^$others_course_item["course_expire_date"]|date_format:"%H:%M"^} {^$student_list[$others_course_item["student_id"]]["student_name"]^}</option>
{^/foreach^}
{^/if^}
            </select>
          </td>
{^else^}
          <input type="hidden" name="course_info[{^$course_idx^}][refer]" value="0" />
{^/if^}
          <td>
            <input type="date" name="course_info[{^$course_idx^}][start_date]" class="table-text-field" style="width:150px;" />
            <input type="time" name="course_info[{^$course_idx^}][start_time]" class="table-text-field" style="width:150px; margin-left:5px;" />
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][course_hours]" class="table-text-field">
              <option value="1">1小时</option>
              <option value="1.5">1.5小时</option>
              <option value="2">2小时</option>
            </select>
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][room_id]" class="table-text-field">
{^foreach from=$room_list key=room_id item=room_name^}
              <option value="{^$room_id^}">{^$room_name^}</option>
{^/foreach^}
            </select>
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][subject_teacher]" class="table-text-field" style="width:200px;">
{^foreach from=$subject_teacher_info key=subject_id item=teacher_list^}
{^if $subject_id|in_array:$allow_subject_list^}
              <optgroup label="{^$subject_list[$subject_id]^}">
{^foreach from=$teacher_list item=teacher_member_id^}
                <option value="{^$subject_id^}-{^$teacher_member_id^}">{^$teacher_info[$teacher_member_id]["m_name"]^}</option>
{^/foreach^}
              </optgroup>
{^/if^}
{^/foreach^}
            </select>
          </td>
        </tr>
{^/for^}
      </tbody>
    </table>
{^/if^}
{^/if^}
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=student_info&student_id={^$student_id^}" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if $create_able_flg^}
    <button name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认</a>
{^/if^}
  </div>
</form>
{^include file=$comfooter_file^}