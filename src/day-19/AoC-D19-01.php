<?php

require("messagesRulesManager.php");

// Main program.
$options = getopt("f:", ['filePath:']);
$filePath = ($options['f']) ?: $options['filePath'];
$input = explode("\n\n", file_get_contents($filePath));
$rules = explode("\n", $input[0]);
$messages = explode("\n", $input[1]);
$messagesRulesManager = new MessagesRulesManager($rules);
$ruleNumber = 0;
$totalMatches = 0;
foreach ($messages as $message) {
  if ($messagesRulesManager->checkMatchRule($message, $ruleNumber)) {
    $totalMatches++;
  }
}
echo "\nThe number of messages that match the rule $ruleNumber is $totalMatches.\n"; // 104
?>