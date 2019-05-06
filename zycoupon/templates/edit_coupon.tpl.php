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

<form name="myform" id="myform" action="?m=zycoupon&c=zycoupon&a=edit" method="post">

    <div class="pad-10">
        <div class="common-form">
            <div id="div_setting_2" class="contentList">

                <fieldset>
                    <legend>基本信息</legend>
                    <table width="100%" class="table_form">
                        <tbody>
                        <tr>
                            <th width="120">优惠卷名称</th>
                            <td><input type="text" name="couponname" class="input-text" id="couponname" size="15" value="<?php echo $info['couponname']?>"></td>
                        </tr>
                        <tr>
                            <th width="120">限制满减类型</th>
                            <td><select id="type" name="type">
                                    <option value="0" <?php if($info['type']==0){?> selected <?php }?>>无门槛</option>
                                    <option value="1" <?php if($info['type']==1){?> selected <?php }?>>满减</option>
                                    <option value="2" <?php if($info['type']==2){?> selected <?php }?>>叠加满减</option>
                                </select></td>
                        </tr>
                        <tr  id="full" <?php if($info['type']==0){?> style="display: none" <?php }?> >
                            <th width="120">满</th>
                            <td><input type="text" name="full" value="<?php echo $info['full']?>" class="input-text" size="15"></td>
                        </tr>
                        <tr>
                            <th width="120">减</th>
                            <td><input type="text" name="minus" value="<?php echo $info['minus']?>" class="input-text" id="minus" size="15" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                        </tr>
                        <tr>
                            <th width="120">优惠卷时间方式设置</th>
                            <td><select id="vaild_type" name="vaild_type">
                                    <option value="1" <?php if($info['vaild_type']==1){?> selected <?php }?>>固定时间</option>
                                    <option value="2" <?php if($info['vaild_type']==2){?> selected <?php }?>>相对时间</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th width="120">开始时间</th>
                            <td><?php echo form::date('begintime',date('Y-m-d',$info['begintime']))?></td>
                        </tr>
                        <tr>
                            <th width="120">结束时间</th>
                            <td><?php echo form::date('endtime',date('Y-m-d',$info['endtime']))?></td>
                        </tr>
                        <tr id="days" <?php if($info['vaild_type']==1){?> style="display: none" <?php }?> >
                            <th width="120">领取后有效天数</th>
                            <td><input type="text" name="days" value="<?php echo $info['days']?>" class="input-text"  size="15" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                        </tr>
                        <tr>
                            <th width="120">限制商品类型</th>
                            <td><select name="limittype">
                                    <option value="0" <?php if($info['limittype']==0){?> selected <?php }?>>全场通用</option>
                                    <?php foreach($type as $row){?>
                                        <option value=<?php echo $row["id"]?> <?php if($info['limittype']==$row["id"]){?> selected <?php }?>>仅限<?php echo $row["cate_name"]?>类别</option>
                                    <?php }?>
                                </select></td>
                        </tr>

                        <tr>
                            <th width="120">优惠卷总数量</th>
                            <td><input type="text" name="totalnum" value="<?php echo $info['totalnum']?>" class="input-text" id="totalnum" size="15" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
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
<script>
    $('#type').on("change", function(){
        if(jQuery("#type  option:selected").val()==0){
            $('#full').hide();
        }else {
            $('#full').show();
        }
    });
    $('#vaild_type').on("change", function(){
        if(jQuery("#vaild_type  option:selected").val()==1){
            $('#days').hide();
        }else {
            $('#days').show();
        }
    });
</script>

<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>swfupload/swf2ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
</body>
</html>