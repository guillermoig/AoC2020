<?php

function printArray(array $list) {
  $values = implode(", ", $list);
  echo "Values: $values\n";
}

function printArrayDouble(array $list) {
  foreach ($list as $key => $value) {
    $values = implode(", ", $value);
    echo "Value $key: $values\n";
  }
}

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

function getRulesByField(array $rules) {
  $rulesByField = [];
  foreach($rules as $rule) {
    $validNumbers = [];
    if (!preg_match("/(?P<ruleName>\w+?\s?\w+):\s(?P<num11>\d+)-(?P<num12>\d+)\sor\s(?P<num21>\d+)-(?P<num22>\d+)/", $rule, $matches)) {
      continue;
    }
    $ruleName = str_replace(" ", "_", $matches['ruleName']);
    $validNumbers = array_merge($validNumbers, range($matches['num11'], $matches['num12']));
    $validNumbers = array_merge($validNumbers, range($matches['num21'], $matches['num22']));
    $rulesByField[$ruleName] = $validNumbers;
  }
  return $rulesByField;
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
$rulesByField = getRulesByField($rules);

// Print rules
// echo "\nReglas:\n";
// printArrayDouble($rulesByField);
// return;

// Remove not valid tickets.
unset($nearbyTickets[0]);
// $nearbyTickets[] = $myTicket[1];
foreach ($nearbyTickets as $key => $ticket) {
  $ticketNumbers = explode(",", $ticket);
  foreach ($ticketNumbers as $number) {
    if (!isset($validNumbers[$number])) {
      unset($nearbyTickets[$key]);
      break;
    }
  }
}

// Group numbers of nearby tickets by order.
$nearbyTicketNumbersByOrder = [];
foreach ($nearbyTickets as $ticket) {
  $ticketNumbers = explode(",", $ticket);
  foreach ($ticketNumbers as $key => $value) {
    $nearbyTicketNumbersByOrder[$key][] = $value;
  }
}

// Print numbers by order.
// echo "\nNúmeros de tickets agrupados por orden:\n";
// printArrayDouble($nearbyTicketNumbersByOrder);
// return;

// Identify fields.
$rulesOrder = [];
$rulesByFieldTmp = $rulesByField;
foreach ($nearbyTicketNumbersByOrder as $order => $group) {
  // echo "\nGrupo: $order\n";
  // var_dump(count($group));
  foreach ($rulesByFieldTmp as $ruleName => $intervals) {
    $numbersChecked = array_diff($group, $intervals);
    // var_dump("$ruleName: " . count($numbersChecked));
    if (count($numbersChecked) == 0) {
      // echo "\nIntervalo escogido para grupo $order: $ruleName\n";
      $rulesOrder[$order][] = $ruleName;
    }
  }
}
// Print rules names by order.
// echo "\nReglas según orden:\n";
// printArrayDouble($rulesOrder);

$rulesOrderFiltered = [];
$finish = FALSE;
$lastRuleAdded = '';
$laps = 0;
while (!empty($rulesOrder)) {
  foreach ($rulesOrder as $index => $rules) {
    if (count($rules) == 1) {
      $rulesOrderFiltered[$index] = current($rules);
      $lastRuleAdded = current($rules);
      echo "\nAñadiendo el que solo tiene uno\n";
      unset($rulesOrder[$index]);
      var_dump($lastRuleAdded);
      var_dump('Index: ' . $index);
      var_dump($rulesOrderFiltered);
      break;
    }
  }
  $rulesOrderTmp = [];
  foreach ($rulesOrder as $index => $rules) {
    // if ($index == 19) {
    //   echo "\nEliminando el último incorporado\n";
    //   var_dump($index);
    //   var_dump($rules);
    //   var_dump($lastRuleAdded);
    //   echo "\nDiferencia:\n";
    //   var_dump(array_diff($rules, [$lastRuleAdded]));
    // }
    $rulesOrderTmp[$index] = array_diff($rules, [$lastRuleAdded]);
    // if ($index == 19) {
    //   echo "\Comprobando si se ha eliminado\n";
    //   var_dump($rules);
    // }
  }
  $rulesOrder = $rulesOrderTmp;
    printArrayDouble($rulesOrder);

  // printArrayDouble($rulesOrder);
  $laps++;
}
// Print rules names by order.
echo "\nReglas filtradas:\n";
var_dump($rulesOrderFiltered);

// Get rules with "departure" in their name.
$departureRules = array_filter(
  $rulesOrderFiltered,
  function ($value) {
    return (strstr($value, "departure") !== FALSE);
  }
);

// Print rules with "departure" in their name.
echo "\nReglas que contienen 'departure':\n";
printArray($departureRules);
var_dump($departureRules);

$myTicketNumbers = explode(",",$myTicket[1]);

echo "\nNúmeros de mi ticket:\n";
printArray($myTicketNumbers);

$myTicketDepartureNumbers = array_intersect_key($myTicketNumbers, $departureRules);
echo "\nNúmeros de mi ticket de 'departure':\n";
printArray($myTicketDepartureNumbers);

$product = array_product($myTicketDepartureNumbers);
echo "\nEl producto de los campos 'departure' es: $product\n";

?>