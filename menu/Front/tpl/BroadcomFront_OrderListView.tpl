{^include file=$comheader_file^}
  <div class="table-line">
{^foreach from=$order_status_list key=order_status_key item=order_status_name^}
    <a href="./?menu=front&act=order_list&order_status={^$order_status_key^}" class="button-field{^if $order_status_key eq $order_status^} ui-btn-orange{^/if^}">{^$order_status_name^}</a>
{^/foreach^}
  </div>
{^if $order_status eq "4"^}
  <div class="table-line">
    <a href="./?menu=front&act=order_list&order_status={^$order_status^}" class="button-field{^if !$order_item_flg^} ui-btn-orange{^/if^}">已退款订单</a>
    <a href="./?menu=front&act=order_list&order_status={^$order_status^}&order_item=1" class="button-field{^if $order_item_flg^} ui-btn-orange{^/if^}">已退款合同</a>
  </div>
{^/if^}
{^if empty($order_list)^}
  <p>无{^$order_status_list[$order_status]^}{^$content_text^}</p>
{^else^}
  <div class="main-table">
    <h2>{^$order_status_list[$order_status]^}{^$content_text^}</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:300px;">{^$content_text^}号</th>
          <th style="width:200px;">学员姓名</th>
          <th style="width:200px;">联系电话</th>
          <th style="width:200px;">年级</th>
          <th style="width:200px;">应付款</th>
          <th style="width:200px;">已付款</th>
          <th style="width:200px;">待付款</th>
          <th style="width:200px;">订单创建日</th>
          <th style="width:200px;">订单审核日</th>
          <th style="width:150px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_list item=order_info^}
        <tr>
          <td><a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}&b={^$back_link^}" class="text-link">{^$order_info["order_number"]|escape^}</a></td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["student_name"]|escape^}{^/if^}</td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["covered_mobile_number"]|escape^}{^/if^}</td>
          <td>{^if isset($student_info_list[$order_info["student_id"]])^}{^$student_info_list[$order_info["student_id"]]["grade_name"]|escape^}{^/if^}</td>
          <td>{^$order_info["order_payable"]|escape^}元</td>
          <td>{^$order_info["order_payment"]|escape^}元</td>
          <td>{^$order_info["order_debt"]|escape^}元</td>
          <td>{^$order_info["insert_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>{^$order_info["order_examine_date"]|date_format:"%Y-%m-%d"^}</td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}&b={^$back_link^}">详情</a>
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
{^include file=$compagina_file^}
{^/if^}
{^include file=$comfooter_file^}