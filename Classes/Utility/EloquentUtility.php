<?php
namespace Litovchenko\AirTable\Utility;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidatorFactory;
use Litovchenko\AirTable\Utility\BaseUtility;

class EloquentUtility {

	public static function bootEloquent($connection = NULL) {
		if(class_exists('Illuminate\Database\Capsule\Manager')){
			
			// eloquent
			$capsule = new \Illuminate\Database\Capsule\Manager;
			$capsule->addConnection(EloquentUtility::getDefaultConnection());
			$capsule->setEventDispatcher(
			  new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container)
			);
			$capsule->setAsGlobal();
			$capsule->bootEloquent();
			
			$app = new \Illuminate\Container\Container();
			\Illuminate\Support\Facades\Facade::setFacadeApplication($app);
			
			$app->singleton('db', function ($app) use ($capsule) {
				return $capsule;
			});

			# $app->singleton('macroable-models', function () use ($app) {
			# 	return new \Javoscript\MacroableModels\MacroableModels();
			# });
			
			$app->singleton('hash', function () use ($app) {
				return new \Illuminate\Hashing\HashManager($app);
			});
			
			#$app->singleton('validator', function () use ($app) {
			#	$validator = new Factory($app['translator'], $app);
			#	return new \Illuminate\Hashing\HashManager($app);
			#});

			#class_alias('Cache', 'Illuminate\Cache\Repository\Cache');
			#class_alias(\Illuminate\Support\Facades\DB::class, 'DB');
			#class_alias(\Illuminate\Support\Facades\Hash::class, 'Hash');
			
			#\Illuminate\Support\Facades\DB::connection()->getPdo();
			#\Illuminate\Support\Facades\Hash::make('password');
		}
	}

	public static function getDefaultConnection() {
		// Для более новых версий TYPO3
		if (version_compare(TYPO3_version, '8.0.0', '>=')) {
			$connections = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'];
			return [
				'driver' => 'mysql',
				'host' => $connections['host'],
				'database' => $connections['dbname'],
				'username' => $connections['user'],
				'password' => $connections['password'],
				'charset' => $connections['charset'],
				'prefix' => '',
			];
		} else {
			$connections = $GLOBALS['TYPO3_CONF_VARS']['DB'];
			return [
				'driver' => 'mysql',
				'host' => $connections['host'],
				'database' => $connections['database'],
				'username' => $connections['username'],
				'password' => $connections['password'],
				'charset' => $connections['charset'],
				'prefix' => '',
			];
		}
	}
}
