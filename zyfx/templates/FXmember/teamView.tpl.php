<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<style type="text/css">
.table_form th{text-align: left;}
</style>

<div class="pad-10">
<div class="table-list">
<div class="common-form">
	<div id="div_setting_2" class="contentList">
        
        <fieldset>
		<legend>团队用户</legend>
        <table width="100%" class="table_form">
            <thead>
                <tr>
                    <th> </th>
                    <th>id</th>
                    <th>名称</th>
                    <th>手机</th>
                </tr>
            </thead>
			<tbody>
                <?php foreach($data as $key=>$value){ $num = 1;?>
                    <tr>
                            <td rowspan="<?php if(count($value) == 0)echo 1 ;else echo count($value)?>"><?php echo $key+1?>级团员</td>

                            <?php foreach($value as $k=>$v){ ?>
                                <?php if($num != 1){$num++; echo "</tr>";}?>
                                <td width="120"><?php echo $v["userid"]; ?></td>
                                <td><?php echo $v["nickname"]; ?></td>
                                <td><?php echo $v["mobile"]; ?></td>
                                </tr>
                            <?php }?>
                    <?php if($value == null) echo "<td>无</td><td>无</td><td>无</td></tr>";?>

                <?php }?>
			</tbody>
		</table>
        </fieldset>
        
	</div>
<input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view_team'}).close();"/>

</div>
</div>

</div>
</div>


</body>
</html>
