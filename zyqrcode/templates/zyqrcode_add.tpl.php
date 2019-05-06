<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/qrcode/switchery/switchery.min.css" />
<script src="<?php echo APP_PATH?>statics/qrcode/switchery/switchery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/qrcode/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/qrcode/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script type="text/javascript" src="<?php echo APP_PATH?>statics/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo APP_PATH?>statics/js/swfupload/swf2ckeditor.js"></script>
<!--校验-->
<script type="text/javascript">
    <!--
    $(function(){
        $.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
        $("#name").formValidator({onshow:"<?php echo L("input").L('名称')?>",onfocus:"<?php echo L("input").L('名称')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('名称')?>"});
        $("#project").formValidator({onshow:"<?php echo L("input").L('名称')?>",onfocus:"<?php echo L("input").L('名称')?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('名称')?>"});
        $("#url").formValidator({onshow:"<?php echo L("input").L('地址')?>",onfocus:"<?php echo L("input").L('地址')?>"}).regexValidator({regexp:"^http(s)?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&]*([^<>])*$",onerror:"<?php echo L('地址格式不正确')?>"});
        //图片格式验证
        $("#thumb").formValidator({
            onshow: "（必选）",
            onfocus: "（必填）请上传图片文件",
            oncorrect: "（正确）",
            empty:false
        }).regexValidator({
            regexp: regexEnum.picture,
            onerror: "只能上传图片文件"
        }).regexValidator({regexp:"^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&]*([^<>])*$",onerror:"<?php echo L('onerror')?>"});
    })
    //-->
</script>
<form name="myform" id="myform" action="" method="post" >
	<div class="pad-10">
		<div class="common-form">
			<div id="div_setting_2" class="contentList">
				<fieldset>
				<legend>基本信息</legend>
				<table width="100%" class="table_form" id="mytable">
					<tbody>
                        <tr>
                            <th width="125">项目名称</th>
                            <td><input style="width: 50%;" type="text" name="project" id="project" class="input-text" required=""></td>
                        </tr>
						<tr>
							<th width="125">APP名称</th>
							<td><input style="width: 50%;" type="text" name="name" id="name" class="input-text" required=""></td>
						</tr>
						<tr>
							<th width="125">下载地址</th>
							<td><input style="width: 50%;" type="text" name="url" id="url" class="input-text" required=""></td>
						</tr>
						<tr>
							<th>APP LOGO</th>
							<td>
								<div style="width: 161px; text-align: center;">
									<div class='upload-pic img-wrap'>
                                        <input type='hidden' name='thumb' id='thumb' value='' required="">
										<a href='javascript:void(0);'
										   onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey;?>');return false;">
											<img src='statics/images/icon/upload-pic.png' id='thumb_preview' width='135' height='113' style='cursor:hand; margin-left: 13px;' />
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
							<th>是否在二维码显示LOGO</th>
							<td>
								<input type="checkbox" class="js-switch" checked name="isthumb" value="1" />
							</td>
						</tr>
                        <tr>
                            <th>是否在前台显示</th>
                            <td>
                                <input type="checkbox" class="js-switch2" checked name="isshow" value="1" />
                            </td>
                        </tr>
						<script>
							var elem = document.querySelector('.js-switch');
							var init = new Switchery(elem);
						</script>
                        <script>
                            var elem = document.querySelector('.js-switch2');
                            var init = new Switchery(elem);
                        </script>
					</tbody>
				</table>
				</fieldset>
			</div>
			<input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>
		</div>
	</div>
</form>
</body>
</html>