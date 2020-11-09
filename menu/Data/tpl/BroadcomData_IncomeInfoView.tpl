{^include file=$comheader_file^}
{^include file=$period_select_file^}
<div class="main-table">
  <h2>确认收入</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:200px;">业绩类型</th>
        <th style="width:500px;">消课小时</th>
        <th style="width:500px;">收入金额</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$course_data key=course_type item=course_item^}
      <tr>
        <td>{^$course_type_list[$course_type]^}</td>
        <td>{^$course_item["count"]^}小时</td>
        <td>{^$course_item["amount"]^}元</td>
      </tr>
{^/foreach^}
      <tr>
        <td style="font-weight:bold;">总计</td>
        <td style="font-weight:bold;">{^$total_count^}小时</td>
        <td style="font-weight:bold;">{^$total_amount^}元</td>
      </tr>
    </tbody>
  </table>
</div>
{^include file=$comfooter_file^}
