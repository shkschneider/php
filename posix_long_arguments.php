<?php

/*
 * posix_long_arguments
 *
 * Original idea  by  B Crawford & <eric.brison[at]anakeen.com>
 * Made in  2011  by  Alan "Shtark" SCHNEIDER
 *		      <shk.schneider[at]gmail.com>
 *
 * Supports	-OPTION[...]
 *		--OPTION
 *		--OPTION=VALUE
 *		--
 * Try		-a -bc --host=localhost --port=21 --verbose test
 *
 * Released under GNU GPLv2+.
 */

function posix_long_arguments($argv) {
  $arguments = array();
  // little trick to keep track of regular arguments
  $arguments['--'] = 1;
  for ($index = 1; isset($argv[$index]); $index++)
    {
      // breaks on --
      if ($argv[$index] == '--')
	{
	  $arguments['--'] = $index + 1;
	  return $arguments;
	}
      // handles --OPTION=VALUE
      else if (preg_match_all("/^--([^=]+)=(.*)$/", $argv[$index], $matches))
	$arguments[$matches[1][0]] = $matches[2][0];
      // handles --OPTION
      else if (preg_match_all("/^--([^=]+)$/", $argv[$index], $matches))
	$arguments[$matches[1][0]] = true;
      // handles -OPTION[...]
      else if (preg_match_all("/^-([a-zA-Z0-9]+)$/", $argv[$index], $matches))
	foreach (str_split($matches[1][0], 1) as $opt)
	  $arguments[$opt] = true;
      // break for regular arguments
      else
	break ;
    }
  $arguments['--'] = $index;
  return $arguments;
}

$arguments = posix_long_arguments($argv);
print_r($arguments);
for ($index = $arguments['--']; isset($argv[$index]); $index++)
  print $argv[$index] . "\n";

?>
