<?php
require_once('inc.php');
require "vendor/autoload.php";


$word = 'flertall';


$pos_count = 0;
$neg_count = 0;
$total_count = 0;

$path = "norec/corpus/train";
$d = dir( $path );
while (false !== ($entry = $d->read()))
{
    //echo "{$entry}\n";
    if ( pathinfo($entry, PATHINFO_EXTENSION) == 'txt' )
    {
        $file_a = explode("_", str_replace(".txt", '', $entry ));
        $rating = $file_a[2];

        $text = file_get_contents($path . "/". $entry );

        $total_count++;
        if (preg_match ("/\b{$word}\b/ui", $text, $m))
        {
            if ( $rating > 3 )
                $pos_count++;
            else
                $neg_count++;
        }

        if ( $pos_count+$pos_count > 0 )
        {
            $pos_percent = round( ($pos_count / ($pos_count+$neg_count)) * 100 );
            $neg_percent = round( ($neg_count / ($pos_count+$neg_count)) * 100 );
        }
        else{
            $pos_percent = $neg_percent = 0;
        }
        
        if ( $total_count % 5000 == 0 )
        {
            echo "\nPOS: {$pos_count} - {$pos_percent}%\n";
            echo "NEG: {$neg_count} - {$neg_percent}%\n";
            echo "TOTAL:{$total_count}\n";
        }
        

    }       
}
$d->close();

echo "POS: {$pos_count} - {$pos_percent}%\n";
echo "NEG: {$neg_count} - {$neg_percent}%\n";
echo "TOTAL:{$total_count}\n";