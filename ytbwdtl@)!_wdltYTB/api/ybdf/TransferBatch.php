<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>批量打款请求</title>
</head>
<body>
<table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
    <tr>
        <th align="center" height="20" colspan="5" bgcolor="#6BBE18">
            批量打款请求
        </th>
    </tr>
     <form method="post" action="SendTransferBatch.php" target="_blank" enctype="multipart/form-data">
         <tr >
            <td width="20%" align="left">&nbsp;EXCEL导入模板下载：</td>
            <td width="5%"  align="center"> : &nbsp;</td>
            <td width="55%" align="left">
                <a href="muban/test.xls">点击下载</a>
            </td>
            <td width="5%"  align="center"> - </td>
            <td width="15%" align="left">模板文件</td>
        </tr>
        <tr>
            <td>               
            </td>
        </tr>
        <tr >
            <td width="20%" align="left">&nbsp;批量打款文件路径：</td>
            <td width="5%"  align="center"> : &nbsp;</td>
            <td width="55%" align="left">
                <input type="file" id="file" name="file" accept="application/vnd.ms-excel">
            </td>
            <td width="5%"  align="center"> - </td>
            <td width="15%" align="left">excelFile</td>
        </tr>
      
        <tr >
            <td width="20%" align="left">&nbsp;</td>
            <td width="5%"  align="center">&nbsp;</td>
            <td width="55%" align="center">
                <input type="submit" value="批量打款" />
            </td>
            <td width="5%"  align="center">&nbsp;</td>
            <td width="15%" align="left">&nbsp;</td>
        </tr>
    </form>
</table>
</body>
</html>

