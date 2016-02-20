<?php
// Load the relation user friend

// userID  friendID
// 2       275
// 2       428
// 2       515


// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);

$file = fopen("./database/user_friends.dat", "r") or exit("Unable to open file!");
//Output a line of the file until the end is reache
$i=0;
while(!feof($file))
{
	$line = fgets($file);
	$atributes = explode("\t",$line);
	
	switch( count($atributes)) {
		case 0:
			// Do Nothing
			break;
		case 1:
			// Do nothing
			break;
		case 2:
			// create the relation add_friend_to
			$id1 = (int)trim($atributes[0]);
			$id2 = (int)trim($atributes[1]);
			//echo "Lee ". $id1 . " a friend of ". $id2 ."\n";			
			$userId1 = 0;
			$userId2 = 0;
			
			$queryString = "match (n:user {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString,array('id' => $id1));
			$result = $query->getResultSet();
			
			foreach ($result as $row) {
				$user1 = $client->getNode($row['x']->getId());
				$userId1 = $user1->getProperty('id');
				//echo "user " . $id1 ." founded\n";
			}
			
			$queryString = "match (n:user {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString, array('id' => $id2));
			$result = $query->getResultSet();
			
			foreach ($result as $row) {
				$user2 = $client->getNode($row['x']->getId());
				$userId2 = $user2->getProperty('id');
				//echo "user " . $id2 ." founded\n";
			}
			//echo "Crea relacion " . $userId1 . " -> " . $userId2 ."\n\n";
			$user1->relateTo($user2, 'ADD_FRIEND_TO')->save();
			break;	
		default:
			echo "Error en registro\n";
			
	}		
	
	//print_r($doc);
	$i = $i +1;
}
fclose($file);
echo $i . " Relation ADD_FRIEND_TO created\n"; 
?>
