{^include file=$comheader_file^}
  <div class="main-table">
    <h2>校区管理</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>校区名</th>
          <th>校区地址</th>
          <th>电话</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
{^foreach from=$school_info_list key=school_id item=school_name^}
        <tr>
          <td>{^$school_name^}</td>
          <td></td>
          <td></td>
          <td>
            <a class="button-field operate-button"><i class="fa fa-angle-down"></i> 操作</a>
            <div class="operate-option">
              <a href="./?menu=admin&act=room_info&school_id={^$school_id^}" class="text-link">教室管理</a>
            </div>
          </td>
        </tr>
{^/foreach^}
      </tbody>
    </table>
  </div>
{^include file=$comfooter_file^}