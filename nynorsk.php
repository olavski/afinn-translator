<?php
require_once('inc.php');
require "vendor/autoload.php";

$counter = 1;
$words = \Olavski\Afinn\TabTools::load( DATA_DIR . "/no/translated.tsv" );

$nynorsk_words = [];

foreach( $words as $word => $weight )
{
    $translated_word = \Olavski\Translate\Apertium::translate( $word , 'nob', 'nno');
    $translated_word = mb_strtolower( $translated_word );

    echo "{$counter}\t{$word} -> {$translated_word} [{$weight}]\n";
    $counter++;

    if ( !empty($translated_word) )
    {
        if ( 
            array_search($translated_word, $words) === false && 
            array_search($translated_word, $nynorsk_words) === false 
        )
        {
            $nynorsk_words[$translated_word] = $weight;
            \Olavski\Afinn\TabTools::save( DATA_DIR . "/no/nynorsk.tsv", $nynorsk_words );
        }
    }
    sleep(1);
}

