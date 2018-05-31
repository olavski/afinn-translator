<?php

$url = "http://folk.uio.no/eivinabe/norec-1.0.1.tar.gz";

echo "Downloading: {$url}\n";
file_put_contents('norec-1.0.1.tar.gz',
    file_get_contents( $url )
);

