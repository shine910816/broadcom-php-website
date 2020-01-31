{^if $navigation_flg^}
<div class="bread-crumbs-bar">
  <ul>
    <li><a href="./">首页</a></li>
{^foreach from=$disp_nav_list item=disp_nav_item^}
{^if $disp_nav_item^}
    <li class="nav_item">{^$disp_nav_item^}</li>
{^/if^}
{^/foreach^}
  </ul>
</div>
{^/if^}