<?php
// conectar
$m = new MongoClient();

// seleccionar una base de datos
$bd = $m->lastfm;

// seleccionar una coleccióequivalente a una tabla en una base de datos relacional)
$coleccion = $bd->artist;

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
			$doc = array();
			break;
		case 1:
			$doc = array( "_id" => $atributes[0]);
			break;
		case 2:
			$doc = array( "_id" => $atributes[0], "name" => $atributes[1]);
			break;	
		case 3:
			$doc = array( "_id" => $atributes[0], "name" => $atributes[1], "url" => $atributes[2]);
			break;		
		case 4	:
			$doc = array( "_id" => $atributes[0], "name" => $atributes[1], "url" => $atributes[2], "pictureurl" => $atributes[3]);
			break;	
		default:
			$doc = array();
			echo "Error en registro\n";
			
	}		
	$coleccion->insert($doc);
	
	//print_r($doc);
	$i = $i +1;
}
fclose($file);
echo $i; 
?>
