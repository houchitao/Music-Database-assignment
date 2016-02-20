<?
// Load the relation TAGS_TO

// userID  artistID        tagID   day     month   year
// 2       52      13      1       4       2009
// 2       52      15      1       4       2009
// 2       52      18      1       4       2009

// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);

$file = fopen("./database/user_taggedartists.dat", "r") or exit("Unable to open file!");

//Output a line of the file until the end is reache
$i=0;
while(!feof($file))
{
	$line = fgets($file);
	$atributes = explode("\t",$line);
	
	switch( count($atributes)) {
		case 6:
			$userId = (int)trim($atributes[0]);
			$artistId = (string)trim($atributes[1]);
			$tagId = (int)trim($atributes[2]);
			$day = (int)trim($atributes[3]);
			$month = (int)trim($atributes[4]);
			$year = (int)trim($atributes[5]);
			
			$date = "$year-$month-$day";
			
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
				
			//echo "$userId->relatedTo($artistId,'TAGS_TO')->setProperty('id','$tagId')->setProperty('date','$year-$month-$day')->save()\n";
			
			$user->relateTo($artist, 'TAGS_TO')
				 ->setProperty('id', $tagId)
				 ->setProperty('date',$date)
				 ->save();
			
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