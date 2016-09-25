<?php
namespace severak\forms;

class form
{
	public $action = '';
	public $method = 'POST';
	public $values = [];
	public $fields = [];
	protected $_rules = [];

	public function __construct($action, $method='POST')
	{
		$this->action = $action;
		$this->method = strtoupper($method);
	}

	public function field($name, $type='text', $attr=[])
	{
		if (isset($this->fields[$name])) {
			throw new programmerException('Field '.$name.' already defined.');
		}

		$attr['name'] = $name;
		$attr['type'] = $type;

		// todo generovat id

		$this->fields[$name] = $attr;

		// todo implicitní rule's
	}

	public function rule($name, $callback, $message)
	{
		// todo
	}

	public function fill($data)
	{
		// todo
	}

	public function validate()
	{
		// todo
	}

	public function show()
	{
		// todo
		return 'tada';
	}

	public function showOpen()
	{
		// todo
	}

	public function showField($field)
	{
		// todo
	}

	public function showLabel($field)
	{
		// todo
	}	

	public function showError($field)
	{
		// todo
	}

	public function showItem($label, $field, $error)
	{
		// todo
	}

	public function showClose()
	{
		// todo
	}

	public function setSkin($skin)
	{
		// todo
	}
}