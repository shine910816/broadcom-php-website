{^include file=$comheader_file^}
<div class="main-table">
  <h2>本周校区数据 ({^$week_date_text^})</h2>
  <table class="disp_table">
    <thead>
      <tr>
       <th>签单量</th>
       <th>签单金额</th>
       <th>消课量</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{^$week_achieve_count^}单</td>
        <td>{^$week_achieve_amount^}元</td>
        <td>{^$week_course_count^}小时</td>
      </tr>
    </tbody>
  </table>
</div>
<div class="main-table">
  <h2>本月校区数据 ({^$month_date_text^})</h2>
  <table class="disp_table">
    <thead>
      <tr>
        <th>签单量</th>
        <th>签单金额</th>
        <th>消课量</th>
        <th>消课完成率</th>
        <th>实收金额</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{^$month_achieve_count^}单</td>
        <td>{^$month_achieve_amount^}元</td>
        <td>{^$month_course_count^}小时</td>
        <td>{^$month_course_percent^}</td>
        <td>{^$month_actual_amount^}元</td>
      </tr>
    </tbody>
  </table>
</div>
{^include file=$comfooter_file^}
