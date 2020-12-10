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
sort($seat_ids);
$min = min($seat_ids);
$max = max($seat_ids);
$full_seat_ids = array_fill($min, ($max - $min) + 1, 0);
$result = array_shift(array_diff(array_keys($full_seat_ids), $seat_ids));
echo "El identificador de mi asiento es: $result\n\n";
?>