<?php
namespace severak\forms\skins;

class plain
{
	protected $_form;

	public function __construct($form)
	{
		$this->_form = $form;
	}

	protected function _tag($name, $attr=[], $autoClose=true)
	{
		$out = '<' . $name . ' ';
		foreach ($attr as $key => $value) {
			if (is_null($key)) continue;
			$out .= $key . '="';
			if ($value===true) {
				$out .= 'true';
			} else {
				$out .= htmlspecialchars($value, ENT_HTML5);	
			}
			$out .= '" ';
		}
		if ($autoClose) {
			$out .= '/';
		}
		$out .= '>';
		return $out;
	}

	public function showOpen()
	{
		return $this->_tag(
			'form', 
			[
				'action'=>$this->_form->action,
				'method'=>$this->_form->method
			],
			false
		);
	}

	public function showField($name)
	{
		$def = $this->_form->fields[$name];
		$attr = $def;
		unset($attr['label']);

		if ($def['type']=='select') {

		} elseif ($def['type']=='textarea') {

		} else {
			return $this->_tag('input', $attr);
		}
	}

	public function showLabel($name)
	{
		$def = $this->_form->fields[$name];
		return $this->_tag('label', ['for'=> $def['id']], false) . htmlspecialchars($def['label']) . '</label>';
	}	

	public function showError($name)
	{
		if (!empty($this->_form->errors[$name])) {
			return '<b>' . htmlspecialchars($this->_form->errors[$name]) . '</b></br>';
		}
		return '';
	}

	public function showItem($name)
	{
		$def = $this->_form->fields[$name];
		if ($def['type']=='hidden' || $def['type']=='submit') {
			return $this->showField($name);
		}
		return $this->showLabel($name) . $this->showField($name) . $this->showError($name);
	}

	public function showClose()
	{
		return '</form>';
	}

	public function show()
	{
		$out = $this->showOpen();
		foreach ($this->_form->fields as $name => $def) {
			$out .= $this->showItem($name);	
		}
		$out .= $this->showClose();
		return $out;
	}
}