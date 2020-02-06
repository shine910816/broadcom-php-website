  <h1>个人基本信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">姓名</div>
      <div class="table-item-value"><input type="text" name="member_info[m_name]" value="{^$member_info["m_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">生日</div>
      <div class="table-item-value"><input type="date" name="member_info[m_birthday]" value="{^$member_info["m_birthday"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">性别</div>
      <div class="table-item-value">
        <select name="member_info[m_gender]" class="text-field">
          <option value="1"{^if $member_info["m_gender"] eq "1"^} selected{^/if^}>男</option>
          <option value="0"{^if $member_info["m_gender"] eq "0"^} selected{^/if^}>女</option>
        </select>
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">身份证号</div>
      <div class="table-item-value"><input type="text" name="member_info[m_id_code]" value="{^$member_info["m_id_code"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">手机号</div>
      <div class="table-item-value"><input type="text" name="member_info[m_mobile_number]" value="{^$member_info["m_mobile_number"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">邮箱地址</div>
      <div class="table-item-value"><input type="text" name="member_info[m_mail_address]" value="{^$member_info["m_mail_address"]|escape^}" class="text-field" /></div>
    </div>
  </div>
{^if isset($user_err_list["m_mobile_number"]) or isset($user_err_list["m_mail_address"])^}
  <div class="table-line">
    <div class="table-item-b"></div>
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value">{^if isset($user_err_list["m_mobile_number"])^}<span class="error-hint">{^$user_err_list["m_mobile_number"]^}</span>{^/if^}</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value">{^if isset($user_err_list["m_mail_address"])^}<span class="error-hint">{^$user_err_list["m_mail_address"]^}</span>{^/if^}</div>
    </div>
  </div>
{^/if^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">婚姻状况</div>
      <div class="table-item-value">
        <select name="member_info[m_married_type]" class="text-field">
  {^foreach from=$married_type_list key=married_type_key item=married_type_item^}
          <option value="{^$married_type_key^}"{^if $married_type_key eq $member_info["m_married_type"]^} selected{^/if^}>{^$married_type_item^}</option>
  {^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">现居住地</div>
      <div class="table-item-value"><input type="text" name="member_info[m_address]" value="{^$member_info["m_address"]|escape^}" class="text-field" /></div>
    </div>
  </div>
  <h1>学历信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">毕业院校</div>
      <div class="table-item-value"><input type="text" name="member_info[m_college]" value="{^$member_info["m_college"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">专业</div>
      <div class="table-item-value"><input type="text" name="member_info[m_major]" value="{^$member_info["m_major"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">学历</div>
      <div class="table-item-value">
        <select name="member_info[m_educated]" class="text-field">
  {^foreach from=$educated_list key=educated_key item=educated_item^}
          <option value="{^$educated_key^}"{^if $educated_key eq $member_info["m_educated"]^} selected{^/if^}>{^$educated_item^}</option>
  {^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">入学时间</div>
      <div class="table-item-value"><input type="date" name="member_info[m_college_start_date]" value="{^$member_info["m_college_start_date"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">毕业时间</div>
      <div class="table-item-value"><input type="date" name="member_info[m_college_end_date]" value="{^$member_info["m_college_end_date"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">获取方式</div>
      <div class="table-item-value">
        <select name="member_info[m_educated_type]" class="text-field">
  {^foreach from=$educated_type_list key=educated_type_key item=educated_type_item^}
          <option value="{^$educated_type_key^}"{^if $educated_type_key eq $member_info["m_educated_type"]^} selected{^/if^}>{^$educated_type_item^}</option>
  {^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <h1>紧急联系人</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">姓名</div>
      <div class="table-item-value"><input type="text" name="member_info[m_contact_name]" value="{^$member_info["m_contact_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">关系</div>
      <div class="table-item-value">
        <select name="member_info[m_contact_relationship]" class="text-field">
  {^foreach from=$contact_relationship_list key=contact_relationship_key item=contact_relationship_item^}
          <option value="{^$contact_relationship_key^}"{^if $contact_relationship_key eq $member_info["m_contact_relationship"]^} selected{^/if^}>{^$contact_relationship_item^}</option>
  {^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">手机号</div>
      <div class="table-item-value"><input type="text" name="member_info[m_contact_mobile_number]" value="{^$member_info["m_contact_mobile_number"]|escape^}" class="text-field" /></div>
    </div>
  </div>
