<?php
require_once 'config/config.php';

$cliFile = sprintf('%s/Cli/%s.php', __DIR__, $argv[1]);
if (!file_exists($cliFile)) {
   throw new Exception(sprintf('"%s" cliFile does not exsist', $cliFile));
}

require_once $cliFile;
