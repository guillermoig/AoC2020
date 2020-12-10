<?php

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$numberList = explode("\n",file_get_contents($filePath));

// Solution
// Sort the array
sort($numberList);
// Change values to keys
$numberList = array_fill_keys($numberList, 0);
$previous_key = 0;
// Create an associative array where key is the value of $numberList
// and value is the difference between that value and the previuos one.
foreach ($numberList as $key => $item) {
  $numberList[$key] = $key - $previous_key;
  $previous_key = $key;
}
// Add the jump between the las adapter and the built-in adapter
$numberList[$previous_key + 3] = 3;
// Create an array with the count of each repeated value.
$numbersCounter = array_count_values($numberList);
// Calculate the product between the counters of 1 and 3.
$product = $numbersCounter[1] * $numbersCounter[3];
echo "\nThe number of differences of 1 jolt is: $numbersCounter[1]";
echo "\nThe number of differences of 3 jolt is: $numbersCounter[3]";
echo "\nThe product of both is: $product\n\n";
return;
?>