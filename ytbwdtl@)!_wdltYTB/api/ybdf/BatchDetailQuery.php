<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>打款批次明细查询</title>
    </head>
    <body>
        <table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
            <tr>
                <th align="center" height="20" colspan="5" bgcolor="#6BBE18">
                    打款批次明细查询	
                </th>
            </tr> 
            <form method="post" action="SendBatchDetailQuery.php" target="_blank">               
                <tr >
                    <td width="20%" align="left">&nbsp;产品类型：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <select name="product">
                            <option value="RJT">日结通出款</option>
                            <option value=""selected="selected">代付代发出款</option>
                        </select>
                        <span style="color:#FF0000;font-weight:100;">默认代付代发</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">product</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;批次号：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="batch_No" value="" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">batch_No</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;订单号：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="order_Id" value="" />                       
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">order_Id</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;</td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input type="submit" value="查询" />
                    </td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="15%" align="left">&nbsp;</td> 
                </tr>
            </form>
        </table>
    </body>
</html>
