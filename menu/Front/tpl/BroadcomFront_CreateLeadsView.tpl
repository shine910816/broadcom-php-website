{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <h1>基本信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员姓名</div>
      <div class="table-item-value"><input type="text" name="student_info[student_name]" value="{^$student_info["student_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">性别</div>
      <div class="table-item-value">
        <select name="student_info[student_gender]" class="text-field">
          <option value="1"{^if $student_info["student_gender"] eq "1"^} selected{^/if^}>男</option>
          <option value="0"{^if $student_info["student_gender"] eq "0"^} selected{^/if^}>女</option>
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">年级</div>
      <div class="table-item-value">
        <select name="student_info[student_entrance_year]" class="text-field">
{^foreach from=$grade_list key=grade_key item=grade_item^}
          <option value="{^$adjust_year - $grade_key^}"{^if $adjust_year - $grade_key eq $student_info["student_entrance_year"]^} selected{^/if^}>{^$grade_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
{^if isset($user_err_list["student_name"])^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value"><span class="error-hint">{^$user_err_list["student_name"]^}</span></div>
    </div>
  </div>
{^/if^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">手机号</div>
      <div class="table-item-value"><input type="text" name="student_info[student_mobile_number]" value="{^$student_info["student_mobile_number"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">一级渠道</div>
      <div class="table-item-value">
        <select name="student_info[media_channel_code]" class="text-field">
{^foreach from=$media_channel_list key=media_channel_key item=media_channel_item^}
          <option value="{^$media_channel_key^}"{^if $media_channel_key eq $student_info["media_channel_code"]^} selected{^/if^}>{^$media_channel_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
{^if isset($user_err_list["student_mobile_number"])^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value"><span class="error-hint">{^$user_err_list["student_mobile_number"]^}</span></div>
    </div>
  </div>
{^/if^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">在读学校</div>
      <div class="table-item-value"><input type="text" name="student_info[student_school_name]" value="{^$student_info["student_school_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">家庭住址</div>
      <div class="table-item-value"><input type="text" name="student_info[student_address]" value="{^$student_info["student_address"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">意向程度</div>
      <div class="table-item-value">
        <select name="student_info[purpose_level]" class="text-field">
{^foreach from=$purpose_level_list key=purpose_level_key item=purpose_level_item^}
          <option value="{^$purpose_level_key^}"{^if $purpose_level_key eq $student_info["purpose_level"]^} selected{^/if^}>{^$purpose_level_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <h1>亲属信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">亲属关系</div>
      <div class="table-item-value">
        <select name="student_info[student_relatives_type]" class="text-field">
{^foreach from=$relatives_type_list key=relatives_type_key item=relatives_type_item^}
          <option value="{^$relatives_type_key^}"{^if $relatives_type_key eq $student_info["student_relatives_type"]^} selected{^/if^}>{^$relatives_type_item^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">家长姓名</div>
      <div class="table-item-value"><input type="text" name="student_info[student_relatives_name]" value="{^$student_info["student_relatives_name"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">家长电话</div>
      <div class="table-item-value"><input type="text" name="student_info[student_relatives_mobile_number]" value="{^$student_info["student_relatives_mobile_number"]|escape^}" class="text-field" /></div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=front&act=my_leads" class="button-field ui-btn-b ui-btn-grey"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-b"><i class="fa fa-check"></i> 保存</button>
  </div>
</form>
{^include file=$comfooter_file^}