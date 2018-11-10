<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$config = require_once(CONFIG_PATH . 'database.php');

$capsule->addConnection($config);
