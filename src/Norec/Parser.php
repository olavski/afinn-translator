<?php

namespace Olavski\Norec;

//1_nn_123.txt
class Parser
{
    public static function read( $filename )
    {
        $doc = [
            'rating'    => 0,
            'language'  => '',
            'title'     => '',
            'text'      => '',
        ];

        $content = file_get_contents($filename);
        preg_match_all("/^# ([a-z _]+) = (.+)$/miu", $content, $m);
        //print_r( $m  );

        $rating = 0;
        $title = '';
        $language = '';
        $text = '';

        
        
        for( $x =0; $x < count($m[0]); $x++ )
        {
            $key = trim($m[1][$x]);
            $value = trim( $m[2][$x] );
            //$value = str_replace("\"", '', $value);

            if ( $key == 'text' )
            {
                if ( empty($doc['title']) )
                {
                    $doc['title'] = $value;
                    $doc['text'] .= $value . ".\n";
                }
                else
                    $doc['text'] .= $value . "\n";
            }
            elseif ( $key == 'language' )
                $doc['language'] = $value;
            elseif ( $key == 'rating' )
                $doc['rating'] = intval($value);
            elseif ( $key == 'newdoc id' )
                $doc['id'] = intval($value);

        }

        return $doc;    
    }

    public static function save( $path, $doc )
    {
        $filename = "{$doc['id']}_{$doc['language']}_{$doc['rating']}.txt";

        file_put_contents( $path . "/". $filename, $doc['text'] );
    }

    public static function processDir( $read_path, $save_path )
    {
        echo "processDir( $read_path, $save_path )\n";
        $d = dir( $read_path );
        while (false !== ($entry = $d->read()))
        {
            if ( pathinfo($entry, PATHINFO_EXTENSION) == 'conllu' )
            {
                $doc = \Olavski\Norec\Parser::read( $read_path . "/". $entry );
    
                \Olavski\Norec\Parser::save( $save_path, $doc );
                
                echo "{$doc['id']} - [{$doc['rating']}] {$doc['title']}\n";
                
            }
                
        }
        $d->close();
    }
}
