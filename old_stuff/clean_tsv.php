<?php
// clean TSV Files
//  - removed duplicates
//  - ensures lowercase
//  - ensures tab

$file = "norwegian.tsv";

$content = file_get_contents($file);

$new_content = '';
$line_a = explode("\n", $content);
$word_a = [];
foreach($line_a as $line)
{
    if ( strpos($line, "\t" ) !== false )
    {
        $tmp = explode("\t", $line);
        $word = mb_strtolower( trim($tmp[0]) );
        if ( array_search($word, $word_a) === false )
        {
            $score = trim($tmp[1]);
            if ( empty($score) )
                die("Missing score: $score \n");

            $new_content .=  $word. "\t". $score . PHP_EOL;
        }else
        {
            echo "DUPLICATE: {$line} \n";
        }
        

    }else{
        echo "INVALID LINE: $line \n";
    }
}

if ( !empty($new_content) )
    file_put_contents($file . "-clean", $new_content);
