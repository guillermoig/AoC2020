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
$acc = processInstructions($input);
echo "The value of accumulator is: $acc\n";
?>