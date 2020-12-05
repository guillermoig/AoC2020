<?php
$input = explode("\n",file_get_contents("./input.txt"));
$seat_ids = [];
foreach ($input as $code) {
  $input_row = substr($code, 0, 7);
  $input_column = substr($code, 7, 3);
  $row = bindec(str_replace('B', 1, str_replace('F', 0, strtoupper($input_row))));
  $column = bindec(str_replace('R', 1, str_replace('L', 0, strtoupper($input_column))));
  $seat_ids[] = ($row * 8) + $column;
}
var_dump($seat_ids);
$max = max($seat_ids);
echo "El identificador con número máximo es: $max\n\n";
?>