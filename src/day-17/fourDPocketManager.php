<?php

/**
 * Class FourDPocketManager.
 * @package adventofcode\day11
 */
class FourDPocketManager {

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
        $pocket[0][0][$line] = str_split($data, 1);
      }
    }
    $this->pocket = $pocket;
  }

  public function getCubeNeighborsStatus(int $wIndex, int $zIndex, int $yIndex, int $xIndex): array {
    $neighborsStatus = [
      'active' => 0,
      'inactive' => 0,
    ];
    for ($w = ($wIndex - 1); $w <= ($wIndex + 1); $w++) {
      if (!isset($this->pocket[$w])) {
        $neighborsStatus['inactive'] += 27;
        continue;
      }
      for ($z = ($zIndex - 1); $z <= ($zIndex + 1); $z++) {
        if (!isset($this->pocket[$w][$z])) {
          $neighborsStatus['inactive'] += 9;
          continue;
        }
        for ($y = ($yIndex - 1); $y <= ($yIndex + 1); $y++) {
          if (!isset($this->pocket[$w][$z][$y])) {
            $neighborsStatus['inactive'] += 3;
            continue;
          }
          for ($x = ($xIndex - 1); $x <= ($xIndex + 1); $x++) {
            if (!isset($this->pocket[$w][$z][$y][$x])) {
              $neighborsStatus['inactive']++;
              continue;
            }
            if ($this->pocket[$w][$z][$y][$x] == self::ACTIVE) {
              // echo "Active: $z $y $x\n";
              $neighborsStatus['active']++;
              continue;
            }
            $neighborsStatus['inactive']++;
          }
        }
      }
    } 
    if (!isset($this->pocket[$wIndex][$zIndex][$yIndex][$xIndex])
      || ($this->pocket[$wIndex][$zIndex][$yIndex][$xIndex] == self::INACTIVE)
    ) {
      $neighborsStatus['inactive']--;
    }
    elseif ($this->pocket[$wIndex][$zIndex][$yIndex][$xIndex] == self::ACTIVE) {
      $neighborsStatus['active']--;
    }
    return $neighborsStatus;
  }

  public function getTotalActive() {
    $total = 0;
    foreach ($this->pocket as $threeDRegion) {
      foreach ($threeDRegion as $region) {
        foreach ($region as $line) {
          foreach ($line as $element) {
            $total += ($element == self::ACTIVE) ? 1 : 0;
          }
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
    foreach ($this->pocket as $wIndex => $threeDRegion) {
      foreach ($threeDRegion as $zIndex => $twoDRegion) {
        $pocketStream .= "\n\nw = $wIndex, z = $zIndex";
        foreach ($twoDRegion as $oneLine) {
          $pocketStream .= "\n" . implode("", $oneLine);
        }
      }
    }
    $pocketStream .= "\n";
    return $pocketStream;
  }

  public function simulateCycle() {
    $newPocket = [];
    $wLowLimit = array_key_first($this->pocket) - 1;
    $wHighLimit = array_key_last($this->pocket) + 1;
    $zLowLimit = array_key_first($this->pocket[0]) - 1;
    $zHighLimit = array_key_last($this->pocket[0]) + 1;
    $yLowLimit = array_key_first($this->pocket[0][0]) - 1;
    $yHighLimit = array_key_last($this->pocket[0][0]) + 1;
    $xLowLimit = array_key_first($this->pocket[0][0][0]) - 1;
    $xHighLimit = array_key_last($this->pocket[0][0][0]) + 1;
    for ($w = $wLowLimit; $w <= $wHighLimit; $w++) {
      for ($z = $zLowLimit; $z <= $zHighLimit; $z++) {
        for ($y = $yLowLimit; $y <= $yHighLimit; $y++) {
          for ($x = $xLowLimit; $x <= $xHighLimit; $x++) {
            if (!isset($this->pocket[$w][$z][$y][$x])) {
              $this->pocket[$w][$z][$y][$x] = self::INACTIVE;
              $newPocket[$w][$z][$y][$x] = self::INACTIVE;
            }
            $neighborsStatus = $this->getCubeNeighborsStatus($w, $z, $y, $x);
            // echo "$z, $y, $x => {$this->pocket[$w][$z][$y][$x]} => Active: {$neighborsStatus['active']}; Inactive: {$neighborsStatus['inactive']}\n";
            if ($this->pocket[$w][$z][$y][$x] == self::ACTIVE
              && !in_array($neighborsStatus['active'], [2, 3])
            ) {
              $newPocket[$w][$z][$y][$x] = self::INACTIVE;
              // echo "$z, $y, $x => Inactive\n";
              continue;
            }
            if (
              ($this->pocket[$w][$z][$y][$x] == self::INACTIVE)
              && ($neighborsStatus['active'] == 3)
            ) {
              $newPocket[$w][$z][$y][$x] = self::ACTIVE;
              // echo "$z, $y, $x => Active\n";
              continue;
            }
            $newPocket[$w][$z][$y][$x] = $this->pocket[$w][$z][$y][$x];
          }
        }
      }
    }
    ksort($newPocket);
    $this->pocket = $newPocket;
  }

}

?>
