<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<head>
<title>画面权限分配</title>
<link rel="shortcut icon" type="image/x-icon" href="../img/favicon.png" />
<style type="text/css">
* {
  margin:0;
  padding:0;
  border:0;
  font-family:"Microsoft Yahei","Arial","SimSun";
  font-size:14px;
}
body {
  padding:0 5px 15px;
}
div.main-table {
  border:1px #CCC solid;
  border-radius:5px;
  width:auto;
  margin-top:15px;
  overflow:hidden;
}
div.main-table h2 {
  width:auto;
  height:4em;
  text-align:left;
  line-height:4em;
  font-size:1.2em;
  color:#3F4F65;
  padding-left:20px;
  background-image:url("../img/h2-bg.png");
  background-repeat:repeat;
  border-bottom:1px #CCC solid;
}table.disp_table {
  width:100%;
  border-collapse:collapse;
}
table.disp_table thead tr {
  background-color:#E3EAF3;
}
table.disp_table thead tr th {
  width:auto;
  height:35px;
  line-height:35px;
  text-align:left;
  padding:5px;
  color:#55759F;
  font-weight:bold;
}
table.disp_table tbody tr:nth-child(even) {
  background-color:#F9F9F9;
}
table.disp_table tbody tr:hover {
  background-color:#DAE4F2;
}
table.disp_table tbody tr td {
  width:auto;
  height:35px;
  line-height:35px;
  text-align:left;
  padding:5px;
}
.left_border {
  border-left:1px solid #CCC;
}
</style>
</head>
<body>
<div class="main-table">
<h2>画面权限分配</h2>
<table class="disp_table">
  <thead>
    <tr>
      <th style="width:150px;">对应画面</th>
      <th class="left_border" style="width:100px;">逻辑名</th>
      <th class="left_border" style="width:100px;">校长</th>
      <th class="left_border" style="width:100px;">营销主管</th>
      <th class="left_border" style="width:100px;">课程顾问</th>
      <th class="left_border" style="width:100px;">市场专员</th>
      <th class="left_border" style="width:100px;">学管主管</th>
      <th class="left_border" style="width:100px;">学管</th>
      <th class="left_border" style="width:100px;">教学主管</th>
      <th class="left_border" style="width:100px;">教师</th>
      <th class="left_border" style="width:100px;">兼职教师</th>
      <th class="left_border" style="width:100px;">财务人事</th>
    </tr>
  </thead>
  <tbody>
<xsl:for-each select="root/functions/function">
    <tr>
      <td><xsl:value-of select="@name"/></td>
      <td class="left_border"><xsl:value-of select="@menu"/><br/><xsl:value-of select="@act"/></td>
<xsl:for-each select="position">
      <td class="left_border">阅览 <xsl:choose><xsl:when test="@readable=1">✔</xsl:when><xsl:otherwise>✖</xsl:otherwise></xsl:choose><br/>编辑 <xsl:choose><xsl:when test="@editable=1">✔</xsl:when><xsl:otherwise>✖</xsl:otherwise></xsl:choose></td>
</xsl:for-each>
    </tr>
</xsl:for-each>
  </tbody>
</table>
</div>
</body>
</html>
</xsl:template>
</xsl:stylesheet>