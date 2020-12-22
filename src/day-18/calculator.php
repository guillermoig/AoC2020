<?php

/**
 * Class Calculator.
 * @package adventofcode\day18
 */
class Calculator {

  /**
   * Cosntructor of the class
   */
  public function __construct() {
  }

  public function getResult($operation) {
    if (!preg_match_all("/(?P<items>(([+*]\s)?((\d+)|(\(((?>[^()]+)|(?R))*\)))))+?/", $operation, $matches)) {
      return 0;
    }
    $items = $matches['items'];
    $result = 0;
    foreach ($items as $operation) {
      if (preg_match("/^(?P<num>\d+){1}/", $operation, $elements)) {
        $result = $elements['num'];
        continue;
      }
      if (preg_match("/(((?P<op>[+*])\s)?\((?P<newItem>((?>[^()]+)|(?R))*)\))/", $operation, $elements)) {
        if (empty($elements['op'])) {
          $result = $this->getResult($elements['newItem']);
        }
        elseif ($elements['op'] == '+') {
          $result += $this->getResult($elements['newItem']);
        }
        elseif ($elements['op'] == '*') {
          $result *= $this->getResult($elements['newItem']);
        }
        continue;
      }
      if (preg_match("/((?P<op>[+*])\s(?P<num>\d+)){1}/", $operation, $elements)) {
        if ($elements['op'] == '+') {
          $result += $elements['num'];
        }
        elseif ($elements['op'] == '*') {
          $result *= $elements['num'];
        }
        continue;
      }
    }
    return $result;
  }

  public function getResultSumBeforeProduct($operation) {
    if (!preg_match_all("/(?P<items>(([+*])|((\d+)|(\(((?>[^()]+)|(?R))*\)))))+?/", $operation, $matches)) {
      return 0;
    }
    $items = $matches['items'];
    $result = 0;
    $tmpResult = [];
    // First: solve operations in brackets.
    foreach ($items as $item) {
      if (preg_match("/\((?P<newItem>((?>[^()]+)|(?R))*)\)/", $item, $elements)) {
        $tmpResult[] = $this->getResultSumBeforeProduct($elements['newItem']);
        continue;
      }
      $tmpResult[] = $item;
    }
    $items = $tmpResult;
    $tmpResult = [];
    // Second: solve additions
    $i = 0;
    while ($i < count($items)) {
      if ($items[$i] == '+') {
        $lastKey = count($tmpResult) - 1;
        $tmpResult[$lastKey] = $tmpResult[($lastKey)] + $items[($i + 1)];
        $i += 2;
        continue;
      }
      $tmpResult[] = $items[$i];
      $i++;
    }
    $items = $tmpResult;
    $tmpResult = [];
    foreach ($items as $item) {
      if (preg_match("/^(?P<num>\d+){1}/", $item, $element)) {
        $tmpResult[] = $element['num'];
      }
    }
    return array_product($tmpResult);
  }
}

?>
