{^include file=$comheader_file^}
{^if empty($refund_list)^}
  <p>无待审核退款转让合同</p>
{^else^}
  <div class="main-table">
    <h2>合同退转审核</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>学员姓名</th>
          <th>合同号</th>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>课时余量</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$refund_list item=refund_info^}
        <tr>
          <td><a href="./?menu=education&act=student_info&student_id={^$refund_info["student_id"]|escape^}" class="text-link">{^$refund_info["student_name"]|escape^}</a></td>
          <td>{^$refund_info["contract_number"]|escape^}</td>
          <td>{^$refund_info["item_name"]|escape^}</td>
          <td>{^$item_type_list[$refund_info["item_type"]]^}</td>
          <td>{^$item_method_list[$refund_info["item_method"]]^}</td>
          <td>{^$refund_info["refund_amount"]^}</td>
          <td><a href="./?menu=front&act=refund_info&order_item_id={^$refund_info["order_item_id"]^}" class="button-field ui-btn-orange">详情</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
{^include file=$comfooter_file^}