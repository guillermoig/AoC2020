<?php

function getValueAfterMask(int $decValue, string $mask) {
  $binValue = decbin($decValue);
  $valueStartIndex = strlen($mask) - strlen($binValue);
  $maskExplode = str_split($mask, 1);
  $value = 0;
  foreach ($maskExplode as $key => $bitMask) {
    if ($bitMask == 'X') {
      if ($key < $valueStartIndex) {
        $value .= '0';
        continue;
      }
      $value .= $binValue[$key - $valueStartIndex];
      continue;
    }
    $value .= $bitMask;
  }
  return bindec($value);
}

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$data = explode("\n", file_get_contents($filePath));

$memory= [];
$mask = '';
foreach ($data as $line) {
  if (preg_match("/mask\s=\s(?P<mask>[X01]{36})/", $line, $maskExp)) {
    $mask = $maskExp['mask'];
    // echo "Máscara: $mask\n";
  }
  elseif (preg_match("/mem\[(?P<address>\d+)\]\s=\s(?P<value>\d+)/", $line, $memExp)) {
    $address = $memExp['address'];
    $value = $memExp['value'];
    // echo "Dirección de memoria: $address\n";
    // echo "Valor: $value\n";
    $memory[$address] = getValueAfterMask($value, $mask);
  }
}
$total = array_sum($memory);
echo "\nThe sum of all values in memeory is: $total\n";
?>