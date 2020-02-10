{^if $user_err_flg^}
  <div class="main-table error-table">
    <h2>请参考以下提示，修改已填写的内容</h2>
{^foreach from=$user_err_list item=user_err_item^}
    <p>■ {^$user_err_item^}</p>
{^/foreach^}
  </div>
{^/if^}