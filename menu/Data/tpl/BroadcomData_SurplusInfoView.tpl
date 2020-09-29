{^include file=$comheader_file^}
{^if $past_flg^}
<div class="table-line">
  <a href="./?menu=data&act=surplus_info" class="button-field ui-btn-orange"><i class="fa fa-share"></i> 返回当前数据</a>
</div>
<form method="get" action="./">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <div class="table-line">
    <select name="past_date" class="table-text-field" style="width:150px; height:35px; margin-right:10px;">
{^foreach from=$past_date_list key=past_key item=past_item^}
      <option value="{^$past_key^}"{^if $past_key eq $past_date^} selected{^/if^}>{^$past_item^}</option>
{^/foreach^}
    </select>
    <button type="submit" name="past" value="1" class="button-field ui-btn-green"><i class="fa fa-check"></i> 前往</button>
  </div>
</form>
{^else^}
<div class="table-line">
  <a href="./?menu=data&act=surplus_info&past=1" class="button-field ui-btn-purple"><i class="fa fa-reply"></i> 查看过去数据</a>
</div>
{^/if^}
<div class="main-table pb_15">
  <h2>学员剩余价值</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:200px;">学员姓名</th>
        <th style="width:400px;">手机号</th>
        <th style="width:200px;">年级</th>
        <th style="width:300px;">剩余课时</th>
        <th style="width:300px;">剩余实付金额</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$result_data key=student_id item=student_info^}
      <tr>
        <td>{^$student_info["student_name"]^}</td>
        <td>{^$student_info["student_mobile_number"]^}</td>
        <td>{^$student_info["student_grade_name"]^}</td>
        <td>{^$student_info["student_surplus_count"]^}小时</td>
        <td>{^$student_info["student_surplus_amount"]^}元</td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
  <p>总计</p>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员人数</div>
      <div class="table-item-value">{^$student_count^}人</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">剩余课时</div>
      <div class="table-item-value">{^$total_count^}小时</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">实付金额</div>
      <div class="table-item-value">{^$total_amount^}元</div>
    </div>
  </div>
</div>
{^include file=$comfooter_file^}
