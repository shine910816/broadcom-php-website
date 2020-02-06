{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <div class="table-line"></div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">旧密码</div>
      <div class="table-item-value"><input type="password" name="password[old]" class="text-field" /></div>
    </div>
{^if isset($user_err_list["old"])^}
    <div class="table-item-b"><span class="error-hint">{^$user_err_list["old"]^}</span></div>
{^/if^}
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">新密码</div>
      <div class="table-item-value"><input type="password" name="password[new]" class="text-field" /></div>
    </div>
{^if isset($user_err_list["new"])^}
    <div class="table-item-b"><span class="error-hint">{^$user_err_list["new"]^}</span></div>
{^/if^}
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">确认密码</div>
      <div class="table-item-value"><input type="password" name="password[cnf]" class="text-field" /></div>
    </div>
{^if isset($user_err_list["cnf"])^}
    <div class="table-item-b"><span class="error-hint">{^$user_err_list["cnf"]^}</span></div>
{^/if^}
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=member&act=top" class="button-field ui-btn-b ui-btn-grey">返回</a>
    <input type="submit" name="do_change" value="确认修改" class="button-field ui-btn-b" />
  </div>
</form>
{^include file=$comfooter_file^}
