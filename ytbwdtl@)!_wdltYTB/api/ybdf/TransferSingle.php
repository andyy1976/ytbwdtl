<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>单笔打款请求</title>
    </head>
    <body>
        <table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:solid 1px #107929">
            <tr>
                <th align="center" height="20" colspan="5" bgcolor="#6BBE18">
                    单笔打款请求	
                </th>
            </tr> 
            <form method="post" action="SendTransferSingle.php" target="_blank">
                <tr >
                    <td width="20%" align="left">&nbsp;出款金额：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="amount" value="0.01" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">amount</td> 
                </tr>	
                <tr >
                    <td width="20%" align="left">&nbsp;收款人姓名：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="account_Name" value="李小蔓" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">account_Name</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;收款账号：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="account_Number" value="6214830107831017" />
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">account_Number</td> 
                </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;收银银行：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                         <input size="70" type="text" name="bank_Code" value="中国银行" />                        
                        <span style="color:#FF0000;font-weight:100;">*</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">amount</td> 
                </tr>
                 <tr >
                    <td width="20%" align="left">&nbsp;收款银行支行名称：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="branch_Bank_Name" value="" />
                        <span style="color:#FF0000;font-weight:100;"><br>非直联银行请添写支行信息</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">branch_Bank_Name</td> 
                </tr>   
                 <tr >
                    <td width="20%" align="left">&nbsp;收款行省份编码：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="province" value="" />
                        <span style="color:#FF0000;font-weight:100;"><br>非直联银行请添写省份编码</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">province</td> 
                 </tr>
                 <tr >
                    <td width="20%" align="left">&nbsp;收款行城市：</td>
                    <td width="5%"  align="center"> : &nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input size="70" type="text" name="city" value="" />
                        <span style="color:#FF0000;font-weight:100;"><br>非直联银行请添写收款城市</span>
                    </td>
                    <td width="5%"  align="center"> - </td> 
                    <td width="15%" align="left">city</td> 
                 </tr>
                <tr >
                    <td width="20%" align="left">&nbsp;</td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="55%" align="left"> 
                        <input type="submit" value="单击打款" />
                    </td>
                    <td width="5%"  align="center">&nbsp;</td> 
                    <td width="15%" align="left">&nbsp;</td> 
                </tr>
            </form>
        </table>
    </body>
</html>
