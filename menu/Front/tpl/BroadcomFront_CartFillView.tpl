{^include file=$comheader_file^}
{^include file=$usererror_file^}
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
{^if $add_present_flg^}
  <input type="hidden" name="main_item_id" value="{^$main_item_id^}" />
  <h1>主课程信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程名</div>
      <div class="table-item-value">{^$main_item_info["item_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">课程类型</div>
      <div class="table-item-value">{^$item_type_list[$main_item_info["item_type"]]^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">授课方式</div>
      <div class="table-item-value">{^$item_method_list[$main_item_info["item_method"]]^}</div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">年级</div>
      <div class="table-item-value">{^$item_grade_list[$main_item_info["item_grade"]]^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">价格</div>
      <div class="table-item-value">{^$main_item_info["item_price"]|number_format^}{^$item_unit_list[$main_item_info["item_unit"]]^}</div>
    </div>
  </div>
{^/if^}
{^if $scope_out_flg^}
  <p>当前年级无法继续选择课程</p>
{^else^}
{^if empty($item_info_list)^}
  <p>当前没有适用的课程</p>
{^else^}
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="page" value="{^$current_page^}" />
  <input type="hidden" name="student_id" value="{^$student_id^}" />
  <div class="main-table">
    <h2>{^if $add_present_flg^}赠课{^else^}主课{^/if^}课程选择</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>选择</th>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>价格</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$item_info_list item=item_info^}
        <tr>
          <td><label class="button-field ui-btn-check{^if $add_item_id eq $item_info["item_id"]^} ui-btn-orange{^/if^}"><input type="radio" name="add_item_id" value="{^$item_info["item_id"]^}"{^if $add_item_id eq $item_info["item_id"]^} checked{^/if^} />&nbsp;<i class="fa fa-cart-plus"></i>&nbsp;</label></td>
          <td>{^$item_info["item_name"]|escape^}</td>
          <td>{^$item_type_list[$item_info["item_type"]]^}</td>
          <td>{^$item_method_list[$item_info["item_method"]]^}</td>
          <td>{^$item_grade_list[$item_info["item_grade"]]^}</td>
          <td>{^$item_info["item_price"]|number_format^}{^$item_unit_list[$item_info["item_unit"]]^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">购买数量</div>
      <div class="table-item-value"><input type="text" name="item_amount" value="{^$item_amount^}" class="text-field hylight-field" /></div>
    </div>
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=my_leads" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if !empty($item_info_list)^}
    <button type="submit" name="do_add" value="1" class="button-field ui-btn-green"><i class="fa fa-plus"></i> 添加</button>
{^/if^}
{^if !$scope_out_flg^}
    <a href="./?menu=front&act=cart_info&student_id={^$student_id^}" class="button-field ui-btn-purple"><i class="fa fa-shopping-cart"></i> 已选择课程({^$cart_count^})</a>
    <a href="./?menu=front&act=order_create&student_id={^$student_id^}" class="button-field ui-btn-orange"><i class="fa fa-cart-arrow-down"></i> 结算</a>
{^/if^}
  </div>
{^/if^}
</form>
{^include file=$comfooter_file^}