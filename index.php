<?php
require 'lib/flight/Flight.php';
require "lib/flight/autoload.php";

Flight::init();
Flight::set('flight.handle_errors', false);
require "lib/tracy/src/tracy.php";
use \Tracy\Debugger;
Debugger::enable();

flight\core\Loader::addDirectory("lib/flourish");

Flight::register('db', 'sparrow', [], function($db) {
	$db->setDb('pdosqlite://localhost/'.__DIR__.'/pasteur.sqlite');
	$db->show_sql = true;
});

Flight::route('/', function(){
	$db = Flight::db();
	$request = Flight::request();
	
	$languages = [
		'html' => 'HTML',
		'php' => 'PHP',
		'lua' => 'lua',
	];

	$form = new severak\forms\form('/', 'POST');
	$form->field('text', 'textarea', ['rows'=>10, 'cols'=>80, 'label'=>'Your paste', 'required'=>true]);
	$form->field('name', 'text', ['label'=>'Optional title']);
	$form->field('language', 'select', ['options'=>$languages]);
	$form->field('act_paste', 'submit', ['label'=>'PASTE!']);
	
	if ($request->method=='POST' && $form->fill($request->data)->validate()) {
		$F = $request->data;
		$db->from('pasteur_paste')->insert(['text'=>$F['text'], 'name'=>$F['name']])->execute();
		Flight::redirect('/paste/' . $db->insert_id);
	}

	return Flight::render('register.php', ['form'=>$form]);
});

Flight::route('/paste/@id', function($id){
	$db = Flight::db();
	$paste = $db->from('pasteur_paste')->where(['id'=>$id])->one();

	echo '<pre>' . $paste['text'] . '</pre>';
});


Flight::route('/register', function(){
	$request = Flight::request();

	$form = new severak\forms\form('/register', 'POST');
	$form->field('email', 'email', ['label'=>'E-mail', 'required'=>true]);
	$form->field('password', 'password', ['label'=>'Password', 'required'=>true]);
	$form->field('password_again', 'password', ['label'=>'again']);
	$form->field('real_name', 'text', ['label'=> 'Real name', 'placeholder'=>'Joe Doe', 'autocomplete'=>'name']);
	$form->field('register', 'submit');

	$form->rule('password_again', function($_, $all) {
		return $all['password']==$all['password_again'];
	}, 'must be repetition of password');
	
	// dump($form);

	if ($request->method=='POST' && $form->fill($request->data)->validate()) {
		// register that bastard
	}

	return Flight::render('register.php', ['form'=>$form]);
});

Flight::start();