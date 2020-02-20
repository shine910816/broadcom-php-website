{^include file=$comheader_file^}
  <h1>基本信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员姓名</div>
      <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">性别</div>
      <div class="table-item-value">{^if $student_info["student_gender"] eq "1"^}男{^else^}>女<{^/if^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">年级</div>
      <div class="table-item-value">{^$student_info["student_grade"]|escape^}</div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">手机号</div>
      <div class="table-item-value">{^$student_info["student_mobile_number"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">在读学校</div>
      <div class="table-item-value">{^$student_info["student_school_name"]|escape^}</div>
    </div>
  </div>
  <h1>已购课程</h1>
{^if empty($order_item_list)^}
  <p>无已购课程</p>
{^else^}
  <div class="main-table">
    <table class="disp_table">
      <thead>
        <tr>
          <th>合同号</th>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>数量</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_item_list key=order_item_id item=order_item_data^}
        <tr>
          <td>{^$order_item_data["contract_number"]|escape^}</td>
          <td>{^$item_list[$order_item_data["item_id"]]["item_name"]|escape^}</td>
          <td>{^$item_type_list[$item_list[$order_item_data["item_id"]]["item_type"]]^}</td>
          <td>{^$item_method_list[$item_list[$order_item_data["item_id"]]["item_method"]]^}</td>
          <td>{^$item_grade_list[$item_list[$order_item_data["item_id"]]["item_grade"]]^}</td>
          <td>{^$order_item_data["order_item_amount"]^}</td>
          <td>{^$order_item_status_list[$order_item_data["order_item_status"]]^}</td>
          <td></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
  <h1>订单信息</h1>
{^if empty($order_list)^}
  <p>无订单信息</p>
{^else^}
  <div class="main-table">
    <table class="disp_table">
      <thead>
        <tr>
          <th>订单号</th>
          <th>应付款</th>
          <th>已付款</th>
          <th>待付款</th>
          <th>状态</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$order_list item=order_info^}
        <tr>
          <td><a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}" class="text-link">{^$order_info["order_number"]|escape^}</a></td>
          <td>{^$order_info["order_payable"]|escape^}元</td>
          <td>{^$order_info["order_payment"]|escape^}元</td>
          <td>{^$order_info["order_debt"]|escape^}元</td>
          <td>{^$order_status_list[$order_info["order_status"]]^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}



{^include file=$comfooter_file^}