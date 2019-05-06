<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
        $("#couponname").formValidator({empty:true,onshow:"不能为空。",onfocus:"字符应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"字符应该为2-20位之间"});
        $("#mobile").formValidator({onshow:"请输入手机号",onfocus:"手机号不能为空"}).inputValidator({min:1,max:999,onerror:"手机号不能为空"})});
</script>


<style type="text/css">
    .table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zypoints&c=zypoints&a=edit" method="post">

    <div class="pad-10">
        <div class="common-form">
            <div id="div_setting_2" class="contentList">

                <fieldset>
                    <legend>基本信息</legend>
                    <table width="100%" class="table_form">
                        <tbody>
                        <tr>
                            <th width="120">礼物名称</th>
                            <td><input type="text" required name="giftname" class="input-text" id="giftname" size="15" value="<?php echo $info['giftname']?>"></td>
                        </tr>
                        <tr>
                            <th width="120">详细描述</th>
                            <td><input type="text" name="giftdes" class="input-text" id="giftdes" size="15" value="<?php echo $info['giftdes']?>"></td>
                        </tr>
                        <tr>
                            <th>缩略图</th>
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
                            <th width="120">所需积分</th>
                            <td><input type="text" required name="giftpoint" class="input-text" id="giftpoint" size="15" value="<?php echo $info['giftpoint']?>" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                        </tr>
                        <tr>
                            <th width="120">礼物数量</th>
                            <td><input type="text" required name="giftnum" class="input-text" id="giftnum" size="15" value="<?php echo $info['giftnum']?>" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                        </tr>
                        <tr>
                            <th width="120">是否显示</th>
                            <td>
                                <input type="radio" name="status" value="1" <?php if($info['status']==1){ ?> checked <?php } ?>>是
                                <input type="radio" name="status" value="0" <?php if($info['status']==0){ ?> checked <?php } ?>>否
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>
                <div class="bk15"></div>

            </div>
            <input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
            <input class="dialog" name="dosubmit" id="dosubmit" type="submit" value="确认"/>

        </div>

    </div>
    </div>
</form>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
</body>
</html>