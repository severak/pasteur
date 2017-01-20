<?php
namespace severak\forms\rules;

use severak\forms\ruleInterface;

class required implements ruleInterface
{
	function check($current, $all)
	{
		return !is_null($current) && strlen($current)>0;
	}
}