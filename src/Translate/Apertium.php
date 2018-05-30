<?php
namespace Olavski\Translate;

class Apertium{

    public static function translate( $word, $from, $to )
    {
        $url = "https://www.apertium.org/apy/translate?q=". urlencode($word) ."&markUnknown=no&langpair={$from}%7C{$to}";
        //echo "BokmalNynorsk : $url \n";
        $data =  \Olavski\Afinn\JsonTools::load( $url );
        
        if ( !empty( $data['responseData'] ) && !empty( $data['responseData']['translatedText'] )  )
        {
            return mb_strtolower( trim($data['responseData']['translatedText']) );
        }

        return null;
    }
}