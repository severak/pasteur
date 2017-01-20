<?php
require 'lib/flight/Flight.php';
require "lib/flight/autoload.php";

flight\core\Loader::addDirectory("lib/flourish");

require "lib/tracy/src/tracy.php";
use \Tracy\Debugger;
Debugger::enable();

use Nibble\NibbleForms\NibbleForm as form;

Flight::register('db', 'sparrow', [], function($db) {
	$db->setDb('pdosqlite://localhost/'.__DIR__.'/pasteur.sqlite');
	$db->show_sql = true;
});

Flight::route('/', function(){
	$db = Flight::db();
	$request = Flight::request();

	$form = form::getInstance('/');
	$form->addField('text', 'textarea', ['rows'=>30, 'cols'=>80, 'required'=>true, 'label'=>'Your paste']);
	$form->addField('name', 'text', ['label'=>'Optional title', 'required'=>false]);
	
	if ($request->method=='POST' && $form->validate()) {
		$F = $_POST['nibble_form'];
		$db->from('pasteur_paste')->insert(['text'=>$F['text'], 'name'=>$F['name']])->execute();
		Flight::redirect('/paste/' . $db->insert_id);
	}

	echo $form->render();
});

Flight::route('/paste/@id', function($id){
	$db = Flight::db();
	$paste = $db->from('pasteur_paste')->where(['id'=>$id])->one();

	echo '<pre>' . $paste['text'] . '</pre>';
});


Flight::route('/register', function(){
	$request = Flight::request();

	$form = new severak\forms\form('/register', 'POST');
	$form->field('email', 'email', ['label'=>'E-mail']);
	$form->field('password', 'password', ['label'=>'Password']);
	$form->field('password_again', 'password', ['label'=>'again']);
	$form->field('real_name', 'text', ['label'=> 'Real name', 'placeholder'=>'Joe Doe', 'autocomplete'=>'name']);
	$form->field('register', 'submit');

	$form->rule('password_again', function($_, $all) {
		return $all['password']==$all['password_again'];
	}, 'must be repetition of password');
	
	$form->rule('email', new \severak\forms\rules\required(), "qq");

	// dump($form);

	if ($request->method=='POST' && $form->fill($request->data)->validate()) {
		// register that bastard
	}

	return Flight::render('register.php', ['form'=>$form]);
});

Flight::start();