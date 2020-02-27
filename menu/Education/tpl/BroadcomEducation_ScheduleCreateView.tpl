{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^if $ready_flg^}
  <input type="hidden" name="school_id" value="{^$school_id^}" />
  <input type="hidden" name="item_id" value="{^$item_info["item_id"]^}" />
  <div class="main-table pb_15">
    <h2>课程信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^$item_info["item_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程安排</div>
        <div class="table-item-value">{^$item_info["item_unit_hour"]|escape^}小时/节 (共{^$item_info["item_unit_amount"]|escape^}节)</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程科目</div>
        <div class="table-item-value">{^foreach from=$allow_subject_array item=subject_id^}{^$subject_list[$subject_id]^} {^/foreach^}</div>
      </div>
    </div>
  </div>
  <div class="main-table">
    <h2>课程安排</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>时间</th>
          <th>教室</th>
          <th>教师</th>
          <th>学科</th>
        </tr>
      </thead>
      <tbody>
{^for $course_idx=1 to $item_info["item_unit_amount"]^}
        <tr>
          <td>
            <input type="date" name="course_info[{^$course_idx^}][start_date]" value="{^$today_date^}" class="table-text-field" style="width:150px;" />
            <input type="time" name="course_info[{^$course_idx^}][start_time]" value="09:00" class="table-text-field" style="width:150px; margin-left:5px;" />
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][room_id]" class="table-text-field">
{^foreach from=$room_list key=room_id item=room_name^}
              <option value="{^$room_name^}">{^$room_name^}</option>
{^/foreach^}
            </select>
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][teacher_member_id]" class="table-text-field" style="width:200px;">
{^foreach from=$teacher_list key=teacher_id item=teacher_name^}
              <option value="{^$teacher_id^}">{^$teacher_name^}</option>
{^/foreach^}
            </select>
          </td>
          <td>
            <select name="course_info[{^$course_idx^}][subject_id]" class="table-text-field">
{^foreach from=$allow_subject_array item=subject_id^}
              <option value="{^$subject_id^}">{^$subject_list[$subject_id]^}</option>
{^/foreach^}
            </select>
          </td>
        </tr>
{^/for^}
      </tbody>
    </table>
  </div>
{^else^}
  <h1>课程选择</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程选择</div>
      <div class="table-item-value">
        <select name="selected_item_id" class="text-field">
{^foreach from=$class_item_list key=item_id item=item_name^}
          <option value="{^$item_id^}">{^$item_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
{^if $ready_flg^}
    <a href="./?menu=education&act=schedule_create" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认课程</button>
{^else^}
    <a href="./?menu=education&act=schedule_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_select" value="1" class="button-field ui-btn-green"><i class="fa fa-plus"></i> 添加课表</button>
{^/if^}
  </div>
</form>
{^include file=$comfooter_file^}