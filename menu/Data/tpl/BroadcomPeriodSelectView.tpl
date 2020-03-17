<div class="main-table pb_15">
  <h2>统计时间区间</h2>
  <form action="./" method="get">
    <input type="hidden" name="menu" value="{^$current_menu^}" />
    <input type="hidden" name="act" value="{^$current_act^}" />
{^assign var=param_text value=""^}
{^if !empty($param_list)^}
{^foreach from=$param_list key=param_key item=param_value^}
    <input type="hidden" name="{^$param_key^}" value="{^$param_value^}" />
{^assign var=param_text value=$param_text|cat:"&"|cat:$param_key|cat:"="|cat:$param_value^}
{^/foreach^}
{^/if^}
    <div class="table-line">
      <a href="./?menu={^$current_menu^}&act={^$current_act^}&period_type=1{^$param_text^}" class="button-field{^if $period_type eq "1"^} ui-btn-orange{^/if^}" style="margin-left:10px;">本周</a>
      <a href="./?menu={^$current_menu^}&act={^$current_act^}&period_type=2{^$param_text^}" class="button-field{^if $period_type eq "2"^} ui-btn-orange{^/if^}">本月</a>
      <a href="./?menu={^$current_menu^}&act={^$current_act^}&period_type=3{^$param_text^}" class="button-field{^if $period_type eq "3"^} ui-btn-orange{^/if^}">上月</a>
      <input type="date" name="start" value="{^$period_start_date|date_format:"%Y-%m-%d"^}" class="table-text-field" style="width:150px; height:35px; margin-right:10px;" />
      <input type="date" name="end" value="{^$period_end_date|date_format:"%Y-%m-%d"^}" class="table-text-field" style="width:150px; height:35px; margin-right:10px;" />
      <button type="submit" name="period_type" value="4" class="button-field{^if $period_type eq "4"^} ui-btn-orange{^/if^}">自定义</button>
    </div>
  </form>
</div>