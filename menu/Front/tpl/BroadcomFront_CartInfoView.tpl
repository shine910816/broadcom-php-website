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
          <th>优惠方式</th>
          <th>优惠额度</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$cart_list key=main_item_id item=main_item_info^}
        <tr>
          <td>{^$cart_item_info[$main_item_id]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$cart_item_info[$main_item_id]["item_type"]]^}</td>
          <td>{^$item_method_list[$cart_item_info[$main_item_id]["item_method"]]^}</td>
          <td>{^$item_grade_list[$cart_item_info[$main_item_id]["item_grade"]]^}</td>
          <td>{^$cart_item_info[$main_item_id]["item_price"]|escape^}{^$item_unit_list[$cart_item_info[$main_item_id]["item_unit"]]^}</td>
          <td><input type="text" name="item_amount[{^$main_item_id^}]" value="{^$main_item_info["amount"]^}" class="table-text-field" /></td>
          <td>{^$item_discount_type_list[$cart_item_info[$main_item_id]["item_discount_type"]]^}</td>
          <td><input type="text" name="item_discount_amount[{^$main_item_id^}]" value="{^$main_item_info["amount"]^}" class="table-text-field" /></td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=front&act=add_item&student_id={^$student_id^}&main_item_id={^$main_item_id^}">添加赠课</a>
              <a href="./?menu=front&act=cart_info&student_id={^$student_id^}&delete_item_id={^$main_item_id^}">删除</a>
            </div>
          </td>
        </tr>
{^if isset($main_item_info["present"])^}
{^foreach from=$main_item_info["present"] key=present_item_id item=present_item_amount^}
        <tr>
          <td>&nbsp;-&nbsp;{^$cart_item_info[$present_item_id]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$cart_item_info[$present_item_id]["item_type"]]^}</td>
          <td>{^$item_method_list[$cart_item_info[$present_item_id]["item_method"]]^}</td>
          <td>{^$item_grade_list[$cart_item_info[$present_item_id]["item_grade"]]^}</td>
          <td>{^$cart_item_info[$present_item_id]["item_price"]|escape^}{^$item_unit_list[$cart_item_info[$present_item_id]["item_unit"]]^}</td>
          <td><input type="text" name="item_amount[{^$present_item_id^}]" value="{^$present_item_amount^}" class="table-text-field" /></td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=front&act=cart_info&student_id={^$student_id^}&delete_item_id={^$present_item_id^}">删除</a>
            </div>
          </td>
        </tr>
{^/foreach^}
{^/if^}
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=my_leads" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if !empty($cart_list)^}
    <button type="submit" name="do_change" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认数量修改</button>
{^/if^}
    <a href="./?menu=front&act=add_item&student_id={^$student_id^}" class="button-field ui-btn-purple"><i class="fa fa-cart-plus"></i> 继续添加课程</a>
    <a href="./?menu=front&act=create_order&student_id={^$student_id^}" class="button-field ui-btn-orange"><i class="fa fa-cart-arrow-down"></i> 结算</a>
  </div>
{^/if^}
</form>
{^include file=$comfooter_file^}