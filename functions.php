<?php
if (isset($_REQUEST['uid']))
  $uid = $_REQUEST['uid'];
else 
  $uid = "35";

function printHeader() {
	echo "<html>\n";
	echo "<head><title>COMP5338 - Project</title></head>\n";
	echo "<body>\n";

}
function printFooter() {
	echo "</body>\n";
	echo "</html>\n";

}

function printSearchForm($u) {
	echo 'Search Artist | <a href="recomendationscount.php?uid='.$u.'">Listened by my friends order by listening count</a> | <a href="recomendationsfriends.php?uid='.$u.'">Artist listened by my friends</a> | <a href="recomendationstags.php?uid='.$u.'">Recomendations by Random Tag</a><br><br>';
	echo '<form action="artists.php" method="post">';
	echo '<input type="hidden" name="action" value="search">';
	echo '<input type="hidden" name="uid" value="'.$u.'">';
	echo '<input type="text" name="patern" value="">';
	echo '<input type="submit" value="search">';
	echo '</form>';

}
function printArtistInfo($a) {

	$m = new MongoClient();
	$collection = $m->lastfm->artist;

	$cursor = $collection->find(array('_id'=>$a));

	foreach ($cursor as $doc) {
    	echo "Artist WebSite: <a href='".$doc["url"]."'>" .$doc["url"] . "</a></br>"; 
		echo "<img src='".$doc["pictureurl"]."'></br>"; 
	}
}
?>