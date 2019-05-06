<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>
<form name="myform" id="myform" action="" method="post" >
	<div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>基本信息</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
						<tr>
							<th width="125">驳回理由</th>
							<td><textarea name="reason" style="width:80%;height:200px;" required style="letter-spacing: 1px;"><?php echo $info['reason']?></textarea></td>
						</tr>
					</tbody>
				</table>
				</fieldset>
			</div>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
			<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
		</div>
	</div>
</form>
</body>
</html>