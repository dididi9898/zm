<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/layui.all.js"></script>
<style>
	div{
	}
</style>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>excel导入</em></a>　    
    </div>
</div>

<div class="layui-row">
	<div class="layui-col-md3">
		<div class="layui-row">
			你的内容 2
		</div>
		<div class="layui-row">
			你的内容 3
		</div>
	</div>
	<div class="layui-col-md7">
		你的内容 1
	</div>
</div>

<div class="pad-10">
	<fieldset>
	<legend style=" display: inline-block; float:left;">会员资料导入</legend>
	<div class="bk15"></div>

		<div style="margin: 10px;">
		<form method="post" action="?m=zyexcel&c=excel&a=excel_dr_ceshi" enctype="multipart/form-data">

			<input type="file" name="file_stu"/>
			<input type="submit" name="dosubmit" value="导入" style="padding: 3px 6px;"/>
		</form>
		</div>
	</fieldset>
</div>
<div class="bk15"></div>



<!-- 循环体 
<div class="pad-10">
	<fieldset>
	<legend style=" display: inline-block; float:left;">会员资料导入</legend>
	<div class="bk15"></div>	
	
		<div style="margin: 10px;">
		<form method="post" action="?m=zyexcel&c=excel&a=excel_drlist" enctype="multipart/form-data">
			<input type="file" name="file_stu"/>
			<input type="submit" name="dosubmit" value="导入" style="padding: 3px 6px;"/>
		</form>
		</div>
	</fieldset>
</div>
<div class="bk15"></div>
 循环体 -->



</body>
</html>
