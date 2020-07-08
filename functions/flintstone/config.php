<?php
require_once 'load.php';
//require_once 'bot-functions.php';

function connect_to_db($dbName){
    // new database
    $options = array(
        'ext' => '.bt',
        'formatter' => new \Flintstone\Formatter\JsonFormatter(),
    );
    global $$dbName;
    $$dbName = new \Flintstone\Flintstone($dbName, $options);

}