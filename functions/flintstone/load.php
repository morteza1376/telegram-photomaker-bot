<?php
$files = array(
    'Config.php',
    'Database.php',
    'Exception.php',
    'Flintstone.php',
    'Line.php',
    'Validation.php',
    'Cache/CacheInterface.php',
    'Cache/ArrayCache.php',
    'Formatter/FormatterInterface.php',
    'Formatter/JsonFormatter.php',
    'Formatter/SerializeFormatter.php',

);
foreach($files as $file) {
    require_once 'flintstone/'.$file;
}