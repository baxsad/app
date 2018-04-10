<?php

return array(
	//app
	'displayErrorDetails'    => true,
	'addContentLengthHeader' => false,

	//db
	'db' => array(
		'host'   => '',
		'user'   => '',
		'pass'   => '',
		'dbname' => '',
	),

	//logger
	'logger' => array(
		'name' => 'girleo',
        'level' => Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/../logs/app.log',
	)
);