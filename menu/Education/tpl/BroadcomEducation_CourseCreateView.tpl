{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="school_id" value="{^$base_course_info["school_id"]^}" />
  <input type="hidden" name="student_id" value="{^$base_course_info["student_id"]^}" />
  <div class="main-table pb_15">
    <h2>学员信息确认及时间选择</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">联系电话</div>
        <div class="table-item-value">{^$student_info["covered_mobile_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">年级</div>
        <div class="table-item-value">{^$student_info["student_grade_name"]|escape^}</div>
      </div>
    </div>
    <input type="hidden" name="order_item_id" value="{^$base_course_info["order_item_id"]^}" />
    <input type="hidden" name="item_id" value="{^$base_course_info["item_id"]^}" />
{^if $audition_flg^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">试听类型</div>
        <div class="table-item-value">
{^foreach from=$audition_type_list key=audition_type_key item=audition_type_name^}
          <label class="button-field ui-btn-check{^if $audition_type_key eq $base_course_info["audition_type"]^} ui-btn-orange{^/if^}"><input name="audition_type" value="{^$audition_type_key^}" type="radio"{^if $audition_type_key eq $base_course_info["audition_type"]^} checked{^/if^}>{^$audition_type_name^}</label>
{^/foreach^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">
{^foreach from=$course_type_list key=course_type_key item=course_type_name^}
          <label class="button-field ui-btn-check{^if $course_type_key eq $base_course_info["course_type"]^} ui-btn-orange{^/if^}"><input name="course_type" value="{^$course_type_key^}" type="radio"{^if $course_type_key eq $base_course_info["course_type"]^} checked{^/if^}>{^$course_type_name^}</label>
{^/foreach^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">试听剩余</div>
        <div class="table-item-value">{^$student_info["audition_hours"]^}小时</div>
      </div>
    </div>
{^else^}
    <input type="hidden" name="course_type" value="{^$base_course_info["course_type"]^}" />
    <input type="hidden" name="audition_type" value="{^$base_course_info["audition_type"]^}" />
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^$order_item_info["item_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^$order_item_info["item_method_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">合同号</div>
        <div class="table-item-value">{^$order_item_info["contract_number"]|escape^}</div>
      </div>
    </div>
{^/if^}
{^if $multi_flg or $audition_flg^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">时间方式</div>
        <div class="table-item-value">
{^foreach from=$time_type_list key=time_type_key item=time_type_name^}
          <label class="button-field ui-btn-check{^if $time_type_key eq $time_info["time_type"]^} ui-btn-orange{^/if^}"><input name="time_type" value="{^$time_type_key^}" type="radio"{^if $time_type_key eq $time_info["time_type"]^} checked{^/if^}>{^$time_type_name^}</label>
{^/foreach^}
        </div>
      </div>
    </div>
{^else^}
    <input type="hidden" name="time_type" value="1" />
{^/if^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">授课时间</div>
        <div class="table-item-value"><input type="time" name="start_time" value="{^$time_info["start_time"]^}" class="text-field" /></div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课时长</div>
        <div class="table-item-value">
{^if $base_course_info["course_type"] eq "4"^}
          <input type="hidden" name="course_hours" value="{^$order_item_info["item_unit_hour"]^}" />{^$order_item_info["item_unit_hour"]^}小时
{^else^}
          <select name="course_hours" class="text-field">
{^foreach from=$hours_list item=hour_num^}
            <option value="{^$hour_num^}"{^if $hour_num eq $time_info["course_hours"]^} selected{^/if^}>{^$hour_num^}小时</option>
{^/foreach^}
          </select>
{^/if^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课教师</div>
        <div class="table-item-value"><select name="subject_teacher" class="text-field">{^$teacher_list_content^}</select></div>
      </div>
    </div>
    <div class="table-line" style="height:40px;">
      <button type="submit" class="button-field ui-btn-purple ui-btn-big">确认信息</button>
    </div>
  </div>
</form>
{^if isset($base_data)^}
<style type="text/css">
.day_button {
  width:70px!important;
  padding:0;
}
</style>
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="base_data" value="{^$base_data^}" />
  <input type="hidden" name="time_data" value="{^$time_data^}" />
{^if $time_info["time_type"] eq "1"^}
  <div class="main-table">
    <h2>自定义时间选择</h2>
    <table class="disp_table">
{^foreach from=$course_list key=year_num item=year_item^}
{^foreach from=$year_item key=month_num item=month_item^}
      <thead>
        <tr>
          <th>{^$year_num^}年{^$month_num^}月</th>
          <th>周一</th>
          <th>周二</th>
          <th>周三</th>
          <th>周四</th>
          <th>周五</th>
          <th>周六</th>
          <th>周日</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$month_item key=week_idx item=week_item^}
        <tr>
          <td>第{^$week_idx + 1^}周</td>
{^foreach from=$week_item key=day_idx item=day_info^}
          <td>
{^if !empty($day_info)^}
{^if !$day_info["out"]^}
{^if $day_info["able"]^}
            <label class="button-field day_button ui-btn-check"><input type="checkbox" name="selected_date[]" value="{^$day_info["date"]^}" />{^$day_info["day"]^}</label>
{^else^}
            <input type="button" value="{^$day_info["day"]^}" class="button-field day_button ui-btn-red" />
{^/if^}
{^else^}
            <input type="button" value="{^$day_info["day"]^}" class="button-field day_button ui-btn-black" />
{^/if^}
{^/if^}
          </td>
{^/foreach^}
        </tr>
{^/foreach^}
      </tbody>
{^/foreach^}
{^/foreach^}
    </table>
  </div>
{^else^}
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}">
    <h2>其他学员时间选择</h2>
{^if empty($course_list)^}
    <p>当前时间无其他学员的已排课程，请切换至自定义时间进行排课。</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:100px;"></th>
          <th style="width:300px;">授课时间</th>
          <th style="width:300px;">授课时长</th>
          <th style="width:300px;">学科</th>
          <th style="width:300px;">教师</th>
          <th style="width:300px;">已排人数</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$course_list item=course_item^}
        <tr>
          <td><label class="button-field ui-btn-check"><input name="selected_info[]" value="{^$course_item["param"]^}" type="checkbox" />&nbsp;<i class="fa fa-check-square"></i>&nbsp;</label></td>
          <td>{^$course_item["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_item["course_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^$course_item["course_hours"]^}小时</td>
          <td>{^$course_item["subject_name"]^}</td>
          <td>{^$course_item["teacher_member_name"]^}</td>
          <td title="{^$course_item["student_names"]^}">{^$course_item["student_count"]^}人</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^/if^}
{^if !empty($course_list)^}
  <div class="table-line">
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i>&nbsp;确认排课</button>
  </div>
{^/if^}
</form>
{^/if^}
{^include file=$comfooter_file^}