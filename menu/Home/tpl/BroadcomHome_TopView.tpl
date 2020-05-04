{^include file=$comheader_file^}
<script type="text/javascript" src="js/echarts.min.js"></script>
<div class="main-table">
  <h2>校区业绩完成度</h2>
  <div style="width:100%; height:900px;">
    <div style="width:100%; height:450px;" id="amount_chart"></div>
    <div style="width:100%; height:450px;" id="count_chart"></div>
  </div>
  <table class="disp_table">
    <thead>
      <tr>
        <th>日期</th>
        <th>目标订单收入</th>
        <th>实际订单收入</th>
        <th>订单收入完成率</th>
        <th>目标订单数量</th>
        <th>实际订单数量</th>
        <th>订单数量完成率</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>2020年1月</td>
        <td>80,000元</td>
        <td>76,300元</td>
        <td>95.4%</td>
        <td>120单</td>
        <td>117单</td>
        <td>97.5%</td>
      </tr>
      <tr>
        <td>2020年2月</td>
        <td>75,000元</td>
        <td>73,260元</td>
        <td>97.7%</td>
        <td>110单</td>
        <td>98单</td>
        <td>89.1%</td>
      </tr>
      <tr>
        <td>2020年3月</td>
        <td>60,000元</td>
        <td>40,630元</td>
        <td>67.7%</td>
        <td>90单</td>
        <td>67单</td>
        <td>74.4%</td>
      </tr>
      <tr>
        <td>2020年4月</td>
        <td>65,000元</td>
        <td>66,490元</td>
        <td style="color:#F10;">102.3%</td>
        <td>100单</td>
        <td>102单</td>
        <td style="color:#F10;">102%</td>
      </tr>
      <tr>
        <td>2020年5月</td>
        <td>70,000元</td>
        <td>86,480元</td>
        <td style="color:#F10;">123.5%</td>
        <td>100单</td>
        <td>133单</td>
        <td style="color:#F10;">133%</td>
      </tr>
      <tr>
        <td>2020年6月</td>
        <td>80,000元</td>
        <td>0元</td>
        <td>0%</td>
        <td>120单</td>
        <td>0单</td>
        <td>0%</td>
      </tr>
    </tbody>
  </table>
</div>
<script type="text/javascript">
var option1 = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    title: {
        text: '订单收入',
        textStyle: {
            fontWeight: 'normal',
            fontSize: 14
        }
    },
    legend: {
        data: ['目标', '实际', '完成率']
    },
    xAxis: [
        {
            type: 'category',
            data: ['2020年1月', '2020年2月', '2020年3月', '2020年4月', '2020年5月', '2020年6月'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',
            name: '金额',
            axisLabel: {
                formatter: '{value}元'
            }
        },
        {
            type: 'value',
            name: '比率',
            axisLabel: {
                formatter: '{value}%'
            }
        }
    ],
    series: [
        {
            name: '目标',
            type: 'bar',
            data: [80000, 75000, 60000, 65000, 70000, 80000]
        },
        {
            name: '实际',
            type: 'bar',
            data: [76300, 73260, 40630, 66490, 86480, 0]
        },
        {
            name: '完成率',
            type: 'line',
            yAxisIndex: 1,
            data: [95.4, 97.7, 67.7, 102.3, 123.5, 0]
        }
    ]
};
var option2 = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    title: {
        text: '订单数量',
        textStyle: {
            fontWeight: 'normal',
            fontSize: 14
        }
    },
    legend: {
        data: ['目标', '实际', '完成率']
    },
    xAxis: [
        {
            type: 'category',
            data: ['2020年1月', '2020年2月', '2020年3月', '2020年4月', '2020年5月', '2020年6月'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',
            name: '合同数',
            axisLabel: {
                formatter: '{value}单'
            }
        },
        {
            type: 'value',
            name: '比率',
            axisLabel: {
                formatter: '{value}%'
            }
        }
    ],
    series: [
        {
            name: '目标',
            type: 'bar',
            data: [120, 110, 90, 100, 100, 110]
        },
        {
            name: '实际',
            type: 'bar',
            data: [117, 98, 67, 102, 133, 0]
        },
        {
            name: '完成率',
            type: 'line',
            yAxisIndex: 1,
            data: [97.5, 89.1, 74.4, 102, 133, 0]
        }
    ]
};
echarts.init(document.getElementById('amount_chart')).setOption(option1);
echarts.init(document.getElementById('count_chart')).setOption(option2);
</script>
{^include file=$comfooter_file^}
