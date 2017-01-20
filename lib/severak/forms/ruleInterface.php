<?php
namespace severak\forms;

interface ruleInterface
{
	public function check($current, $all);
}
