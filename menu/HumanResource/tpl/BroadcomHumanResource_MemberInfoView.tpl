{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <h1>登录信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">登录名</div>
      <div class="table-item-value"><input type="text" name="login_info[m_name]" value="{^$member_info["m_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">职位</div>
      <div class="table-item-value">
        <select name="login_info[m_gender]" class="text-field">
          <option value="1"{^if $member_info["m_gender"] eq "1"^} selected{^/if^}>男</option>
          <option value="0"{^if $member_info["m_gender"] eq "0"^} selected{^/if^}>女</option>
        </select>
      </div>
    </div>
  </div>
{^include file=$member_info_template_file^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu={^$current_menu^}&act=top" class="button-field ui-btn-b ui-btn-grey">返回</a>
    <input type="submit" name="do_submit" value="确认修改" class="button-field ui-btn-b" />
  </div>
</form>
{^include file=$comfooter_file^}
