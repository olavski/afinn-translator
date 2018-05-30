<?php

// convert json  files to tab-separated values


$d = dir("./data");

while (false !== ($file = $d->read()))
{
    if ( strpos($file, '.json') )
    {
        echo $file."\n";
        $data = json_decode(
            file_get_contents( "./data/". $file )
        , 1);

        print_r( $data );
        $new_content = '';
        foreach(  $data as $word => $score )
        {
            $new_content.= mb_strtolower($word) . "\t" . $score . PHP_EOL;
        }

        file_put_contents(
            "./data/". str_replace(".json", ".tsv", $file),
            $new_content
        );
    }
    
}
$d->close();