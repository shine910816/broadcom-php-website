{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^if $multi_flg^}
  <input type="hidden" name="multi_course_id" value="{^$multi_course_id^}" />
{^else^}
  <input type="hidden" name="course_id" value="{^$course_id^}" />
{^/if^}
  <div class="main-table pb_15">
    <h2>排课信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程详情</div>
        <div class="table-item-value">{^$base_info["item_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程性质</div>
        <div class="table-item-value">{^$base_info["course_type_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">排课类型</div>
        <div class="table-item-value">{^$base_info["course_detail_type_name"]^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">科目</div>
        <div class="table-item-value">{^$base_info["subject_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">任课教师</div>
        <div class="table-item-value">{^$base_info["teacher_member_name"]^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">授课时间</div>
        <div class="table-item-value">{^if $base_info["confirm_flg"]^}{^$base_info["actual_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$base_info["actual_expire_date"]|date_format:"%H:%M"^}{^else^}{^$base_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$base_info["course_expire_date"]|date_format:"%H:%M"^}{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课时长</div>
        <div class="table-item-value">{^if $base_info["confirm_flg"]^}{^$base_info["actual_course_hours"]^}{^else^}{^$base_info["course_hours"]^}{^/if^}小时</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">消课状态</div>
        <div class="table-item-value">{^if $base_info["confirm_flg"]^}已{^else^}未{^/if^}消课</div>
      </div>
{^if $base_info["confirm_flg"]^}
      <div class="table-item-b">
        <div class="table-item-name">消课人</div>
        <div class="table-item-value">{^$base_info["confirm_member_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">消课时间</div>
        <div class="table-item-value">{^$base_info["confirm_date"]|date_format:"%Y-%m-%d %H:%M"^}</div>
      </div>
{^/if^}
    </div>
  </div>

  <div class="main-table">
    <h2>学员信息</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>学员姓名</th>
          <th>学员电话</th>
          <th>学员年级</th>
          <th>教务</th>
          <th>合同号</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$detail_list item=student_info^}
        <tr>
          <td>{^$student_info["student_name"]^}</td>
          <td>{^$student_info["student_mobile_number"]^}</td>
          <td>{^$student_info["student_grade_name"]^}</td>
          <td>{^$student_info["assign_member_name"]^}</td>
          <td>{^$student_info["contract_number"]^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <!--a href="./?menu=education&act=course_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a-->
    <button type="submit" name="do_delete" value="1" class="button-field ui-btn-red"{^if !$base_info["delete_able"]^} title="{^$base_info["delete_msg"]^}" disabled{^/if^}><i class="fa fa-close"></i> 删除排课</button>
    <button type="submit" name="do_confirm" value="1" class="button-field ui-btn-green"{^if !$base_info["confirm_able"]^} title="{^$base_info["confirm_msg"]^}" disabled{^/if^}><i class="fa fa-check"></i> 确认消课</button>
  </div>
</form>
{^include file=$comfooter_file^}