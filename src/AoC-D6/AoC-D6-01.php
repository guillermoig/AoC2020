<?php
$input = explode("\n\n",file_get_contents("./input.txt"));
$sum = 0;
foreach ($input as $group_answer) {
  $removed_returns = preg_replace( "/\r|\n/", "", $group_answer);
  $group_answer_parsed = str_split($removed_returns);
  $unique_answers = array_unique($group_answer_parsed);
  $sum += count($unique_answers);
}
echo "La suma de todas las respuestas es: $sum\n\n";
?>