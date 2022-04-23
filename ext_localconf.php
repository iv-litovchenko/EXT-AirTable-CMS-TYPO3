<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext'; // typo3conf/ext path
foreach (glob($typo3conf_path."/*") as $filename) {
	$extensionKey = basename($filename);
	if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extensionKey)){
		$vendorAutoload = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'.$extensionKey.'/Vendor/autoload.php';
		if(file_exists($vendorAutoload)){
			require $vendorAutoload;
		}
	}
}

// Генерация SQL структуры таблиц (в TYPO3 v11 это убрали)
// Перевел на ext_tables.sql (но как быть при первой устновке в TYPO3 11 пока не понятно)
if (version_compare(TYPO3_version, '11.0.0', '<')) {
	#$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
	#$signalSlotDispatcher->connect(
	#	'TYPO3\\CMS\\Install\\Service\\SqlExpectedSchemaService',
	#	'tablesDefinitionIsBeingBuilt',
	#	\Litovchenko\AirTable\Hooks\DatabaseSchemaService::class,
	#	'addMysqlTableAndFields'
	#);
	#unset($signalSlotDispatcher);
}

// Sql-подключение Laravel
if(class_exists('Litovchenko\AirTable\Utility\EloquentUtility')){
	\Litovchenko\AirTable\Utility\EloquentUtility::bootEloquent();
}

// Register a node in ext_localconf.php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'fieldErrorDisplay',
	'priority' => 40,
    'class' => \Litovchenko\AirTable\Domain\Model\Fields\FieldErrorDisplay::class,
];

// Register a node in ext_localconf.php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'textTinyMCE',
	'priority' => 50,
    'class' => \Litovchenko\AirTable\Domain\Model\Fields\TextTinyMCE::class,
];

// Регистрация
\Litovchenko\AirTable\Utility\AnnotationRegistrationExtLocalconf::main();

?>