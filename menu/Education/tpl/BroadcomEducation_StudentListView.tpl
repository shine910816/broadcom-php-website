{^include file=$comheader_file^}
{^if !empty($student_info_list)^}
  <div class="main-table">
    <h2>学员管理</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>姓名</th>
          <th>手机号</th>
          <th>年级</th>
          <th>在读学校</th>
          <th>受理人</th>
          <th>受理时间</th>
          <th>创建人</th>
          <th>创建时间</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$student_info_list item=student_info_item^}
        <tr>
          <td><a href="./?menu=education&act=student_info&student_id={^$student_info_item["student_id"]^}" class="text-link">{^$student_info_item["student_name"]|escape^}</a></td>
          <td>{^$student_info_item["student_mobile_number"]|escape^}</td>
          <td>{^$student_info_item["grade_name"]^}</td>
          <td>{^$student_info_item["student_school_name"]|escape^}</td>
          <td>{^$member_name_list[$student_info_item["assign_member_id"]]^}</td>
          <td>{^$student_info_item["assign_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>{^$member_name_list[$student_info_item["operated_by"]]^}</td>
          <td>{^$student_info_item["insert_date"]|date_format:"%Y-%m-%d"^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^include file=$compagina_file^}
  </div>
{^else^}
  <p>暂无数据</p>
{^/if^}
{^include file=$comfooter_file^}