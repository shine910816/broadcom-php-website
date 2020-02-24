{^include file=$comheader_file^}
  <div class="table-line">
    <a href="./?menu=education&act=schedule_create" class="button-field ui-btn-purple"><i class="fa fa-plus"></i> 添加课表</a>
  </div>
  <div class="main-table{^if empty($student_info_list)^} pb_15{^/if^}">
    <h2>班课课程管理</h2>
{^if empty($schedule_info_list)^}
    <p>暂无课表</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th>姓名</th>
          <th>节数</th>
          <th>单节时长</th>
          <th>开始日</th>
          <th>结束日</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$schedule_info_list item=schedule_item^}
        <tr>
          <td><a href="./?menu=education&act=schedule_info&schedule_id={^$schedule_item["schedule_id"]|escape^}" class="text-link">{^$item_list[$schedule_item["item_id"]]["item_name"]^}</a></td>
          <td>{^$schedule_item["item_unit_amount"]^}节</td>
          <td>{^$schedule_item["item_unit_hour"]^}小时</td>
          <td>{^$schedule_item["schedule_start_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>{^$schedule_item["schedule_expire_date"]|date_format:"%Y-%m-%d"^}</td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=education&act=schedule_info&schedule_id={^$schedule_item["schedule_id"]|escape^}">详细</a>
            </div>
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$comfooter_file^}