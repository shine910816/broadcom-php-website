{^include file=$comheader_file^}
{^include file=$usererror_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="student_id" value="{^$student_id^}" />
  <h1>学员信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员姓名</div>
      <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">当前年级</div>
      <div class="table-item-value">{^$student_info["student_grade"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">会员级别</div>
      <div class="table-item-value">{^$student_info["student_level_name"]|escape^}</div>
    </div>
  </div>
{^if $scope_out_flg^}
  <p>当前年级无法继续选择课程</p>
{^else^}
{^if empty($cart_list)^}
  <p>还未选择课程</p>
{^else^}
  <div class="main-table">
    <h2>已选择课程</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>价格</th>
          <th>数量</th>
          <th>优惠额度</th>
          <th>应支付</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$cart_list key=main_item_id item=main_item_info^}
        <tr>
          <td>{^$cart_item_info[$main_item_id]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$cart_item_info[$main_item_id]["item_type"]]^}</td>
          <td>{^$item_method_list[$cart_item_info[$main_item_id]["item_method"]]^}</td>
          <td>{^$item_grade_list[$cart_item_info[$main_item_id]["item_grade"]]^}</td>
          <td>{^$cart_item_info[$main_item_id]["item_price"]|number_format^}{^$item_unit_list[$cart_item_info[$main_item_id]["item_unit"]]^}</td>
          <td>{^$main_item_info["amount"]^}</td>
          <td>{^if $cart_item_info[$main_item_id]["item_discount_type"] eq "1"^}{^$cart_item_info[$main_item_id]["item_discount_amount"]^}元{^elseif $cart_item_info[$main_item_id]["item_discount_type"] eq "2"^}{^$cart_item_info[$main_item_id]["item_discount_amount"]^}%{^/if^}</td>
          <td>{^$payable_price_list[$main_item_id]^}元</td>
        </tr>
{^if isset($main_item_info["present"])^}
{^foreach from=$main_item_info["present"] key=present_item_id item=present_item_amount^}
        <tr>
          <td>&nbsp;-&nbsp;{^$cart_item_info[$present_item_id]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$cart_item_info[$present_item_id]["item_type"]]^}</td>
          <td>{^$item_method_list[$cart_item_info[$present_item_id]["item_method"]]^}</td>
          <td>{^$item_grade_list[$cart_item_info[$present_item_id]["item_grade"]]^}</td>
          <td>{^$cart_item_info[$present_item_id]["item_price"]|number_format^}{^$item_unit_list[$cart_item_info[$present_item_id]["item_unit"]]^}</td>
          <td>{^$present_item_amount^}</td>
          <td></td>
          <td>{^$payable_price_list[$present_item_id]^}元</td>
        </tr>
{^/foreach^}
{^/if^}
{^/foreach^}
      </tbody>
    </table>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">应付款</div>
      <div class="table-item-value">{^$total_price^}元</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">已付款</div>
      <div class="table-item-value"><input type="text" name="payment_amount" value="{^$payment_amount^}" class="text-field hylight-field" /></div>
    </div>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=my_leads" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if !empty($cart_list)^}
    <a href="./?menu=front&act=cart_info&student_id={^$student_id^}" class="button-field ui-btn-purple"><i class="fa fa-cart-plus"></i> 调整数量及优惠</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 创建订单</button>
{^/if^}
  </div>
{^/if^}
</form>
{^include file=$comfooter_file^}