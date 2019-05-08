<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>


<style type="text/css">
.table_form th{text-align: left;}
</style>

<form name="myform" id="myform" action="?m=zymanagement&c=order&a=addEX" method="post">
<div class="pad-10">
<input type="hidden" name="order_sn" value=<?php echo $info["order_sn"] ?> >
<div class="common-form">
	<div id="div_setting_2" class="contentList">
    
    	<fieldset>
        <legend>快递信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="120">快递公司名称</th>
					<td>
                        <?php echo $orderInfo["name"]?>
                    </td>
				</tr>
                <tr> 
					<th>快递单号</th>
                    <td><?php echo $EXinfo->LogisticCode?></td>
				</tr>
			</tbody>
		</table>
        <p></p>
        <p></p>
        <p></p>
        <legend>物流详情</legend>
        <table width="100%" class="table_form">
            <tbody>
            <?php foreach($EXinfo->Traces as $row){ ?>
            <tr>
                <th width="7%">时间：</th>
                <td width="17%"><?php echo $row->AcceptTime?></td>
                <th width="7%">地点:</th>
                <td><?php echo $row->AcceptStation?></td>
            </tr>
            <?php }?>
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
