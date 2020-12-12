<?php

require_once('SeatManager.php');

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$rows = explode("\n",file_get_contents($filePath));
$seats = [];
foreach ($rows as $key => $row) {
  $seats[$key] = str_split($row);
}
// Solution
$seatManager = new SeatManager($seats);
$anyChange = TRUE;
while ($anyChange) {
  // $seatManager->printSeatsGrid();
  $seatManager->assignSeats("first");
  $anyChange = $seatManager->getChangeAfterAssignment();
}
$seatManager->printSeatsGrid();
$occupiedSeats = $seatManager->getOccupiedSeats();
echo "\nThe number of occupied seats is: $occupiedSeats\n\n"; // 2045
?>