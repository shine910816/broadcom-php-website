{^include file=$comheader_file^}
{^include file=$usererror_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="member_id" value="{^$member_id^}" />
  <h1>个人基本信息</h1>
  <div class="table-line">
    <div class="table-item-b">
        <div class="table-item-name">姓名</div>
        <div class="table-item-value">{^$member_info["m_name"]^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">手机号</div>
        <div class="table-item-value">{^$member_info["m_mobile_number"]^}</div>
      </div>
    </div>
  <h1>岗位信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">在职状态</div>
      <div class="table-item-value">
        <select name="m[member_employed_status]" class="text-field">
{^foreach from=$employed_status_list key=employed_status_key item=employed_status_item^}
          <option value="{^$employed_status_key^}"{^if $member_info["member_employed_status"] eq $employed_status_key^} selected{^/if^}>{^$employed_status_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">校区</div>
      <div class="table-item-value">
        <select name="m[school_id]" class="text-field">
{^foreach from=$school_list key=school_key item=school_item^}
          <option value="{^$school_key^}"{^if $member_info["school_id"] eq $school_key^} selected{^/if^}>{^$school_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">岗位</div>
      <div class="table-item-value">
        <select name="m[member_position]" class="text-field">
{^foreach from=$position_list key=position_key item=position_item^}
          <option value="{^$position_key^}"{^if $member_info["member_position"] eq $position_key^} selected{^/if^}>{^$position_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">职级</div>
      <div class="table-item-value">
        <select name="m[member_position_level]" class="text-field">
{^foreach from=$position_level_list key=position_level_key item=position_level_item^}
          <option value="{^$position_level_key^}"{^if $member_info["member_position_level"] eq $position_level_key^} selected{^/if^}>{^$position_level_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">备注</div>
      <div class="table-item-value"><input type="text" name="m[member_note]" value="{^$member_info["member_note"]|escape^}" class="text-field" /></div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu={^$current_menu^}&act=member_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_submit" value="1" class="button-field ui-btn-green"{^if !$change_position_able_flg^} title="{^$change_position_disable_msg^}" disabled{^/if^}><i class="fa fa-check"></i> 确认修改<button>
  </div>
</form>
{^include file=$comfooter_file^}
