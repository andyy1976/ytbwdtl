<?php
date_default_timezone_set("PRC");
$nowtime = time();
$rq = date("Y-m-d", $nowtime);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
        <meta name="generator" content="FFKJ.Net" />
        <link rev="MADE" href="mailto:FFKJ@FFKJ.Net">
        <title>在线--后台</title>
        <link rel="stylesheet" type="text/css" href="../Skins/Admin_Style.Css" />
        <script language="JavaScript" src="muban/mydate.js"></script>
    </head>
    <body>
        <form action="SendAccountBalanaceQuery.php" method="post">
        开始时间:<input type="text" name="date" onfocus="MyCalendar.SetDate(this)" value="<?php echo $rq; ?>">
        <input type="submit" value="开始查询">
        </form>
    </body>
</html>