<?php

function getCardinalPoint(string $currentPoint, string $turn) {
  $cardinalPoints = 'NESW';
  $nextPoint = '';
  if (!preg_match("/(?P<turn>[RL])(?P<num>\d+)/", $turn, $matches)) {
    return $currentPoint;
  }
  $current = strpos($cardinalPoints, $currentPoint);
  $offset = $matches['num'] / 90;
  $offset = ($matches['turn'] == 'L') ? -$offset : $offset;
  $offset = $current + $offset;
  if ($offset > 3) {
    $offset = ($offset % 3) - 1;
  }
  $nextPoint = substr($cardinalPoints, $offset, 1);
  return $nextPoint;
}

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$instructions = str_replace("\n","", file_get_contents($filePath));
$groups = preg_split("/[RL]\d+/",$instructions);
// var_dump($groups);

// Create an array with turns
if (preg_match_all("/(?P<turns>[RL]\d+)+?/", $instructions, $matches)) {
  $turns = $matches['turns'];
}
// var_dump($turns);

// Array of cardinal points that show direction each time.
$directions = ['E'];
foreach ($turns as $key => $turn) {
  $directions[] = getCardinalPoint($directions[$key], $turn);
}
// var_dump($directions);

// Change in each group 'F' by its direction.
foreach ($groups as $key => $group) {
  $groups[$key] = str_replace('F', $directions[$key], $group);
}
// var_dump($groups);

// Join all groups in one string
$processedInstructions = implode($groups);

// Operations
if (preg_match_all("/(N(?P<num>\d+))+?/", $processedInstructions, $matches)) {
  $north = array_sum($matches['num']);
}
if (preg_match_all("/(S(?P<num>\d+))+?/", $processedInstructions, $matches)) {
  $south = array_sum($matches['num']);
}
if (preg_match_all("/(E(?P<num>\d+))+?/", $processedInstructions, $matches)) {
  $east = array_sum($matches['num']);
}
if (preg_match_all("/(W(?P<num>\d+))+?/", $processedInstructions, $matches)) {
  $west = array_sum($matches['num']);
}
$manhattanDistance = abs($north - $south) + abs($east - $west);
echo "\nThe Manahattan disntace is $manhattanDistance\n"; // 1601
?>