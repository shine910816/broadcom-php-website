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
        <div class="table-item-value">{^$student_info["covered_mobile_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">在读学校</div>
        <div class="table-item-value">{^$student_info["student_school_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">受理人</div>
        <div class="table-item-value">{^$student_info["assign_member_name"]|escape^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">剩余试听</div>
        <div class="table-item-value">{^$student_info["audition_hours"]|escape^}小时</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">联系人</div>
        <div class="table-item-value">{^$student_info["relatives_info"]|escape^}</div>
      </div>
    </div>
    <div class="table-line">
      <a href="./?menu=education&act=student_edit&student_id={^$student_info["student_id"]|escape^}" class="button-field ui-btn-purple" style="margin-left:10px;"><i class="fa fa-pencil"></i> 修改信息</a>
    </div>
  </div>
  <div class="main-table{^if empty($course_list)^} pb_15{^/if^}" id="course_filter">
    <h2>课程安排</h2>
    <form action="./" method="get">
      <input type="hidden" name="menu" value="{^$current_menu^}" />
      <input type="hidden" name="act" value="{^$current_act^}" />
      <input type="hidden" name="student_id" value="{^$student_id^}" />
      <div class="table-line">
        <div class="table-item-b">
          <div class="table-item-name">消课状态</div>
          <div class="table-item-value">
            <label class="button-field ui-btn-check{^if $confirm_flg eq "0"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="0"{^if $confirm_flg eq "0"^} checked{^/if^} />未消课</label>
            <label class="button-field ui-btn-check{^if $confirm_flg eq "1"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="1"{^if $confirm_flg eq "1"^} checked{^/if^} />已消课</label>
            <label class="button-field ui-btn-check{^if $confirm_flg eq "2"^} ui-btn-orange{^/if^}"><input type="radio" name="confirm_flg" value="2"{^if $confirm_flg eq "2"^} checked{^/if^} />全部</label>
          </div>
        </div>
        <div class="table-item-b">
          <div class="table-item-name">学科</div>
          <div class="table-item-value">
            <select name="subject_id" class="text-field">
              <option value="10">全部学科</option>
{^foreach from=$subject_list key=subject_key item=subject_name^}
              <option value="{^$subject_key^}"{^if $subject_key eq $subject_id^} selected{^/if^}>{^$subject_name^}</option>
{^/foreach^}
            </select>
          </div>
        </div>
      </div>
      <div class="table-line">
        <button type="submit" class="button-field ui-btn-green ui-btn-big" style="margin-left:10px;"><i class="fa fa-check"></i> 查询</button>
      </div>
    </form>
    <div class="table-line"></div>
{^if empty($course_list)^}
    <p style="text-align:center;">无已排课程</p>
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
          <td>{^if $course_info["audition_type"]^}试听课{^else^}{^$course_type_list[$course_info["course_type"]]^}{^/if^}</td>
          <td>{^if $course_info["audition_type"]^}{^$course_type_list[$course_info["course_type"]]^}{^else^}{^$item_list[$course_info["item_id"]]["item_name"]^}{^/if^}</td>
          <td>{^$course_info["course_start_date"]|date_format:"%Y-%m-%d %H:%M"^}~{^$course_info["course_expire_date"]|date_format:"%H:%M"^}</td>
          <td>{^if isset($teacher_info[$course_info["teacher_member_id"]])^}{^$teacher_info[$course_info["teacher_member_id"]]["m_name"]^}{^/if^}</td>
          <td>{^$subject_list[$course_info["subject_id"]]^}</td>
          <td>{^if $course_info["confirm_flg"]^}已{^else^}未{^/if^}消课</td>
          <td><a href="./?menu=education&act=course_confirm{^if $course_info["multi_course_id"]^}&multi_course_id={^$course_info["multi_course_id"]^}{^else^}&course_id={^$course_id^}{^/if^}&b={^$back_link^}" class="button-field ui-btn-orange">详细</a></td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^/if^}
  </div>
{^include file=$compagina_file^}
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
          <th>已排未消课时</th>
          <th>已消课时</th>
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
          <td>{^$order_item_data["wait_confirm"]^}</td>
          <td>{^$order_item_data["order_item_confirm"]^}</td>
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
{^if !empty($history_list)^}
  <div class="main-table pb_15">
    <h2>修改历史记录</h2>
{^foreach from=$history_list key=his_key item=history_item^}
    <p style="color:#000;">　{^$history_item["created_name"]^} {^$history_item["date_passed"]^} 修改了{^$history_item["history_count"]^}项信息 ({^$his_key^})</p>
{^foreach from=$history_item["history_detail"] item=his_detail_item^}
    <p>　　{^if $his_detail_item["old"]^}修改{^else^}添加{^/if^} {^$his_detail_item["name"]^} {^$his_detail_item["new"]^}{^if $his_detail_item["old"]^} (原为 {^$his_detail_item["old"]^}){^/if^}</p>
{^/foreach^}
{^/foreach^}
  </div>
{^/if^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=student_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
  </div>
{^include file=$comfooter_file^}