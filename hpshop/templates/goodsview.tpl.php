<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>

<style type="text/css">
	/*隐藏radio按钮*/
input[type="radio"] { opacity: 0;}
.myradio { display: inline-block; vertical-align: middle; margin: 0; padding: 0; width: 70px; height: 24px; border-radius: 20px; position: relative; overflow: hidden;}
.mrclose { background-color: #f40;/*#e8e8e8;*/}
.mropen { background-color: #67e66c;}
.myradio .open, .myradio .close { width: 22px; height: 22px; font-size: 13px; border-radius: 50%; background: #fff; color: #fff; position: absolute; top: 0; left: 0; border: 1px solid #e8e8e8;}
.myradio .open { color: #fff; background-color: #fff;}
.hidden { display: none}
.disabled { pointer-events: none; cursor: default;}
.myradio .close { left: auto; right: 0; }
.myradio .open:after { content: '开启'; position: absolute; top: 0; left: 30px; width: 28px; height: 24px; line-height: 22px; }
.myradio .close:before { content: '关闭'; position: absolute; top: 0; left: -35px; width: 28px; height: 24px; line-height: 22px;}

input[type="radio"] + label::before { content: "\a0"; /*不换行空格*/ display: inline-block; vertical-align: middle; font-size: 16px; width: 1em; height: 1em; margin-right: .4em; border-radius: 50%; border: 1px solid #01cd78; text-indent: .15em; line-height: 1; margin-left: 10px; margin-top: 5px; -moz-box-sizing: border-box;  /*Firefox3.5+*/-webkit-box-sizing: border-box; /*Safari3.2+*/-o-box-sizing: border-box; /*Opera9.6*/-ms-box-sizing: border-box; /*IE8*/box-sizing: border-box; margin-top: -2px;}
input[type="radio"]:checked + label::before { background-color: #01cd78; background-clip: content-box; padding: .18em; font-size: 16px;}
input[type="radio"] { position: absolute; clip: rect(0, 0, 0, 0);}

</style>


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="" method="post" >
<div class="pad-10">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form" id="mytable">
			<tbody>
     
				<tr>
					<th width="125">商品名称</th>  
					<td><input readonly="readonly" type="text" name="gname" id="gname" class="input-text" value="<?php echo $info['goods_name'];?>"><span id="balance"></span></input></td>
				</tr>

				<tr>
					<th width="125">商品简述</th>  
					<td><textarea disabled="disabled" rows="4" cols="60" name="summary" style="resize: vertical;"><?php echo $info['summary'];?></textarea></td>
				</tr>

				<tr>
					<th width="125">所属栏目</th>  
					<td>
						<select disabled="disabled" name="cid" required="required" >
						    <option value="">请选择栏目</option>
						    <?php 
								if(is_array($cinfo)){	
									foreach($cinfo as $v){
							?>  
						    <option value="<?php echo $v['id'] ;?>" <?php if ($info['catid']==$v['id']) {?>selected<?php }?>><?php if ($v['level'] != 0) echo '|'; ?><?php echo str_repeat('-', $v['level']*8)?><?php echo $v['cate_name'] ;?></option>
						    <?php 
									}
								}
							?>
						</select>
					</td>
				</tr>

				<!-- <tr>
					<th width="125">所属品牌</th>  
					<td>
						<select disabled="disabled" name="bid" required="required" >
						    <option value="">请选择品牌</option>
						    <?php 
								if(is_array($binfo)){	
									foreach($binfo as $b){
							?>  
						    <option value="<?php echo $b['id'] ;?>" <?php if ($info['brand_id']==$b['id']) {?>selected<?php }?>><?php echo $b['brandname'] ;?></option>
						    <?php 
									}
								}
							?>
							<option value="qt"><?php echo '其他品牌' ;?></option>
						</select>
					</td>
				</tr> -->

				<tr>
					<th width="125">商品类型</th>  
					<td>
						<select disabled="disabled" name="tid" required="required" >
						    <option value="">请选择商品类型</option>
						    <?php 
								if(is_array($tinfo)){	
									foreach($tinfo as $t){
							?>  
						    <option value="<?php echo $t['id'] ;?>" <?php if ($info['type_id']==$t['id']) {?>selected<?php }?>><?php echo $t['type_name'] ;?></option>
						    <?php 
									}
								}
							?>
						</select>
					</td>
				</tr>

				<?php 
				if( !empty($gattr) ){	
				?> 
				<tr class="cylstr">
					<th width="125">商品规格</th>  
					<td>
						<table id="spectable" class="layui-table">
							<thead>
								<tr>
									<?php
										foreach($gattr as $kn=>$vn){
									?> 
									<th><?php echo $vn['attrname'];?></th>
									<?php 
										}
									?>
									<th>本店价</th>
									<th>库存</th>
									<!-- <th>操作</th> -->
								</tr>
							</thead>

							<tbody>
								

								<?php 
								if(is_array($gspec)){	
									foreach($gspec as $ks=>$vs){
								?>
								<?php $zarr = explode(',',$vs['specids']) ;?>
								<tr>
									<?php
										foreach($zarr as $kz=>$vz){
									?> 
									<td><?php echo $vz;?></td>
									<?php 
										}
									?>
									
									<td><input disabled="disabled" type="text" name="goodsspecs[<?php echo $vs['id']?>][bprice]" required placeholder="请输入价格" class="layui-input bp" value="<?php echo $vs['specprice']; ?>"></td>
									<td><input disabled="disabled" type="text" name="goodsspecs[<?php echo $vs['id']?>][stock]" placeholder="请输入库存" class="layui-input st" value="<?php echo $vs['specstock']; ?>"></td>
									

									<!-- <?php 
									if($vs['status'] == 1){	
									?>
									<td><button type="button" onclick="changeopen(this)" class="layui-btn  layui-btn-sm layui-btn-primary"><i class="layui-icon">&#xe620;</i>禁用</button></td>
									<?php 
									}else{	
									?>
									<td><button type="button" onclick="changeopen(this)" class="layui-btn  layui-btn-sm "><i class="layui-icon">&#xe620;</i>启用</button></td>
									<?php 
									}
									?> -->

								</tr>
								<?php 
									}
								}
								?>	


							</tbody>
						</table>

                    </td>
				</tr>
				<?php 
					}
				?>	

				<?php 
				if(is_array($gattrs)){	
					foreach($gattrs as $k=>$v){
				?> 
				<tr class="cylstr">
					<th width="125"><?php echo $v['attrname'];?></th>  
					<td><input disabled="disabled" type="text" name="goods_attrs[<?php echo $v['id'];?>][val]" class="input-text" value="<?php echo $v['val'];?>" required="required"></input>
					
				</tr>
				<?php 
					}
				}
				?>	

				<tr>
					<th>商品主图</th>  
					<td>
						<div style="width: 161px; text-align: center;">
							<div class='upload-pic img-wrap'><input type='hidden' name='thumb' id='thumb' value=''>
								<a href='javascript:void(0);'>
				<img src='<?php if($info['thumb']) { echo $info['thumb']; } else { echo "http://localhost/cs/statics/images/icon/upload-pic.png"; } ?>' id='thumb_preview' width='135' height='113' style='cursor:hand; margin-left: 13px;' /></a>
							</div>
						</div>
					</td>
				</tr>

				<tr>
			      <th width="80">商品相册</th>
			      <td><input name="goodsimg" type="hidden" value="1">
					<fieldset class="blue pad-10">
					<legend>图片列表</legend>	
					<?php 
						if(empty($alinfo)){	
							
					?>	
			        <center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>10</font> 张</div></center><div id="goodsimg" class="picList"></div>
			        <?php 
						}else{
							foreach($alinfo as $ks=>$vs){
					?>
			        
			        <div id='image_caseimg_<?php echo $ks;?>' style='padding:1px'>
			        	<img src="<?php echo $vs['url'];?>" height="40" />
			        	<input readonly="readonly" type='text' name='goodsimg_url[]' value='<?php echo $vs['url'];?>' style='width:310px;' ondblclick='image_priview(this.value);' class='input-text'> 
			        	<input readonly="readonly" type='text' name='goodsimg_alt[]' value='<?php echo $vs['alt'];?>' style='width:160px;' class='input-text'> 
			        </div>
			        <?php 
						}
					}
					?>

					</fieldset>
					<div class="bk10"></div>
					
			    </tr>

			    <tr>
					<th width="125">市场价</th>  
					<td><input readonly="readonly" type="text" name="mprice" class="input-text" value="<?php echo $info['market_price'];?>" required="required"></input></td>
				</tr>
				<tr>
					<th width="125">本店价</th>  
					<td><input readonly="readonly" type="text" name="sprice" class="input-text" value="<?php echo $info['shop_price'];?>" required="required"></input></td>
				</tr>
				<tr>
					<th width="125">总库存</th>  
					<td><input disabled="disabled" type="text" name="stock" class="input-text" value="<?php echo $info['stock'];?>"></input></td>
				</tr>

			    <tr>
					<th width="125">商品内容信息</th>  
					<td>
						<div id='content_tip'></div><textarea disabled="disabled" name="content" id="content" boxid="content"><?php echo $info['content'];?></textarea><script type="text/javascript" src="http://localhost/cs/statics/js/ckeditor/ckeditor.js"></script>
						<script type="text/javascript">
							CKEDITOR.replace( 'content',{height:300,pages:true,subtitle:true,textareaid:'content',module:'content',catid:'0',
							flashupload:true,alowuploadexts:'',allowbrowser:'1',allowuploadnum:'10',authkey:'837c2cb82c762905350dca821370e337',
							filebrowserUploadUrl : 'index.php?m=attachment&c=attachments&a=upload&module=content&catid=0&dosubmit=1',
							toolbar :
							[
								['Source','-','Templates'],
							    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
							    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['ShowBlocks'],['Image','Capture','Flash','flashplayer','MyVideo'],['Maximize'],
							    '/',
							    ['Bold','Italic','Underline','Strike','-'],
							    ['Subscript','Superscript','-'],
							    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
							    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
							    ['Link','Unlink','Anchor'],
							    ['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
							    '/',
							    ['Styles','Format','Font','FontSize'],
							    ['TextColor','BGColor'],
							    ['attachment'],
							]
							});
						</script>
					</td>
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

