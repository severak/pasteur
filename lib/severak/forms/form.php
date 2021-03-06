<?php
namespace severak\forms;

class form
{
	public $action = '';
	public $method = 'POST';
	public $values = [];
	public $fields = [];
	public $errors = [];
	
	public $defaultMessages = [
		'required' => 'Field is required.'
	];

	protected $_rules = [];
	protected $_id = 'form';
	protected $_skin;

	public function __construct($action, $method='POST')
	{
		$this->action = $action;
		$this->method = strtoupper($method);

		$this->_skin = new \severak\forms\skins\plain($this);
	}

	public function field($name, $type='text', $attr=[])
	{
		if (isset($this->fields[$name])) {
			throw new programmerException('Field '.$name.' already defined.');
		}

		$attr['name'] = $name;
		$attr['type'] = $type;
		if (!isset($attr['label'])) $attr['label'] = ucfirst($name); 
		
		if ($type=='submit') $attr['value'] = $attr['label'];
		
		// automatic ID
		if (empty($attr['id'])) $attr['id'] = $this->_id . '_' . $name;

		$this->fields[$name] = $attr;

		// implicit rule's
		if (!empty($attr['required'])) $this->rule($name, new \severak\forms\rules\required(), $this->defaultMessages['required']);
	}

	public function rule($name, $callback, $message)
	{
		$this->_rules[$name][] = ['check'=>$callback, 'message'=>$message];
	}

	public function fill($data)
	{
		foreach ($this->fields as $name=>$def) {
			$val = isset($data[$name]) ? $data[$name] : null;
			$this->values[$name] = $val;
			if ($def['type']=='submit') continue; // we dont want to overwrite defaults
			$this->fields[$name]['value'] = $val; // todo: opravdu to chceme?
		}
		return $this;
	}

	public function reset()
	{
		$this->errors = [];
		return $this->fill([]);
	}

	public function validate()
	{
		$valid = true;
		foreach ($this->_rules as $name => $rules) {
			foreach ($rules as $rule) {
				$passed = true;
				if (is_object($rule['check']) && is_a($rule['check'], '\severak\forms\ruleInterface')) {
					$passed = $rule['check']->check($this->values[$name], $this->values);
				} else {
					$passed = call_user_func_array($rule['check'], [$this->values[$name], $this->values]);	
				}
				if (empty($passed)) {
					$this->errors[$name] = $rule['message'];
					$valid = false;
					break;
				}
			}
		}
		return $valid;
	}

	public function show()
	{
		return $this->_skin->show();
	}

	// shortcuts to skin

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