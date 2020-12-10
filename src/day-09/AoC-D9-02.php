<?php

/**
 * Checks if a number can be got by adding two numbers from an array.
 * 
 * @param array $numberList
 *   The array with the numbers to use in the rule.
 * @param int $searchedNumber
 *   The number to get by adding two numbers from the array.
 * 
 * @return bool
 *   TRUE if the number can be got by adding two numbers from the array.
 *   Else it returns FALSE;
 */
function checkNumberFollowsRule(array $numberList, int $searchedNumber): bool {
  $improvedNumberList = array_combine($numberList, $numberList);
  $check = FALSE;
  foreach ($improvedNumberList as $number1) {
    $number2 = $searchedNumber - $number1;
    if (isset($improvedNumberList[$number2])) {
      $check = TRUE;
      break;
    }
  }
  return $check;
}

// Main program.
$options = getopt("f:p:", ['filePath:', 'preamble:']);
$filePath = ($options['f']) ?: $options['filePath'];
$numberList = explode("\n",file_get_contents($filePath));
$number = NULL;
$itemsToExtract = ($options['p']) ?: $options['preamble'];
for ($index = $itemsToExtract; $index < count($numberList); $index++) {
  $offset = $index - $itemsToExtract;
  $subNumberList = array_slice($numberList, $offset, $itemsToExtract);
  $check = checkNumberFollowsRule($subNumberList, $numberList[$index]);
  if (!$check) {
    $searchedKey = $index;
    $searchedNumber = $numberList[$index];
    break;
  }
}
if (($searchedNumber)) {
  echo "\nThe number that does not follow the rule is: $searchedNumber"; // 41682220
}
else {
  echo "\nIt was impossible to found the number that does not follow the rule.";
  return;
}
// Remove number from array to avoid processing it.
unset($numberList[$searchedKey]);
// Second loop to find the consecutive numbers.
$sumFirstLast = NULL;
for ($index = 0; $index < count($numberList); $index++) {
  $subtotal = $numberList[$index];
  $subIndex = $index;
  $subSet = [];
  $subSet[] = $numberList[$index];
  while ($subtotal < $searchedNumber) {
    $subIndex++;
    $subSet[] = $numberList[$subIndex];
    $subtotal += $numberList[$subIndex];
  }
  if ($subtotal == $searchedNumber) {
    sort($subSet);
    $sumFirstLast = $subSet[0] + $subSet[count($subSet) - 1];
    break;
  }
}
if (($sumFirstLast)) {
  echo "\n\nThe sum of first and last set of numbers that gets $searchedNumber is: $sumFirstLast\n\n"; // 5388976
}
else {
  echo "\n\nIt was impossible to found a ser of numbers that gets $searchedNumber.\n\n";
}
?>