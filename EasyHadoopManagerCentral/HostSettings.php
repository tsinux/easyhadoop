<?php
include_once "config.inc.php";

include_once "templates/header.html";
include_once "templates/host_settings_sidebar.html";

$mysql = new Mysql();

if(!@$_GET['action'])
{
	echo '<div class="span10">
	'.$lang['chooseLeftSidebar'].'
	</div>';
}
elseif($_GET['action'] == "GlobalSettings")
{
	if(!@$_GET['do'])
	{
		$sql = "select set_id, filename, create_time from ehm_host_settings where ip = '0' order by create_time desc";
		$mysql->Query($sql);
		echo '<div class=span10>';
		
		echo '<a href="HostSettings.php?action=GlobalSettings&do=Add" class="btn">'.$lang['addSettings'].'</a>';
		echo '<a href="HostSettings.php?action=GlobalSettings&do=EtcHosts" class="btn">'.$lang['etchostsSettings'].'</a>';
		
		echo '<h2>'.$lang['globalSettings'].'</h2>';
		
		echo '<div class="alert alert-error">';
		echo $lang['globalSettingTips'];
		echo '</div>';
		
		echo '<table class="table table-striped">';
		echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['filename'].'</th>
                  <th>'.$lang['createTime'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';
		$i = 1;
		while($arr = $mysql->FetchArray())
		{
			echo '<tr>
                  	<td>'.$i.'</td>
                  	<td>'.$arr['filename'].'</td>
                  	<td>'.$arr['create_time'].'</td>
                  	<td>
                  	<div class="btn-group">
   						 <a class="btn" href="HostSettings.php?action=GlobalSettings&do=Edit&setid='.$arr['set_id'].'">'.$lang['edit'].'</a>
   						 <a class="btn btn-danger" onclick="javascript:realconfirm(\''.$lang['removeConfirm'].'\', \'HostSettings.php?action=GlobalSettings&do=Remove&setid='.$arr['set_id'].'\'); return false;" href="#">'.$lang['remove'].'</a>
                  	</div>
                  	</td>
                	</tr>';
			$i++;
		}
		echo '</tbody></table>';
		echo '</div>';
	}#not any action

	elseif ($_GET['do'] == "Add")
	{
		if(!$_POST['content'])
		{
			echo '<div class=span10>';
			echo '<h1>'.$lang['addSettings'].'</h1>';
			include_once "templates/add_global_settings_form.html";
			echo '</div>';
		}
		else
		{
			$sql = "insert ehm_host_settings set filename='".$_POST['filename']."', content = '".$_POST['content']."', create_time=current_timestamp(), ip='0'";
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingAdded']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
		}
	}
	
	elseif ($_GET['do'] == "Edit")
	{
		$set_id = $_GET['setid'];
		$sql = "select * from ehm_host_settings where set_id='".$set_id."'";
		$mysql->Query($sql);
		$arr = $mysql->FetchArray();
		if(!$_POST['content'])
		{
			echo '<div class=span10>';
			echo '<h1>'.$lang['modifySettings'].'</h1>';
			include_once "templates/edit_global_settings_form.html";
			echo '</div>';
		}
		else
		{
			$sql = "update ehm_host_settings set filename='".$_POST['filename']."', content = '".$_POST['content']."' where set_id='".$set_id."'";
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingUpdated']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
		}
	}

	elseif ($_GET['do'] == "Remove")
	{
		$set_id = $_GET['setid'];
		$sql = "delete from ehm_host_settings where set_id = '".$set_id."'";
		$mysql->Query($sql);
		echo "<script>alert('".$lang['settingRemoved']."'); this.location='HostSettings.php?action=GlobalSettings';</script>";
	}
	elseif($_GET['do'] == "EtcHosts")
	{
		echo '<div class=span10>';
		echo '<h1>'.$lang['etchostsSettings'].'</h1>';
		
		echo '<div class="alert alert-error">';
		echo $lang['makeEtcHostTips'];
		echo '</div>';
		
		$sql = "select * from ehm_hosts";
		$mysql->Query($sql);
		echo "<pre>";
		while($arr = $mysql->FetchArray())
		{
			echo $arr['ip']."\t".$arr['hostname']."<br />";
		}
		echo "</pre>";
		echo '</div>';
	}
	else
	{
		echo "Unknown Command";	
	}
}

elseif($_GET['action'] == 'NodeSettings')
{
	if(!$_GET['do'])
	{
		echo '<div class=span10>';
		echo '<h2>'.$lang['hostSettings'].'</h2>';
		
		echo '<div class="alert alert-error">';
		echo $lang['nodeSettingTips'];
		echo '</div>';
		
		$sql = "select * from ehm_hosts order by create_time desc";
		$res = $mysql->Query($sql);
		//echo '<table class="table table-striped">';
		/*echo '<thead>
                <tr>
                  <th>#</th>
                  <th>'.$lang['hostname'].'</th>
                  <th>'.$lang['ipAddr'].'</th>
                  <th>'.$lang['nodeRole'].'</th>
                  <th>'.$lang['createTime'].'</th>
                  <th>'.$lang['action'].'</th>
                </tr>
                </thead>
                <tbody>';*/
		$i = 1;
		echo '<div class="accordion" id="accordion2">'."\n";
		while($arr = $mysql->FetchArray($res))
		{
			echo $sql1 = "select * from ehm_host_settings where ip = '".$arr['ip']."' order by create_time desc";
			echo $res1 = $mysql->Query($sql1);
			echo '<div class="accordion-group">'."\n";
			echo '<div class="accordion-heading">'."\n";
			echo '<strong class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">'.$arr['hostname'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$arr['ip'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$arr['role'].'&nbsp;&nbsp;&nbsp;&nbsp;</strong>'."\n";
    		echo '</div>'."\n";
			//echo '</div>';
			echo '<div id="collapse'.$i.'" class="accordion-body collapse">'."\n";
			echo '<div class="accordion-inner">'."\n";
			echo '<table class="table table-striped">'."\n";
			while($arr1 = $mysql->FetchArray($res1))
			{var_dump($arr1);
				echo '<tr>'."\n";
				echo '<td>'."\n";
				echo $arr1['filename']."\n";
				echo '</td>'."\n";
				echo '<td>'."\n";
				echo '<div class="btn-group">'."\n";
				echo '<a class="btn" href="HostSettings.php?action=NodeSettings&do=Edit&ip='.$arr['ip'].'&set_id='.$arr1['set_id'].'">'.$lang['edit'].'</a>'."\n";
				echo '<a class="btn btn-danger" onclick=javascript:realconfirm("'.$lang['removeConfirm'].'","HostSettings.php?action=NodeSettings&do=Remove&ip='.$arr['ip'].'&set_id='.$arr1['set_id'].'");return false; href="#">'.$lang['remove'].'</a>'."\n";
				echo '</div>'."\n";
				echo '</td>'."\n";
				echo '</tr>'."\n";
			}
			echo '</table>'."\n";
			echo '</div>'."\n";
			echo "</div>"."\n";
			echo "</div>"."\n";
			$i++;
		}
		echo '</div>'."\n";
	}
	elseif($_GET['do'] == "Add")
	{
		if(!$_POST['ip'])
		{
			$ip = $_GET['ip'];
			
			echo '<div class=span10>';
			echo '<h1>'.$lang['addSettings'].'</h1>';
			include_once "templates/add_node_settings_form.html";
			echo '</div>';
		}
		else
		{
			$ip = $_POST['ip'];
			$filename = $_POST['filename'];
			$content = $_POST['content'];
			
			$sql = "insert ehm_host_settings set filename='".$filename."', content = '".$content."', create_time=current_timestamp(), ip = '".$ip."'";
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingAdded']."');this.location='HostSettings.php?action=NodeSettings';</script>";
		}
	}
	elseif ($_GET['do'] == "Edit")
	{
		if(!$_POST['set_id'])
		{
			$ip = $_GET['ip'];
			$set_id = $_GET['set_id'];
			$host_id = $arr['host_id'];
			$sql = "select * from ehm_host_settings where ip = '".$ip."' and set_id='".$set_id."'";
			$mysql->Query($sql);
			$arr = $mysql->FetchArray();
	
			echo '<div class=span10>';
			echo '<h1>'.$lang['modifySettings'].'</h1>';
			include_once 'templates/edit_node_settings_form.html';
			echo '</div>';
		}
		else
		{
			$set_id = $_POST['set_id'];
			$filename = $_POST['filename'];
			$content = $_POST['content'];
	
			$sql = "update ehm_host_settings set filename='".$filename."', content = '".$content."' where set_id = ".$set_id;
			$mysql->Query($sql);
			echo "<script>alert('".$lang['settingUpdated']."');this.location='HostSettings.php?action=NodeSettings';</script>";
		}
	}
	elseif ($_GET['do'] == "Remove")
	{
		$set_id = $_GET['set_id'];
		$ip = $_GET['ip'];
		$sql = "delete from ehm_host_settings where set_id=".$set_id." and ip = '".$ip."'";
		$mysql->Query($sql);
		echo "<script>alert('".$lang['settingRemoved']."'); this.location='HostSettings.php?action=NodeSettings';</script>";
	}
}


include_once "templates/footer.html";
?>