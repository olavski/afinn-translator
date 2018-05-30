<?php

namespace Olavski\Afinn;

class Wordlist
{
    public $words = [];
    public $stoplist = [];
    
    public function addFile( $file )
    {
        $start_count = count( $this->words );
        echo "addFile( $file )\n";
        $this->add(
            \Olavski\Afinn\TabTools::load( $file )
        );
        echo "Words added: ". ( count( $this->words ) - $start_count ). PHP_EOL;
        echo "Total words: ". count( $this->words ) . PHP_EOL;
    }

    public function stoplistFile( $filename )
    {
        echo "stoplistFile( $filename )\n";
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
        
        while (($line = fgets($fp)) !== false)
        {
            
            if ( strpos($line, "\t") !== false )
            {
                $tmp = explode("\t", $line);
                $word = mb_strtolower( trim($tmp[0]) );

                
            }elseif (!empty($line)){
                $word = mb_strtolower(trim($line));
            }
            
            //echo "STOPWORD: '{$word}'\n";
            if ( !empty($word)  && array_search($word, $this->stoplist) === false)
            {
                $this->stoplist[] = $word;
                if ( isset($this->words[$word] ) )
                {
                    //echo "Stoplist Remove: {$word}\n";
                    unset( $this->words[$word] );
                }
            }else
                echo " - IGNORED\n";
        }
    
        fclose($fp);

        
        echo "Stoplist words: ". count($this->stoplist) . PHP_EOL;
        echo "After Stoplist: Total words: ". count( $this->words ) . PHP_EOL;

        return $this->stoplist;
    }

    public function add( $words )
    {
        if ( empty($words) )    return;

        foreach($words as $word => $weight )
        {
            if ( array_search($word,  $this->stoplist ) === false )
            {
                if ( isset($this->words[$word] ) )
                {
                    if ( $weight != $this->words[$word] )
                    {
                        echo "Overwriting: \n";
                        echo "  {$word} -> {$this->words[$word]}\n";
                        $this->words[$word] = $weight;
                        echo "  {$word} -> {$this->words[$word]}\n";
                    }
                    
                }else
                    $this->words[$word] = $weight;
            }
        }
    }

    public function stoplist()
    {
        
    }


    public function save( $filename )
    {
        \Olavski\Afinn\TabTools::save( $filename, $this->words );
    }
}