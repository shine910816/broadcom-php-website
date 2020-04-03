{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="course_id" value="{^$course_id^}" />
  <div class="main-table pb_15">
    <h2>排课信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_list[$course_info["student_id"]]["student_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">形式</div>
        <div class="table-item-value">{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}试听课{^else^}{^$course_type_list[$course_info["course_type"]]^}{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}{^$course_type_list[$course_info["course_type"]]^}{^else^}{^$item_list[$course_info["item_id"]]["item_name"]^}{^/if^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">时间</div>
        <div class="table-item-value">{^$course_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["course_expire_date"]|date_format:"%H:%M"^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">教室</div>
        <div class="table-item-value">{^$room_list[$course_info["room_id"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">教师</div>
        <div class="table-item-value">{^$teacher_info[$course_info["teacher_member_id"]]["m_name"]^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学科</div>
        <div class="table-item-value">{^$subject_list[$course_info["subject_id"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">返课理由</div>
        <div class="table-item-value">{^$reset_reason_list[$course_info["confirm_flg"]]^}</div>
      </div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=reset_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_cancel" value="1" class="button-field ui-btn-purple"><i class="fa fa-check"></i> 撤销返课</button>
    <button type="submit" name="do_confirm" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认返课</button>
  </div>
</form>
{^include file=$comfooter_file^}