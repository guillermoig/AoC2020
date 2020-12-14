<?php

function addBitToAddresses(string $bit, array $addresses): array {
  if (empty($addresses)) {
    if ($bit == 'X') {
      return ['0', '1'];
    }
    return [$bit];
  }
  if ($bit == 'X') {
    $arrayZeros = $addresses;
    $arrayOnes = $addresses;
    foreach ($arrayZeros as &$item) {
      $item .= '0';
    }
    foreach ($arrayOnes as &$item) {
      $item .= '1';
    }
    $addresses = array_merge($arrayZeros, $arrayOnes);
    return $addresses;
  }
  foreach ($addresses as &$address) {
    $address .= $bit;
  }
  return $addresses;
}

function getMemoryAdresses(int $initialAddress, string $mask) {
  $binAddress = decbin($initialAddress);
  $valueStartIndex = strlen($mask) - strlen($binAddress);
  $maskExplode = str_split($mask, 1);
  $bit = 0;
  $values = [];
  foreach ($maskExplode as $key => $bitMask) {
    if ($bitMask == '0') {
      if ($key < $valueStartIndex) {
        $bit = '0';
      }
      else {
        $bit = $binAddress[$key - $valueStartIndex];
      }
    }
    elseif ($bitMask == '1') {
      $bit = '1';
    }
    else {
      $bit = $bitMask;
    }
    $values = addBitToAddresses($bit, $values);
  }
  $decAddresses = [];
  foreach ($values as $binMemAddress) {
    $decAddresses[] = bindec($binMemAddress);
  }
  return ($decAddresses);
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
    echo "Máscara: $mask\n";
  }
  elseif (preg_match("/mem\[(?P<address>\d+)\]\s=\s(?P<value>\d+)/", $line, $memExp)) {
    $address = $memExp['address'];
    $value = $memExp['value'];
    echo "Dirección de memoria: $address\n";
    echo "Valor: $value\n";
    $newAddresses = getMemoryAdresses($address, $mask);
    foreach ($newAddresses as $newAddress) {
      $memory[$newAddress] = $value;
    }
  }
}
$total = array_sum($memory);
echo "\nThe sum of all values in memeory is: $total\n";
?>