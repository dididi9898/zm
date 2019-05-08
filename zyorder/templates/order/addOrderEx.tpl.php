<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zyorder&c=order&a=addEX" method="post">
<div class="pad-10">
<input type="hidden" name="ordersn" value=<?php echo $info["ordersn"] ?> >
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>添加快递信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="120">快递公司名称</th>
					<td>
                        <select name="EXid" >
                            <option  value="">快递名称</option>
                        <?php foreach($data as $row){?>
                            <option value=<?php echo $row["EXid"]?>><?php echo $row["name"]?></option>
                        <?php }?>
                        </select>
                    </td>
				</tr>
                <tr> 
					<th>快递单号</th>
                    <td><input type="text" name="logistics_order" value="" class="input-text" id="logistics_order" size="15"></td>
				</tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>
        
	</div>
<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>

</div>

</div>
</div>
</form>

</body>
</html>
