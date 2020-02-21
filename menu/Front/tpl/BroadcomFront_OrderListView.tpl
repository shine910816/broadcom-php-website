{^include file=$comheader_file^}
  <div class="table-line">
{^foreach from=$order_status_list key=order_status_key item=order_status_name^}
    <a href="./?menu=front&act=order_list&order_status={^$order_status_key^}" class="button-field{^if $order_status_key eq $order_status^} ui-btn-orange{^/if^}">{^$order_status_name^}</a>
{^/foreach^}
  </div>
{^if empty($order_list)^}
  <p>无{^$order_status_list[$order_status]^}订单</p>
{^else^}
  <div class="main-table">
    <h2>{^$order_status_list[$order_status]^}订单</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>订单号</th>
          <th>学员姓名</th>
          <th>联系电话</th>
          <th>年级</th>
          <th>应付款</th>
          <th>已付款</th>
          <th>待付款</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_list item=order_info^}
        <tr>
          <td><a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}&b={^$back_link^}" class="text-link">{^$order_info["order_number"]|escape^}</a></td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["student_name"]|escape^}{^/if^}</td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["student_mobile_number"]|escape^}{^/if^}</td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["grade_name"]|escape^}{^/if^}</td>
          <td>{^$order_info["order_payable"]|escape^}元</td>
          <td>{^$order_info["order_payment"]|escape^}元</td>
          <td>{^$order_info["order_debt"]|escape^}元</td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}">详情</a>
{^if $order_status eq "1"^}
              <a href="./?menu=front&act=order_payment&order_id={^$order_info["order_id"]|escape^}">付款</a>
{^/if^}
            </div>
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
{^include file=$comfooter_file^}