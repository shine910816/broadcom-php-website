{^include file=$comheader_file^}
<script type="text/javascript">
$(document).ready(function(){
    $(".date_input").focus(function(){
        $(".time_type_label").removeClass("ui-btn-orange");
        $("#customize_type").addClass("ui-btn-orange");
        $("input[name='period_type']").attr("checked", false);
        $("#customize_radio").prop("checked", "checked");
    });
});
</script>
<div class="main-table pb_15">
  <h2>排课信息筛选</h2>
  <form action="./" method="get">
    <input type="hidden" name="menu" value="{^$current_menu^}" />
    <input type="hidden" name="act" value="{^$current_act^}" />
    <div class="table-line">
      <div class="table-item-a" style="width:66.6%;">
        <div class="table-item-name" style="width:12.5%; padding:0 0.5%;">上课时间</div>
        <div class="table-item-value" style="padding:0 0.5%;">
          <label class="button-field ui-btn-check{^if $period_type eq "1"^} ui-btn-orange{^/if^} time_type_label"><input type="radio" name="period_type" value="1"{^if $period_type eq "1"^} checked{^/if^} />本周</label>
          <label class="button-field ui-btn-check{^if $period_type eq "2"^} ui-btn-orange{^/if^} time_type_label"><input type="radio" name="period_type" value="2"{^if $period_type eq "2"^} checked{^/if^} />本月</label>
          <label class="button-field ui-btn-check{^if $period_type eq "3"^} ui-btn-orange{^/if^} time_type_label"><input type="radio" name="period_type" value="3"{^if $period_type eq "3"^} checked{^/if^} />上月</label>
          <label class="button-field ui-btn-check{^if $period_type eq "4"^} ui-btn-orange{^/if^} time_type_label" id="customize_type"><input type="radio" name="period_type" value="4" id="customize_radio"{^if $period_type eq "4"^} checked{^/if^} />自定义</label>
          <input type="date" name="start_date" value="{^$period_start_date|date_format:"%Y-%m-%d"^}" class="text-field date_input" style="width:150px; margin-right:10px;" />
          <input type="date" name="end_date" value="{^$period_end_date|date_format:"%Y-%m-%d"^}" class="text-field date_input" style="width:150px; margin-right:10px;" />
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">消课状态</div>
        <div class="table-item-value">
          <label class="button-field ui-btn-check{^if $confirm_flg eq "1"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="1"{^if $confirm_flg eq "1"^} checked{^/if^} />已消课</label>
          <label class="button-field ui-btn-check{^if $confirm_flg eq "0"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="0"{^if $confirm_flg eq "0"^} checked{^/if^} />未消课</label>
          <label class="button-field ui-btn-check{^if $confirm_flg eq "2"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="2"{^if $confirm_flg eq "2"^} checked{^/if^} />全部</label>
        </div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员</div>
        <div class="table-item-value">
          <select name="student_id" class="text-field">
            <option value="0">全部学员</option>
{^foreach from=$student_list key=student_key item=student_name^}
            <option value="{^$student_key^}"{^if $student_key eq $student_id^} selected{^/if^}>{^$student_name^}</option>
{^/foreach^}
          </select>
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">教师</div>
        <div class="table-item-value">
{^if $teacher_member_list_flg^}
          <select name="teacher_member_id" class="text-field">
            <option value="0">全部教师</option>
{^foreach from=$teacher_list key=teacher_member_key item=teacher_name^}
            <option value="{^$teacher_member_key^}"{^if $teacher_member_key eq $teacher_member_id^} selected{^/if^}>{^$teacher_name^}</option>
{^/foreach^}
          </select>
{^else^}
          {^$user_member_name^}
{^/if^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">教务</div>
        <div class="table-item-value">
{^if $assign_member_list_flg^}
          <select name="assign_member_id" class="text-field">
            <option value="0">全部教务</option>
{^foreach from=$member_list key=member_key item=member_name^}
            <option value="{^$member_key^}"{^if $member_key eq $assign_member_id^} selected{^/if^}>{^$member_name^}</option>
{^/foreach^}
          </select>
{^else^}
          {^$user_member_name^}
{^/if^}
        </div>
      </div>
    </div>
    <div class="table-line">
      <button type="submit" class="button-field ui-btn-green" style="margin-left:10px;"><i class="fa fa-check"></i> 查询</button>
{^if !empty($course_list)^}
      <a href="./?{^$output_url^}" class="button-field ui-btn-purple" target="_blank"><i class="fa fa-file-excel-o"></i> 导出当前数据</a>
{^/if^}
    </div>
  </form>
</div>
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}">
    <h2>排课列表</h2>
{^if empty($course_list)^}
    <p>当前条件下无排课信息</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th>学员姓名</th>
          <th>年级</th>
          <th>教务</th>
          <th>课程详情</th>
          <th>消课状态</th>
          <th>排课类型</th>
          <th>上课时间</th>
          <th>科目</th>
          <th>上课时长</th>
          <th>任课教师</th>
          <th>消课人</th>
          <th>消课时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$course_list item=course_info^}
{^foreach from=$course_info["course_info"] key=student_idx item=course_item^}
        <tr>
          <td>{^$course_item["student_name"]^}</td>
          <td>{^$course_item["student_grade_name"]^}</td>
          <td>{^$course_item["assign_member_name"]^}</td>
          <td><span title="{^$course_item["contract_number"]^}">{^$course_info["item_name"]^}</span></td>
{^if $student_idx eq "0"^}
          <td rowspan="{^$course_info["course_info"]|count^}">{^if $course_info["confirm_flg"]^}已{^else^}未{^/if^}消课</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^$course_info["course_detail_type_name"]^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^if $course_info["confirm_flg"]^}{^$course_info["actual_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["actual_expire_date"]|date_format:"%H:%M"^}{^else^}{^$course_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["course_expire_date"]|date_format:"%H:%M"^}{^/if^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^$course_info["subject_name"]^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^if $course_info["confirm_flg"]^}{^$course_info["actual_course_hours"]^}{^else^}{^$course_info["course_hours"]^}{^/if^}小时</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^$course_info["teacher_member_name"]^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^$course_info["confirm_member_name"]^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}">{^$course_info["confirm_date"]|date_format:"%Y-%m-%d %H:%M"^}</td>
          <td rowspan="{^$course_info["course_info"]|count^}"><a href="./?menu=education&act=course_confirm&multi_course_id={^$course_info["multi_course_id"]^}" class="button-field ui-btn-orange">详细</a></td>
{^/if^}
{^/foreach^}
{^/foreach^}
        </tr>
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$comfooter_file^}