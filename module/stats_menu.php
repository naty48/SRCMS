<?php
$data = $core -> aConfig;
$hGwStatus = @fsockopen($data['serverIP'], $data['gatewayPort'], $errno, $errstr, 0.3);
$hGsStatus = @fsockopen($data['serverIP'], $data['gamePort'], $errno, $errstr, 0.3);
$hStatuses = array();
$onlineImg = "<img src='img/status/online.gif' />";
$offlineImg = "<img src='img/status/offline.gif' />";
if($hGwStatus) { $hStatuses['gw'] = $onlineImg; }
else { $hStatuses['gw'] = $offlineImg; }

if($hGsStatus) { $hStatuses['gs'] = $onlineImg; }
else { $hStatuses['gs'] = $offlineImg; }

$nOnlinePlayers = misc::getOnlinePlayersCount();
core::$sql -> changeDB('acc');
$nAccounts = core::$sql -> getRow("select count(*) from TB_User");
$nPlayersMax = core::$sql -> getRow("SELECT top 1 nUserCount FROM _ShardCurrentUser WHERE nShardID = '".$core -> aConfig['shardID']."' ORDER BY nUserCount desc");

core::$sql -> changeDB('shard');

$nChars = core::$sql  -> getRow("select count(*) from _Char");
$nGuilds = core::$sql  -> getRow("select count(*) from _Guild");

core::$sql  -> changeDB('acc');

echo " 
		<hr>
		<b> Server info :</b><br/>
		<hr>
		Players online: <font color='green'>$nOnlinePlayers/$data[playersLimit]</font><br/>
		Max online: <font color='green'>$nPlayersMax</font><br/>
		Experience rate: <font color='green'>$data[expRate]</font><br/>
		Party Experience rate: <font color='green'>$data[partyExpRate]</font><br/>
		Gold drop coeficent: <font color='green'>$data[goldDropRate]</font><br/>
		Item drop coeficent: <font color='green'>$data[itemDropRate]</font><br/>
		<hr>
		<b>Status :</b><br/>
		<hr>
		Gateway Server: $hStatuses[gw]<br/>
		Game Server: $hStatuses[gs]<br/>
		Accounts: <font color='green'>$nAccounts</font><br/>
		Characters: <font color='green'>$nChars</font><br/>
		Guilds: <font color='green'>$nGuilds</font>
		";
?>