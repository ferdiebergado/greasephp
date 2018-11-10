<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class BaseModel extends Model
{
    public function __construct()
    {
        $capsule = new Capsule;
        $config = require(CONFIG_PATH . 'database.php');
        $capsule->addConnection($config);
        $capsule->bootEloquent();
        parent::__construct();
    }
}
