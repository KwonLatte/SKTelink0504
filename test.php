<?php

require_once('Ansim.php');
$ansim = new Ansim;

// 매핑 할 대상번호
$to = "0212345678";
// 할당받은 안심번호
$from = "050412345678";

// 안심번호에 대상번호 매핑
$res = $ansim->reqNewAnsim($from, $to);
$ansim->debug();

if (substr(trim($res), -4, 4) === "0000") {
    echo "MAPPING REQUEST SUCCESS!<br/>" . PHP_EOL;
} else {
    echo "MAPPING REQUEST ERROR...<br/>" . PHP_EOL;
    exit;
}

// 매핑한 번호 재검증
$res = $ansim->getRealNumber($from);
$ansim->debug();

$res = preg_replace("/(\s+)/", " ", $res);
list($header, $has_number) = explode(" ", $res);

if (trim($has_number) == $to) {
    echo "MAPPING SUCCESS!<br/>" . PHP_EOL;
} else {
    echo "MAPPING ERROR!<br/>" . PHP_EOL;
}

// 연결된 안심번호 해제
$res = $ansim->delAnsim($from);
$ansim->debug();

if (substr(trim($res), -4, 4) === "0000") {
    echo "MAPPING RELEASE SUCCESS!<br/>" . PHP_EOL;
} else {
    echo "MAPPING RELEASE ERROR...<br/>" . PHP_EOL;
    exit;
}
