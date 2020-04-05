{^include file=$comheader_file^}
  <div class="table-line">
    <a href="./?menu=education&act=my_course_list&date={^$prev_date^}" class="button-field ui-btn-orange"><i class="fa fa-chevron-left"></i> 前一月</a>
    <a class="button-field">{^$current_date_text^}</a>
    <a href="./?menu=education&act=my_course_list&date={^$next_date^}" class="button-field ui-btn-orange">后一月 <i class="fa fa-chevron-right"></i></a>
  </div>
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}">
    <h2>排课列表</h2>
{^if empty($course_list)^}
    <p>当前日无排课信息</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:200px;">学员姓名</th>
          <th style="width:200px;">形式</th>
          <th style="width:315px;">课程名</th>
          <th style="width:315px;">时间</th>
          <th style="width:200px;">消课时间</th>
          <th style="width:200px;">教师</th>
          <th style="width:200px;">学科</th>
          <th style="width:200px;">消课情况</th>
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
          <td>{^$course_info["course_hours"]^}</td>
          <td>{^$teacher_info[$course_info["teacher_member_id"]]["m_name"]^}</td>
          <td>{^$subject_list[$course_info["subject_id"]]^}</td>
          <td>{^if $course_info["reset_examine_flg"]^}已返课{^else^}{^if $course_info["reset_flg"]^}待返课审核{^else^}{^if $course_info["confirm_flg"]^}已消课{^else^}未消课{^/if^}{^/if^}{^/if^}</td>
          <td><a href="./?menu=education&act=course_confirm&course_id={^$course_info["course_id"]^}" class="button-field ui-btn-orange">详细</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$comfooter_file^}