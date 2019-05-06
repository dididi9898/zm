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
//        $("#shopname").formValidator({empty:true,onshow:"不能为空。"}).inputValidator({min:2,max:20,onerror:"名称应该为2-20位之间"}).ajaxValidator({
//            type : "get",
//            url : "",
//            data :"m=zymall&c=shop&a=editAjax&typename="+$("#shopname").val(),
//            datatype : "html",
//            async:'false',
//            success : function(s){
//                console.log(s);
//                /*if(data!= '')*/
//                if(s == 1)
//                {
//                    return true;
//                }
//                else
//                {
//                    return false;
//                }
//            },
//            buttons: $("#dosubmit"),
//            onerror : "<?php //echo L('该名称已存在')?>//",
//            onwait : "<?php //echo L('checking')?>//"
//
//        });
        $("#motto").formValidator({empty:true,onshow:"不能为空。",onfocus:"密码应该为2-20位之间"}).inputValidator({min:2,max:20,onerror:"字符应该为2-20位之间"});

        $("#mobile").formValidator({onshow:"请输入手机号",onfocus:"手机号不能为空"}).inputValidator({min:1,max:999,onerror:"手机号不能为空"})});
</script>


<style type="text/css">
    .table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zymall&c=zyshop&a=edit" method="post">
    <input type="hidden" value=<?php echo $info["shopID"]?> name="shopID">
    <div class="pad-10">
        <div class="common-form">
            <div id="div_setting_2" class="contentList">

                <fieldset>
                    <legend>基本信息</legend>
                    <table width="100%" class="table_form">
                        <tbody>
                        <tr>
                            <th width="120">商品名</th>
                            <td><input type="text" name="info[shopname]" value="<?php echo $info["shopname"]?>" class="input-text" id="shopname" size="15"></td>
                        </tr>
                        <tr>
                            <th>商品简述</th>
                            <td>
                                <textarea name="info[sketch]" id="" cols="80" rows="5" maxlength="200" onchange="this.value=this.value.substring(0, 200)" onkeydown="this.value=this.value.substring(0, 200)" onkeyup="this.value=this.value.substring(0, 200)"><?php echo $info["sketch"]?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">商品类型</th>
                            <td><select name="info[typeID]">
                                    <?php foreach($shopType as $row){?>
                                    <option value=<?php echo $row["typeID"]?> <?php if($info["typeID"]== $row["typeID"]) echo "selected"?>><?php echo $row["typename"]?></option>
                                    <?php }?>
                                </select></td>
                        </tr>
                        <tr>
                            <th width="120">价格</th>
                            <td><input type="text" name="info[price]" value="<?php echo $info["price"]?>" class="input-text" id="price" size="15"></td>
                        </tr>
                        <tr>
                            <th width="120">库存</th>
                            <td><input type="text" name="info[repertory]" value="<?php echo $info["repertory"]?>" class="input-text" id="repertory" size="15"></td>
                        </tr>
                        <tr>
                            <th width="120">排序</th>
                            <td><input type="text" name="info[sort]" value="<?php echo $info["sort"]?>" class="input-text" id="sort" size="15"></td>
                        </tr>
                        <tr>
                            <th width="120">是否上架</th>
                            <td><input type="Radio" name="info[putaway]" value="1" <?php if($info["putaway"]== "1") echo "checked"?> class="input-text" checked size="15"> 是
                            <input type="Radio" name="info[putaway]" value="0"  <?php if($info["putaway"]== "0") echo "checked"?> class="input-text"  size="15"> 否</td>
                        </tr>
                        <tr>
                            <th>商品规格</th>
                            <td>
                                <center><div class='onShow' id='nameTip'>不同规格用","分割</div></center>
                                <textarea name="info[unspecification]" id="" cols="110" rows="1"><?php echo $info["unspecification"]?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">缩略图</th>
                            <td>

                                <a href='javascript:void(0);' onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey;?>');return false;">
                                <?php if($info["thumb"]== ""){ ?>
                                <img src="statics/images/member/nophoto.gif" id='thumb_preview' name="info[photo]" height="90" width="90" onerror="this.src='statics/images/member/nophoto.gif'">
                                <?php }else{?>
                                <img src=<?php echo $info["thumb"]?> id='thumb_preview' name="info[photo]" height="90" width="90" onerror="this.src='statics/images/member/nophoto.gif'">
                                <?php }?>
                                <input type="hidden" name="info[thumb]" id="thumb" value=<?php echo $info["thumb"]?>>
                            </td>
                        </tr>
                        <tr>
                            <th width="80"> 商品内容轮播图	  </th>
                            <td><input name="info[pictureurls]" type="hidden" value="1">
                                <fieldset class="blue pad-10">
                                    <legend>图片列表</legend><center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>10</font> 张 注：双击地址框查看图片,第二个为图片排序框</div></center>
                                    <div id="shopPictureurls" class="picList">
                                        <?php foreach($shopPicture as $key=>$value){  ?>
                                            <li id="image<?php $num= mt_rand(1, 10000); echo $num; ?>">
                                                <input type="text" name="shopPictureurls_url[]" value=<?php echo $value ?> style="width:310px;" ondblclick="image_priview(this.value);" class="input-text">
                                                <input type="text" name="shopPictureurls_alt[]" value=<?php echo $key ?> style="width:160px;" class="input-text" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;">
                                                <a href="javascript:remove_div('image<?php echo $num;?>')">移除</a>
                                            </li>

                                        <?php }?>
                                    </div>
                                </fieldset>

                                <div class="bk10"></div>
                                <div class='picBut cu'><a href='javascript:void(0);' onclick="javascript:flashupload('pictureurls_images', '附件上传','shopPictureurls',change_images,'10,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey_1;?>')"/> 选择图片 </a></div>  </td>

                        </tr>
                        <tr>
                            <th width="80"> 商品内容详细图	  </th>
                            <td><input name="info[pictureurls]" type="hidden" value="1">
                                <fieldset class="blue pad-10">
                                    <legend>图片列表</legend><center><div class='onShow' id='nameTip'>您最多可以同时上传 <font color='red'>10</font> 张 注：双击地址框查看图片,第二个为图片排序框</div></center>
                                    <div id="pictureurls" class="picList">
                                        <?php foreach($picture as $key=>$value){  ?>
                                            <li id="image<?php $num= mt_rand(1, 10000); echo $num; ?>">
                                                <input type="text" name="pictureurls_url[]" value=<?php echo $value ?> style="width:310px;" ondblclick="image_priview(this.value);" class="input-text">
                                                <input type="text" name="pictureurls_alt[]" value=<?php echo $key ?> style="width:160px;" class="input-text" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;">
                                                <a href="javascript:remove_div('image<?php echo $num;?>')">移除</a>
                                            </li>

                                        <?php }?>
                                    </div>
                            </fieldset>

                            <div class="bk10"></div>
                            <div class='picBut cu'><a href='javascript:void(0);' onclick="javascript:flashupload('pictureurls_images', '附件上传','pictureurls',change_images,'10,jpg|jpeg|gif|png|bmp,1,,,0','content','6','<?php echo $authkey_1;?>')"/> 选择图片 </a></div>  </td>

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
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
</body>
</html>