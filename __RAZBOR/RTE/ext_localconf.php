<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// Ts-настройки (виз. редактор), обработка "lib.parseFunc_RTE", при использовании визуального редактора "TinyMCE
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'pi1/class.tx_yii2_tinymce_parsehtml.php');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'setup','<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/pi1/static/lib.parseFunc_RTE.ts">',43); // setupTSConfig

?>