<?php

require("fourDPocketManager.php");

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode("\n", file_get_contents($filePath));

// Initialize pocket with input.
$pocketManager = new fourDPocketManager($input);

// Get the neighbors status
// $w = 0;
// $z = -1;
// $y = 1;
// $x = 0;
// $neighbors = $pocketManager->getCubeNeighborsStatus($z, $y, $x);
// echo "$z, $y, $x => Active: {$neighbors['active']}; Inactive: {$neighbors['inactive']}\n";

// Check first cycle.
$cycles = 6;
for ($inc = 0; $inc < $cycles; $inc++) {
  $pocketManager->simulateCycle();
}
$pocket = $pocketManager->getPrintablePocket();
echo "$pocket\n";
$totalActive = $pocketManager->getTotalActive();
echo "\nThe total number of active cubes is: $totalActive\n";
?>