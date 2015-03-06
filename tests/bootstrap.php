<?php
// to allow for development within context of pressor/starter, we'll also check if this is a submodule
$vendors = array(__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../../vendor/autoload.php');
foreach ($vendors as $vendor)
{
	if ($autoload = realpath($vendor))
	{
		break;
	}
}

if (!$autoload)
{
	throw new RuntimeException('Cannot locate Composer vendor autoload file');
}

require_once($autoload);
