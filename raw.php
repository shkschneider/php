<?php

function raw_key()
{
  return trim(`read -s -n1 value; echo \$value`);
}

echo "$> ";
$key = raw_key();
echo "$key\n";

?>
