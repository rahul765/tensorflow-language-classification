<?php
// scalarArg.php

require __DIR__ . "/../safeRun.php";

function takesInt(int $i){
    return $i;
}

safeRun("Passing an int", function(){
    $i = 1;
    $result = takesInt($i);
    echo "Returned:<br>";
    var_dump($result);
});

safeRun("Passing a float", function(){
    $f = 1.1;
    $result = takesInt($f);
    echo "Returned:<br>";
    var_dump($result);
});

safeRun("Passing a boolean", function(){
    $b = true;
    $result = takesInt($b);
    echo "Returned:<br>";
    var_dump($result);
});

safeRun("Passing a string", function(){
    $s = "1";
    $result = takesInt($s);
    echo "Returned:<br>";
    var_dump($result);
});

safeRun("Passing a string which in no way can be considered an int", function(){
    $s = "not an integer";
    $result = takesInt($s);
    echo "Returned:<br>";
    var_dump($result);
});

safeRun("Passing a PHP object", function(){
    $d = new DateTime();
    $result = takesInt($d);
    echo "Returned:<br>";
    var_dump($result);
});

class C {}
safeRun("Passing a bespoke object", function(){
    $o = new C();
    $result = takesInt($o);
    echo "Returned:<br>";
    var_dump($result);
});