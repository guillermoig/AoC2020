<?php


// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode(",", file_get_contents($filePath));
$inputLenght = count($input);
$serie = [];
$counter = 1;
foreach ($input as $number) {
  $serie[$number][] = $counter;
  $counter++;
}
$max = 30000000;
$lastNumberSpoken = array_key_last($serie);
for ($turn = $inputLenght + 1; $turn <= $max; $turn++) {
  if (count($serie[$lastNumberSpoken]) == 1) {
    $lastNumberSpoken = 0;
  }
  elseif (count($serie[$lastNumberSpoken]) > 1) {
    $lastTime = array_key_last($serie[$lastNumberSpoken]);
    $prevLastTime = $lastTime - 1;
    $lastNumberSpoken = $serie[$lastNumberSpoken][$lastTime] - $serie[$lastNumberSpoken][$prevLastTime];
  }
  $serie[$lastNumberSpoken][] = $turn;
  if (count($serie[$lastNumberSpoken]) > 2) {
    $firstKey = array_key_first($serie[$lastNumberSpoken]);
    unset($serie[$lastNumberSpoken][$firstKey]);
  }
}
$inputString = implode(",", $input);
echo "\nThe last number spoken for $inputString in the 2020th time is: $lastNumberSpoken\n";
?>