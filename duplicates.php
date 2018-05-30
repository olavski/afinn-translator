<?php
require_once('inc.php');
require "vendor/autoload.php";


// Remove duplicates from file

$filename = DATA_DIR . "/no/add.tsv";

echo "Removing duplicates: $filename \n";
$words = \Olavski\Afinn\TabTools::load( $filename );
\Olavski\Afinn\TabTools::save( $filename, $words );