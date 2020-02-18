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
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_list item=order_info^}
        <tr>
          <td>{^$order_info["order_number"]|escape^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
{^include file=$comfooter_file^}