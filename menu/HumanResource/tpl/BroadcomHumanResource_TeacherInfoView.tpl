{^include file=$comheader_file^}
{^include file=$usererror_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="teacher_member_id" value="{^$teacher_member_id^}" />
  <h1>教师信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">姓名</div>
      <div class="table-item-value">{^$teacher_name|escape^}</div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">教师资格证号</div>
      <div class="table-item-value"><input type="text" name="teacher_member_info[m_licence_number]" value="{^$teacher_member_info["m_licence_number"]|escape^}" class="text-field" /></div>
    </div>
    <div class="table-item-b" style="width:66.6%!important;">
      <div class="table-item-name" style="width:12%!important;">科目</div>
      <div class="table-item-value" style="width:84%!important;">
{^foreach from=$subject_list key=subject_key item=subject_name^}
        <label class="button-field ui-btn-check{^if $subject_key|in_array:$teacher_subject_list^} ui-btn-orange{^/if^}">
          <input type="checkbox" name="teacher_subject_list[]" value="{^$subject_key^}"{^if $subject_key|in_array:$teacher_subject_list^} checked{^/if^} />{^$subject_name^}
        </label>
{^/foreach^}
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">小学星级</div>
      <div class="table-item-value">
        <select name="teacher_member_info[m_primary_star_level]" class="text-field">
{^foreach from=$star_level_list key=star_level_key item=star_level_name^}
          <option value="{^$star_level_key^}"{^if $star_level_key eq $teacher_member_info["m_primary_star_level"]^} selected{^/if^}>{^$star_level_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">初中星级</div>
      <div class="table-item-value">
        <select name="teacher_member_info[m_junior_star_level]" class="text-field">
{^foreach from=$star_level_list key=star_level_key item=star_level_name^}
          <option value="{^$star_level_key^}"{^if $star_level_key eq $teacher_member_info["m_junior_star_level"]^} selected{^/if^}>{^$star_level_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">高中星级</div>
      <div class="table-item-value">
        <select name="teacher_member_info[m_senior_star_level]" class="text-field">
{^foreach from=$star_level_list key=star_level_key item=star_level_name^}
          <option value="{^$star_level_key^}"{^if $star_level_key eq $teacher_member_info["m_senior_star_level"]^} selected{^/if^}>{^$star_level_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=human_resource&act=teacher_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_change" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 创建<button>
  </div>
</form>
{^include file=$comfooter_file^}
