<?

// namespace ze;


// 1. 生成安全码：
// $code = Ze\Secure::encode($arr)；

// 2. 校验安全码：
// $isOK = Ze\Secure::verify($arr, $code);


$arr = [
    'id' => 1234567,
    'amt' => 1000
];

// $code = Secure::Join($arr);
$code = Ze\Secure::encode($arr);

echo $code;
echo "<br><br>";
var_dump($arr);
echo "<br><br>";

// $code = '$2y$10$oNCy5tNsDk2rxW/jVCM9ne2iJbr.QV.Fq57Uwr2LutC2CZnOqEqyy';

$arr = [
    'id' => 1234567,
    'amt' => 1000
];

$result = Ze\Secure::verify($arr, $code);
var_dump($result);
echo "<br><br>";


$arr = [
    'id' => 1234567,
    'amt' => 1000123
];

$result = Ze\Secure::verify($arr, $code);
var_dump($result);