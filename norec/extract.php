<?php
require_once('../inc.php');
require "../vendor/autoload.php";

// extract text from Conllu files
$sets = ['dev', 'train', 'test'];
foreach( $sets as $set )
{
    $read_path = "norec/conllu/{$set}";
    $save_path = "corpus/{$set}";
    //processDir( $read_path, $save_path );
    \Olavski\Norec\Parser::processDir( $read_path, $save_path );
}




 


