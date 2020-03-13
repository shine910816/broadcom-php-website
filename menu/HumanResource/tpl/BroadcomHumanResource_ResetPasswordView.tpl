{^include file=$comheader_file^}
{^include file=$usererror_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="member_id" value="{^$member_id^}" />
  <h1>成员信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">姓名</div>
      <div class="table-item-value">{^$member_info["m_name"]|escape^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">手机号码</div>
      <div class="table-item-value">{^$member_info["m_mobile_number"]|escape^}</div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=human_resource&act=member_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_submit" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 重置<button>
  </div>
</form>
{^include file=$comfooter_file^}
