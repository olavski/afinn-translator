<?php

namespace Olavski\Afinn;

class Translate
{
    private $translator;
    private $output_language;
    private $data_dir;

    public $words;
    public $delay = 1; // seconds delay between api calls

    public $same_a = []; // translated word same as english
    public $translated_a = []; // translated words

    function __construct( $lang, $data_dir )
    {
        $this->output_language = $lang;
        $this->data_dir = $data_dir;
        $this->language_dir = $this->data_dir . "/{$lang}";

        if (!file_exists($this->language_dir))
            mkdir($this->language_dir);

        $this->translator = new \Stichoza\GoogleTranslate\TranslateClient('en', $this->output_language );
    }

    public function run( $afinn_json_file )
    {
        $this->words = Tools::loadTsv( $afinn_json_file );

        $counter = 1;
        foreach ( $this->words as $word => $score )
        {
            $translated_word = $this->translator->translate($word);
            $translated_word = mb_strtolower( $translated_word );

            echo "{$counter}\t{$word} -> {$translated_word} [{$score}]\n";
            $counter++;

            if ( empty($translated_word) || $translated_word == $word  )
                $this->same_a[$translated_word] = $score;
            else
                $this->translated_a[$translated_word] = $score;
                    
            $this->save();
            sleep(  $this->delay );
        }
    }

    private function save()
    {
        TabTools::save( $this->language_dir."/en-untranslated.tsv", $this->same_a );
        TabTools::save( $this->language_dir."/translated.tsv", $this->translated_a );

        // file_put_contents( $this->language_dir . "/afinn-{$this->output_language}-same.json", 
        //     json_encode($this->same_a),
        //     JSON_PRETTY_PRINT
        // );

        // file_put_contents( $this->language_dir . "/afinn-{$this->output_language}-new.json", 
        //     json_encode($this->translated_a),
        //     JSON_PRETTY_PRINT
        // );

    }
}