<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/switchery/switchery.min.css" />
<script src="<?php echo APP_PATH?>statics/funds/switchery/switchery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script type="text/javascript" src="<?php echo APP_PATH?>statics/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo APP_PATH?>statics/js/swfupload/swf2ckeditor.js"></script>
<form name="myform" id="myform" action="" method="post" >
	<div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>基本信息</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
						<tr>
							<th width="125">银行</th>
							<td><input style="width: 50%;" type="text" name="bank" id="bank" class="input-text" required="" value="<?php echo $info['bank']?>"></td>
						</tr>
						<tr>
							<th width="125">描述</th>
							<td><input style="width: 50%;" type="text" name="desc" id="desc" class="input-text" required="" value="<?php echo $info['desc']?>" /></td>
						</tr>
						<tr>
							<th>银行LOGO</th>
							<td>
								<div style="width: 161px; text-align: center;">
									<div class='upload-pic img-wrap'>
										<input type='hidden' name='thumb' id='thumb'  value="<?php echo $info['thumb']?>" />
										<a href='javascript:void(0);'
										   onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey;?>');
											   return false;">
											<?php if(empty($info['thumb'])){?>
												<img src='statics/images/icon/upload-pic.png' id='thumb_preview' width='135' height='113' style='cursor:hand; margin-left: 13px;' />
											<?php }else{?>
												<img src='<?php echo $info['thumb'];?>' id='thumb_preview' width='135' height='113' style='cursor:hand; margin-left: 13px;' />
											<?php }?>

										</a>
										<input type="button" style="line-height:0;padding:0 7px;margin-right:5px;" class="button layui-btn layui-btn-normal"
											   onclick="crop_cut_thumb($('#thumb').val());return false;" value="裁切图片">
										<input type="button" style="line-height:0;padding:0 7px; margin-right:0;" class="button layui-btn layui-btn-danger"
											   onclick="$('#thumb_preview').attr('src','statics/images/icon/upload-pic.png');$('#thumb').val('');return false;" value="取消图片">
										<script type="text/javascript">
											function crop_cut_thumb(id){
												if (id=='') {
													alert('请先上传缩略图');
													return false;
												}
												window.top.art.dialog({
													title:'裁切图片',
													id:'crop',
													iframe:'index.php?m=content&c=content&a=public_crop&module=content&catid='+0+'&picurl='+encodeURIComponent(id)+'&input=thumb&preview=thumb_preview',
													width:'680px',
													height:'480px'
												},
												function(){
													var d = window.top.art.dialog({id:'crop'}).data.iframe;
													d.uploadfile();
													return false;
												},
												function(){
													window.top.art.dialog({id:'crop'}).close()
												});
											};
										</script>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<th>是否显示</th>
							<td>
								<?php if($info['status']==1){ ?>
									<input type="checkbox" class="js-switch" checked name="status" value="1" />
								<?php }else{?>
									<input type="checkbox" class="js-switch" name="status" value="0" />
								<?php }?>
							</td>
						</tr>
						<script>
							var elem = document.querySelector('.js-switch');
							var init = new Switchery(elem);

							elem.onchange = function() {
								if(elem.checked){
									elem.value = 1;
								}else{
									elem.value = 0;
								}
							};
						</script>
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