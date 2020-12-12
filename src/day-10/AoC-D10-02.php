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

// Filter elements which value is 1.
$oneElements = array_filter(
  $numberList,
  function ($item) {
    return ($item ==1);
  }
);

// Create an array for each group of consecutive 1's, inside the array.
$subGroups = [];
$subGroupsCounter = [];
$previous_key = 0;
$newGroup = [];
$lastElement = array_key_last($oneElements);
foreach ($oneElements as $key => $value) {
  if (empty($newGroup) || ($key - $previous_key == 1)) {
    var_dump("Añadimos $key al grupo.");
    $newGroup[] = $key;
  }
  elseif ($key - $previous_key >= 3) {
    // Remove last element of the newGroup, since it is the last and
    // cannot be remove for combinations.
    var_dump("Eliminamos $previous_key del grupo.");
    unset($newGroup[array_key_last($newGroup)]);
    var_dump("Añadimos el grupo.");
    var_dump($newGroup);
    if (!empty($newGroup)) {
      $subGroups[] = $newGroup;
      $subGroupsCounter[] = count($newGroup);
    }
    var_dump("Creamos nuevo grupo.");
    $newGroup = [];
    $newGroup[] = $key;
  }
  if ($key == $lastElement) {
    unset($newGroup[array_key_last($newGroup)]);
    if (!empty($newGroup)) {
      $subGroups[] = $newGroup;
      $subGroupsCounter[] = count($newGroup);
    }
  }
  else {
    $previous_key = $key;
  }
}
echo "\n\nSubGroups:\n";
var_dump($subGroups);
echo "\n\nSubGroupsCounter:\n";
var_dump($subGroupsCounter);
/* Now the calculus:
* Each number of elements in each group as following:
* - Count == 1 => 2^1 ^ number of elements with 1 item
* - Count == 2 => 2^2 ^ number of elements with 2 items
* - Count == 3 => (2^3 - 1) ^ number of elements with 3 items
*   (The only one that is not valid is = 000)
* - Count == 4 => (2^4 - 3) ^ number of elements with 4 items
*   (The only three that are not valid are = 0000, 0001, 1000)
* 
* ... and so on.
*/ 
$subGroupsCounter = array_count_values($subGroupsCounter);
var_dump($subGroupsCounter);
$combinations = 1;
foreach ($subGroupsCounter as $exp => $total) {
  $base = pow(2, $exp);
  if ($exp == 3) {
    $base = $base - 1;
  }
  if ($exp == 4) {
    $base = $base - 3;
  }
  $combinations *= pow($base, $total);
}
echo "\nThe number of different combinations is: $combinations\n\n";
return;
?>