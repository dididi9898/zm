<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>


<!-- 样式库 -->
<style type="text/css">
    .clear{ clear: both; }
    .btn:hover{text-decoration: none;}
    .btn {display: inline-block; height: 34px; line-height: 34px; padding: 0 14px; background-color: #009688; color: #fff; white-space: nowrap; text-align: center; font-size: 14px; border: none; border-radius: 2px; cursor: pointer; transition: all .3s; -webkit-transition: all .3s; box-sizing: border-box;}
    .btn:hover {opacity: .8;color: #fff;}
    .btn-primary {
        background-color: #fff;
        border: 1px solid #C9C9C9;
        color: #555;
    }
    .btn-warm {
        background-color: #FFB800;
    }
    .btn-danger {
        background-color: #FF5722;
    }
    .btn-info {
        background-color: #1E9FFF;
    }


    .btn-sm {
        height: 30px;
        line-height: 30px;
        padding: 0 10px;
        font-size: 12px;
    }
    .btn-xs {
        height: 22px;
        line-height: 22px;
        padding: 0 5px;
        font-size: 12px;
    }
</style>

<div class="pad-lr-10">

	<div class="common-form">
		<form name="myform" action="?m=zymember&c=zymember&a=config_edit&pc_hash=<?php echo $_SESSION['pc_hash'];?>"
		 method="post">

			<div class="common-form">
				<div id="div_setting_2" class="contentList">

					<table width="100%" class="table_form">
						<tbody>
							<tr>
								<td width="200">新会员默认积分点数</td>
								<td><input type="text" name="info[defualtpoint]" class="input-text" value="<?php echo $member_setting['defualtpoint']?>">(请输入新会员默认积分点数)</td>
							</tr>

							<tr>
								<td>会员注册协议</td>
								<td>
									<textarea name="info[regprotocol]" id="regprotocol" style="width:80%;height:120px;"><?php echo $member_setting['regprotocol']?></textarea>
								</td>
							</tr>

						</tbody>
					</table>

					<div class="bk15"></div>

				</div>
				<input class="btn btn-sm" name="dosubmit" id="dosubmit" type="submit" value="提交" style="padding: 0 10px;" />
			</div>


		</form>

		<div class="bk10"></div>
	</div>
</div>




</body>

</html>