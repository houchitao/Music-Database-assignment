<?php
// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);
$label = $client->makeLabel('artist');


$file = fopen("./database/artists.dat", "r") or exit("Unable to open file!");
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
			// Do Nthing
			break;
		case 1:
			// Do nothing
			break;
		case 2:
			// Only add Artist with id and name
			$id = $atributes[0];
			$name = $atributes[1];
			
			$artist = $client->makeNode();
			
			$artist->setProperty('id', $id)
				->setProperty('name', $name)
				->save();
			//$artistId = $artist->getId();
			$labels = $artist->addLabels(array($label));
			//$bobId = $bob->getId();

			//echo "Artist created with id: " . $artistId;
			break;	
		case 3:
			$id = $atributes[0];
			$name = $atributes[1];
			$artist = $client->makeNode();
			$artist->setProperty('id', $id)
				->setProperty('name', $name)
				->save();
			$labels = $artist->addLabels(array($label));
			break;		
		case 4	:
			$id = $atributes[0];
			$name = $atributes[1];
			$artist = $client->makeNode();
			$artist->setProperty('id', $id)
				->setProperty('name', $name)
				->save();
			$labels = $artist->addLabels(array($label));
			break;	
		default:
			$doc = array();
			echo "Error en registro\n";
			
	}		
	
	//print_r($doc);
	$i = $i +1;
}
fclose($file);
echo $i . " Artists imported\n"; 
?>
