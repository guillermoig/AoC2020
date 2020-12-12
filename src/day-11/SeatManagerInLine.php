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
    $closestSeats[] = (isset($this->seatsGrid[$row - 1][$column - 1])) ?
      $this->seatsGrid[$row - 1][$column - 1] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row - 1][$column])) ?
      $this->seatsGrid[$row - 1][$column] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row - 1][$column + 1])) ?
      $this->seatsGrid[$row - 1][$column + 1] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row][$column - 1])) ?
      $this->seatsGrid[$row][$column - 1] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row][$column + 1])) ?
      $this->seatsGrid[$row][$column + 1] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row + 1][$column - 1])) ?
      $this->seatsGrid[$row + 1][$column - 1] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row + 1][$column])) ?
      $this->seatsGrid[$row + 1][$column] : self::LIMIT;
    $closestSeats[] = (isset($this->seatsGrid[$row + 1][$column + 1])) ?
      $this->seatsGrid[$row + 1][$column + 1] : self::LIMIT;
    return $closestSeats;
  }

  public function getFirstSeats(int $row, int $column) {
    $firstSeats = [];
    $exitCondition = [self::FREE, self::LIMIT, self::OCCUPIED];
    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row - $i][$column - $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row - $i][$column];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row - $i][$column + $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row][$column - $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row][$column + $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row + $i][$column - $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row + $i][$column];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

    $i = 0;
    do {
      $i++;
      $seat = $this->seatsGrid[$row + $i][$column - $i];
    } while (!in_array($seat, $exitCondition));
    $firsSeats[] = $seat;

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

  public function assignByClosestSeats() {
    $newSeatsGrid = $this->seatsGrid;
    $this->changeAfterAssignment = FALSE;
    for ($row = 0; $row <= $this->lastRow; $row++) {
      for ($column = 0; $column <= $this->lastColumn ; $column++) { 
        $closestSeats = $this->getClosestSeats($row, $column);
        if ($newSeatsGrid[$row][$column] == self::FREE) {
          if (!in_array(self::OCCUPIED, $closestSeats)) {
            $newSeatsGrid[$row][$column] = self::OCCUPIED;
            $this->changeAfterAssignment = TRUE;
          }
        }
        elseif ($newSeatsGrid[$row][$column] == self::OCCUPIED) {
          $seatsStatusCounter = array_count_values($closestSeats);
          $seatsOccupied = isset($seatsStatusCounter[self::OCCUPIED]) ?
            $seatsStatusCounter[self::OCCUPIED] : 0;
          if ($seatsOccupied >= 4) {
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
