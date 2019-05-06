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
        <legend>基本信息</legend>
		<table width="100%" class="table_form">
			<tbody>
				<tr> 
					<th width="120">账号名称</th>
					<td><?php echo $member['username']?></td> 
				</tr>
                <tr>
                    <th>团长ID</th>
                    <td><?php echo $member['userid']?></td>
                </tr>
                <tr>
					<th>头像</th>  
					<td>
					<?php if(empty($member['headimgurl'])){?>
                        <img src="statics/images/member/nophoto.gif" height="90" width="90" onerror="this.src='statics/images/member/nophoto.gif'">
                    <?php }else{ ?>
                        <img src="<?php echo $member['headimgurl']?>" height="90" width="90">
                    <?php }?>
                    </td>
				</tr>
                <tr>
					<th>昵称</th>  
					<td><?php echo $member['nickname']?></td>
				</tr>
                <tr> 
					<th>手机号码</th>
					<td><?php echo $member['mobile']?></td> 
				</tr>
                <tr>
					<th>注册日期</th>  
					<td><?php echo date('Y-m-d H:i:s',$member['addTime']);?></td>
				</tr>
                <tr>
                    <th>省份</th>
                    <td><?php echo $member['province'];?></td>
                </tr>
                <tr>
                    <th>城市</th>
                    <td><?php echo $member['province'];?></td>
                </tr>
                <tr>
                    <th>详细地址</th>
                    <td><?php echo $member['address'];?></td>
                </tr>
			</tbody>
		</table>
        </fieldset>
        <div class="bk15"></div>
        
<!--        <fieldset>-->
<!--		<legend>团员资金流向</legend>-->
<!--        <table width="100%" class="table_form">-->
<!--            <thead>-->
<!--                <tr>-->
<!--                    <th>id</th>-->
<!--                    <th>名称</th>-->
<!--                    <th>手机</th>-->
<!--                </tr>-->
<!--            </thead>-->
<!--			<tbody>-->
<!--                --><?php //$num = 1;?>
<!--                --><?php //foreach($childdata as $row){ ?>
<!--                    --><?php //if($row == null) continue; ?>
<!--				<tr>-->
<!--					<th width="120">--><?php //echo $num; $num += 1; ?><!--</th>-->
<!--					<td>--><?php //echo $row["nickname"]?><!--<a href="index.php?m=zymanagement&c=money&a=capitaldetails&phone=--><?php //echo $row["mobile"]?><!--&fatherId=--><?php //echo $member["userid"]?><!--" ><img src="--><?php //echo IMG_PATH?><!--admin_img/detail.png"></a></td>-->
<!--					<td>--><?php //echo $row["mobile"]?><!--</td>-->
<!--				</tr>-->
<!--                --><?php //}?>
<!--			</tbody>-->
<!--		</table>-->
<!--        </fieldset>-->
<!--        -->
<!--	</div>-->
<input type="button" class="dialog" name="dosubmit" id="dosubmit" onclick="window.top.art.dialog({id:'view_pid'}).close();"/>

</div>
</div>

</div>
</div>

</body>
</html>
