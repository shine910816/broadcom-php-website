<h1>个人基本信息</h1>
<div class="table-line">
  <div class="table-item-b">
    <div class="table-item-name">姓名</div>
    <div class="table-item-value">
      <input type="text" name="member_info[m_name]" value="{^$member_info["m_name"]|escape^}" class="text_field" />
    </div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">生日</div>
    <div class="table-item-value">
      <input type="date" name="member_info[m_birthday]" value="{^$member_info["m_birthday"]|escape^}" class="text_field" />
    </div>
  </div>
  <div class="table-item-b">
    <div class="table-item-name">性别</div>
    <div class="table-item-value">
      <select name="member_info[m_gender]" class="text_field">
        <option value="1"{^if $member_info["m_gender"] eq "1"^} selected{^/if^}>男</option>
        <option value="0"{^if $member_info["m_gender"] eq "0"^} selected{^/if^}>女</option>
      </select>
    </div>
  </div>
</div>
