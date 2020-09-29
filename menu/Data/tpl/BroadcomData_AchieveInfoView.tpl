{^include file=$comheader_file^}
<script type="text/javascript">
$(document).ready(function(){
    $("#school_select, #section_select").change(function(){
        var school_id = $("#school_select").val();
        var section_id = $("#section_select").val();
        var ajax_url = "./?menu={^$current_menu^}&act={^$current_act^}&ajax=1&school=" + school_id + "&section=" + section_id;
        $.get(ajax_url, function(result){
            $("#member_select").empty().html(result);
        });

    });
});
</script>
{^include file=$period_select_file^}
<div class="main-table pb_15">
  <h2>业绩数据</h2>
  <form action="./" method="get">
    <input type="hidden" name="menu" value="{^$current_menu^}" />
    <input type="hidden" name="act" value="{^$current_act^}" />
    <input type="hidden" name="period_type" value="{^$period_type^}" />
{^if $period_type eq "4"^}
    <input type="hidden" name="start" value="{^$period_start_date|date_format:"%Y-%m-%d"^}" />
    <input type="hidden" name="end" value="{^$period_end_date|date_format:"%Y-%m-%d"^}" />
{^/if^}
    <div class="table-line">
      <div class="table-item-b">
        <div class="table-item-name">校区选择</div>
        <div class="table-item-value">
          <select name="school_id" class="text-field" id="school_select">
{^foreach from=$school_list key=school_id item=school_name^}
            <option value="{^$school_id^}"{^if $param_list["school_id"] eq $school_id^} selected{^/if^}>{^$school_name^}</option>
{^/foreach^}
          </select>
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">部门选择</div>
        <div class="table-item-value">
          <select name="section_id" class="text-field" id="section_select">
            <option value="0">全部部门</option>
{^foreach from=$section_list key=section_id item=section_name^}
            <option value="{^$section_id^}"{^if $param_list["section_id"] eq $section_id^} selected{^/if^}>{^$section_name^}</option>
{^/foreach^}
          </select>
        </div>
      </div>
      <div class="table-item-b">
        <div class="table-item-name">员工选择</div>
        <div class="table-item-value">
          <select name="member_id" class="text-field" id="member_select">
            <option value="0">全部员工</option>
{^if isset($member_list[$param_list["section_id"]])^}
{^foreach from=$member_list[$param_list["section_id"]] key=member_id item=member_name^}
            <option value="{^$member_id^}"{^if $param_list["member_id"] eq $member_id^} selected{^/if^}>{^$member_name^}</option>
{^/foreach^}
{^/if^}
          </select>
        </div>
      </div>
    </div>
    <div class="table-line" style="height:40px;">
      <button type="submit" class="button-field ui-btn-green ui-btn-big">确认</button>
    </div>
    <div class="table-line"></div>
  </form>
  <table class="disp_table">
    <thead>
      <tr>
        <th style="width:200px;">业绩类型</th>
        <th style="width:400px;">签单数</th>
        <th style="width:400px;">签单金额</th>
        <th style="width:400px;">打款退单数</th>
        <th style="width:400px;">打款退单金额</th>
        <th style="width:400px;">实收金额</th>
        <th style="width:400px;">减退费实收金额</th>
      </tr>
    </thead>
    <tbody>
{^foreach from=$achieve_type_list key=achieve_type item=achieve_type_name^}
      <tr>
        <td>{^$achieve_type_name^}</td>
        <td>{^$achieve_data[$achieve_type]["order_count"]^}</td>
        <td>{^$achieve_data[$achieve_type]["order_amount"]^}</td>
        <td>{^$achieve_data[$achieve_type]["cancel_order_count"]^}</td>
        <td>{^$achieve_data[$achieve_type]["cancel_order_amount"]^}</td>
        <td>{^$achieve_data[$achieve_type]["total_amount"]^}</td>
        <td>{^$achieve_data[$achieve_type]["calculate_amount"]^}</td>
      </tr>
{^/foreach^}
    </tbody>
  </table>
  <div class="table-line">
    <div class="table-item-a">
      <div class="table-item-name">签单单底/实收单底</div>
      <div class="table-item-value" style="text-align:right;">{^$average_amount^}元/单</div>
    </div>
  </div>
</div>
<div class="main-table">
  <h2>校区消课时长</h2>
  <table class="disp_table">
    <thead>
      <tr>
{^foreach from=$course_type_list item=course_type_name^}
        <th style="width:500px;">{^$course_type_name^}</th>
{^/foreach^}
        <th style="width:500px;">合计</th>
      </tr>
    </thead>
    <tbody>
      <tr>
{^assign var=course_total value=0^}
{^foreach from=$course_data item=course_confirm_amount^}
{^assign var=course_total value=$course_total+$course_confirm_amount^}
        <td>{^$course_confirm_amount^}小时</td>
{^/foreach^}
        <td>{^$course_total^}小时</td>
      </tr>
    </tbody>
  </table>
</div>
{^include file=$comfooter_file^}
