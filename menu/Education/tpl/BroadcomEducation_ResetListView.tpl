{^include file=$comheader_file^}
  <div class="main-table{^if empty($reset_list)^} pb_15{^/if^}">
    <h2>返课列表</h2>
{^if empty($reset_list)^}
    <p>当前无返课信息</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:150px;">课程性质</th>
          <th style="width:150px;">排课类型</th>
          <th style="width:400px;">课程名称</th>
          <th style="width:300px;">授课时间</th>
          <th style="width:150px;">授课教师</th>
          <th style="width:150px;">学科</th>
          <th style="width:150px;">消课人</th>
          <th style="width:200px;">消课时间</th>
          <th style="width:100px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$reset_list key=reset_key item=course_info^}
        <tr>
          <td>{^$course_info["course_type_name"]^}</td>
          <td>{^$course_info["course_detail_type_name"]^}</td>
          <td>{^$course_info["item_name"]^}</td>
          <td>{^$course_info["actual_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["actual_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^$course_info["teacher_member_name"]^}</td>
          <td>{^$course_info["subject_name"]^}</td>
          <td>{^$course_info["confirm_member_name"]^}</td>
          <td>{^$course_info["confirm_date"]|date_format:"%Y-%m-%d %H:%M"^}</td>
          <td><a href="./?menu=education&act=reset_confirm&{^if $course_info["multi_flg"]^}multi_{^/if^}course_id={^$reset_key^}" class="button-field ui-btn-orange">详细</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$comfooter_file^}