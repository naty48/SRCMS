<?php
global $core;
switch($_GET['type'])
{

	//Characters Ranking
	
	case('char'):
	{
		if(isset($_GET['name']) && security::isSecureString($_GET['name'], 3))
		{
			$nCharID = char::charIDByCharname($_GET['name']);
			if($nCharID == 0)
			{
				echo "There is no character with such nickname<br/>";
			}
			else
			{
				//main
				//switch to acc db
				$szUsername = user::usernameByCharname($_GET['name']);
				$bCanView = core::$sql -> getRow("select ispublic from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'");
				if($bCanView == 1) //DEBUG >= = =
				{
					//switch to shd db
					core::$sql -> changeDB("shard");
					$hGuild = core::$sql -> getRow("select JobType from _CharTrijob where CharID='$nCharID'");
					$jobType = char::jobTypeByID($hGuild);
					$hData = core::$sql -> fetchArray("select * from _Char where CharID='$nCharID'");
					if(strlen($hData['NickName16']) < 2) $hData['NickName16'] = "<b>None</b>";
					$ownerStr = null;
					if($core -> aConfig['allowShowCharOwner'] == 1)
					{
						$ownerStr = "<td>Owner account</td>
							<td><a href='?pg=viewprofile&username=$szUsername'>$szUsername</td>";
					}
				
					echo "<table valign='top' id='table-3'  border='0' cellpadding='5' cellspacing='3'>

							<td>
						<table border='0' cellpadding='0' cellspacing='0'>
							$ownerStr
						 </table><br/>";
						 
						 /*
						 
						 for character gold add this line :
						 
						 <td>Gold</td><td>$hData[RemainGold]</td><tr/> 
						 
						 */
					$nGuildName = guild::guildNameByID($hData['GuildID']);
					$nCharID = char::charIDByCharname($_GET['name']);
					core::$sql -> changeDB("log");
					$charstatus = core::$sql -> exec("select top 1 EventID,EventTime from  _LogEventChar where CharID = '$nCharID' order by EventTime DESC");
					if(core::$sql -> numRows("select top 1 EventID,EventTime from  _LogEventChar where CharID = '$nCharID' order by EventTime DESC") == 0)
					{
					$StatusIS = "<img src='img/status/offline.gif' /> Offline";
					}
					else
					{
					while($row1 = mssql_fetch_array($charstatus))
					{
					$charEvent = $row1['EventID'];
					switch($charEvent)
					{
					case 4:
					$StatusIS = "<img src='img/status/online.gif' /> Online";
					break;
					case 6:
					$StatusIS = "<img src='img/status/offline.gif' /> Offline";
					break;
					default:
					$StatusIS = "<img src='img/status/offline.gif' /> Offline";
					break;
					}
					}
					}
					core::$sql -> changeDB("acc");
					//Tiger Girl
					$uniquetg = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_CH_TIGERWOMAN' ");
					//Cerburus
					$uniqueker = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_EU_KERBEROS' ");
					//IVY
					$uniqueivy = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_AM_IVY' ");
					//Uruchi
					$uniqueuruchi = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_OA_URUCHI' ");
					//Isy
					$uniqueisy = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_KK_ISYUTARU' ");
					//Lord Yarkan
					$uniquelord = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_TK_BONELORD' ");
					//Demon
					$uniquedemon = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_RM_TAHOMET' ");
					//SOSO
					$uniquesoso = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_TQ_BLACKSNAKE' ");	

					$uniquemedusa = core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]' and MobName = 'MOB_TQ_WHITESNAKE' ");	
					
					echo "<img src='img/character/$hData[RefObjID].gif' width='171'>
											<br /><br />
						<u>Character Info:</u>
						<br /><br />
						<table id='table-3' border='0' cellspacing='0' cellpadding='0'>
						<td>Nick</td><td>$hData[CharName16]</td><tr/>
						<td>Guild</td><td>$nGuildName</td><tr/>
						<td>Job</td><td>$jobType</td><tr/>
						<td>Player Items </td><td><a href='?pg=rank&type=set_char&name=$hData[CharName16]'>Click Here For < $hData[CharName16] > Items</a></td><tr/>
						<td>Health points</td><td><span class='hp'>&nbsp;&nbsp; $hData[HP] &nbsp;&nbsp;</span></td><tr/>
						<td>Mana points</td><td><span class='mp'>&nbsp;&nbsp; $hData[MP] &nbsp;&nbsp;</span></td><tr/>
						<td>Job alias</td><td>$hData[NickName16]</td><tr/>
						<td>Level</td><tD>$hData[CurLevel]</td><tr/>
						<td>Experience</td><td>$hData[ExpOffset]</td><tr/>
						<td>Strength</td><td>$hData[Strength]</td><tr/>
						<td>Intellect</td><td>$hData[Intellect]</td><tr/>
						<td>Skill points</td><td>$hData[RemainSkillPoint]</td><tr/>
						<td>Free stat points</td><td>$hData[RemainStatPoint]</td><tr/>
						<td>Berserker</td><td>$hData[RemainHwanCount]/5</td><tr/>
						<td>Player Status</td><td>$StatusIS</td><tr/>
						<td>Last Logout</td><td>$hData[LastLogout]</td><tr/>
						</table>
						<br /><br />
						<u>Unique Kills (No Titans):</u>
						<br /><br />
						<table id='table-3' border='0' cellspacing='0' cellpadding='0'>
						<td>Tiger Girl Kills</td><td>$uniquetg</td><tr/>
						<td>Cerberus Kills</td><td>$uniqueker</td><tr/>
						<td>Captain Ivy</td><td>$uniqueivy</td><tr/>
						<td>Uruchi</td><td>$uniqueuruchi</td><tr/>
						<td>Isyutaru</td><td>$uniqueisy</td><tr/>
						<td>Lord Yarkan</td><td>$uniquelord</td><tr/>
						<td>Demon Shaitan</td><td>$uniquedemon</td><tr/>
						<td>SoSo The Black Viper</td><td>$uniquesoso</td><tr/>
						<td>BeakYung The White Viper (Medusa)</td><td>$uniquemedusa</td>
						</table>
						</td>
							";
			

			core::$sql -> changeDB('acc');
			if(core::$sql -> numRows("select * from Evangelion_uniques where CharName='$hData[CharName16]'") == 0)
			{
			echo "<td>This character has no uniques kills ! </td></table>";
			}
			else
			{
			echo "<td><u>Latest Unique Kills by :$hData[CharName16] </u><br /><br />
			<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
					<td>Unique name</td><td>Time</td><tr/>";
			$hQuery = core::$sql -> exec("select top 30 * from Evangelion_uniques where CharName='$hData[CharName16]' order by time desc");
			while($row = mssql_fetch_array($hQuery))
			{
				$datetime1 = strtotime($row['time']);
				$mssqldate1 = date("d-m-y", $datetime1);
				echo "<tr>";
				$UniqueType = $row['MobName'];
				switch($UniqueType) {
				// Tiger Girl
				case "MOB_CH_TIGERWOMAN":
				echo '<td>Tiger Girl</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_CH_TIGERWOMAN_L3":
				echo '<td>Tiger Girl (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				// XXX
				case "MOB_OA_URUCHI":
				echo '<td>Uruchi</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_KK_ISYUTARU":
				echo '<td>Isyutaru</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_TK_BONELORD":
				echo '<td>Lord Yarkan</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_EU_KERBEROS":
				echo '<td>Cerberus</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_AM_IVY":
				echo '<td>Captain Ivy</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_RM_TAHOMET":
				echo '<td>Demon Shaitan</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_KK_ISYUTARU_L3":
				echo '<td>Isyutaru (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_TK_BONELORD_L3":
				echo '<td>Lord Yarkan (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_RM_TAHOMET_L3":
				echo '<td>Demon Shaitan (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_EU_KERBEROS_L3":
				echo '<td>Cerberus (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_AM_IVY_L3":
				echo '<td>Captain Ivy (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_OA_URUCHI_L3":
				echo '<td>Uruchi (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				case "MOB_TQ_BLACKSNAKE_L3":
				echo '<td>SoSo The Hades Viper (Titan)</td><td>'.$mssqldate1.'</td>';
				break;
				default:
				break;
				}
				echo "<tr/>";
			}


			echo "</table></td></table>";

			core::$sql -> changeDB('shard');
			}
			misc::back();
			} else echo "Owner of account on which this character is created didn't want you to view he's (her) data.<br/>";
			}
			} 
			else 
			{
			core::$sql -> changeDB("shard");
			$hQuery = core::$sql -> exec("select top 50 * from _Char where CharName16 not like '%[GM]%' order by CurLevel desc");
			
			echo "<table id='table-3' width='100%' border='0' cellpadding='0' cellspacing='0'>
			<td width='5%' align='center' class='thead'>Rank</td>
			<td width='5%' align='center' class='thead'>Race</td>
			<td width='30%' align='center' class='thead'>Nick</td>
			<td width='15%' align='center' class='thead'>Level</td>
			<td width='15%' align='center' class='thead'>SP</td>
			<td width='15%' align='center' class='thead'>Strength</td>
			<td width='15%' align='center' class='thead'>Intellect</td><tr/>";
			$n = 1;
			while($row = mssql_fetch_array($hQuery))
			{
				
				$szUsername = user::usernameByCharname($row['CharName16']);
				$bCanView = core::$sql -> getRow("select ispublic from srcms_userprofiles where JID='".user::accountJIDbyUsername($szUsername)."'");
				if($bCanView > 0)
				{
					
				$icon = "";
					if($row['RefObjID'] < 3000) $icon = "<img src='img/Character/race_china.png'>";
					else $icon="<img src='img/Character/race_euro.png'>";
					echo "<td align='center'>$n</td>
					<td align='center'>$icon</td>
					<td align='center'><a href='?pg=rank&type=char&name=$row[CharName16]'>$row[CharName16]</a></td>
					<td align='center'>$row[CurLevel]</td>
					<td align='center'>$row[RemainSkillPoint]</td>
					<td align='center'>$row[Strength]</td>
					<td align='center'>$row[Intellect]</td><tr/>";
					$n++;

				}
			}
			echo "</table>";
		}
	}
	break;
	
	//Search Character Script.
	
	case('search_char'):
	{
		if(!isset($_POST['searchfor']))
		{
		
			echo " Max results: 50 !<br />
				<form method='post'>
				Charname: <input type='text' name='searchfor' maxlength='16' placeholder='Nickname'>
				<br />
				<input type='submit' name='submit' value='Search'>
				</form>
				";
		}
		else
		{
			$bExit = false;
			if(!security::isSecureString($_POST['searchfor'], 3))
			{
				$bExit = true;
				echo "Character name contains forbidden symbols !<br />";
			}
			if(strlen($_POST['searchfor']) == 0)
			{
				$bExit = true;
				echo "Character name can't be 0 symbols long !<br />";
			}
			if(strlen($_POST['searchfor']) > 16)
			{
				$bExit = true;
				echo "Character name too long !<br />";
			}
			
			if(!$bExit)
			{
				echo "<br />";
				core::$sql -> changeDB('shard');
				$hQuery = core::$sql -> exec("select top 50 * from _Char where CharName16 like '%$_POST[searchfor]%'");
				$nResults = 0;
				echo "<table id='table-3' border='0' cellpadding='0' cellspacing='0'><td>Char name</td><tr />";
				while($row = mssql_fetch_array($hQuery))
				{
					echo "<td><a href='?pg=rank&type=char&name=$row[CharName16]'>$row[CharName16]</a></td><tr />";
					$nResults++;
				}
				echo "</table>";
				if($nResults > 0) 
				{
					echo "<br />Found [$nResults] characters !<br /></table>";
				}
				else 
				echo "No characters found <br />";
			}
		}
		echo "<br /><br />";
		misc::back();
	
		
	}
	break;
	
	//Search Guild System
	
	case('search_guild'):
	{
		core::$sql -> changeDB('shard');
		if(!isset($_POST['search_for']))
		{
			echo "<form method='post'> 
					Guild name: <input type='text' name='search_for' maxlength='16' placeholder='Guild name to search for'><br />
					<input type='submit' name='submit' value='Search'>
					</form>";
		}
		else
		{	
			$bExit = false;
			if(!security::isSecureString($_POST['search_for'], 3))
			{
				$bExit = true;
				echo "Guild name contains forbidden symbols !<br />";
			}
			
			if(strlen($_POST['search_for']) == 0)
			{
				$bExit = true;
				echo "Guild name length can't be 0<br />";
			}
			
			if(strlen($_POST['search_for']) > 16)
			{
				$bExit = true;
				echo "Guild name too long<br />";
			}
			
			if(!$bExit)
			{
				core::$sql -> changeDB('shard');
				
				$hQuery = core::$sql -> exec("select top 50 * from _Guild where Name like '%$_POST[search_for]%'");
				
				if($nResults = mssql_num_rows($hQuery) > 0)
				{
					echo "<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
							<td>Guild name</td><tr />
							";
						while($row = mssql_fetch_array($hQuery))
						{
							echo "<td><a href='?pg=rank&type=guild&name=$row[Name]'>$row[Name]</a></td><tr />";
						}
						echo "</table><br /><br />Total results: [$nResults]<br />";
					
					
				}
				else echo "No results !<br /><br />";
				
			
			}
		}
		echo "<br /> <br />";
			misc::back();
	}
	break;
	
	//Guilds Ranking
	
	case('guild'):
	{
		core::$sql -> changeDB('shard');
		if(!isset($_GET['name']))
		{
		
			$hQuery = core::$sql -> exec("select top 50 * from _Guild where ID > 0 and ID != 24 order by Lvl desc,GatheredSP desc");
			
			echo "<table id='table-3' class='tborder' width='100%' border='0' cellpadding='0' cellspacing='0'>
					<td align='center' class='thead'>Rank</td>
					<td align='center' class='thead'>Name</td>
					<td align='center' class='thead'>Level</td>
					<td align='center' class='thead'>Members</td>
					<td align='center' class='thead'>Points</td><tr/>
					";
			$nGuild = 1;
			while($row = mssql_fetch_array($hQuery))
			{
				$nMembers = core::$sql -> getRow("select count(*) from _GuildMember where GuildID='$row[ID]'");
				echo "
				<td align='center'>$nGuild</td>
				<td align='center'><a href='?pg=rank&type=guild&name=$row[Name]'>$row[Name]</a></td>
				<td align='center'>$row[Lvl]</td>
				<td align='center'>$nMembers</td>
				<td align='center'>$row[GatheredSP]</td><tr/>";
				$nGuild++;
			}
			echo "</table>";
			misc::back();
		}
		else
		{
			if(security::isSecureString($_GET['name'], 3))
			{
				if(core::$sql -> numRows("select * from _Guild where Name='$_GET[name]'") == 0)
				{
					echo "Guild with such name not found.";
				}
				else
				{
					$hGuildData = mssql_fetch_array(mssql_query("select * from _Guild where Name='$_GET[name]'"));
					$hGuildMembers = core::$sql -> exec("select * from _GuildMember where GuildID='$hGuildData[ID]' order by MemberClass asc,Contribution DESC,GuildWarKill DESC,CharLevel DESC,GP_Donation DESC");
			
					/*
					for guild Gold add those lines :
					
					<td align='center'>Gold</td>
					<td align='center'>$hGuildData[Gold]</td>
					
					*/
					
					echo "<table id='table-3' class='tborder' width='100%' border='0' cellpadding='0' cellspacing='0'>
							<td align='center'>Name</td>
							<td align='center'>$hGuildData[Name]</td><tr/>
							
							<td align='center'>Level</td>
							<td align='center'>$hGuildData[Lvl]</td><tr/>
							
							<td align='center'>Points</td>
							<td align='center'>$hGuildData[GatheredSP]</td><tr/>
							
							<td align='center'>Foundation</td>
							<td align='center'>$hGuildData[FoundationDate]</td><tr/>
						</table>
						<br/><br/>";
						
						echo "<table  id='table-3' class='tborder' width='100%' border='0' cellpadding='0' cellspacing='0'>
								<td width='5%' align='center' class='thead'>Rank</td>
								<td width='5%' align='center' class='thead'>Race</td>
								<td width='15%' align='center' class='thead'>Charname</td>
								<td width='15%' align='center' class='thead'>Nick</td>
								<td width='10%' align='center' class='thead'>Level</td>
								<td width='15%' align='center' class='thead'>Donation</td>
								<td width='15%' align='center' class='thead'>Guild War Kills</td>
								<td width='15%' align='center' class='thead'>Guild War Killed</td>
								<td width='15%' align='center' class='thead'>Type</td><tr/>
								";
							$n = 1;
						while($row = mssql_fetch_array($hGuildMembers))
						{
							$cName = char::charnameByCharID($row['CharID']);
							$szNickname = "";
							
							$nRefObjID = core::$sql -> getRow("select RefObjID from _Char where CharName16='$cName'");
							$icon = "";
							if($nRefObjID < 3000) $icon = "<img src='img/Character/race_china.png'>";
							else $icon="<img src='img/Character/race_euro.png'>";
							
							$memberType = "";
							if($row['MemberClass'] == 0) $memberType = "<font color='green'>Master</font>";
							else
							$memberType = "Member";
							
							if(empty($row['Nickname'])) $szNickname = "<font color='blue'>NONE</font>";
							else $szNickname = $row['Nickname'];
							echo "
							<td align='center'>$n</td>
							<td align='center'>$icon</td>
							<td align='center'><a href='?pg=rank&type=char&name=$cName'>$cName</a></td>
							<td align='center'>$szNickname</td>
							<td align='center'>$row[CharLevel]</td>
							<td align='center'>$row[GP_Donation]</td>
							<td align='center'>$row[GuildWarKill]</td>
							<td align='center'>$row[GuildWarKilled]</td>
							<td align='center'>$memberType</td><tr/>";
							$n++;
						}
						echo "</table>";
						misc::back();
						
						
				}
			}	else echo "Invalid guild name.";
		}
	}
	break;
	
	//Unique Kills Ranking
	
	case('unique'):
	{
		core::$sql -> changeDB('acc');
		$n = 1;
		$hQuery = core::$sql -> exec("select top 100 * from Evangelion_uniques order by time desc");
		echo "<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td>#</td>
				<td>Charname</td>
				<td>Unique</td>
				<td>Time</td>
				<tr/>";
		while($row = mssql_fetch_array($hQuery))
		{
				$UniqueType = $row['MobName'];
				switch($UniqueType) {
				// Tiger Girl
				case "MOB_CH_TIGERWOMAN":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Tiger Girl</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Cerberus
				case "MOB_EU_KERBEROS":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Cerberus</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Captain Ivy
				case "MOB_AM_IVY":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Captain Ivy</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Uruchi
				case "MOB_OA_URUCHI":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Uruchi</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Isyutaru
				case "MOB_KK_ISYUTARU":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Isyutaru</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Lord Yarkan
				case "MOB_TK_BONELORD":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Lord Yarkan</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Demon Shaitan
				case "MOB_RM_TAHOMET":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>Demon Shaitan</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Medusa
				case "MOB_TQ_BLACKSNAKE":
				echo '<td>'.$n.'</td><td><a href="?pg=rank&type=char&name='.$row['CharName'].'">'.$row['CharName'].'</a></td><td>SoSo The Black Viper	</td><td>'.$row['time'].'</td><tr/>';
				break;
				//Anything Else will be posted has pk2 name (must be added to those lines if you wanna add a real name.
				default:
				break;
				}
			$n++;
		}
		
		echo "</table>";
	
	
	}
	break;
	
	// Job Ranking
	
	case('job'):
	{
		core::$sql -> changeDB("shard");
		$hQuery = core::$sql -> exec("select top 50 * from _CharTrijob order by Contribution desc, Exp desc, Level desc");
		echo "
		<table id='table-3' border='0'>
		<tr>
		<td align='center'><a href='?pg=rank&type=jobtrader'><img src='img/trader-icon.png' alt='Trader'/> Trader</a></td>
		<td align='center'><a href='?pg=rank&type=jobthief'><img src='img/thief-icon.png' alt='Thief'/>  Thief</a></td>
		<td align='center'><a href='?pg=rank&type=jobhunter'><img src='img/hunter-icon.png' alt='Hunter'/> Hunter</a></td>
		<td align='center'><a href='?pg=rank&type=job'><img src='img/trader-icon.png' alt='Trader'/><img src='img/hunter-icon.png' alt='Hunter'/><img src='img/thief-icon.png' alt='Thief'/> All</a></td>
		</tr>
		</table>
		<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td align='center'>Rank</td><td align='center'>Char name</td><td align='center'>Job type</td><td align='center'>Exp</td><td align='center'>Contribution</td><tr />";
		$n = 1;
		while($row = mssql_fetch_array($hQuery))
		{
			$jobType = char::jobTypeByID($row['JobType']);
			$charName = char::charnameByCharID($row['CharID']);
			echo "
			<td align='center'>$n</td><td align='center'><a href='?pg=rank&type=char&name=$charName'>$charName</a></td><td align='center'>$jobType</td><td align='center'>$row[Exp]</td><td align='center'>$row[Contribution]</td><tr />";
			$n++;
		}
		echo "</table>";
		break;
		
	}
	
	//Job Ranking - Thiefs Only	

	case('jobthief'):
	{
		core::$sql -> changeDB("shard");
		$hQuery = core::$sql -> exec("select top 50 * from _CharTrijob where JobType = 2 order by Contribution desc, Exp desc, Level desc");
		echo "
		<table id='table-3' border='0'>
		<tr>
		<td align='center'><a href='?pg=rank&type=jobtrader'><img src='img/trader-icon.png' alt='Trader'/> Trader</a></td>
		<td align='center'><a href='?pg=rank&type=jobthief'><img src='img/thief-icon.png' alt='Thief'/>  Thief</a></td>
		<td align='center'><a href='?pg=rank&type=jobhunter'><img src='img/hunter-icon.png' alt='Hunter'/> Hunter</a></td>
		<td align='center'><a href='?pg=rank&type=job'><img src='img/trader-icon.png' alt='Trader'/><img src='img/hunter-icon.png' alt='Hunter'/><img src='img/thief-icon.png' alt='Thief'/> All</a></td>
		</tr>
		</table>

		<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td align='center'>Rank</td><td align='center'>Char name</td><td align='center'>Job type</td><td align='center'>Exp</td><td align='center'>Contribution</td><tr />";
		$n = 1;
		while($row = mssql_fetch_array($hQuery))
		{
			$jobType = char::jobTypeByID($row['JobType']);
			$charName = char::charnameByCharID($row['CharID']);
			echo "<td align='center'>$n</td><td align='center'><a href='?pg=rank&type=char&name=$charName'>$charName</a></td><td align='center'>$jobType</td><td align='center'>$row[Exp]</td><td align='center'>$row[Contribution]</td><tr />";
			$n++;
		}
		echo "</table>";
		break;
		
	}
	
	//Job Ranking - Hunters Only
	
	case('jobhunter'):
	{
		core::$sql -> changeDB("shard");
		$hQuery = core::$sql -> exec("select top 50 * from _CharTrijob where JobType = 3 order by Contribution desc, Exp desc, Level desc");
		echo "
		<table id='table-3' border='0'>
		<tr>
		<td align='center'><a href='?pg=rank&type=jobtrader' ><img src='img/trader-icon.png' alt='Trader'/> Trader</a></td>
		<td align='center'><a href='?pg=rank&type=jobthief'><img src='img/thief-icon.png' alt='Thief'/>  Thief</a></td>
		<td align='center'><a href='?pg=rank&type=jobhunter'><img src='img/hunter-icon.png' alt='Hunter'/> Hunter</a></td>
		<td align='center'><a href='?pg=rank&type=job'><img src='img/trader-icon.png' alt='Trader'/><img src='img/hunter-icon.png' alt='Hunter'/><img src='img/thief-icon.png' alt='Thief'/> All</a></td>
		</tr>
		</table>

		<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td align='center'>Rank</td><td align='center'>Char name</td><td align='center'>Job type</td><td align='center'>Exp</td><td align='center'>Contribution</td><tr />";
		$n = 1;
		while($row = mssql_fetch_array($hQuery))
		{
			$jobType = char::jobTypeByID($row['JobType']);
			$charName = char::charnameByCharID($row['CharID']);
			echo "<td align='center'>$n</td><td align='center'><a href='?pg=rank&type=char&name=$charName'>$charName</a></td><td align='center'>$jobType</td><td align='center'>$row[Exp]</td><td align='center'>$row[Contribution]</td><tr />";
			$n++;
		}
		echo "</table>";
		break;
		
	}
	
	//Job Ranking - Trader Only
	
	case('jobtrader'):
	{
		core::$sql -> changeDB("shard");
		$hQuery = core::$sql -> exec("select top 50 * from _CharTrijob where JobType = 1 order by Contribution desc, Exp desc, Level desc");
		echo "
		<table id='table-3' border='0'>
		<tr>
		<td align='center'><a href='?pg=rank&type=jobtrader'><img src='img/trader-icon.png' alt='Trader'/> Trader</a></td>
		<td align='center'><a href='?pg=rank&type=jobthief'><img src='img/thief-icon.png' alt='Thief'/>  Thief</a></td>
		<td align='center'><a href='?pg=rank&type=jobhunter'><img src='img/hunter-icon.png' alt='Hunter'/> Hunter</a></td>
		<td align='center'><a href='?pg=rank&type=job'><img src='img/trader-icon.png' alt='Trader'/><img src='img/hunter-icon.png' alt='Hunter'/><img src='img/thief-icon.png' alt='Thief'/> All</a></td>
		</tr>
		</table>

		<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td align='center'>Rank</td><td align='center'>Char name</td><td align='center'>Job type</td><td align='center'>Exp</td><td align='center'>Contribution</td><tr />";
		$n = 1;
		while($row = mssql_fetch_array($hQuery))
		{
			$jobType = char::jobTypeByID($row['JobType']);
			$charName = char::charnameByCharID($row['CharID']);
			echo "<td align='center'>$n</td><td align='center'><a href='?pg=rank&type=char&name=$charName'>$charName</a></td><td align='center'>$jobType</td><td align='center'>$row[Exp]</td><td align='center'>$row[Contribution]</td><tr />";
			$n++;
		}
		echo "</table>";
		break;
		
	}
	
	//Honor Ranking
	
	case('honor'):
	{
		core::$sql -> changeDB("shard");
		
		$hHonorRank = core::$sql -> exec("select * from _TrainingCampHonorRank where CampID IS NOT NULL order by Ranking ASC");
		
		echo "<table id='table-3' border='0' cellpadding='0' cellspacing='0'>
				<td align='center'>Rank</td><td align='center'>Owner</td><td align='center'>Graduates</td><tr/>
			";
		while($row = mssql_fetch_array($hHonorRank))
		{
			$data = misc::getCampDataByID($row['CampID']);
			echo "<td align='center'>$row[Ranking]</td><td align='center'><a href='?pg=rank&type=char&name=$data[OwnerName]'>$data[OwnerName]</a></td><td align='center'>$data[GraduateCount]</td><tr/>";
		}
		echo "</table>";
	
	}
	break;
	
	//We'll be added in future updates.
	
	case('set_plus'):
		core::$sql -> changeDB("shard");
		echo "
		<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
		<tr>
		<td>Char Name</td>
		<td>Image</td>
		<td>Item Name</td>
		<td>Type</td>
		<td>Level</td>
		<td>Plus Value</td>
		</tr>
		";
		$query = core::$sql -> exec("
			select top 50 it.OptLevel, ch.CharName16, obj.AssocFileIcon128, obj.Country, obj.CodeName128, obj.ReqLevel1 , item.ItemClass , adv.nOptValue ,es.EndTextString
			from _Items as it
			LEFT JOIN [dbo].[_Inventory] as inv ON it.ID64 = inv.ItemID
			LEFT JOIN [dbo].[_Char] as ch ON inv.CharID = ch.CharID
			LEFT JOIN [dbo].[_RefObjCommon] as obj ON it.RefItemID = obj.ID
			LEFT JOIN [dbo].[_RefObjItem] as item ON obj.Link = item.ID
			LEFT JOIN [dbo].[_BindingOptionWithItem] as adv ON it.ID64 = adv.nItemDBID
			LEFT JOIN [dbo].[C_EquipStrings] as es on obj.NameStrID128 = es.TextString
			where ch.CharName16 is not NULL and CodeName128 not like '%stone%' and CharName16 not like '%]%'
			ORDER BY it.OptLevel DESC, obj.ReqLevel1 DESC, item.ItemClass DESC, adv.nOptValue DESC
		");
			
		while ($row = mssql_fetch_array($query)){		
		echo '<tr>';
		echo '<td align="center"><a href="?pg=rank&type=char&name='.$row['CharName16'].'">'.$row['CharName16'].'</a></td>';
        echo '<td align="center"><img src="./'.$row['AssocFileIcon128'].'.png" width="32" height="32"  alt=""/></td>';
		echo '<td>'.$row['EndTextString'].'</td>';
		$totalvalue = $row['OptLevel']+$row['nOptValue'];
		$advonly = $row['nOptValue'];
		$itemclass = $row['ItemClass'];
		switch($itemclass) {
		case 1:
		echo '<td>Normal</td>';
		break;
		case 2:
		echo '<td>Seal Of Moon</td>';
		break;
		case 3:
		echo '<td>Seal Of Sun</td>';
		break;
		case 4:
		echo '<td>Normal</td>';
		break;
		case 5:
		echo '<td>Seal Of Moon</td>';
		break;
		case 6:
		echo '<td>Seal Of Sun</td>';
		break;
		case 7:
		echo '<td>Normal</td>';
		break;
		case 8:
		echo '<td>Seal Of Star</td>';
		break;
		case 9:
		echo '<td>Seal Of Moon</td>';
		break;
		case 10:
		echo '<td>Seal Of Sun</td>';
		break;
		case 11:
		echo '<td>Normal</td>';
		break;
		case 12:
		echo '<td>Seal Of Star</td>';
		break;
		case 13:
		echo '<td>Seal Of Moon</td>';
		break;
		case 14:
		echo '<td>Seal Of Sun</td>';
		break;
		case 15:
		echo '<td>Normal</td>';
		break;
		case 16:
		echo '<td>Seal Of Star</td>';
		break;
		case 17:
		echo '<td>Seal Of Moon</td>';
		break;
		case 18:
		echo '<td>Seal Of Sun</td>';
		break;
		case 19:
		echo '<td>Normal</td>';
		break;
		case 20:
		echo '<td>Seal Of Star</td>';
		break;
		case 21:
		echo '<td>Seal Of Moon</td>';
		break;
		case 22:
		echo '<td>Seal Of Sun</td>';
		break;
		case 23:
		echo '<td>Normal</td>';
		break;
		case 24:
		echo '<td>Seal Of Star</td>';
		break;
		case 25:
		echo '<td>Seal Of Moon</td>';
		break;
		case 26:
		echo '<td>Seal Of Sun</td>';
		break;
		case 27:
		echo '<td>Normal</td>';
		break;
		case 28:
		echo '<td>Seal Of Star</td>';
		break;
		case 29:
		echo '<td>Seal Of Moon</td>';
		break;
		case 30:
		echo '<td>Seal Of Sun</td>';
		break;
		default:
		echo '<td>Normal</td>';
		break;
		}
		echo '<td align="center">'.$row['ReqLevel1'].'</td>';
		echo '<td>'.$totalvalue.'';
		switch($advonly) {
		case "NULL":
		break;
		case 1:
		echo '+(1) ADV</td>';
		break;
		case 2:
		echo '+(2) ADV</td>';
		break;
		}
		echo '</tr>';
		}	

		echo '</table>';

	break;
	
	
	
	case('set_char'):
$nCharName = $_GET['name'];
core::$sql -> changeDB("shard");
echo "
<table id='table-3' border='1' cellpadding='0' cellspacing='0'>
<tr>
<td>Char Name</td>
<td>Image</td>
<td>Item Name</td>
<td>Type</td>
<td>Level</td>
<td>Plus Value</td>
</tr>
";
$query = core::$sql -> exec("
select it.OptLevel, ch.CharName16, obj.AssocFileIcon128, obj.Country, obj.CodeName128 ,obj.ReqLevel1 , item.ItemClass , adv.nOptValue ,es.EndTextString
from _Items as it
LEFT JOIN [dbo].[_Inventory] as inv ON it.ID64 = inv.ItemID
LEFT JOIN [dbo].[_Char] as ch ON inv.CharID = ch.CharID
LEFT JOIN [dbo].[_RefObjCommon] as obj ON it.RefItemID = obj.ID
LEFT JOIN [dbo].[_RefObjItem] as item ON obj.Link = item.ID
LEFT JOIN [dbo].[_BindingOptionWithItem] as adv ON it.ID64 = adv.nItemDBID
LEFT JOIN [dbo].[C_EquipStrings] as es on obj.NameStrID128 = es.TextString
where ch.CharName16 = '$nCharName' and inv.Slot between 0 and 12 and inv.Slot != 8
");
while ($row = mssql_fetch_array($query)){	
echo '<tr>';
echo '<td align="center"><a href="?pg=rank&type=char&name='.$row['CharName16'].'">'.$row['CharName16'].'</a></td>';
if($row['AssocFileIcon128'] == 'xxx'){
echo '<td><img src="./item/clean.png" width="32" height="32"  alt=""/></td>';
} else {
echo '<td><img src="./'.$row['AssocFileIcon128'].'.png" width="32" height="32"  alt=""/></td>';
}
if($row['CodeName128'] == 'DUMMY_OBJECT'){
echo '<td>-</td>';
} else {
echo '<td>'.$row['EndTextString'].'</td>';		
}
$totalvalue = $row['OptLevel']+$row['nOptValue'];
$advonly = $row['nOptValue'];
if($row['CodeName128'] == 'ITEM_ETC_AMMO_ARROW_01' || $row['CodeName128'] == 'ITEM_ETC_AMMO_BOLT_01' || $row['CodeName128'] == 'DUMMY_OBJECT'){
echo '<td>-</td>';
} else {
$itemclass = $row['ItemClass'];
switch($itemclass) {
case 27:
echo '<td>Normal</td>';
break;
case 28:
echo '<td>Seal Of Star</td>';
break;
case 29:
echo '<td>Seal Of Moon</td>';
break;
case 30:
echo '<td>Seal Of Sun</td>';
break;
default:
echo '<td>Normal</td>';
break;
}}
if($row['CodeName128'] == 'ITEM_ETC_AMMO_ARROW_01' || $row['CodeName128'] == 'ITEM_ETC_AMMO_BOLT_01' || $row['CodeName128'] == 'DUMMY_OBJECT'){
echo '<td>-</td>';
} else {
echo '<td>'.$row['ReqLevel1'].'</td>';
}
if($row['CodeName128'] == 'ITEM_ETC_AMMO_ARROW_01' || $row['CodeName128'] == 'ITEM_ETC_AMMO_BOLT_01' || $row['CodeName128'] == 'DUMMY_OBJECT'){
echo '<td>-</td>';
} else {
echo '<td>'.$totalvalue.'';
switch($advonly) {
case "NULL":
break;
case 1:
echo '+(1) ADV</td>';
break;
case 2:
echo '+(2) ADV</td>';
break;
}}
echo '</tr>';
}	

echo '</table>';
misc::back();
	break;
	
	// Default Page (Without Page Selected) .
	
	default:
	{
		//list functions
		
		break;
	}
}

?>