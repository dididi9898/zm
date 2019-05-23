<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<div class="pad_10">
<div class="table-list">
	<form name="smsform" action="" method="get" >
		<div class="explain-col search-form" style="font-size: 16px;color:red;">
			<span>
				短信通道更新了。更快，更稳定。价格第至每条5分。短信平台支持移动，联通，电信三大运营商106快速发送。快捷，稳定即时到达！如需帮助联系 QQ:2821153693
			</span>
		</div>
	</form>
<div class="btn text-l">
	<span class="font-fixh green">
		已绑定用户ID：<?php echo $info['uid']?>
	</span>
</div>
	

<!--用户状态表单	-->
<div class="common-form">
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">	
	<form name="myform" action="?m=sms&c=sms&a=edit" method="post" id="myform">
<!--		用户信息表-->
		<table width="100%" class="table_form">
<!--				用户id-->
			<tr>
				<td width="120">sms_uid<font color="#C0C0C0"><span>(用户id)</span></font></td> 
				<td>
					<input type="text" name="uid" id="uid" size="20" value="<?php echo $info['uid']?>">
				</td>
			</tr>
<!--				产品id-->
			<tr>
				<td  width="120">sms_pid <font color="#C0C0C0"><span>(产品id)</font></td> 
				<td>
					<input type="text" name="pid" id="pid" size="20" value="<?php echo $info['pid']?>">
				</td>
			</tr>
<!--			密钥-->
			<tr>
				<td  width="120">passwd <font color="#C0C0C0"><span>(密钥)</font></td> 
				<td><input type="text" name="passwd" id="passwd" size="20" value="<?php echo $info['passwd']?>"></td>
			</tr>
		</table>

		<div class="bk15"></div>  <!--空行-->
<!--		提交按钮-->
		<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button" id="dosubmit">
	</form>
</div>

</div>
</div>

</body>
</html>