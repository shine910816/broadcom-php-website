{^include file=$comheader_file^}
<form action="./" method="post">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <div class="main-table pb_15">
    <h2>学员信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">年级</div>
        <div class="table-item-value">{^$student_info["grade_name"]|escape^}</div>
      </div>
    </div>
  </div>
  <div class="main-table pb_15">
    <h2>课程信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^if !empty($order_item_info)^}{^$order_item_info["item_name"]|escape^}{^else^}试听课{^/if^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^if !empty($order_item_info)^}{^$item_method_list[$order_item_info["item_method"]]^}{^else^}一对一{^/if^}</div>
      </div>
    </div>
  </div>
{^if !empty($set_course_info)^}
已经排课的课程
{^/if^}
  <div class="main-table{^if !$create_able_flg^} pb_15{^/if^}">
    <h2>{^$course_type_list[$course_type]^}排课</h2>
{^if !$create_able_flg^}
    <p>无法排课</p>
{^else^}
{^if $course_type eq "4"^}
班课课表
{^else^}
    <table class="disp_table">
      <thead>
        <tr>
          <th>时间</th>
          <th>教室</th>
          <th>教师</th>
          <th>学科</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
{^/if^}
{^/if^}
  </div>
</form>
{^include file=$comfooter_file^}