<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>excel导入</em></a>　    
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
