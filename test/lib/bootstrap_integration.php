<?php

if(!is_file(dirname(__FILE__).'/integration_config.php'))
{
	throw new Exception("The file integration_config.php must be created");
}

require_once(dirname(__FILE__)."/integration_config.php");
require_once(dirname(__FILE__)."/bootstrap.php");