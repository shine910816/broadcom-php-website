{^include file=$comheader_file^}
{^include file=$period_select_file^}
<div class="main-table pb_15">
  <h2>业绩数据</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:200px;">业绩类型</th>
        <th style="width:400px;">签单数</th>
        <th style="width:400px;">签单金额</th>
        <th style="width:400px;">打款退单数</th>
        <th style="width:400px;">打款退单金额</th>
        <th style="width:400px;">实收金额</th>
        <th style="width:400px;">减退费实收金额</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$achieve_type_list key=achieve_type item=achieve_type_name^}
      <tr>
        <td>{^$achieve_type_name^}</td>
        <td>{^$result_data[$achieve_type]["order_count"]^}单</td>
        <td>{^$result_data[$achieve_type]["order_amount"]^}元</td>
        <td>{^$result_data[$achieve_type]["cancel_order_count"]^}单</td>
        <td>{^$result_data[$achieve_type]["cancel_order_amount"]^}元</td>
        <td>{^$result_data[$achieve_type]["total_amount"]^}元</td>
        <td>{^$result_data[$achieve_type]["calculate_amount"]^}元</td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
  <div class="table-line">
    <div class="table-item-a">
      <div class="table-item-name">签单单底/实收单底</div>
      <div class="table-item-value">{^$average_amount^}元/单</div>
    </div>
  </div>
</div>
<div class="main-table">
  <h2>消课时长</h2>
  <table class="disp_table">
    <thead>
      <tr>
{^foreach from=$course_type_list item=course_type_name^}
        <th style="width:500px;">{^$course_type_name^}</th>
{^/foreach^}
      </tr>
    </thead>
    <tbody>
      <tr>
{^foreach from=$course_confirm_result_data item=course_confirm_amount^}
        <td>{^$course_confirm_amount^}小时</td>
{^/foreach^}
      </tr>
    </tbody>
  </table>
</div>
{^include file=$comfooter_file^}
