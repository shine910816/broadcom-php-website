{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="student_id" value="{^$student_id^}" />
  <div class="main-table pb_15">
    <h2>基本信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">性别</div>
        <div class="table-item-value">{^if $student_info["student_gender"] eq "1"^}男{^else^}女{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">年级</div>
        <div class="table-item-value">{^$student_info["student_grade"]|escape^}</div>
      </div>
    </div>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">手机号</div>
        <div class="table-item-value">{^$student_info["student_mobile_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">在读学校</div>
        <div class="table-item-value">{^$student_info["student_school_name"]|escape^}</div>
      </div>
    </div>
  </div>
  <div class="table-line"></div>

  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学管选择</div>
      <div class="table-item-value">
        <select name="assign_member_id" class="text-field">
{^if !empty($assign_able_list)^}
{^foreach from=$assign_able_list item=member_info^}
          <option value="{^$member_info["member_id"]^}">{^$position_list[$member_info["member_position"]]^}-{^$member_info["m_name"]^}</option>
{^/foreach^}
{^/if^}
        </select>
      </div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=education&act=my_student_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_assign" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认分配</button>
  </div>
</form>
{^include file=$comfooter_file^}