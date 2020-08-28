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
          <th>创建人</th>
          <th>创建时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$student_info_list item=student_info_item^}
        <tr>
          <td><a href="./?menu=education&act=student_info&student_id={^$student_info_item["student_id"]^}" class="text-link">{^$student_info_item["student_name"]|escape^}</a></td>
          <td>{^$student_info_item["covered_mobile_number"]|escape^}</td>
          <td>{^$student_info_item["grade_name"]^}</td>
          <td>{^$student_info_item["student_school_name"]|escape^}</td>
          <td>{^$member_name_list[$student_info_item["operated_by"]]^}</td>
          <td>{^$student_info_item["insert_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=education&act=course_create&student_id={^$student_info_item["student_id"]|escape^}">安排试听</a>
              <a href="./?menu=front&act=cart_fill&student_id={^$student_info_item["student_id"]|escape^}">添加课程</a>
              <a href="./?menu=front&act=cart_info&student_id={^$student_info_item["student_id"]|escape^}">已选课程</a>
              <a href="./?menu=education&act=student_edit&student_id={^$student_info_item["student_id"]|escape^}">修改信息</a>
              <a href="./?menu=education&act=student_assign&student_id={^$student_info_item["student_id"]|escape^}">分配学管</a>
            </div>
          </td>
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