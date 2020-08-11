{^include file=$comheader_file^}
  <div class="main-table pb_15">
    <h2>基本信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">性别</div>
        <div class="table-item-value">{^if $student_info["student_gender"] eq "1"^}男{^else^}女{^/if^}</div>
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
  </div>
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}">
    <h2>课程安排</h2>
{^if empty($course_list)^}
    <p>无已排课程</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:200px;">课程类型</th>
          <th style="width:300px;">课程名</th>
          <th style="width:300px;">时间</th>
          <th style="width:200px;">教师</th>
          <th style="width:200px;">学科</th>
          <th style="width:200px;">状态</th>
          <th style="width:100px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$course_list key=course_id item=course_info^}
        <tr>
          <td>{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}试听课{^else^}{^$course_type_list[$course_info["course_type"]]^}{^/if^}</td>
          <td>{^if $course_info["course_type"] eq "5" or $course_info["course_type"] eq "6"^}{^$course_type_list[$course_info["course_type"]]^}{^else^}{^$item_list[$course_info["item_id"]]["item_name"]^}{^/if^}</td>
          <td>{^$course_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["course_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^$teacher_info[$course_info["teacher_member_id"]]["m_name"]^}</td>
          <td>{^$subject_list[$course_info["subject_id"]]^}</td>
          <td>{^if $course_info["confirm_flg"]^}已{^else^}未{^/if^}消课</td>
          <td><a href="./?menu=education&act=course_confirm{^if $course_info["multi_course_id"]^}&multi_course_id={^$course_info["multi_course_id"]^}{^else^}&course_id={^$course_id^}{^/if^}&b={^$back_link^}" class="button-field ui-btn-orange">详细</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
  <div class="main-table{^if empty($order_item_list)^} pb_15{^/if^}">
    <h2>已购课程</h2>
{^if empty($order_item_list)^}
    <p>无已购课程</p>
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th>合同号</th>
          <th>课程名</th>
          <th>课程类型</th>
          <th>授课方式</th>
          <th>年级</th>
          <th>状态</th>
          <th>课时余量</th>
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
          <td>{^$order_item_status_list[$order_item_data["order_item_status"]]^}</td>
          <td>{^$order_item_data["order_item_remain"]^}</td>
          <td>
{^if $order_item_data["order_item_status"] eq "2"^}
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=education&act=course_create&order_item_id={^$order_item_id|escape^}">课程安排</a>
              <a href="./?menu=education&act=contract_refund&order_item_id={^$order_item_id|escape^}">退款转让</a>
            </div>
{^/if^}
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
  <div class="main-table{^if empty($order_list)^} pb_15{^/if^}">
    <h2>订单信息</h2>
{^if empty($order_list)^}
    <p>无订单信息</p>
{^else^}
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
          <td><a href="./?menu=front&act=order_info&order_id={^$order_info["order_id"]|escape^}&b={^$back_link^}" class="text-link">{^$order_info["order_number"]|escape^}</a></td>
          <td>{^$order_info["order_payable"]|escape^}元</td>
          <td>{^$order_info["order_payment"]|escape^}元</td>
          <td>{^$order_info["order_debt"]|escape^}元</td>
          <td>{^$order_status_list[$order_info["order_status"]]^}</td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=student_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
  </div>
{^include file=$comfooter_file^}