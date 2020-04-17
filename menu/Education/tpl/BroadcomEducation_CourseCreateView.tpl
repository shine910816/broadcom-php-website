{^include file=$comheader_file^}
<form action="./" method="get">
  <input type="hidden" name="menu" value="{^$current_menu^}" />
  <input type="hidden" name="act" value="{^$current_act^}" />
  <input type="hidden" name="school_id" value="{^$school_id^}" />
  <input type="hidden" name="student_id" value="{^$student_id^}" />
  <div class="main-table pb_15">
    <h2>学员信息</h2>
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">学员姓名</div>
        <div class="table-item-value">{^$student_info["student_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">联系电话</div>
        <div class="table-item-value">{^$student_info["covered_mobile_number"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">年级</div>
        <div class="table-item-value">{^$student_info["student_grade_name"]|escape^}</div>
      </div>
    </div>
  </div>
  <div class="main-table pb_15">
    <h2>课程信息</h2>
    <input type="hidden" name="order_item_id" value="{^$base_course_info["order_item_id"]^}" />
    <input type="hidden" name="item_id" value="{^$base_course_info["item_id"]^}" />
{^if $audition_flg^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">试听类型</div>
        <div class="table-item-value">
{^foreach from=$audition_type_list key=audition_type_key item=audition_type_name^}
          <label class="button-field ui-btn-check{^if $audition_type_key eq $base_course_info["audition_type"]^} ui-btn-orange{^/if^}"><input name="audition_type" value="{^$audition_type_key^}" type="radio"{^if $audition_type_key eq $base_course_info["audition_type"]^} checked{^/if^}>{^$audition_type_name^}</label>
{^/foreach^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">
{^foreach from=$course_type_list key=course_type_key item=course_type_name^}
          <label class="button-field ui-btn-check{^if $course_type_key eq $base_course_info["course_type"]^} ui-btn-orange{^/if^}"><input name="course_type" value="{^$course_type_key^}" type="radio"{^if $course_type_key eq $base_course_info["course_type"]^} checked{^/if^}>{^$course_type_name^}</label>
{^/foreach^}
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">试听剩余</div>
        <div class="table-item-value">{^$student_info["audition_hours"]^}小时</div>
      </div>
    </div>
{^else^}
    <input type="hidden" name="course_type" value="{^$base_course_info["course_type"]^}" />
    <input type="hidden" name="audition_type" value="{^$base_course_info["audition_type"]^}" />
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">课程名</div>
        <div class="table-item-value">{^$order_item_info["item_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">授课方式</div>
        <div class="table-item-value">{^$order_item_info["item_method_name"]|escape^}</div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">合同号</div>
        <div class="table-item-value">{^$order_item_info["contract_number"]|escape^}</div>
      </div>
    </div>
{^/if^}
  </div>


  
</form>
{^include file=$comfooter_file^}