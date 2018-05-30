<?php
require_once('inc.php');
require "vendor/autoload.php";


if ( isset($argv[1]) )
    $lang = $argv[1];
else
    $lang = trim(readline("Enter language directory (Example: 'no'): "));

if ( empty($lang) )
    die("Error: Missing language directory\n");

$language_dir = DATA_DIR . "/". $lang;
$build_dir = DATA_DIR . "/{$lang}/build";

if ( !file_exists($language_dir) )
    die("Error: Language directory not found!\n");

if ( !file_exists($build_dir) )
    mkdir($build_dir);


$files = getDirFiles($language_dir, 'tsv');
print_r( $files );

// validate
$is_valid = true;
foreach($files as $file => $k)
{
    if ( $file != 'stoplist.tsv' )
    {
        echo "Validating: {$file} \n";
        if ( ! \Olavski\Afinn\TabTools::validate($language_dir .'/' . $file ))
            $is_valid = false;
    }
}
if ( !$is_valid)
    die("Invalit TSV Files. Please fix and try again");


$wordlist = new Olavski\Afinn\Wordlist();

// 1 - translated
if ( !isset($files['translated.tsv']) )
    die("ERROR: translated.tsv required");

$wordlist->addFile($language_dir .'/translated.tsv');

// 2 - other files
foreach($files as $file => $k)
{
    if ( $file != 'translated.tsv' && $file != 'stoplist.tsv' )
    {
        $wordlist->addFile($language_dir ."/{$file}");
    }
}
// 3 - stoplist
$wordlist->stoplistFile($language_dir ."/stoplist.tsv");


$wordlist->save( $language_dir ."/build/{$lang}.tsv" );


function getDirFiles( $dir, $ext = '' )
{
    $file_a = [];
    $d = dir($dir);
    while (false !== ($entry = $d->read()))
    {
        if ( empty($ext) || pathinfo($entry, PATHINFO_EXTENSION) )
        $file_a[$entry] = true;
    }
    $d->close();

    return $file_a;
}