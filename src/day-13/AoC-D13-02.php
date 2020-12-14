<?php

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$data = explode("\n", file_get_contents($filePath));
// Get initial timestamp.
$timestamp = $data[0];
var_dump($timestamp);
// Get bus IDs (values) and their difference in minutes (keys).
$busIds = explode(",", $data[1]);
$busIds = array_filter(
  $busIds,
  function ($item) {
    return ($item != 'x');
  }
);
var_dump($busIds);
$earliestTimeFound = FALSE;

/**
 * El truco consiste en que cada vez que encuentres el número que satisfaga el/los anteriores
 * cambies el incremento multiplicándolo por el número del que acabas de encontrar la solución.
 */
$prevBusId = $busIds[0];
$increment = 1;
while (!$earliestTimeFound) {
  foreach ($busIds as $key => $busId) {
    echo "\n$key => $busId";
    $remainder = ($timestamp + $key) % $busId;
    echo "\nResto: $remainder";
    if ($remainder != 0) {
      $earliestTimeFound = FALSE;
      if ($busId != $prevBusId) {
        // $increment = $prevBusId * ($busId - $remainder);
        $increment = $increment * $prevBusId;
        $prevBusId = $busId;
        echo "\nIncrement: $increment";
      }
      break;
    }
    else {
      $earliestTimeFound = TRUE;
    }
  }
  if (!$earliestTimeFound) {
    $timestamp += $increment;
    
  }
  echo "\nTimestamp: $timestamp";

}
echo "\n\nThe earliest time id: $timestamp\n";
?>