<?php
require_once('inc.php');
require "vendor/autoload.php";


if ( isset($argv[1]) )
    $lang = $argv[1];
else
    $lang = trim(readline("Enter language directory (Example: 'no'): "));

if ( empty($lang) )
    die("Error: Missing language directory\n");

$builder = (new \Olavski\Afinn\Build( $lang, DATA_DIR ) )->compile();