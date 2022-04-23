<?php
if(file_exists($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/BackendRoutes.php')){
	return require($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/BackendRoutes.php');
} else {
	return [];
}