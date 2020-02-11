{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <h1>课程信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程名</div>
      <div class="table-item-value"><input type="text" name="item_info[item_name]" value="{^$item_info["item_name"]|escape^}" class="text-field hylight-field" /></div>
    </div>
    <div class="table-item-b" style="width:66.6%!important;">
      <div class="table-item-name" style="width:12%!important;">科目</div>
      <div class="table-item-value" style="width:84%!important;">
{^foreach from=$subject_list key=subject_key item=subject_name^}
        <label>
          <input type="checkbox" name="item_info[item_labels][]" value="{^$subject_key^}"{^if $subject_key|in_array:$item_info["item_labels"]^} checked{^/if^} />
          <span>{^$subject_name^}</span>
        </label>
{^/foreach^}
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程类型</div>
      <div class="table-item-value">
        <select name="item_info[item_type]" class="text-field hylight-field">
{^foreach from=$item_type_list key=item_type_key item=item_type_name^}
          <option value="{^$item_type_key^}"{^if $item_type_key eq $item_info["item_type"]^} selected{^/if^}>{^$item_type_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">授课方式</div>
      <div class="table-item-value">
        <select name="item_info[item_method]" class="text-field hylight-field">
{^foreach from=$item_method_list key=item_method_key item=item_method_name^}
          <option value="{^$item_method_key^}"{^if $item_method_key eq $item_info["item_method"]^} selected{^/if^}>{^$item_method_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">年级</div>
      <div class="table-item-value">
        <select name="item_info[item_grade]" class="text-field">
{^foreach from=$item_grade_list key=item_grade_key item=item_grade_name^}
          <option value="{^$item_grade_key^}"{^if $item_grade_key eq $item_info["item_grade"]^} selected{^/if^}>{^$item_grade_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
  </div>





  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=admin&act=item_list" class="button-field ui-btn-b ui-btn-grey"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-b"><i class="fa fa-check"></i> 创建<button>
  </div>
</form>
{^include file=$comfooter_file^}