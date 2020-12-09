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
    $number = $numberList[$index];
    break;
  }
}
if (($number)) {
  echo "\nThe number that does not follow the rule is: $number\n\n"; // 41682220
}
else {
  echo "\nIt was impossible to found the number that does not follow the rule.\n\n";
}
?>