{^include file=$comheader_file^}
<h1>个人基本信息</h1>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">姓名</div>
    <div class="table-item-value">{^$member_info["m_name"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">生日</div>
    <div class="table-item-value">{^$member_info["m_birthday"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">性别</div>
    <div class="table-item-value">{^if $member_info["m_gender"] eq "1"^}男{^else^}女{^/if^}</div>
  </div>
</div>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">手机号</div>
    <div class="table-item-value">{^$member_info["m_mobile_number"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">邮箱地址</div>
    <div class="table-item-value">{^$member_info["m_mail_address"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">身份证号</div>
    <div class="table-item-value">{^$member_info["m_id_code"]|escape^}</div>
  </div>
</div>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">婚姻状况</div>
    <div class="table-item-value">{^$married_type_list[$member_info["m_married_type"]]^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">现居住地</div>
    <div class="table-item-value">{^$member_info["m_address"]|escape^}</div>
  </div>
</div>
<h1>学历信息</h1>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">毕业院校</div>
    <div class="table-item-value">{^$member_info["m_college"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">专业</div>
    <div class="table-item-value">{^$member_info["m_major"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">学历</div>
    <div class="table-item-value">{^$educated_list[$member_info["m_educated"]]^}</div>
  </div>
</div>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">入学时间</div>
    <div class="table-item-value">{^$member_info["m_college_start_date"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">毕业时间</div>
    <div class="table-item-value">{^$member_info["m_college_end_date"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">获取方式</div>
    <div class="table-item-value">{^$educated_type_list[$member_info["m_educated_type"]]^}</div>
  </div>
</div>
<h1>紧急联系人</h1>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">姓名</div>
    <div class="table-item-value">{^$member_info["m_contact_name"]|escape^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">关系</div>
    <div class="table-item-value">{^$contact_relationship_list[$member_info["m_contact_relationship"]]^}</div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">手机号</div>
    <div class="table-item-value">{^$member_info["m_contact_mobile_number"]|escape^}</div>
  </div>
</div>
{^include file=$comfooter_file^}
