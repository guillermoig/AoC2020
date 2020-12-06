<?php
$input = explode("\n\n",file_get_contents("./input.txt"));
$sum = 0;
foreach ($input as $group_answer) {
  $user_answers_string = explode("\n", $group_answer);
  $user_answers_array = array_map(
    function($user_answer) {
      return str_split($user_answer);
    },
    $user_answers_string
  );
  $intersect = $user_answers_array[0];
  foreach ($user_answers_array as $user_answers) {
    $intersect = array_intersect($intersect, $user_answers);
  }
  $sum += count($intersect);
}
echo "La suma de todas las respuestas es: $sum\n\n";
?>