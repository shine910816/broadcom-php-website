{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^if $edit_mode^}
  <input type="hidden" name="member_id" value="{^$member_id^}" />
{^else^}
  <h1>登录信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">登录名</div>
      <div class="table-item-value"><input type="text" name="member_login_name" value="{^$member_login_name|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">职位</div>
      <div class="table-item-value">
        <select name="member_position_level" class="text-field">
{^foreach from=$position_level_list key=position_level_key item=position_level_item^}
          <option value="{^$position_level_key^}"{^if $member_position_level eq $position_level_key^} selected{^/if^}>{^$position_level_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
{^if isset($user_err_list["member_login_name"])^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value"><span class="error-hint">{^$user_err_list["member_login_name"]^}</span></div>
    </div>
  </div>
{^/if^}
{^/if^}
{^include file=$member_info_template_file^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu={^$current_menu^}&act=top" class="button-field ui-btn-b ui-btn-grey">返回</a>
    <input type="submit" name="do_submit" value="{^if $edit_mode^}确认修改{^else^}创建{^/if^}" class="button-field ui-btn-b" />
  </div>
</form>
{^include file=$comfooter_file^}
