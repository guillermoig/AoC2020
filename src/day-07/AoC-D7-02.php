<?php

function getChildrenTotal(array $tree, string $searchedValue, int $bagsNum): ?int {
  if (!isset($tree[$searchedValue])) {
    return $bagsNum;
  }
  $children = $tree[$searchedValue];
  $totalChildren = 0;
  foreach ($children as $child) {
    $totalChildren += getChildrenTotal($tree, $child['color'], $child['num']);
  }
  $total = $bagsNum + ($bagsNum * $totalChildren);
  return $total;
}

$input = explode("\n", file_get_contents("./input.txt"));
$colorChildren = [];
foreach ($input as $line) {
  // If line does not contain a data about colors.
  if (!preg_match("/(?P<container>\w+\s\w+)\sbags\scontain(?P<content>.+)\./", $line, $matches)) {
    continue;
  }
  // If the content colors is "no other bags".
  if (preg_match("/\s?no\sother\sbags/", $matches['content'])) {
    continue;
  }
  $parentColor = $matches['container'];
  // If content colors do not match with the given structure.
  if (!preg_match_all("/(\s(?P<num>\d+)\s(?P<color>\w+\s\w+)\sbags?,?)+?/", $matches['content'], $children)) {
    continue;
  }
  $childrenColors = $children['color'];
  $childrenTotal = $children['num'];
  // Add to each content color its containers.
  foreach ($childrenColors as $key => $child) {
    $colorChildren[$parentColor][] = [
      'color' => $child,
      'num' => $childrenTotal[$key],
    ];
  }
}
// So far, in $colorChildren we have an array where keys are the color of the
// bags that can contain other bags and values are the colors of the bags it
// can have.
$searched_color = 'shiny gold';
$bagsNum = 1;
$childrenTotal = getChildrenTotal($colorChildren, $searched_color, $bagsNum) - $bagsNum;
echo "El número de bolsas que puede contener una bolsa de color $searched_color es: $childrenTotal\n";
?>