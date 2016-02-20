<?php
require('vendor/autoload.php');

$client = new Everyman\Neo4j\Client('localhost', 7474);
$label = $client->makeLabel('user');
for( $i=2; $i <= 2100; $i++) {
	// reate the users for test (all the users have the same name: Thomas)
	$user = $client->makeNode();
	$user->setProperty('id', $i)
         ->setProperty('name', 'Thomas')
         ->save();
	$labels = $user->addLabels(array($label));
	$userId = $user->getId();
	//echo "User created with id: " . $bobId;
}



?>
