{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <div class="main-table pb_15">
    <h2>制定目标</h2>
      <div class="table-line">
        <div class="table-item-b">
          <div class="table-item-name">目标期间</div>
          <div class="table-item-value">
            <select name="target_date" class="text-field">
{^foreach from=$date_list key=date_key item=date_item^}
              <option value="{^$date_key^}">{^$date_item^}</option>
{^/foreach^}
            </select>
          </div>
        </div>
        <div class="table-item-b">
          <div class="table-item-name">营销目标</div>
          <div class="table-item-value"><input type="text" name="front_target" value="0" class="text-field auto-select" /></div>
        </div>
        <div class="table-item-b">
          <div class="table-item-name">学管目标</div>
          <div class="table-item-value"><input type="text" name="back_target" value="0" class="text-field auto-select" /></div>
        </div>
      </div>
      <div class="table-line">
        <div class="table-item-b">
          <div class="table-item-name">消课目标</div>
          <div class="table-item-value"><input type="text" name="course_target" value="0" class="text-field auto-select" /></div>
        </div>
        <div class="table-item-b">
          <div class="table-item-name">合计目标</div>
          <div class="table-item-value"><input type="text" name="total_target" value="0" class="text-field auto-select" /></div>
        </div>
      </div>
  </div>
  <div class="table-line"></div>
  <div class="table-line">
    <a href="./?menu=data&act=target_info" class="button-field"><i class="fa fa-chevron-left"></i> 返回</a>
    <button type="submit" name="do_submit" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 确认</button>
  </div>
</form>
{^include file=$comfooter_file^}
