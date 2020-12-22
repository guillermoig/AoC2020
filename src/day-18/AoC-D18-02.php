<?php

require("calculator.php");

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode("\n", file_get_contents($filePath));
$calculator = new Calculator();

$total = 0;
foreach ($input as $line) {
  // $line_splitted = explode(" // ", $line);
  // $operation = $line_splitted[0];
  // $expected_result = $line_splitted[1];
  // $result = $calculator->getResultSumBeforeProduct($operation);
  // if ($result != $expected_result) {
  //   echo "\nError: ";
  // }
  // print_r("Obtenido: $result <==> Esperado: $expected_result\n");
  $result = $calculator->getResultSumBeforeProduct($line);
  $total += $result;
}
echo "\nThe sum of all results is: $total\n";

?>