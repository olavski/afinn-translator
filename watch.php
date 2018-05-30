<?php
require_once('inc.php');
require "vendor/autoload.php";


if ( isset($argv[1]) )
    $lang = $argv[1];
else
    $lang = trim(readline("Enter language directory (Default: 'no'): "));

$lang = strtolower($lang);

if ( empty($lang) )
    $lang = 'no';

( new \Olavski\Afinn\Build( $lang, DATA_DIR ) )->watch();
