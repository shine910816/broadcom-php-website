{^include file=$comheader_file^}
  <h1>出错啦</h1>
  <p>错误代码： {^$err_code^}</p>
  <p>发生时间： {^$err_date|date_format:"%Y-%m-%d %H:%M:%S"^}</p>
  <p>对您造成了不便敬请谅解</p>
  <h1>您可以尝试以下操作避免发生错误</h1>
  <p>■ 点击返回按钮至上一页面重新操作</p>
  <p>■ 请勿擅自修改地址栏及源代码中的内容</p>
  <p>■ 如遇系统繁忙请稍后再试</p>
  <p></p>
  <button class="button-field ui-btn-big" onclick="javascript:history.back()"><i class="fa fa-chevron-left"></i> 返回</button>
{^include file=$comfooter_file^}
