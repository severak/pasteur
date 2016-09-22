<?php
require 'lib/flight/Flight.php';
require "lib/flight/autoload.php";

flight\core\Loader::addDirectory("lib/flourish");

fCore::enableDebugging(TRUE);

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

Flight::start();