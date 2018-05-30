<?php
require_once('inc.php');
require "vendor/autoload.php";

use Olavski\Afinn\Translate;


// $words = json_decode(
//     file_get_contents('afinn-165-en.json')
//     , 1);

// print_r( $words  );


// foreach ( $words as $word => $score )
// {

//     $translated_word = $tr->translate($word);
//     echo "{$word} -> {$translated_word} \n";
//     sleep(2);
// }

(new Translate('sv', DATA_DIR))->run(ENGLISH_AFINN_FILE );
