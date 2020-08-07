{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="order_id" value="{^$order_id^}" />
  <h1>订单信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">订单号</div>
      <div class="table-item-value">{^$order_info["order_number"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">订单状态</div>
      <div class="table-item-value">{^$order_status_list[$order_info["order_status"]]^}</div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员姓名</div>
      <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">当前年级</div>
      <div class="table-item-value">{^$student_info["grade_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">会员级别</div>
      <div class="table-item-value">{^$student_info["student_level_name"]|escape^}</div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">合计金额</div>
      <div class="table-item-value">{^$order_info["order_payable"]^}元</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">已付款</div>
      <div class="table-item-value">{^$order_info["order_payment"]^}元</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">应付款</div>
      <div class="table-item-value">{^$order_info["order_debt"]^}元</div>
    </div>
  </div>
{^if $examine_info["order_examine_flg"]^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">审核人</div>
      <div class="table-item-value">{^$examine_info["order_examiner_name"]^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">审核日期</div>
      <div class="table-item-value">{^$examine_info["order_examine_date"]^}</div>
    </div>
  </div>
{^/if^}
  <div class="main-table">
    <h2>订单内课程</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>合同号</th>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>价格</th>
          <th>数量</th>
          <th>优惠额度</th>
          <th>应付款</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_item_info key=order_item_id item=order_item_data^}
        <tr>
          <td>{^$order_item_data["contract_number"]|escape^}</td>
          <td>{^$item_list[$order_item_data["item_id"]]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$item_list[$order_item_data["item_id"]]["item_type"]]^}</td>
          <td>{^$item_method_list[$item_list[$order_item_data["item_id"]]["item_method"]]^}</td>
          <td>{^$item_grade_list[$item_list[$order_item_data["item_id"]]["item_grade"]]^}</td>
          <td>{^$order_item_data["order_item_price"]^}{^$item_unit_list[$item_list[$order_item_data["item_id"]]["item_unit"]]^}</td>
          <td>{^$order_item_data["order_item_amount"]^}</td>
          <td>{^if $order_item_data["order_item_discount_type"] eq "1"^}{^$order_item_data["order_item_discount_amount"]^}元{^elseif $order_item_data["order_item_discount_type"] eq "2"^}{^$order_item_data["order_item_discount_amount"]^}%{^/if^}</td>
          <td>{^$order_item_data["order_item_payable_amount"]^}元</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
  <div class="main-table">
    <h2>账面流水</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>日期</th>
          <th>订单号</th>
          <th>合同号</th>
          <th>金额</th>
          <th>操作人</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$payment_detail key=payment_id item=payment_data^}
        <tr>
          <td style="width:200px;">{^$payment_data["insert_date"]^}</td>
          <td style="width:350px;">{^$payment_data["order_number"]^}</td>
          <td style="width:350px;">{^$payment_data["contract_number"]^}</td>
          <td style="width:100px; text-align:right;{^if $payment_data["red_flg"]^} color:#F60000;{^/if^}">{^$payment_data["payment_amount"]^}</td>
          <td style="width:100px;">{^$payment_data["creater_name"]^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="{^$back_link^}" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if $cancel_able_flg^}
    <button type="submit" name="do_cancel" value="1" class="button-field ui-btn-purple"><i class="fa fa-close"></i> 审核未通过并退款</button>
{^/if^}
{^if $pass_able_flg^}
    <button type="submit" name="do_pass" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 审核通过</button>
{^/if^}
  </div>
</form>
{^include file=$comfooter_file^}