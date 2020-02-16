{^include file=$comheader_file^}
  <div class="table-line">
    <a href="./?menu=front&act=create_leads" class="button-field ui-btn-purple"><i class="fa fa-plus"></i> 添加意向客户</a>
  </div>
{^if !empty($student_info_list)^}
  <div class="main-table">
    <h2>我的意向客户</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>姓名</th>
          <th>手机号</th>
          <th>会员级别</th>
          <th>年级</th>
          <th>渠道来源</th>
          <th>意向程度</th>
          <th>跟进状态</th>
          <th>在读学校</th>
          <th>创建人</th>
          <th>创建时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$student_info_list item=student_info_item^}
        <tr>
          <td>{^$student_info_item["student_name"]|escape^}</td>
          <td>{^$student_info_item["student_mobile_number"]|escape^}</td>
          <td>{^$student_level_list[$student_info_item["student_level"]]^}</td>
          <td>{^$student_info_item["grade_name"]^}</td>
          <td>{^$media_channel_list[$student_info_item["media_channel_code"]]^}</td>
          <td>{^$purpose_level_list[$student_info_item["purpose_level"]]^}</td>
          <td>{^$follow_status_list[$student_info_item["follow_status"]]^}</td>
          <td>{^$student_info_item["student_school_name"]|escape^}</td>
          <td>{^$member_name_list[$student_info_item["operated_by"]]^}</td>
          <td>{^$student_info_item["insert_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=education&act=course_create&student_id={^$student_info_item["student_id"]|escape^}">安排试听</a>
              <a href="./?menu=front&act=cart_fill&student_id={^$student_info_item["student_id"]|escape^}">添加课程</a>
              <a href="./?menu=front&act=cart_info&student_id={^$student_info_item["student_id"]|escape^}">已选课程</a>
              <a href="./?menu=front&act=order_list&student_id={^$student_info_item["student_id"]|escape^}">订单管理</a>
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