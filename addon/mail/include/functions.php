<?php
/**
 * Debug output
 */
function _d() {
  if (!$GLOBALS['debug']) return;
  $args = func_get_args();
  echo implode($args), "\n";
}

/**
 * Callback Action function name
 * the function that handles the result of the send email action. Parameters:
 *
 * @param bool    $result   result of the send action
 * @param string  $to       email address of the recipient
 * @param string  $cc       cc email addresses
 * @param string  $bcc      bcc email addresses
 * @param string  $subject  the subject
 * @param string  $body     the email body
 */
function mail_callback($result, $to, $cc, $bcc, $subject, $body) {
  $result = $result ? 'TRUE' : 'FALSE';
  $to = str_replace("\n", ' ', print_r($to, TRUE));
  $cc = str_replace("\n", ' ', print_r($cc, TRUE));
  $bcc = str_replace("\n", ' ', print_r($bcc, TRUE));
  echo <<< EOT
Result  : $result
To      : $to
CC      : $cc
BCC     : $bcc
Subject : $subject
Body    :
$body
EOT;
}
