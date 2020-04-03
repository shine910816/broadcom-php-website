{^include file=$comheader_file^}
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}">
    <h2>返课列表</h2>
{^if empty($course_list)^}
    <p>当前无返课信息</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:200px;">学员姓名</th>
          <th style="width:200px;">形式</th>
          <th style="width:315px;">课程名</th>
          <th style="width:315px;">时间</th>
          <th style="width:200px;">教室</th>
          <th style="width:200px;">教师</th>
          <th style="width:200px;">学科</th>
          <th style="width:200px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$course_list item=course_info^}
        <tr>
          <td>{^$student_list[$course_info["student_id"]]["student_name"]^}</td>
          <td>{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}试听课{^else^}{^$course_type_list[$course_info["course_type"]]^}{^/if^}</td>
          <td>{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}{^$course_type_list[$course_info["course_type"]]^}{^else^}{^$item_list[$course_info["item_id"]]["item_name"]^}{^/if^}</td>
          <td>{^$course_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["course_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^$room_list[$course_info["room_id"]]^}</td>
          <td>{^$teacher_info[$course_info["teacher_member_id"]]["m_name"]^}</td>
          <td>{^$subject_list[$course_info["subject_id"]]^}</td>
          <td><a href="./?menu=education&act=reset_confirm&course_id={^$course_info["course_id"]^}" class="button-field ui-btn-orange">详细</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$comfooter_file^}