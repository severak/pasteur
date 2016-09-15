<?php
require 'lib/flight/Flight.php';
require "lib/flight/autoload.php";

flight\core\Loader::addDirectory("lib/flourish");

fCore::enableDebugging(TRUE);

use Nibble\NibbleForms\NibbleForm as form;


Flight::route('/', function(){
  $form = form::getInstance('/');
  fCore::expose($form);
  echo 'hello world!';
});

Flight::start();