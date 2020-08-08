{^include file=$comheader_file^}
{^if $editable_flg^}
  <div class="table-line">
    <a href="./?menu=human_resource&act=member_info" class="button-field ui-btn-purple"><i class="fa fa-plus"></i> 添加新成员</a>
  </div>
{^/if^}
{^if !empty($member_list)^}
  <div class="main-table">
    <h2>成员列表</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>姓名</th>
          <th>登录名</th>
          <th>手机号</th>
          <th>邮箱地址</th>
          <th>在职状态</th>
          <th>校区</th>
          <th>岗位</th>
          <th>职级</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$member_list item=member_item^}
        <tr>
          <td style="width:150px;">{^$member_item["m_name"]|escape^}</td>
          <td style="width:150px;">{^$member_item["member_login_name"]|escape^}</td>
          <td style="width:300px;">{^$member_item["m_mobile_number"]|escape^}</td>
          <td style="width:300px;">{^$member_item["m_mail_address"]|escape^}</td>
          <td style="width:150px;">{^$school_list[$member_item["school_id"]]^}</td>
          <td style="width:150px;">{^$employed_status_list[$member_item["member_employed_status"]]^}</td>
          <td style="width:150px;">{^$position_list[$member_item["member_position"]]^}</td>
          <td style="width:150px;">{^$position_level_list[$member_item["member_position_level"]]^}</td>
          <td style="width:150px;">
{^if $editable_flg^}
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=human_resource&act=member_info&member_id={^$member_item["member_id"]|escape^}">修改信息</a>
              <a href="./?menu=human_resource&act=change_position&member_id={^$member_item["member_id"]|escape^}">岗位变动</a>
              <a href="./?menu=human_resource&act=reset_password&member_id={^$member_item["member_id"]|escape^}&page={^$current_page^}">重置密码</a>
            </div>
{^/if^}
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
{^include file=$compagina_file^}
  </div>
{^else^}
  <p>暂无数据</p>
{^/if^}
{^include file=$comfooter_file^}
