<?php

/**
 * Class SeatManager.
 * @package adventofcode\day11
 */
class SeatManager {

  const FLOOR = ".";
  const FREE = "L";
  const LIMIT = "";
  const OCCUPIED = "#";

  /**
   * Property to store initial seats distribution.
   *
   * @var array
   */
  private $seatsGrid;

  /**
   * Property to store the last row.
   *
   * @var int
   */
  private $lastRow;

  /**
   * Property to store the last column.
   *
   * @var int
   */
  private $lastColumn;
  
  /**
   * TRUE if there has been a change after the last process. Else FALSE.
   *
   * @var bool
   */
  private $changeAfterAssignment;

  /**
   * Stores the directions qhere searching seats.
   *
   * @var array
   */
  private $seatsPointers = [
    [-1, -1],
    [-1, 0],
    [-1, 1],
    [0, -1],
    [0 , 1],
    [1, -1],
    [1, 0],
    [1, 1],
  ];

  public function __construct(array $seatsGrid) {
    if (!empty($seatsGrid)) {
      $this->seatsGrid = $seatsGrid;
      $this->lastRow = count($seatsGrid) - 1;
      $this->lastColumn = count($seatsGrid[0]) - 1;
    }
  }

  public function getChangeAfterAssignment() {
    return $this->changeAfterAssignment;
  }

  public function getClosestSeats(int $row, int $column): array {
    $closestSeats = [];
    foreach ($this->seatsPointers as $point) {      
      $closestSeats[] = (isset($this->seatsGrid[$row + $point[0]][$column + $point[1]])) ?
        $this->seatsGrid[$row + $point[0]][$column + $point[1]] : self::LIMIT;
    }
    return $closestSeats;
  }

  public function getFirstSeats(int $row, int $column) {
    $firstSeats = [];
    $exitCondition = [self::FREE, self::LIMIT, self::OCCUPIED];
    foreach ($this->seatsPointers as $point) {      
      $step = 0;
      do {
        $step++;
        $seat = isset($this->seatsGrid[$row + ($step * $point[0])][$column + ($step * $point[1])]) ? 
          $this->seatsGrid[$row + ($step * $point[0])][$column + ($step * $point[1])] : self::LIMIT;
      } while (!in_array($seat, $exitCondition));
      $firstSeats[] = $seat;
    }
    return $firstSeats;
  }

  public function getSeatsGrid() {
    return $this->seatsGrid;
  }

  public function getOccupiedSeats() {
    $seatsInLine = [];
    foreach ($this->seatsGrid as $row) {
      $seatsInLine[] = implode("", $row);
    }
    $seatsInLine = implode("", $seatsInLine);
    return substr_count($seatsInLine, self::OCCUPIED);
  }

  public function assignSeats(string $method) {
    $getAdjacentSeats = ($method == 'closest') ? "getClosestSeats" : "getFirstSeats";
    $allowOccupiedSeats = ($method == 'closest') ? 4 : 5;
    $newSeatsGrid = $this->seatsGrid;
    $this->changeAfterAssignment = FALSE;
    for ($row = 0; $row <= $this->lastRow; $row++) {
      for ($column = 0; $column <= $this->lastColumn ; $column++) { 
        if ($newSeatsGrid[$row][$column] == self::FLOOR) {
          continue;
        }
        $adjacentSeats = $this->$getAdjacentSeats($row, $column);
        if ($newSeatsGrid[$row][$column] == self::FREE) {
          if (!in_array(self::OCCUPIED, $adjacentSeats)) {
            $newSeatsGrid[$row][$column] = self::OCCUPIED;
            $this->changeAfterAssignment = TRUE;
          }
        }
        elseif ($newSeatsGrid[$row][$column] == self::OCCUPIED) {
          $seatsStatusCounter = array_count_values($adjacentSeats);
          $seatsOccupied = isset($seatsStatusCounter[self::OCCUPIED]) ?
            $seatsStatusCounter[self::OCCUPIED] : 0;
          if ($seatsOccupied >= $allowOccupiedSeats) {
            $newSeatsGrid[$row][$column] = self::FREE;
            $this->changeAfterAssignment = TRUE;
          }
        }
      }
    }
    $this->seatsGrid = $newSeatsGrid;
  }

  public function printSeatsGrid() {
    echo "\n";
    foreach ($this->seatsGrid as $row) {
      $line = implode("", $row);
      echo "$line\n";
    }
    echo "\n";
  }
}

?>
