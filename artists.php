<?php
// Include library of functions
include("functions.php");
// conectar
$m = new MongoClient();
$collection = $m->lastfm->artist;
if (isset($_REQUEST['action']))
  $action = $_REQUEST['action'];
else 
  $action="";

printHeader();
switch ($action) {
	case "search":
		// Do the search
		if (isset($_REQUEST['patern']))
			$patern = $_REQUEST['patern'];
		else 
			$patern="";
		$where = array ( 'name' => array('$regex' => new MongoRegex("/$patern/i")));
		$cursor = $collection->find($where);
		printSearchForm($uid); 
		
		echo "<B>User: $uid - Search result</b><br>";
		while ($cursor->hasNext()) {
			$clientObj = $cursor->getNext(); 
            echo "<br>Artist Name: ".$clientObj["name"]."</br>"; 
			echo "Artist WebSite: <a href='".$clientObj["url"]."'>" .$clientObj["url"] . "</a></br>"; 
			echo "<img src='".$clientObj["pictureurl"]."'></br>"; 
			//echo "<br>";
			?>
			<form action="listen.php" method="post">
			<input type="hidden" name="action" value="addlisten">
			<input type="hidden" name="userId" value="<? echo $uid ?>">
			<input type="hidden" name="artistId" value="<? echo $clientObj["_id"] ?>">
			<input type="submit" value="Listen">
			</form>
			<?
		}	
		break;
	default:
	printSearchForm($uid);
}
printFooter();
?>
