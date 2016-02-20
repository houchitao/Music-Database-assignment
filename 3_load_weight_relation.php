<?
// Load the relation WEIGHT

// userID  artistID        weight
// 2       51      13883
// 2       52      11690
// 2       53      11351

// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);

$file = fopen("./database/user_artists.dat", "r") or exit("Unable to open file!");

//Output a line of the file until the end is reache
$i=0;
while(!feof($file))
{
	$line = fgets($file);
	$atributes = explode("\t",$line);
	//echo "id = " . $atributes[0] ."\n";
	//echo "name = " . $atributes[1] ."\n";
	//echo "url = " . $atributes[2] ."\n";
	// is_null
	
	switch( count($atributes)) {
		case 0:
			echo "Linea sin registros\n";
			// Do nothing
			break;
		case 1:
			// Do nothing
			break;
		case 2:
			// Do nothing
			break;	
		case 3:
			$userId = (int)trim($atributes[0]);
			$artistId = (string)trim($atributes[1]);
			$weight = (int)trim($atributes[2]);
			
			// Get the user node
			$queryString = "match (n:user {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString,array('id' => $userId));
			$result = $query->getResultSet();

			foreach ($result as $row) {
				$user = $client->getNode($row['x']->getId());
				$userId = $user->getProperty('id');
			}
			
			// Get the Artist node
			$queryString = "match (n:artist {id: {id}}) return n";
			$query = new Everyman\Neo4j\Cypher\Query($client, $queryString,array('id' => $artistId));
			$result = $query->getResultSet();

			foreach ($result as $row) {
				$artist = $client->getNode($row['x']->getId());
				$artistId = $artist->getProperty('id');
			}
			
			
			//Create the relation 
				
			//echo "User: $userId -> related to (Artist: $artistId, 'WEIGHT')->setProperty('weight','$weight')->save()\n";
			
			$user->relateTo($artist, 'WEIGHT')
				 ->setProperty('weight', $weight)
				 ->save();
			
			break;		
		case 4	:

			break;	
		default:
			echo "Error en registro\n";
			
	}		
	
	//print_r($doc);
	$i = $i +1;
	if (($i%1000) == 0) echo $i . " relations\n";
		
}
fclose($file);
echo $i . " relations imported\n"; 

?>