{^include file=$comheader_file^}
  <div class="table-line">
    <a href="./?menu=admin&act=item_input" class="button-field ui-btn-purple"><i class="fa fa-plus"></i> 添加课程</a>
  </div>
{^if !empty($item_info_list)^}
  <div class="main-table">
    <h2>课程管理</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>价格</th>
          <th>课程安排</th>
          <th>销售状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$item_info_list item=item_info^}
        <tr>
          <td>{^$item_info["item_name"]|escape^}</td>
          <td>{^$item_type_list[$item_info["item_type"]]^}</td>
          <td>{^$item_method_list[$item_info["item_method"]]^}</td>
          <td>{^$item_grade_list[$item_info["item_grade"]]^}</td>
          <td>{^$item_info["item_price"]|escape^}{^$item_unit_list[$item_info["item_unit"]]^}</td>
          <td>{^if $item_info["item_method"] eq "4"^}{^$item_info["item_unit_hour"]|escape^}小时/节 (共{^$item_info["item_unit_amount"]|escape^}节){^else^}自由排课{^/if^}</td>
          <td>{^$item_sale_status_list[$item_info["item_sale_status"]]^}</td>
          <td>{^$item_info["item_id"]|escape^}</td>
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