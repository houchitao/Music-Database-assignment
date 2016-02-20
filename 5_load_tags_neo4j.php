<?php
// connection to neo4j
require('vendor/autoload.php');
$client = new Everyman\Neo4j\Client('localhost', 7474);
$label = $client->makeLabel('tag');


$file = fopen("./database/tags.dat", "r") or exit("Unable to open file!");
$log = fopen("./database/tags.log", "w") or exit("Unable to open file!");
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
			// Do Nothing
			break;
		case 1:
			// Do nothing
			break;
		case 2:
			// Create a tag node
			$id = (int)trim($atributes[0]);
			$name = (string)utf8_decode(trim($atributes[1]));
			fwrite($log,"Reading $id | $name\n");
			
			if (!(is_null($name)) and !(is_null($name))) {
			
				$tag = $client->makeNode();
				$tag->setProperty('id', $id)
					->setProperty('name', $name)
					->save();
				$labels = $tag->addLabels(array($label));
			
			}
			else
				echo "Error: id: $id | name: $name\n";
			break;	
		case 3:
	
			break;		
		case 4	:
	
			break;	
		default:
			$doc = array();
			echo "Error en registro\n";
			
	}		
	
	//print_r($doc);
	$i = $i +1;
	if (($i%1000) == 0) echo $i . " tags\n";
}
fclose($file);
fclose($log);
echo $i . " Tags imported\n"; 
?>
