{^include file=$comheader_file^}
{^include file=$usererror_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
{^include file=$member_info_template_file^}
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=member&act=top" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_change" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认修改</button>
  </div>
</form>
{^include file=$comfooter_file^}
