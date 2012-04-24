<?php

/*
 * posix_arguments
 *
 * Original idea  by  B Crawford & <eric.brison[at]anakeen.com>
 * Made in  2011  by  Alan "Shtark" SCHNEIDER
 *		      <shk.schneider[at]gmail.com>
 *
 * Supports	-OPTION[...]
 *		-[...]OPTION VALUE
 *		--
 * Try		-a -bc -host localhost -port 21 -v -- test
 *
 * Released under GNU GPLv2+.
 */

function posix_arguments($argv) {
  $arguments = array();
  $last_argument = null;
  // little trick to keep track of regular arguments
  $arguments['--'] = 1;
  for ($index = 1; isset($argv[$index]); $index++)
    {
      // breaks on --
      if ($argv[$index] == "--")
	{
	  $arguments['--'] = $index + 1;
	  return $arguments;
	}
      // handles -OPTION[...] or -[...]OPTION VALUE
      else if (preg_match_all('/^\-([a-zA-Z0-9]+)/', $argv[$index], $matches))
	foreach (str_split($matches[1][0], 1) as $opt)
	  {
	    $arguments[$opt] = true;
	    $last_argument = $opt;
	  }
      // handles pending values in -[...]OPTION VALUE
      else if ($last_argument != null)
	{
	  $arguments[$last_argument] = $argv[$index];
	  $last_argument = null;
	}
      // break for regular arguments
      else
	break ;
    }
  $arguments['--'] = $index;
  return $arguments;
}

$arguments = posix_arguments($argv);
print_r($arguments);
for ($index = $arguments['--']; isset($argv[$index]); $index++)
  print $argv[$index] . "\n";

?>
