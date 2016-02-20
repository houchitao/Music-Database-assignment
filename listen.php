<?php
// Include library of functions
include("functions.php");

// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);


// Action from the form
if (isset($_REQUEST['action']))
  $action = $_REQUEST['action'];
else 
  $action="";
  
if (isset($_REQUEST['userId']))
  $userId = $_REQUEST['userId'];
else 
  $userId="";  
  
if (isset($_REQUEST['artistId']))
  $artistId = $_REQUEST['artistId'];
else 
  $artistId="";  

printSearchForm($userId); 
  
echo '<img src="images/headphones.png"><br><br>';
  
switch ($action) {
	case "addlisten":
			
			$queryString = "match (n:user {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString,array('id' => (int)$userId));
			$result = $query->getResultSet();
			
			foreach ($result as $row) {
				$user = $client->getNode($row['x']->getId());
				$userName = $user->getProperty('name');
			}
			
			//echo "User from form:<br>";
			//foreach ($user->getProperties() as $key => $value) {
			//	echo "$key: $value<br>";
			//}
			
			$queryString = "match (n:artist {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString,array('id' => $artistId));
			$result = $query->getResultSet();
			
			foreach ($result as $row) {
				$artist = $client->getNode($row['x']->getId());
				$artistName = $artist->getProperty('name');
			}
			/*
			echo "<br>Artist from form:<br>";
				foreach ($artist->getProperties() as $key => $value) {
				echo "$key: $value\n";
			}
			*/
			
			$relationships = $user->getRelationships(array('WEIGHT'));
			
			// Weight is the number of listenings of the user to the artist
			$weight = 0;
			foreach ($relationships as $relationship) {
				$artistrel = $relationship->getEndNode();
			//	echo "<br><br>Relations<br>";
			//	echo "id: " . $relationship->getId() . "\n";
			//	echo "Artist: " . $artistrel->getProperty('name') . "\n";
			//	echo "weight: " . $relationship->getProperty('weight') . "\n";
				//$relationship->delete();
				
				// If the user has listen the artist increment in 1
				if ($artistId == $artistrel->getProperty('id') ) {
					$weight = $relationship->getProperty('weight') + 1;
					$relationship->setProperty('weight', $weight)
								 ->save();
					echo "User " . $userName . " is listening to " . $artistName .".<br><br>";
					echo "Artists has " . $weight . " listening by the user.";
				}
			}
			//echo "<br><br>Peso obtenido: " . $weight . "<br>";
			if ($weight == 0) {
				//Create the relation when the relation does not exist
				
				$user->relateTo($artist, 'WEIGHT')
					 ->setProperty('weight', '1')
					 ->save();
				echo "User " . $userName . " is listening to " . $artistName .".<br><br>";
				echo "Artists has 1 listening by the user.";	 
			}
		break;
}	

?>