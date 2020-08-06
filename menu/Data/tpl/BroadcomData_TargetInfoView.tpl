{^include file=$comheader_file^}
<div class="table-line">
  <a href="./?menu={^$current_menu^}&act=target_input" class="button-field ui-btn-purple"><i class="fa fa-plus"></i> 添加目标</a>
</div>
<div class="table-line">
  <a href="./?menu={^$current_menu^}&act={^$current_act^}&date={^$date_prev^}" class="button-field ui-btn-orange"><i class="fa fa-chevron-left"></i> 上一月</a>
  <a class="button-field">{^$date_text^}</a>
  <a href="./?menu={^$current_menu^}&act={^$current_act^}&date={^$date_next^}" class="button-field ui-btn-orange">下一月 <i class="fa fa-chevron-right"></i></a>
</div>
<div class="main-table">
  <h2>目标进度</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:500px;">数据类型</th>
        <th style="width:500px;">目标</th>
        <th style="width:500px;">实际</th>
        <th style="width:500px;">完成率</th>
        <th style="width:500px;">差额</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$target_data key=target_key item=target_item^}
      <tr>
        <td>{^$target_type_list[$target_key]^}</td>
        <td>{^$target_item["target"]^}</td>
        <td>{^$target_item["actual"]^}</td>
        <td{^if $target_item["red"]^} style="color:#F60000;"{^/if^}>{^$target_item["percent"]^}</td>
        <td{^if $target_item["red"]^} style="color:#F60000;"{^/if^}>{^$target_item["amount"]^}</td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
</div>
{^include file=$comfooter_file^}
