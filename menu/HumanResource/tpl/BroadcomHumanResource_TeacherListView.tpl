{^include file=$comheader_file^}
{^if !empty($no_subject_teacher_list)^}
  <div class="main-table">
    <h2>未分配科目教师列表</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:150px;">姓名</th>
          <th style="width:150px;">岗位</th>
          <th style="width:150px;">在职状态</th>
          <th style="width:150px;">小学星级</th>
          <th style="width:150px;">初中星级</th>
          <th style="width:150px;">高中星级</th>
          <th style="width:200px;">教师资格账号</th>
          <th style="width:100px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$no_subject_teacher_list item=teacher_member_id^}
        <tr>
          <td>{^$teacher_info_list[$teacher_member_id]["m_name"]|escape^}</td>
          <td>{^$position_list[$teacher_info_list[$teacher_member_id]["member_position"]]^}</td>
          <td>{^$employed_status_list[$teacher_info_list[$teacher_member_id]["member_employed_status"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_primary_star_level"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_junior_star_level"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_senior_star_level"]]^}</td>
          <td>{^$teacher_info_list[$teacher_member_id]["m_licence_number"]|escape^}</td>
          <td>
{^if $editable_flg^}
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=human_resource&act=teacher_info&member_id={^$teacher_member_id|escape^}">修改信息</a>
            </div>
{^/if^}
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^/if^}
{^foreach from=$subject_list key=subject_id item=subject_name^}
  <div class="table-line"><span class="subject_tip_{^$subject_id^}" /></div>
{^if isset($teacher_list[$subject_id])^}
  <div class="main-table">
    <table class="disp_table">
      <thead>
        <tr>
          <th style="width:150px;">姓名</th>
          <th style="width:150px;">岗位</th>
          <th style="width:150px;">在职状态</th>
          <th style="width:150px;">小学星级</th>
          <th style="width:150px;">初中星级</th>
          <th style="width:150px;">高中星级</th>
          <th style="width:200px;">教师资格账号</th>
          <th style="width:100px;">操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$teacher_list[$subject_id] item=teacher_member_id^}
        <tr>
          <td>{^$teacher_info_list[$teacher_member_id]["m_name"]|escape^}</td>
          <td>{^$position_list[$teacher_info_list[$teacher_member_id]["member_position"]]^}</td>
          <td>{^$employed_status_list[$teacher_info_list[$teacher_member_id]["member_employed_status"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_primary_star_level"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_junior_star_level"]]^}</td>
          <td>{^$star_level_list[$teacher_info_list[$teacher_member_id]["m_senior_star_level"]]^}</td>
          <td>{^$teacher_info_list[$teacher_member_id]["m_licence_number"]|escape^}</td>
          <td>
{^if $editable_flg^}
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=human_resource&act=teacher_info&member_id={^$teacher_member_id|escape^}">修改信息</a>
            </div>
{^/if^}
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^else^}
  <p>未分配{^$subject_name^}教师</p>
{^/if^}
{^/foreach^}
{^include file=$comfooter_file^}
