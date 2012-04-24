<?php

/*
 * unix_arguments
 *
 * Original idea  by  B Crawford & <eric.brison[at]anakeen.com>
 * Made in  2011  by  Alan "Shtark" SCHNEIDER
 *		      <shk.schneider[at]gmail.com>
 *
 * Supports	-OPTION[...]
 *		-[...]OPTION VALUE
 *		--OPTION
 *		--OPTION VALUE
 *		--OPTION=VALUE
 *		--
 * Try		-a -bc --host localhost --port 21 --verbose -- test
 *
 * Released under GNU GPLv2+.
 */

function unix_arguments($argv)
{
  $arguments = array();
  $last_argument = null;
  // little trick to keep track of regular arguments
  $arguments['--'] = 1;
  for ($index = 1, $dim = sizeof($argv); $index < $dim; $index++)
    {
      // 0 - handles -- to break options parsing
      if ($argv[$index] == "--")
	{
	  $arguments['--'] = $index + 1;
	  return $arguments;
	}
      // 1 - handles --OPTION
      else if (preg_match("/^--(.+)/", $argv[$index], $matches))
	{
	  $parts = explode("=", $matches[1]);
	  // 1a - handles --OPTION=VALUE
	  if (isset($parts[1]))
	    {
	      $arguments[$parts[0]] = $parts[1];
	      for ($j = 2; isset($parts[$j]); $j++)
		$arguments[$parts[0]] .= "=" . $parts[$j];
	      $last_argument = null;
	    }
	  // 1b - handles --OPTION (set to true) or --OPTION VALUE (see 3)
	  else
	    {
	      $arguments[$parts[0]] = true;
	      $last_argument = $parts[0];
	    }
	}
      // 2 - handles -[...]OPTION in -[...]OPTION VALUE
      else if (preg_match("/^-([^\s]+)/", $argv[$index], $matches))
	{
	  foreach (str_split($matches[1], 1) as $opt)
	    {
	      $arguments[$opt] = true;
	      $last_argument = $opt;
	    }
	}
      // 3 - handles pending values in 1b and 2
      else if ($last_argument != null)
	{
	  $arguments[$last_argument] = $argv[$index];
	  $last_argument = null;
	}
      // 4 - break for regular arguments
      else
	break ;
    }
  $arguments['--'] = $index;
  return $arguments;
}

$arguments = unix_arguments($argv);
print_r($arguments);
for ($index = $arguments['--']; isset($argv[$index]); $index++)
  print $argv[$index] . "\n";

?>
