{^include file=$comheader_file^}
<script type="text/javascript">
$(document).ready(function(){
  $("select#item_method").change(function(){
      if ($(this).val() == "4") {
          $(".period_yes").removeClass("no_disp");
          $(".period_no").addClass("no_disp");
      } else {
          $(".period_yes").addClass("no_disp");
          $(".period_no").removeClass("no_disp");
      }
  });
});
</script>
{^include file=$usererror_file^}
<form action="./" method="post">
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
        <label class="button-field ui-btn-check{^if $subject_key|in_array:$item_info["item_labels"]^} ui-btn-orange{^/if^}">
          <input type="checkbox" name="item_info[item_labels][]" value="{^$subject_key^}"{^if $subject_key|in_array:$item_info["item_labels"]^} checked{^/if^} />{^$subject_name^}
        </label>
{^/foreach^}
      </div>
    </div>
  </div>
{^if isset($user_err_list["item_name"])^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name"></div>
      <div class="table-item-value"><span class="error-hint">{^$user_err_list["item_name"]^}</span></div>
    </div>
  </div>
{^/if^}
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程类型</div>
      <div class="table-item-value">
        <select name="item_info[item_type]" class="text-field">
{^foreach from=$item_type_list key=item_type_key item=item_type_name^}
          <option value="{^$item_type_key^}"{^if $item_type_key eq $item_info["item_type"]^} selected{^/if^}>{^$item_type_name^}</option>
{^/foreach^}
        </select>
      </div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">授课方式</div>
      <div class="table-item-value">
        <select name="item_info[item_method]" class="text-field" id="item_method">
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
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">价格</div>
      <div class="table-item-value">
        <input type="text" name="item_info[item_price]" value="{^$item_info["item_price"]|escape^}" class="text-field auto-select" style="width:46%; text-align:right;" />
        <span class="period_no{^if $item_info["item_method"] eq "4"^} no_disp{^/if^}">元/课时</span>
        <span class="period_yes{^if $item_info["item_method"] neq "4"^} no_disp{^/if^}">元/期</span>
      </div>
    </div>
    <div class="period_yes{^if $item_info["item_method"] neq "4"^} no_disp{^/if^}">
      <div class="table-item-b">
        <div class="table-item-name">每期课节数</div>
        <div class="table-item-value"><input type="text" name="item_info[item_unit_amount]" value="{^$item_info["item_unit_amount"]|escape^}" class="text-field auto-select" /></div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">每节课时长</div>
        <div class="table-item-value">
          <select name="item_info[item_unit_hour]" class="text-field">
            <option value="1"{^if $item_info["item_unit_hour"] eq "1"^} selected{^/if^}>1小时</option>
            <option value="1.5"{^if $item_info["item_unit_hour"] eq "1.5"^} selected{^/if^}>1.5小时</option>
            <option value="2"{^if $item_info["item_unit_hour"] eq "2"^} selected{^/if^}>2小时</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">备注</div>
      <div class="table-item-value"><input type="text" name="item_info[item_desc]" value="{^$item_info["item_desc"]|escape^}" class="text-field" /></div>
    </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=admin&act=item_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 创建<button>
  </div>
</form>
{^include file=$comfooter_file^}