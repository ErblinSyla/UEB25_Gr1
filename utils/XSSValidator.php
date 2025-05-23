<?php

function validateXSSAttacks($data)
{
    $jsXSS = preg_match("/<\/?script>/", $data);
    $htmlXSS = preg_match("/<\/?\w+>/", $data);
    $xmlXSS = preg_match("/<\?xml.*?\?>/", $data);
    $sqlXSS = preg_match("/[\'\"\\\]/", $data);
    $phpXSS = preg_match("/<\?php.*?\?>/", $data);

    switch (true) {
        case $htmlXSS:
            if($jsXSS == 0){
                echo "<script>
                        alert('HTML XSS attack in $data'); 
                        console.log('HTML XSS attack in $data');
                      </script>";
            } else {
                echo "<script>
                        alert('JS XSS attack in $data'); 
                        console.log('JS XSS attack in $data');
                    </script>";
            }
            break;
        case $xmlXSS:
            echo "<script>
                    alert('XML XSS attack in $data'); 
                    console.log('XML XSS attack in $data');
                  </script>";
            break;
        case $sqlXSS:
            echo "<script>
                    alert('SQL XSS attack in $data'); 
                    console.log('SQL XSS attack in $data');
                  </script>";
            break;
        case $phpXSS:
            echo "<script>
                    alert('PHP XSS attack in $data'); 
                    console.log('PHP XSS attack in $data');
                  </script>";
            break;
    }
    return $jsXSS || $sqlXSS || $phpXSS || $xmlXSS;
}