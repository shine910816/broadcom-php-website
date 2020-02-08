{^include file=$comheader_file^}
<h1>成员列表</h1>
{^if !empty($member_list)^}
  <table class="disp_table">
    <thead>
      <tr>
        <th>工号</th>
        <th>姓名</th>
        <th>登录名</th>
        <th>手机号</th>
        <th>邮箱地址</th>
        <th>校区</th>
        <th>岗位</th>
        <th>职级</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$member_list item=member_item^}
      <tr>
        <td>{^$member_item["member_id"]|escape^}</td>
        <td>{^$member_item["m_name"]|escape^}</td>
        <td>{^$member_item["member_login_name"]|escape^}</td>
        <td>{^$member_item["m_mobile_number"]|escape^}</td>
        <td>{^$member_item["m_mail_address"]|escape^}</td>
        <td>{^$school_list[$member_item["school_id"]]^}</td>
        <td>{^$position_list[$member_item["member_position"]]^}</td>
        <td>{^$position_level_list[$member_item["member_position_level"]]^}</td>
        <td>
{^if $editable_flg^}
          <a href="./?menu={^$current_menu^}&act=member_info&member_id={^$member_item["member_id"]|escape^}" class="button-field ui-btn-b">修改信息</a>
{^/if^}
        </td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
{^/if^}
{^include file=$comfooter_file^}
