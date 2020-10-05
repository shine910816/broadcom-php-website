{^include file=$comheader_file^}
{^if empty($user_list)^}
  <p>无</p>
{^else^}
  <div class="main-table">
    <h2>抽奖用户管理</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>号码</th>
          <th>姓名</th>
          <th>手机号码</th>
          <th>抽奖项</th>
          <th>权级</th>
          <th>有效期</th>
          <th>中奖</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$user_list item=user_info^}
        <tr>
          <td>{^$user_info["u_id"]^}</td>
          <td>{^$user_info["u_name"]^}</td>
          <td>{^$user_info["u_mobile"]^}</td>
          <td>{^$user_info["l_name"]^}</td>
          <td>{^$user_info["u_level"]^}</td>
          <td>{^$user_info["l_period"]^}</td>
          <td>{^if $user_info["l_drawn_flg"]^}{^$user_info["l_drawn_date"]^}{^else^}未中奖{^/if^}</td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=admin&act=lottery_user_list&change={^$user_info["u_id"]^}">权级变更</a>
              <a href="./?menu=admin&act=lottery_user_list&reset={^$user_info["u_id"]^}">重置</a>
            </div>
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
{^include file=$comfooter_file^}