<?php

require("tile.php");
require("tileManager.php");

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$tiles = explode("\n\n", file_get_contents($filePath));
$tileManager = new TileManager();
foreach ($tiles as $tile) {
  $tileData = explode("\n", $tile);
  $tileIdLine = array_shift($tileData);
  if (preg_match("/Tile\s(?P<id>\d+):/", $tileIdLine, $matches)) {
    $tileId = $matches['id'];
  }
  $tileManager->addTile(new Tile($tileId, $tileData));
}
$tileManager->setMatches();
// $corners = $tileManager->getCornerTiles();
// $tileManager->getImage();
?>