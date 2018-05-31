<?php
require_once('inc.php');
require "vendor/autoload.php";

$masa = new \Olavski\Afinn\Masa( DATA_DIR ."/no/build/no.tsv" );


// $score = $masa->score("Som en kavalkade av dårlige pornofilmintroer");
// print_r( $masa->data );

$rating_count = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
];
$rating_correct_count = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
];
$total_count = 0;
$correct_count = 0;
$wrong_count = 0;

$path = "norec/corpus/dev";
$d = dir( $path );
while (false !== ($entry = $d->read()))
{
    echo "{$entry}\n";
    if ( pathinfo($entry, PATHINFO_EXTENSION) == 'txt' )
    {
        $file_a = explode("_", str_replace(".txt", '', $entry ));
        $rating = $file_a[2];

        $text = file_get_contents($path . "/". $entry );
        //echo $text . PHP_EOL;
        //echo "\n-------------------------\n";
        $score = $masa->score($text);
        print_r( $masa->data );

        $total_count++;
        $rating_count[$rating]++;
        
        if ( $score > 0  && $rating >=4 || $score < 0 && $rating <= 3 )
        {
            $rating_correct_count[$rating]++;
            $correct_count++;
        }
        else
            $wrong_count++;

        echo "Precision: ". round( ($correct_count/$total_count)*100 ) . PHP_EOL;

        for ($x =6; $x >0; $x--)
        {
            if ( $rating_count[$x] )
                echo "Rating $x Precision: ". round( ($rating_correct_count[$x]/$rating_count[$x])*100 ) . PHP_EOL;
        }
        
        // echo "Rating 5 Precision: ". round( ($rating_correct_count[5]/$rating_count[5])*100 ) . PHP_EOL;
        // echo "Rating 4 Precision: ". round( ($rating_correct_count[4]/$rating_count[4])*100 ) . PHP_EOL;
        // echo "Rating 3 Precision: ". round( ($rating_correct_count[3]/$rating_count[3])*100 ) . PHP_EOL;
        // echo "Rating 2 Precision: ". round( ($rating_correct_count[2]/$rating_count[2])*100 ) . PHP_EOL;
        // echo "Rating 1 Precision: ". round( ($rating_correct_count[1]/$rating_count[1])*100 ) . PHP_EOL;
        
    }       
}
$d->close();


echo "Total: {$total_count} \n";
echo "Correct: {$correct_count} \n";
echo "Wrong: {$wrong_count} \n";
echo "Precision: ". round( ($correct_count/$total_count)*100 ) . PHP_EOL;
echo "Rating 6 Precision: ". round( ($rating_correct_count[6]/$rating_count[6])*100 ) . PHP_EOL;
echo "Rating 5 Precision: ". round( ($rating_correct_count[5]/$rating_count[5])*100 ) . PHP_EOL;
echo "Rating 4 Precision: ". round( ($rating_correct_count[4]/$rating_count[4])*100 ) . PHP_EOL;
echo "Rating 3 Precision: ". round( ($rating_correct_count[3]/$rating_count[3])*100 ) . PHP_EOL;
echo "Rating 2 Precision: ". round( ($rating_correct_count[2]/$rating_count[2])*100 ) . PHP_EOL;
echo "Rating 1 Precision: ". round( ($rating_correct_count[1]/$rating_count[1])*100 ) . PHP_EOL;
