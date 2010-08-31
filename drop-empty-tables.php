<?php
require ('includes/config.php');
require ('includes/dbconnect.php');

function drop_empty_tables(){
	global $mysql_database;
	/* query all tables */
	$sql = "SHOW TABLES FROM $mysql_database";
	if($result = mysql_query($sql)){
	  /* add table name to array */
	  while($row = mysql_fetch_row($result)){
		$found_tables[]=$row[0];
	  }
	  echo 'Found ' . count($found_tables) . ' tables.<br />' . "\n";
	}
	else{
	  die("Error, could not list tables. MySQL Error: " . mysql_error());
	}
	 
	 if (count($found_tables) > 0) {
		$counter = 0;
		/* loop through and drop each table */
		foreach($found_tables as $table_name){
			$records = mysql_query("SELECT * FROM $table_name");
			if(mysql_num_rows($records) == 0){
			
			  $sql = "DROP TABLE $mysql_database.$table_name";
			  if($result = mysql_query($sql)){
				echo "Deleted table: $table_name<br />\n";
				$counter++;
				}
			  else{
				echo "Error deleting $table_name. MySQL Error: " . mysql_error() . "";
			  }
			}
		}
		echo "<br />Deleted $counter tables.";
	}
}

drop_empty_tables();
?>
