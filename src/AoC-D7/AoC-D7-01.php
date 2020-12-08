<?php

function getParents(array $tree, string $searchedValue): ?array {
  if (!isset($tree[$searchedValue])) {
    return NULL;
  }
  $results = $tree[$searchedValue];
  $parents = $tree[$searchedValue];
  foreach ($parents as $parent) {
    $newParents = getParents($tree, $parent);
    if ($newParents) {
      $results = array_merge($results, $newParents);
    }
  }
  return array_unique($results);
}

$input = explode("\n",file_get_contents("./input.txt"));
$color_containers = [];
foreach ($input as $line) {
  // If line does not contain a data about colors.
  if (!preg_match("/(?P<container>\w+\s\w+)\sbags\scontain(?P<content>.+)\./", $line, $matches)) {
    continue;
  }
  // If the content colors is "no other bags".
  if (preg_match("/\s?no\sother\sbags/", $matches['content'])) {
    continue;
  }
  $container_color = $matches['container'];
  // If content colors do not match with the given structure.
  if (!preg_match_all("/(\s(?P<num>\d+)\s(?P<color>\w+\s\w+)\sbags?,?)+?/", $matches['content'], $content_colors)) {
    continue;
  }
  // Add to each content color its containers.
  foreach ($content_colors['color'] as $content_color) {
    $color_containers[$content_color][] = $container_color;
  }
}
// So far, in $color_containers we have an array where keys are the color of a bag,
// and values are the color of bags that can contain them.

$searched_color = 'shiny gold';
$parents = getParents($color_containers, $searched_color);
var_dump($parents);
$number_parents = count($parents);
echo "El número de bolsas que pueden contener bolsas de color $searched_color es: $number_parents\n";
?>