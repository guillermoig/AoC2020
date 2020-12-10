<?php

function processInstructions(array $instructions): ?int {
  $totalInstructions = count($instructions);
  $numExecutions = array_fill(0, count($instructions), 0);
  $goOn = TRUE;
  $index = 0;
  $acc = 0;
  while ($goOn && $index < $totalInstructions) {
    $numExecutions[$index] += 1;
    if (isset($numExecutions[$index]) && $numExecutions[$index] > 1) {
      $acc = NULL;
      $goOn = FALSE;
      continue;
    }
    if (
      !preg_match(
        "/(?P<instruction>\w{3})\s(?P<argument>[+-]\d+)/",
        $instructions[$index],
        $matches
      )
    ) {
      break;
    }
    $instruction = $matches['instruction'];
    $argument = $matches['argument'];
    if ($instruction == 'nop') {
      $index++;
      continue;
    }
    if ($instruction == 'acc') {
      $acc += $argument;
      $index++;
      continue;
    }
    if ($instruction == 'jmp') {
      $index += $argument;
      continue;
    }
  }
  return $acc;
}

$input = explode("\n",file_get_contents("./input.txt"));
$index = 0;
foreach ($input as $line) {
  $newInput = $input;
  if (
    !preg_match(
      "/(?P<instruction>\w{3})\s(?P<argument>[+-]\d+)/",
      $newInput[$index],
      $matches
    )
  ) {
    echo "Error parsing the isntruction number $index";
    $acc = NULL;
    break;
  }
  $instruction = $matches['instruction'];
  if ($instruction == 'acc') {
    $index++;
    continue;
  }
  if ($instruction == 'nop') {
    $newInput[$index] = str_replace('nop', 'jmp', $newInput[$index]);
  }
  elseif ($instruction == 'jmp') {
    $newInput[$index] = str_replace('jmp', 'nop', $newInput[$index]);
  }
  $acc = processInstructions($newInput);
  if (isset($acc)) {
    break;
  }
  $index++;
}
echo "The value of accumulator is: ";
if (isset($acc)) {
  echo "$acc\n";
}
else {
  echo "NULL\n";
}
?>