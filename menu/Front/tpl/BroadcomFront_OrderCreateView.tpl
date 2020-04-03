{^include file=$comheader_file^}
{^include file=$usererror_file^}
<script type="text/javascript">
{^foreach from=$sub_achieve_type_list key=achieve_type item=achieve_item^}
var sub_achieve_type_context_{^$achieve_type^} = '<select name="sub_achieve_type" class="table-text-field">' +
{^foreach from=$achieve_item key=sub_achieve_type item=sub_achieve_name^}
    '<option value="' + "{^$sub_achieve_type^}" + '">' + "{^$sub_achieve_name^}" + '</option>' +
{^/foreach^}
    '</select>';
{^/foreach^}
var audition_teacher_context = '<tr><td><select name="audition_teacher[]" class="table-text-field">' +
{^foreach from=$audition_teacher_list key=t_member_id item=teacher_name^}
    '<option value="' + "{^$t_member_id^}" + '">' + "{^$teacher_name^}" + '</option>' +
{^/foreach^}
    '</select></td></tr>';
var achieve_member_context = '<tr><td><select name="achieve_member[]" class="table-text-field">' +
{^foreach from=$achieve_member_list key=t_member_id item=member_name^}
    '<option value="' + "{^$t_member_id^}" + '"{^if $member_id eq $t_member_id^} selected{^/if^}>' + "{^$member_name^}" + '</option>' +
{^/foreach^}
    '</select></td><td><select name="achieve_ratio[]" class="table-text-field">' +
{^for $k=0 to 9^}
{^assign var=ratio_number value=10*(10-$k)^}
    '<option value="' + "{^$ratio_number^}" + '">' + "{^$ratio_number^}" + '%</option>' +
{^/for^}
    '</select></td></tr>';;
function change_select() {
    var disp_html_text = "";
    switch ($("#achieve_type").val()) {
        case "1":
            disp_html_text = sub_achieve_type_context_1;
            break;
        case "2":
            disp_html_text = sub_achieve_type_context_2;
            break;
        case "3":
            disp_html_text = sub_achieve_type_context_3;
            break;
        default:
            disp_html_text = '<input type="hidden" name="sub_achieve_type" value="0" />';
            break;
    }
    $("#sub_achieve_type_html").empty().html(disp_html_text);
}
function add_achieve_member() {
    $("#achieve_member_html").append(achieve_member_context);
}
$(document).ready(function(){
    change_select();
    add_achieve_member();
    $("#achieve_type").change(function(){
        change_select();
    });
    $("#add_audition_teacher").click(function(){
        $("#audition_teacher_html").append(audition_teacher_context);
    });
    $("#add_achieve_member").click(function(){
        add_achieve_member();
    });
});
</script>
<form action="./" method="post">
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
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="student_id" value="{^$student_id^}" />
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
  <div class="main-table">
    <h2>金额结算</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:500px;">合计金额</th>
          <th style="width:500px;">本次付款</th>
          <th style="width:500px;">业绩类型</th>
          <th style="width:500px;">业绩类型详细</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{^$total_price^}元</td>
          <td><input type="text" name="payment_amount" value="{^$payment_amount^}" class="table-text-field auto-select" /></td>
          <td>
            <select name="achieve_type" class="table-text-field" id="achieve_type">
{^foreach from=$achieve_type_list key=achieve_type item=achieve_name^}
              <option value="{^$achieve_type^}">{^$achieve_name^}</option>
{^/foreach^}
            <select>
          </td>
          <td id="sub_achieve_type_html"></td>
        </tr>
      </tbody>
    </table>
  </div>
{^if !empty($audition_teacher_list)^}
  <div class="table-line">
    <button type="button" class="button-field ui-btn-purple" id="add_audition_teacher"><i class="fa fa-plus"></i> 添加试听教师</button>
  </div>
  <div class="main-table">
    <h2>试听教师</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>教师选择</th>
        </tr>
      </thead>
      <tbody id="audition_teacher_html"></tbody>
    </table>
  </div>
{^/if^}
  <div class="table-line">
    <button type="button" class="button-field ui-btn-purple" id="add_achieve_member"><i class="fa fa-plus"></i> 添加业绩所属人</button>
  </div>
  <div class="main-table">
    <h2>业绩所属人</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>业绩所属人选择</th>
          <th>业绩比例选择</th>
        </tr>
      </thead>
      <tbody id="achieve_member_html"></tbody>
    </table>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=my_leads" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if !empty($cart_list)^}
    <a href="./?menu=front&act=cart_info&student_id={^$student_id^}" class="button-field ui-btn-purple"><i class="fa fa-cart-plus"></i> 调整数量及优惠</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 创建订单</button>
{^else^}
    <a href="./?menu=front&act=cart_fill&student_id={^$student_id^}" class="button-field ui-btn-purple"><i class="fa fa-cart-plus"></i> 添加课程</a>
{^/if^}
  </div>
{^/if^}
</form>
{^include file=$comfooter_file^}