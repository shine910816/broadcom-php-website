{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="order_item_id" value="{^$order_item_id^}" />
  <div class="main-table pb_15">
    <h2>合同退转审核</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$refund_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">合同号</div>
        <div class="table-item-value">{^$refund_info["contract_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课时余量</div>
        <div class="table-item-value">{^$refund_info["refund_amount"]^}小时</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^$refund_info["item_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">课程类型</div>
        <div class="table-item-value">{^$item_type_list[$refund_info["item_type"]]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^$item_method_list[$refund_info["item_method"]]^}</div>
      </div>
    </div>
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
        <div class="table-item-value">{^$oppo_student_name^}</div>
      </div>
{^/if^}
      <div class="table-item-b">
        <div class="table-item-name">操作状态</div>
        <div class="table-item-value">待审核</div>
      </div>
    </div>
{^if $refund_info["refund_type"] eq "1"^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">退款额</div>
        <div class="table-item-value">{^$refund_info["refund_payment_amount"]|number_format^}元</div>
      </div>
    </div>
{^/if^}
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=refund_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_cancel" value="1" class="button-field ui-btn-purple"><i class="fa fa-close"></i> 取消</button>
    <button type="submit" name="do_pass" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认</button>
  </div>
</form>
{^include file=$comfooter_file^}