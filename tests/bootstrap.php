<?php

return call_user_func(function () {
	/** @var \Composer\Autoload\ClassLoader $composer */
	$composer = require_once __DIR__ . '/../vendor/autoload.php';
	$composer->add('JiriHrajeTests', __DIR__);

	if ( ! class_exists('Tester\Assert')) {
		echo 'Install Nette Tester using `composer install --dev`';
		exit(1);
	}

	\Tester\Environment::setup();
	@mkdir($tempDir = __DIR__ . '/temp', 0777, TRUE);
	\Tester\Helpers::purge($tempDir);

	$configurator = new \Nette\Configurator;

	$configurator->setTempDirectory($tempDir);
	$configurator->setDebugMode(FALSE);

	$configurator->addParameters([
		'appDir' => __DIR__ . '/../app',
	]);

	$robotLoader = $configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/../app')
		->register();
	$composer->addClassMap($robotLoader->getIndexedClasses());

	$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
	$configurator->addConfig(__DIR__ . '/tests.neon');

	return $configurator->createContainer();
});
