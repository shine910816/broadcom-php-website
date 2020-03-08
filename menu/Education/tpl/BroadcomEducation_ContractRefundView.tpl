{^include file=$comheader_file^}
  <div class="main-table pb_15">
    <h2>基本信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">手机号</div>
        <div class="table-item-value">{^$student_info["student_mobile_number"]|escape^}</div>
      </div>
    </div>
  </div>
  <div class="main-table pb_15">
    <h2>课程信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">合同号</div>
        <div class="table-item-value">{^$order_item_info["contract_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^$item_list[$order_item_info["item_id"]]["item_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程类型</div>
        <div class="table-item-value">{^$item_type_list[$item_list[$order_item_info["item_id"]]["item_type"]]^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^$item_method_list[$item_list[$order_item_info["item_id"]]["item_method"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">状态</div>
        <div class="table-item-value">{^$order_item_status_list[$order_item_info["order_item_status"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课时余量</div>
        <div class="table-item-value">{^$order_item_info["order_item_remain"]^}</div>
      </div>
    </div>
  </div>
  <div class="table-line"></div>
{^if empty($refund_info)^}
  <form action="./" method="post">
    <input type="hidden" name="menu" value="{^$current_menu^}" />
    <input type="hidden" name="act" value="{^$current_act^}" />
    <input type="hidden" name="order_item_id" value="{^$order_item_id^}" />
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">操作类型</div>
        <div class="table-item-value">
          <select name="refund_type" class="text-field">
            <option value="1"{^if !$allow_refund_flg^} disabled{^/if^}>退款</option>
            <option value="2"{^if !$allow_transfer_flg^} disabled{^/if^}>转让</option>
          </select>
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">退款比例</div>
        <div class="table-item-value">
          <select name="refund_precent" class="text-field"{^if !$allow_refund_flg^} disabled{^/if^}>
            <option value="1">全额</option>
            <option value="0.95">95%</option>
            <option value="0.9">90%</option>
            <option value="0.85">85%</option>
            <option value="0.8">80%</option>
          </select>
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">转让对象</div>
        <div class="table-item-value">
          <select name="oppo_student_id" class="text-field"{^if !$allow_transfer_flg^} disabled{^/if^}>
{^if !empty($student_list)^}
{^foreach from=$student_list item=student_item^}
            <option value="{^$student_item["student_id"]^}">{^$student_item["student_name"]^} ({^$student_item["student_mobile_number"]^})</option>
{^/foreach^}
{^/if^}
          </select>
        </div>
      </div>
    </div>
{^else^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">操作类型</div>
        <div class="table-item-value">{^if $refund_info["refund_type"] eq "1"^}退款{^else^}转让{^/if^}</div>
      </div>
{^if $refund_info["refund_type"] eq "1"^}
      <div class="table-item-b">
        <div class="table-item-name">退款比例</div>
        <div class="table-item-value">{^if $refund_info["refund_precent"] eq "1"^}全额{^else^}{^$refund_info["refund_precent"] * 100^}%{^/if^}</div>
      </div>
{^else^}
      <div class="table-item-b">
        <div class="table-item-name">转让对象</div>
        <div class="table-item-value">{^$student_list[$refund_info["oppo_student_id"]]["student_name"]^}</div>
      </div>
{^/if^}
      <div class="table-item-b">
        <div class="table-item-name">操作状态</div>
        <div class="table-item-value">待审核</div>
      </div>
    </div>
{^/if^}
    <div class="table-line"></div>
    <div class="table-line">
      <a href="./?menu=education&act=student_info&student_id={^$student_id^}" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
{^if $allow_refund_flg or $allow_transfer_flg^}
      <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认</button>
{^/if^}
    </div>
  </form>


{^include file=$comfooter_file^}