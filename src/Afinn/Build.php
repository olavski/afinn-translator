<?php

namespace Olavski\Afinn;

/**
 *  Clean and merge tsv files into one single file
 */
class Build
{
    protected $lang, $data_dir, $language_dir, $build_dir;
    public $files = []; // tsv files
    protected $wordlist;

    public function __construct($lang, $data_dir)
    {
        $this->lang = $lang;
        $this->data_dir =  $data_dir;

        $this->language_dir = $data_dir . "/{$this->lang}";
        $this->build_dir = $data_dir . "/{$this->lang}/build";

        if ( !file_exists($this->language_dir) )
            die("Error: Language directory not found!\n");

        if ( !file_exists($this->build_dir ) )
            mkdir($this->build_dir );

        $this->files = $this->getDirFiles($this->language_dir, 'tsv');
    }

    /**
     *  Watch files - recompile on changes
     */
    public function watch()
    {
        $this->compile();
        $watchers = $this->files;

        foreach( $watchers as $file => $time )
        {
            $watchers[$file] = filemtime($this->language_dir ."/{$file}");
        }

        do{
            $changed = false;

            foreach( $watchers as $file => $time )
            {
                $watchers[$file] = filemtime($this->language_dir ."/{$file}");
            
                if ( $time != $watchers[$file] )
                {
                    $changed = true;
                    $diff =   $watchers[$file] - $time;
                    echo "updated: {$file} -> {$diff}s\n";
                }

            }

            if ( $changed )
            {
                echo "recompile \n";
                $this->compile();
            }
            sleep(1);

        }while( true );
    }


    public function compile()
    {
        $this->wordlist = new \Olavski\Afinn\Wordlist();
        print_r( $this->files );

        if ( !$this->validate() )
        {
            echo "Invalid TSV Files. Please fix and try again";
            return false;
        }


        // 1 - translated
        if ( !isset($this->files['translated.tsv']) )
            die("ERROR: translated.tsv required");

        $this->wordlist->addFile($this->language_dir .'/translated.tsv');

        // 2 - other files
        foreach($this->files as $file => $k)
        {
            if ( $file != 'translated.tsv' && $file != 'stoplist.tsv' )
            {
                $this->wordlist->addFile($this->language_dir ."/{$file}");
            }
        }
        // 3 - stoplist
        $this->wordlist->stoplistFile($this->language_dir ."/stoplist.tsv");


        $this->wordlist->save( $this->language_dir ."/build/{$this->lang}.tsv" );
    }

    
    public function validate()
    {
        // validate
        $is_valid = true;
        foreach($this->files as $file => $k)
        {
            if ( $file != 'stoplist.tsv' )
            {
                echo "Validating: {$file} \n";
                if ( ! \Olavski\Afinn\TabTools::validate($this->language_dir .'/' . $file ) )
                    $is_valid = false;
            }
        }

        return $is_valid;
        
    }

    function getDirFiles( $dir, $ext = '' )
    {
        $file_a = [];
        $d = dir($dir);
        while (false !== ($entry = $d->read()))
        {
            if ( empty($ext) || pathinfo($entry, PATHINFO_EXTENSION) == $ext )
                $file_a[$entry] = true;
        }
        $d->close();

        return $file_a;
    }


}