<?php

$hQuery = core::$sql -> exec("select * from srcms_downloads order by id desc");


echo "<table border='0' id='table-3' cellpadding='0' cellspacing='0'>
	<td align='center'>Name</td><td align='center'>Link</td><td align='center'>Description</td><tr/>";
while($row = mssql_fetch_array($hQuery))
{
	$szName = security::fromHTML($row['name']);
	$szDesc = security::fromHTML($row['description']);
	echo "<td align='center'>$szName</td><td align='center'><a href='$row[link]' target='blank'><b>CLICK HERE</b></a></td>
		  <td align='center'>$szDesc</td><tr/>";
}

echo "</table>";
?>