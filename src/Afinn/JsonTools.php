<?php

namespace Olavski\Afinn;


class JsonTools
{
    public static function load( $filename )
    {
        return json_decode( 
            file_get_contents( $filename )
            , true 
        );
    }

    public static function save( $filename, $array )
    {
        file_put_contents( $filename, 
            json_encode($array),
            JSON_PRETTY_PRINT
        );
    }
}