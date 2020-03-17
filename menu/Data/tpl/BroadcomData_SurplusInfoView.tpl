{^include file=$comheader_file^}
<div class="main-table pb_15">
  <h2>学员剩余价值</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:200px;">学员姓名</th>
        <th style="width:400px;">手机号</th>
        <th style="width:200px;">年级</th>
        <th style="width:200px;">会员类型</th>
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
        <td>{^$student_info["student_level"]^}</td>
        <td>{^$student_info["student_surplus_count"]^}小时</td>
        <td>{^$student_info["student_surplus_amount"]^}元</td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
  <div class="table-line">
    <div class="table-item-b">
      <div class="table-item-name">学员人数总计</div>
      <div class="table-item-value">{^$student_count^}人</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">总计剩余课时</div>
      <div class="table-item-value">{^$total_count^}小时</div>
    </div>
    <div class="table-item-b">
      <div class="table-item-name">总计实付金额</div>
      <div class="table-item-value">{^$total_amount^}元</div>
    </div>
  </div>
</div>
{^include file=$comfooter_file^}
