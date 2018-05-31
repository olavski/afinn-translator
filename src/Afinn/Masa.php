<?php

namespace Olavski\Afinn;

class Masa
{
    protected $word_a = [];
    protected $language;

    public $data = [];

    function __construct( $file  )
    {
        //$this->language = $language;
        $this->loadFile( $file ); //"C:\\code\\nlpserver\\afinn-translator\\data\\no\\build\\no.tsv");
    }

    private function reset()
    {
        $this->data = [
            'polarity_score' => [
                'positive'  => 0,
                'negative'  => 0,
            ],
            'sentiment' => 'neutral',
            'total' => 0,
            'score' => 0,
            'score_keyword_count' => 0,
            'found' => [
                'positive'  => [],
                'negative'  => []
            ],
            'word_count'    => 0,
        ];
    }

    public function score( $text )
    {
        $this->reset();
        $this->calculate( $text );
        
        return $this->data['score'];
    }

    private function calculate( $text )
    {
        $text = mb_strtolower($text);
        $text = str_replace('-', ' ', $text);

        $this->data['word_count'] = str_word_count( $text );

        foreach (array_keys($this->word_a) as $word)
        {
            if (preg_match ("/\b{$word}\b/ui", $text, $m))
            {
                //echo "Word: {$word}: {$this->word_a[$word]}\n";
                
                if ( $this->word_a[$word] > 0 )
                
                    $polarity = 'positive';
                else
                    $polarity = 'negative';
                
                if ( isset( $this->data['found'][$polarity][$word] ) )
                    $this->data['found'][$polarity][$word]++;
                else
                    $this->data['found'][$polarity][$word] = 1;

                    $this->data['total'] += $this->word_a[$word];
                    $this->data['polarity_score'][$polarity] += $this->word_a[$word];
            }
        }

        if ( empty($this->data['found']['positive']) && empty($this->data['found']['negative']) )
        {
            $this->data['score'] = $this->data['score_keyword_count'] = 0;
        }else{
            $this->data['score'] = ( $this->data['polarity_score']['positive'] - $this->data['polarity_score']['negative']*-1 ) / 
            ( $this->data['polarity_score']['positive'] + $this->data['polarity_score']['negative']*-1 );

            $this->data['score_keyword_count'] = ( count($this->data['found']['positive']) - count($this->data['found']['negative']) ) / 
                    ( count($this->data['found']['positive']) + count($this->data['found']['negative']) );
        }

        

        if ( $this->data['score'] > 0 )
            $this->data['sentiment'] = 'positive';
        elseif ( $this->data['score'] < 0 )
            $this->data['sentiment'] = 'negative';
    }

    public function loadFile( $file )
    {
        $filename = $file;
        //$filename = dirname(__FILE__) . "/data/tsv/". $file ;

        if ( !file_exists($filename) )
        {
            echo "File not found!";
            return;
        }

        $fp = fopen( $filename , "r");
        if ($fp) {
            while (($line = fgets($fp)) !== false)
            {
                if ( strpos($line, "\t") !== false )
                {
                   $tmp = explode("\t", $line);
                   $word = mb_strtolower( trim($tmp[0]) );
                   $score = trim( $tmp[1] );
    
                    if ( !empty($word) && is_numeric($score) )
                    {
                        if ( !isset($this->word_a[$word]) )
                            $this->word_a[$word] = $score;
                        else
                            echo "Load: {$word} already loaded \n";
                    }
                }
                // process the line read.
            }
        
            fclose($fp);
        } 
    }
}