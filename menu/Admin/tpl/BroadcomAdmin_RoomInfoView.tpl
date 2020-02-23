{^include file=$comheader_file^}
<script type="text/javascript">
$(document).ready(function(){
    $("#add_room").click(function(){
        var room_text = '<tr><td><input type="text" name="insert_data[]" class="table-text-field" /></td><td>默认在用</td></tr>';
        $("#room_content").append(room_text);
    });
});
</script>
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="school_id" value="{^$school_id^}" />
  <div class="table-line">
    <button type="button" class="button-field ui-btn-purple" id="add_room"><i class="fa fa-plus"></i> 添加教室</button>
  </div>
  <div class="main-table">
    <h2>{^$school_name^}校区教室管理</h2>
    <table class="disp_table">
      <thead>
        <tr>
          <th>教室名</th>
          <th>使用状态</th>
        </tr>
      </thead>
      <tbody id="room_content">
{^if empty($room_info_list)^}
        <tr>
          <td><input type="text" name="insert_data[]" class="table-text-field" /></td>
          <td>默认在用</td>
        </tr>
{^else^}
{^foreach from=$room_info_list key=room_id item=room_info^}
        <tr>
          <td><input type="text" name="room_info[{^$room_id^}][room_name]" value="{^$room_info["room_name"]^}" class="table-text-field" /></td>
          <td>
            <label class="button-field ui-btn-check{^if $room_info["usable_flg"] eq "1"^} ui-btn-orange{^/if^}"><input type="radio" name="room_info[{^$room_id^}][usable_flg]" value="1"{^if $room_info["usable_flg"] eq "1"^} checked{^/if^} />在用</label>
            <label class="button-field ui-btn-check{^if $room_info["usable_flg"] eq "0"^} ui-btn-orange{^/if^}"><input type="radio" name="room_info[{^$room_id^}][usable_flg]" value="0"{^if $room_info["usable_flg"] eq "0"^} checked{^/if^} />弃用</label>
          </td>
        </tr>
{^/foreach^}
{^/if^}
      </tbody>
    </table>
  </div>
  <div class="table-line">
    <a href="./?menu=admin&act=school_list" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_submit" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认修改</button>
  </div>
</form>
{^include file=$comfooter_file^}