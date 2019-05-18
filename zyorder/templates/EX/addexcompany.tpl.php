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
	$("#value").formValidator({empty:false,onshow:"不能为空。"}).inputValidator({min:2,max:20,onerror:"名称应该为2-20位之间"}).ajaxValidator({
        type : "get",
        url : "index.php?m=zyorder&c=order&a=editAjax",
        datatype : "html",
        async:'false',
        success : function(s){
            console.log(s);
            /*if(data!= '')*/
            if(s == 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        },
        buttons: $("#dosubmit"),
        onerror : "<?php echo L('该名称已存在')?>",
        onwait : "<?php echo L('checking')?>"

      })
	});
</script>


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zyorder&c=order&a=add" method="post">
<div class="pad-10">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="120">快递公司名称</th>
					<td><input type="text" name="name" value="" class="input-text" id="newtypename" size="15"></td>
				</tr>
                <tr> 
					<th>快递公司缩写</th>
                    <td><input type="text" name="value" value="" class="input-text" id="value" size="15"></td>
				</tr>
                <tr>
                    <th>是否显示</th>
                    <td><select name="isShow" id="">
                            <option value="1"  >是</option>
                            <option value="2">否</option>
                        </select></td>
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
