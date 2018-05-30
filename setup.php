<?php
require_once('inc.php');
require "vendor/autoload.php";

/**
 *  Download English AFINN File
 */

echo "Downloading.." . ENGLISH_AFINN_SOURCE . PHP_EOL;
//$data = json_decode( file_get_contents( ENGLISH_AFINN_SOURCE ), 1 );

$data = \Olavski\Afinn\JsonTools::load( ENGLISH_AFINN_SOURCE );

if ( !empty($data) && is_array($data) )
{
    \Olavski\Afinn\TabTools::save( ENGLISH_AFINN_FILE, $data );
    
    echo "\n\nSaved ". count($data) . " words\n";
    echo "Complete!!\n";
}else
{
    echo "Something went wrong..";
}
