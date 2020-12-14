<?php

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$data = explode("\n", file_get_contents($filePath));
$timestamp = $data[0];
var_dump($timestamp);
if (!preg_match_all("/,?(?P<busId>\d+),?/", $data[1], $matches)) {
  echo "\nError indetifying bus ids.\n";
  return;
}
$busIds = $matches['busId'];
var_dump($busIds);
$earliestBusId = NULL;
$timeDelaying = $timestamp;
$table = [];
while (!isset($earliestBusId)) {
  foreach ($busIds as $busId) {
    if (($timeDelaying % $busId) == 0) {
      $table[$timestamp][$busId] = 'D';
      $earliestBusId = $busId;
      break;
    }
    else {
      $table[$timestamp][$busId] = '.';
    }
  }
  if (!isset($earliestBusId)) {
    $timeDelaying++;
  }
}
var_dump($timeDelaying);
var_dump($table);
$product = $earliestBusId * ($timeDelaying - $timestamp);
echo "\n\nThe product of the bus ID $earliestBusId by the time I have to wait $timeDelaying is: $product.\n";
?>