<?php

$file = 'afinn-no-new.json';

$data = json_decode(
    file_get_contents( "./data/". $file )
, 1);

print_r( $data );
$new_content = '';
$all_words = [];
$vector_words = [];

foreach(  $data as $word => $score )
{
    if ( array_search($word, $all_words) === false )
        $all_words[] = $word;
}
$counter = 1;
echo "Words loaded:" . count($all_words) . PHP_EOL;

$ignore_a = ['index.php', 'kategori:', '</s>'];
foreach(  $data as $word => $score )
{
    $polarity = ( $score > 0 ) ? 'positive': 'negative';
    
    $word = mb_strtolower($word);
    echo "{$counter} - get_neighbours($word, 'no' )\n";
    if ( $neighbours = get_neighbours($word, 'no') )
    {
        print_r( $neighbours );
        foreach( $neighbours as $neighbour )
        {
            $neighbour = mb_strtolower( $neighbour );
            if ( array_search($neighbour, $all_words) === false )
            {
                if ( isset($vector_words[$neighbour]) )
                    $vector_words[$neighbour]++;
                else
                    $vector_words[$neighbour] = 1;
            }
            
        }
        
    }
    $counter++;
    arsort($vector_words);
    // print_r( array_slice($vector_words, 0, 25) );
    echo "vector items: ". count($vector_words) . PHP_EOL;
    file_put_contents('no_embeddings,json', \json_encode($vector_words, JSON_PRETTY_PRINT) );
}

function get_neighbours($word, $language = 'no')
{
    $url = "http://localhost:6400/polyglot/neighbours?lang={$language}&word=". urlencode($word);

    $data = json_decode(
        @file_get_contents( $url )
    , 1);

    if ( !empty($data['neighbours']) )
    {
        return $data['neighbours'];
    }

    return null;
}