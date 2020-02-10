{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <h1>课程信息</h1>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">课程名</div>
      <div class="table-item-value"><input type="text" name="item_info[item_name]" value="{^$item_info["item_name"]|escape^}" class="text-field hylight-field" /></div>
    </div>
  </div>





  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=admin&act=item_list" class="button-field ui-btn-b ui-btn-grey"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_create" value="1" class="button-field ui-btn-b"><i class="fa fa-check"></i> 创建<button>
  </div>
</form>
{^include file=$comfooter_file^}