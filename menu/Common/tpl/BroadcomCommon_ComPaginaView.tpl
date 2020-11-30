{^if $max_page gt 1^}
    <div class="page-nav-bar">
      <a href="{^$url_page^}&page=1{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field"><i class="fa fa-angle-double-left"></i> 首页</a>
      <a href="{^$url_page^}&page={^if $current_page gt 1^}{^$current_page-1^}{^else^}1{^/if^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field"><i class="fa fa-angle-left"></i> 上一页</a>
{^if $max_page lt 10^}
{^for $page=1 to $max_page^}
      <a href="{^$url_page^}&page={^$page^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field{^if $page eq $current_page^} ui-btn-orange{^/if^}">{^$page^}</a>
{^/for^}
{^else^}
{^if $current_page lt 6^}
{^for $page=1 to 9^}
      <a href="{^$url_page^}&page={^$page^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field{^if $page eq $current_page^} ui-btn-orange{^/if^}">{^$page^}</a>
{^/for^}
{^elseif $current_page gt $max_page-5^}
{^for $page=$max_page-8 to $max_page^}
      <a href="{^$url_page^}&page={^$page^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field{^if $page eq $current_page^} ui-btn-orange{^/if^}">{^$page^}</a>
{^/for^}
{^else^}
{^for $page=$current_page-4 to $current_page+4^}
      <a href="{^$url_page^}&page={^$page^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field{^if $page eq $current_page^} ui-btn-orange{^/if^}">{^$page^}</a>
{^/for^}
{^/if^}
{^/if^}
      <a href="{^$url_page^}&page={^if $current_page lt $max_page^}{^$current_page+1^}{^else^}{^$max_page^}{^/if^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field">下一页 <i class="fa fa-angle-right"></i></a>
      <a href="{^$url_page^}&page={^$max_page^}{^if isset($archor_id)^}#{^$archor_id^}{^/if^}" class="button-field">尾页 <i class="fa fa-angle-double-right"></i></a>
    </div>
{^/if^}
