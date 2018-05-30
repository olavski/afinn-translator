<?php

namespace Olavski\Afinn;

class TabTools
{
	protected $file;

    public static function split( $filename )
    {
        return [
            'positive'  => [],
            'negative'  => []
        ];
    }

    public static function sort( $filename , $col, $dir)
    {
    }

    public static function save( $filename, array $array  = null)
    {
        if ( empty($array) )
            return;

        $fp = fopen($filename, 'w');
        foreach ($array as $word =>  $value)
        {
            $word = mb_strtolower( trim($word) );
            fwrite($fp, "{$word}\t{$value}\n");
        }
        fclose($fp);
    }

    public static function validate( $filename )
    {
        if ( ! mb_check_encoding(file_get_contents($filename), 'UTF-8')) {
            echo "validate( $filename ) - INVALID: NOT UTF8! \n";
            return false;
            // yup, all UTF-8
        }
        $fp = fopen( $filename , "r");
        if (!$fp) 
        {
            throw \Exception("$filename - file not found");
        }

        $is_valid = true;
        $word_a = [];
        while (($line = fgets($fp)) !== false)
        {
            $line = trim($line);

            if ( !empty($line) && strpos($line, "\t") === false )
            {
                echo "validate( $filename ) - INVALID: $line \n";
                $is_valid = false;
            }else{
                $tmp = explode("\t", $line);
                $word = mb_strtolower( trim($tmp[0]) );

                if ( array_search($word, $word_a) !== false)
                {
                    echo "validate( $filename ) - WARNING Duplicate: $line \n";
                }else{
                    $word_a[] = $word;
                }
            }
        }
        fclose($fp);

        return $is_valid;
    }

    public static function load( $filename )
    {
        if ( !file_exists($filename) )
        {
            echo "File not found!";
            return;
        }

        $fp = fopen( $filename , "r");
        if (!$fp) 
        {
            throw \Exception("$filename - file not found");
        }
        
        $word_a = [];

        while (($line = fgets($fp)) !== false)
        {
            if ( strpos($line, "\t") !== false )
            {
                $tmp = explode("\t", $line);
                $word = mb_strtolower( trim($tmp[0]) );
                $score = trim( $tmp[1] );

                if ( !empty($word) && is_numeric($score) )
                {
                    if ( !isset($word_a[$word]) )
                        $word_a[$word] = $score;
                    else
                        echo "loadTsv: {$word} already exists \n";
                }
            }else{
                echo "TAB not found: $line \n";
            }
            // process the line read.
        }
    
        fclose($fp);
        return $word_a;
    }
}

