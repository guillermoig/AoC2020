<?php

function getValidNumbers(array $rules) {
  $validNumbers = [];
  foreach($rules as $rule) {
    if (!preg_match("/\w+?\s?\w+:\s(?P<num11>\d+)-(?P<num12>\d+)\sor\s(?P<num21>\d+)-(?P<num22>\d+)/", $rule, $matches)) {
      continue;
    }
    $validNumbers = array_merge($validNumbers, range($matches['num11'], $matches['num12']));
    $validNumbers = array_merge($validNumbers, range($matches['num21'], $matches['num22']));
  }
  $validNumbers = array_unique($validNumbers);
  sort($validNumbers);
  $validNumbers = array_flip($validNumbers);
  return $validNumbers;
}

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode("\n\n", file_get_contents($filePath));
$rules = explode("\n", $input[0]);
$myTicket = explode("\n", $input[1]);
$nearbyTickets = explode("\n", $input[2]);
// Process rules
$validNumbers = getValidNumbers($rules);

unset($nearbyTickets[0]);
$errorRate = 0;
foreach ($nearbyTickets as $ticket) {
  $ticketNumbers = explode(",", $ticket);
  foreach ($ticketNumbers as $number) {
    if (!isset($validNumbers[$number])) {
      $errorRate += $number;
      break;
    }
  }
}
echo "\nThe rate of not valid tickets is: $errorRate\n";
?>