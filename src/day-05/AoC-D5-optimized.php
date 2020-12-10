<?php
$input = explode("\n",file_get_contents("./input.txt"));
$seat_ids = [];
foreach ($input as $code) {
  $seat_ids[] = bindec(strtr($code, 'FBLR', '0101'));
}
$max = max($seat_ids);
echo "El identificador con número máximo es: $max\n\n";
$full_seat_ids = range(min($seat_ids), $max);
$result = current(array_diff($full_seat_ids, $seat_ids));
echo "El identificador de mi asiento es: $result\n\n";
?>