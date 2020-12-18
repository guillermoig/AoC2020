<?php

/**
 * Class ThreeDPocketManager.
 * @package adventofcode\day11
 */
class ThreeDPocketManager {

  const ACTIVE = "#";
  const INACTIVE = ".";

  /**
   * Property that stores the pcoket.
   *
   * @var array
   */
  private $pocket;

  /**
   * Cosntructor of the class
   *
   * @param array $pocket
   *   The initial distribution of the cubes in an array of strings.
   */
  public function __construct(array $input) {
    $pocket[0] = [];
    if (!empty($input)) {
      foreach ($input as $line => $data) {
        $pocket[0][$line] = str_split($data, 1);
      }
    }
    $this->pocket = $pocket;
  }

  public function getCubeNeighborsStatus(int $zIndex, int $yIndex, int $xIndex): array {
    $neighborsStatus = [
      'active' => 0,
      'inactive' => 0,
    ];
    
    for ($z = ($zIndex - 1); $z <= ($zIndex + 1); $z++) {
      if (!isset($this->pocket[$z])) {
        $neighborsStatus['inactive'] += 9;
        continue;
      }
      for ($y = ($yIndex - 1); $y <= ($yIndex + 1); $y++) {
        if (!isset($this->pocket[$z][$y])) {
          $neighborsStatus['inactive'] += 3;
          continue;
        }
        for ($x = ($xIndex - 1); $x <= ($xIndex + 1); $x++) {
          if (!isset($this->pocket[$z][$y][$x])) {
            $neighborsStatus['inactive']++;
            continue;
          }
          if ($this->pocket[$z][$y][$x] == self::ACTIVE) {
            // echo "Active: $z $y $x\n";
            $neighborsStatus['active']++;
            continue;
          }
          $neighborsStatus['inactive']++;
        }
      }
    }
    if (!isset($this->pocket[$zIndex][$yIndex][$xIndex])
      || ($this->pocket[$zIndex][$yIndex][$xIndex] == self::INACTIVE)
    ) {
      $neighborsStatus['inactive']--;
    }
    elseif ($this->pocket[$zIndex][$yIndex][$xIndex] == self::ACTIVE) {
      $neighborsStatus['active']--;
    }
    return $neighborsStatus;
  }

  public function getTotalActive() {
    $total = 0;
    foreach ($this->pocket as $region) {
      foreach ($region as $line) {
        foreach ($line as $element) {
          $total += ($element == self::ACTIVE) ? 1 : 0;
        }
      }
    }
    return $total;
  }

  /**
   * Returns the pocket array.
   *
   * @return array
   *   The pocket array.
   */
  public function getPocket(): array {
    return $this->pocket;
  }

  /**
   * Returns the pocket as a printable string.
   *
   * @return string
   */
  public function getPrintablePocket(): string {
    if (empty($this->pocket)) {
      return '';
    }
    $pocketStream = "\nThis is the pocket status:";
    foreach ($this->pocket as $depth => $twoDRegion) {
      $pocketStream .= "\n\nz = $depth";
      foreach ($twoDRegion as $oneLine) {
        $pocketStream .= "\n" . implode("", $oneLine);
      }
    }
    $pocketStream .= "\n";
    return $pocketStream;
  }

  public function simulateCycle() {
    $newPocket = [];
    $zLowLimit = array_key_first($this->pocket) - 1;
    $zHighLimit = array_key_last($this->pocket) + 1;
    $yLowLimit = array_key_first($this->pocket[0]) - 1;
    $yHighLimit = array_key_last($this->pocket[0]) + 1;
    $xLowLimit = array_key_first($this->pocket[0][0]) - 1;
    $xHighLimit = array_key_last($this->pocket[0][0]) + 1;
    for ($z = $zLowLimit; $z <= $zHighLimit; $z++) {
      for ($y = $yLowLimit; $y <= $yHighLimit; $y++) {
        for ($x = $xLowLimit; $x <= $xHighLimit; $x++) {
          if (!isset($this->pocket[$z][$y][$x])) {
            $this->pocket[$z][$y][$x] = self::INACTIVE;
            $newPocket[$z][$y][$x] = self::INACTIVE;
          }
          $neighborsStatus = $this->getCubeNeighborsStatus($z, $y, $x);
          // echo "$z, $y, $x => {$this->pocket[$z][$y][$x]} => Active: {$neighborsStatus['active']}; Inactive: {$neighborsStatus['inactive']}\n";
          if ($this->pocket[$z][$y][$x] == self::ACTIVE
            && !in_array($neighborsStatus['active'], [2, 3])
          ) {
            $newPocket[$z][$y][$x] = self::INACTIVE;
            // echo "$z, $y, $x => Inactive\n";
            continue;
          }
          if (
            ($this->pocket[$z][$y][$x] == self::INACTIVE)
            && ($neighborsStatus['active'] == 3)
          ) {
            $newPocket[$z][$y][$x] = self::ACTIVE;
            // echo "$z, $y, $x => Active\n";
            continue;
          }
          $newPocket[$z][$y][$x] = $this->pocket[$z][$y][$x];
        }
      }
    }
    ksort($newPocket);
    $this->pocket = $newPocket;
  }

}

?>
