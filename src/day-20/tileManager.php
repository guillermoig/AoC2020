<?php

// require("tile.php");

/**
 * Class TileManager.
 * @package adventofcode\day20
 */
class TileManager {

  /**
   * Array to store corner tile ids.
   *
   * @var array;
   */
  private $cornerTiles = [];

  /**
   * Array to store the logic of rotations.
   *
   * @var array
   */
  private $rotations;

  /**
   * Array to store the name of the four sides of a tile.
   *
   * @var array
   */
  private $sides = ['N', 'E', 'S', 'W'];

  /**
   * Array to store tile objects.
   *
   * @var array;
   */
  private $tileSet = [];

  /**
   * Array to store which each tile match with.
   *
   * @var array
   */
  private $tilesMatches = [];

  /**
   * Cosntructor of the class
   */
  public function __construct() {
    $this->tileSet = [];
    $this->setRotations();
  }

  public function addTile(Tile $tile) {
    $this->tileSet[$tile->id()] = $tile;
  }
  
  public function getCornerTiles() {
    return $this->cornerTiles;
  }

  protected function getRelativePosition(string $sourceBorder, string $destBorder): array {
    $firstBorder = str_split($sourceBorder);
    // It can be 'N', 'E', 'S' or 'W'.
    $firstSide = $firstBorder[0];
    // It can be 'F' or 'R'.
    $firstOrientation = $firstBorder[1];

    $secondBorder = str_split($destBorder);
    $secondSide = $secondBorder[0];
    $secondOrientation = $secondBorder[1];

    $key = $firstSide . $secondSide . $secondOrientation;
    return $this->rotations[$key];
  }

  public function getTile($id) {
    return $this->tileSet[$id];
  }


  public function setMatches() {
    foreach ($this->tileSet as $sourceId => $sourceTile) {
      $tileSubSet = $this->tileSet;
      unset($tileSubSet[$sourceId]);
      foreach ($sourceTile->getBorders() as $borderId => $borderData) {
        foreach ($tileSubSet as $destId => $destTile) {
          $destTileBorders = $destTile->getBorders();
          if (in_array($borderData, $destTileBorders)) {
            $destMatchedBorder = array_search($borderData, $destTileBorders);
            $position = $this->getRelativePosition($borderId, $destMatchedBorder);
            $this->tilesMatches[$sourceId][$borderId] = [
              'id' => $destId,
              'borderId' => $destMatchedBorder,
              'rotation' => $position['rotation'],
              'flip' => $position['flip'],
            ];
          }
        }
      }
    }
    // print_r($this->tilesMatches);
    $this->setCornerTiles();
    $this->setImage();
  }

  public function setCornerTiles() {
    $cornerTiles = [];
    foreach ($this->tilesMatches as $tileId => $tileMatches) {
      if (count($tileMatches) == 4) {
        $cornerTiles[$tileId] = $tileMatches;
      }
    }
    $this->cornerTiles = $cornerTiles;
  }

  protected function setImage() {
    $image = [];
    $cornerUpLeftMatches = array_filter(
      $this->cornerTiles,
      function ($item) {
        $ids = array_keys($item);
        $found = array_diff($ids, ['EF', 'ER', 'SF', 'SR']);
        return (empty($found));
      }
    );
    $currentTileId = array_key_first($cornerUpLeftMatches);
    $finish = FALSE;
    $hor = 0;
    $ver = 0;
    while (!$finish) {
      $currentTile = $this->tilesMatches[$currentTileId];
      $image[$ver][$hor] = [
        'id' => $currentTileId,
        'rotation' => '',
        'flip' => '',
      ];
      foreach ($currentTile as $side => $tileData) {
        $asideTileId = $tileData['id'];
        $asideTileBorderId = $tileData['borderId'];
        print_r($asideTileId);
        print_r($asideTileBorderId);
        $targetTileData = $this->tilesMatches[$asideTileId][$asideTileBorderId];
        $image[$ver][$hor]['rotation'] .= "," . $targetTileData['rotation'];
        $image[$ver][$hor]['flip'] .= "," . $targetTileData['flip'];
      }
      if (array_key_exists('EF', $currentTile)) {
        break;
        $currentTileId = $currentTile['EF']['id'];
        $hor++;
      }
      elseif (array_key_exists('ER', $currentTile)) {
        $currentTileId = $currentTile['ER']['id'];
        $hor++;
      }
      else {
        $firstOfRowId = $image[$ver][0]['id'];
        if (isset($this->tilesMatches[$firstOfRowId]['SF'])) {
          $currentTileId = $this->tilesMatches[$firstOfRowId]['SF']['id'];
          $hor = 0;
          $ver++; 
        }
        elseif (isset($this->tilesMatches[$firstOfRowId]['SR'])) {
          $currentTileId = $this->tilesMatches[$firstOfRowId]['SR']['id'];
          $hor = 0;
          $ver++;
        }
        else {
          $finish = TRUE;
        }
      }

    }
    print_r($image);
  }

  protected function setRotations() {
    $filePath = basename(__DIR__) . "/rotations.txt";
    $rotationsFile = explode("\n", file_get_contents($filePath));
    $rotations = [];
    foreach ($rotationsFile as $rotationLine) {
      $rotationData = explode(",", $rotationLine);
      $rotations[$rotationData[0]] = [
        'rotation' => $rotationData[1],
        'flip' => $rotationData[2],
      ];
    }
    $this->rotations = $rotations;
    // print_r($this->rotations);
  }

}

?>
