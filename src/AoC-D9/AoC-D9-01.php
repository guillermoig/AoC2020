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

$numberList = explode("\n",file_get_contents("./input.txt"));
$number = NULL;
$itemsToExtract = 25;
$startIn = 25;
for ($index = $startIn; $index < count($numberList); $index++) {
  $offset = $index - $itemsToExtract;
  $subNumberList = array_slice($numberList, $offset, $itemsToExtract);
  $check = checkNumberFollowsRule($subNumberList, $numberList[$index]);
  if (!$check) {
    $number = $numberList[$index];
    break;
  }
}
if (($number)) {
  echo "The number that does not follow the rule is: $number\n"; // 41682220
}
else {
  echo "It was impossible to found the number that does not follow the rule.\n";
}
?>