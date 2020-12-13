<?php

function getOppositePoint(string $point) {
  $normal = 'NESW';
  $opposite = 'SWNE';
  $pos = strpos($normal, $point);
  return (substr($opposite, $pos, 1));
}

function getCardinalPoint(string $currentPoint, string $turn) {
  $cardinalPoints = 'NESW';
  $nextPoint = '';
  if (!preg_match("/(?P<turn>[RL])(?P<num>\d+)/", $turn, $matches)) {
    return $currentPoint;
  }
  $current = strpos($cardinalPoints, $currentPoint);
  $offset = $matches['num'] / 90;
  $offset = ($matches['turn'] == 'L') ? -$offset : $offset;
  $offset = $current + $offset;
  if ($offset > 3) {
    $offset = ($offset % 3) - 1;
  }
  $nextPoint = substr($cardinalPoints, $offset, 1);
  return $nextPoint;
}

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$instructions = explode("\n", file_get_contents($filePath));
$waypoint = [
  'E' => 10,
  'N' => 1,
];
$shipPos = [
  'E' => 0,
  'N' => 0,
];
foreach ($instructions as $inst) {
  if (preg_match("/(?P<letter>[NESW])(?P<num>\d+)/", $inst, $matches)) {
    $point = $matches['letter'];
    if (array_key_exists($point, $waypoint)) {
      $waypoint[$point] += $matches['num'];
    }
    else {
      $oppositePoint = getOppositePoint($point);
      $waypoint[$oppositePoint] -= $matches['num'];
    }
    var_dump($waypoint);
  }
  elseif (preg_match("/F(?P<num>\d+)/", $inst, $matches)) {
    $mult = $matches['num'];
    $incPos = array_map(
      function ($item) use($mult) {
        return $item * $mult;
      },
      $waypoint
    );
    foreach ($incPos as $point => $num) {
      $shipPos[$point] += $num;
    }
    var_dump($shipPos);
  }
  elseif (preg_match("/(?P<turn>[RL]\d+)/", $inst, $matches)) {
    $turn = $matches['turn'];
    $newWaypoint = [];
    foreach ($waypoint as $pos => $num) {
      $newPos = getCardinalPoint($pos, $turn);
      if (in_array($newPos, ['N', 'E'])) {
        $newWaypoint[$newPos] = $num;
      }
      else {
        $oppositePoint = getOppositePoint($newPos);
        $newWaypoint[$oppositePoint] = -$num;
      }
    }
    $waypoint = $newWaypoint;
    var_dump($waypoint);
  }
}
var_dump($shipPos);
$shipPosAbs = array_map(
  function ($item) {
    return abs($item);
  },
  $shipPos
);
var_dump($shipPosAbs);
$manhattanDistance = array_sum($shipPosAbs);
echo "\nThe Manahattan disntace is $manhattanDistance\n"; // 13340
?>