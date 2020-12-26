<?php

/**
 * Class MessagesRulesManager.
 * @package adventofcode\day18
 */
class MessagesRulesManager {

  /**
   * Bidimensional array to save the rules, that is, admitted texts.
   *
   * @var array;
   */
  private $rules;

  /**
   * Cosntructor of the class
   */
  public function __construct(array $rules) {
    $this->setRules($rules);
  }

  public function checkMatchRule(string $message, int $ruleNumber) {
    $pattern = $this->rules[$ruleNumber];
    return (preg_match("/^($pattern)$/", $message));
  }

  protected function getCombinations(array $ruleNumbers): string {   
    $combinations = "";
    foreach ($ruleNumbers as $number) {
      $combinations .= $this->rules[$number];
    }
    return $combinations;
  }

  protected function getLoopCombinations(array $ruleNumbers, string $ruleIndex): string {
    $tmpComb = "";
    $numbersNotIndex = array_diff($ruleNumbers, [$ruleIndex]);
    $ruleIndexKey = array_search($ruleIndex, $ruleNumbers);
    // If the index that creates the loop is at the begining or at the end: (ab)+
    if (
      array_key_first($ruleNumbers) == $ruleIndexKey
      || array_key_last($ruleNumbers) == $ruleIndexKey
    ) {
      foreach ($numbersNotIndex as $number) {
        $tmpComb .= $this->rules[$number];
      }
      $combinations = "(" . $tmpComb . ")+";
    }
    // If the index that creates the loop is in the middle: a(ab)*b
    else {
      $tmpCombPrev = $this->rules[$ruleNumbers[$ruleIndexKey - 1]];
      $tmpCombPost = $this->rules[$ruleNumbers[$ruleIndexKey + 1]];
      $combinations = "(?P<mid>($tmpCombPrev)(?P>mid)?($tmpCombPost))";
    }
    // print_r($combinations);
    return $combinations;
  }

  public function getRules() {
    return $this->rules;
  }

  protected function initializeRules(array $originalRules): array {
    $this->rules = [];
    $processedRules = [];
    foreach ($originalRules as $originalRule) {
      $ruleInfo = explode(": ", $originalRule);
      $index = $ruleInfo[0];
      $ruleData = $ruleInfo[1];
      if (preg_match("/\"(?P<letter>[ab])\"/", $originalRule, $matches)) {
        $this->rules[$index] = $matches['letter'];
        continue;
      }
      $ruleGroups = explode(" | ", $ruleData);
      foreach ($ruleGroups as $ruleGroupItem) {
        $processedRules[$index][] = explode(" ", $ruleGroupItem);
      }
    }
    return $processedRules;
  }

  protected function setRules(array $rules) {
    $originalRules = $this->initializeRules($rules);
    $tmpRules = [];
    while (count($originalRules) > 0) {
      foreach ($originalRules as $ruleIndex => $originalRule) {
        foreach ($originalRule as $itemKey => $itemRules) {
          $keysNotFound = array_diff($itemRules, array_keys($this->rules));
          if (empty($keysNotFound)) {
            if (isset($tmpRules[$ruleIndex])) {
              $tmpRules[$ruleIndex] .= "|" . $this->getCombinations($itemRules);
            }
            else {
              $tmpRules[$ruleIndex] = $this->getCombinations($itemRules);
            }
            unset($originalRules[$ruleIndex][$itemKey]);
            continue;
          }
          if (count($keysNotFound) == 1 && current($keysNotFound) == $ruleIndex) {
            $tmpRules[$ruleIndex] = $this->getLoopCombinations($itemRules, $ruleIndex);
            unset($originalRules[$ruleIndex][$itemKey]);
          }
        }
        if (empty($originalRules[$ruleIndex])) {
          unset($originalRules[$ruleIndex]);
          $this->rules[$ruleIndex] = "({$tmpRules[$ruleIndex]})";
        }
      }
    }
    // print_r($this->rules);
  }
}

?>
