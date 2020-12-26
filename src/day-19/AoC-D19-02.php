<?php

require("messagesRulesManager.php");

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode("\n\n", file_get_contents($filePath));
$rules = explode("\n", $input[0]);
$messages = explode("\n", $input[1]);
$messagesRulesManager = new MessagesRulesManager($rules);
print_r($messagesRulesManager->getRules()[0]);
$ruleNumber = 0;
$totalMatches = 0;
foreach ($messages as $message) {
  print_r("\n$message: ");
  if ($messagesRulesManager->checkMatchRule($message, $ruleNumber)) {
    print_r("Está\n");
    $totalMatches++;
    continue;
  }
  print_r("NO está\n");

}
echo "\nThe number of messages that match the rule $ruleNumber is $totalMatches.\n";
?>