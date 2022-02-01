<?php

ini_set('display_errors' , 1);
error_reporting(E_ALL);
//вывод ошибок

function debug($str)
{
    echo '<pre><h3> ';
    var_dump($str);
    echo'</h3></pre>';
    exit;
}

