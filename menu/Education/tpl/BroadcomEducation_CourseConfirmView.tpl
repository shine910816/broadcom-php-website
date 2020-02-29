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
        <div class="table-item-value">{^$student_list[$course_info["student_id"]]["student_name"]^}{^if $confirm_able_flg and $course_info["course_type"] eq "4"^} 等{^$class_course_list|count^}名学员{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">形式</div>
        <div class="table-item-value">{^$course_type_list[$course_info["course_type"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^if $course_info["course_type"] eq "1"^}一对多试听课{^else^}{^$item_list[$course_info["item_id"]]["item_name"]^}{^/if^}</div>
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
        <div class="table-item-name">消课情况</div>
        <div class="table-item-value">{^if $course_info["confirm_flg"]^}{^if $course_info["reset_examine_flg"]^}已返课{^else^}已消课{^/if^}{^else^}未消课{^/if^}</div>
      </div>
    </div>
  </div>
{^if $confirm_able_flg and $course_info["course_type"] neq "4"^}
  <div class="main-table pb_15">
    <h2>消课信息确认</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">授课时长</div>
        <div class="table-item-value">
          <select name="actual_course_hours" class="text-field">
            <option value="1"{^if $course_info["course_hours"] eq "1.00"^} selected{^/if^}>1小时</option>
            <option value="1.5"{^if $course_info["course_hours"] eq "1.50"^} selected{^/if^}>1.5小时</option>
            <option value="2"{^if $course_info["course_hours"] eq "2.00"^} selected{^/if^}>2小时</option>
          </select>
        </div>
      </div>
    </div>
  </div>
{^/if^}
{^if $reset_able_flg^}
  <div class="main-table pb_15">
    <h2>返课信息确认</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">返课理由</div>
        <div class="table-item-value">
          <select name="reset_flg" class="text-field">
{^foreach from=$reset_reason_list key=reson_code item=reason_name^}
            <option value="{^$reson_code^}">{^$reason_name^}</option>
{^/foreach^}
          </select>
        </div>
      </div>
    </div>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=course_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if $confirm_able_flg^}
    <button type="submit" name="do_confirm" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认消课</button>
{^/if^}
{^if $reset_able_flg^}
    <button type="submit" name="do_reset" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认返课</button>
{^/if^}
  </div>
</form>
{^include file=$comfooter_file^}