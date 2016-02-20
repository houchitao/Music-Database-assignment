<?php

// Include library of functions
include("functions.php");

// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);

printHeader();
printSearchForm($uid);  
echo "<B>User: $uid - Recomendations by Random Tag</b><br>";

$queryString = "match (n:user{id:$uid})-[m:TAGS_TO]-(:artist) with count(m)as num return num";

echo "\n<!-- $queryString -->\n";

$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();		
foreach ($result as $row) {
	$count_tag = $row['x'];
}
$random_tag = rand(0,$count_tag-1);


$queryString = "match (n:user{id:$uid})-[m:TAGS_TO]-(:artist) return m.id";
echo "\n<!-- $queryString -->\n";
$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();

$counter = 0;
$randomTagId = 0;
			
foreach ($result as $row) {

	//echo "tag id: " . $row['x']. " | ";
	//echo "counter: $counter <br>";

	if ($counter == $random_tag)
		$randomTagId = $row['x'];
	$counter++;
}
// echo "<br>randomTag counter = $random_tag<br>";
//echo "<br>randomTagId  = $randomTagId<br>";

$queryString = "match (t:tag{id:$randomTagId}) return t.name";
echo "\n<!-- $queryString -->\n";
$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();
			
foreach ($result as $row) {
	echo "Random Tag: " . $row['x']. "<br>";
}	

	

// Finally get the artist

$queryString = "MATCH (t:user{id:$uid})-[tag: TAGS_TO{ id: $randomTagId}]-(a:artist)-[:WEIGHT]-(u:user) 
WHERE NOT (t)-[:WEIGHT]-(a)
WITH a, count(u) as count
RETURN a order by count desc Limit 5";

echo "\n<!-- $queryString -->\n";

$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();
			
foreach ($result as $row) {
	$artist = $client->getNode($row['x']->getId());
	$artistId = $artist->getProperty('id');
	$artistName = $artist->getProperty('name');
		
	echo "<br>Artist Name: ".$artistName."</br>"; 
	printArtistInfo($artistId);

	?>
	<form action="listen.php" method="post">
	<input type="hidden" name="action" value="addlisten">
	<input type="hidden" name="userId" value="<? echo $uid ?>">
	<input type="hidden" name="artistId" value="<? echo $artistId ?>">
	<input type="submit" value="Listen">
	</form>
	<?
	
	
}

printFooter();
?>