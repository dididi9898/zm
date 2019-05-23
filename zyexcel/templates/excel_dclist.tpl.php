<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>excel导出</em></a>　    
    </div>
</div>


<div class="pad-10">
	<fieldset>
		<form name="myform" action="?m=zyexcel&c=excel&a=excel_dc_ceshi" method="post">
		<legend style=" display: inline-block; float:left;">会员资料导出</legend>
		<div class="bk15"></div>
		<?php echo 选择日期?>  <?php echo form::date('start_addtime_sjs',$_GET['start_addtime_sjs'])?><?php echo L('to')?>   <?php echo form::date('end_addtime_sjs',$_GET['end_addtime_sjs'])?> 

		<input type="submit" name="dosubmit" value="导出" style="margin-left:10px;color: blue;line-height: 25px;">
		<div class="bk15"></div>
		</form>
	</fieldset>
</div>
<div class="bk15"></div>






<!-- 循环体 
<div class="pad-10">
	<fieldset>
		<form name="myform" action="?m=zyshop&c=export&a=export_sjs" method="post">
		<legend style=" display: inline-block; float:left;">设计师导出</legend>
		<div class="bk15"></div>
		<?php echo 选择日期?>  <?php echo form::date('start_addtime_sjs',$_GET['start_addtime_sjs'])?><?php echo L('to')?>   <?php echo form::date('end_addtime_sjs',$_GET['end_addtime_sjs'])?> 

		<input type="submit" name="dosubmit" value="导出" style="margin-left:10px;color: blue;line-height: 25px;">
		<div class="bk15"></div>
		</form>
	</fieldset>
</div>
<div class="bk15"></div>
 循环体 -->


</body>
</html>
