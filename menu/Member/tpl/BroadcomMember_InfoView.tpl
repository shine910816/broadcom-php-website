{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^include file=$member_info_template_file^}
  <div class="table-line"></div>
  <div class="table-line"><input type="submit" name="do_change" value="确认修改" class="button_field" /></div>
</form>
{^include file=$comfooter_file^}
