<?php

/**
 * Class Tile.
 * @package adventofcode\day20
 */
class Tile {

  /**
   * Array to store all available borders after rotating or fliping.
   *
   * @var array;
   */
  private $borders;

  /**
   * Bidimensional array to save the tile data.
   *
   * @var array;
   */
  private $data;

  /**
   * Identifier of the tile.
   *
   * @var int
   */
  private $id;

  /**
   * Number of elements of the height.
   *
   * @var int
   */
  private $height;

  /**
   * Number of elements of the width.
   *
   * @var int
   */
  private $width;

  /**
   * Cosntructor of the class
   */
  public function __construct(int $id, array $data) {
    $this->id = $id;
    $this->setData($data);
    $this->setBorders();
  }

  public function getBorders() {
    return $this->borders;
  }

  public function getNormalBorders() {
    $normalBorders = [
      'NF' => $this->borders['NF'],
      'EF' => $this->borders['EF'],
      'SF' => $this->borders['SF'],
      'WF' => $this->borders['WF'],
    ];
    return $normalBorders;
  }

  public function id() {
    return $this->id;
  }

  protected function setBorders() {
    $borders = [];
    $borders['NF'] = implode("", $this->data[0]);
    $borders['NR'] = strrev($borders['NF']);
    $borders['SF'] = implode("", $this->data[($this->height - 1)]);
    $borders['SR'] = strrev($borders['SF']);
    $borders['WF'] = "";
    $borders['EF'] = "";
    foreach ($this->data as $line) {
      $borders['WF'] .= $line[0];
      $borders['EF'] .= $line[($this->width - 1)];
    }
    $borders['WR'] = strrev($borders['WF']);
    $borders['ER'] = strrev($borders['EF']);
    $this->borders = $borders;
  }

  protected function setData($data) {
    $this->data = [];
    foreach ($data as $key => $line) {
      $this->data[$key] = str_split($line);
    }
    $this->height = count($data);
    $this->width = strlen($data[0]);
  }

  public function getData() {
    return $this->data;
  }

}

?>
