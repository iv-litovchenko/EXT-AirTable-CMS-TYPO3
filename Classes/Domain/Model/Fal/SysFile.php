<?php
namespace Litovchenko\AirTable\Domain\Model\Fal;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class SysFile extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'sys_file',
		'name' 					=> 'Файл',
		'description' 			=> 'Регистрация модели в системе',
		'defaultListTypeRender' => 3,
		'baseFields' => [
			'propref_categories',
		],
		'dataFields' => [
			'fileinfo' => [
				'type' => 'SpecialFalFileInfo',
				'name' => 'Префью',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'name' => [
				'type' => 'Input',
				'show' => 1,
				'liveSearch' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'identifier' => [
				'type' => 'Input',
				'show' => 1,
				'liveSearch' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'sha1' => [
				'type' => 'Input',
				'show' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'size' => [
				'type' => 'Input.Number',
				'show' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'mime_type' => [
				'type' => 'Input',
				'show' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'type' => [
				'type' => 'Switcher.Int',
				'show' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'missing' => [
				'type' => 'Flag',
				'show' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
		],
		'relationalFields' => [
			'storage' => [
				'type' => 'Rel_MTo1',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Fal\SysFileStorage',
				'foreignKey' => 'files',
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'metadata' => [
				'type' => 'Rel_1To1',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Fal\SysFileMetadata',
				'foreignKey' => 'file',
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
				'show' => 1,
			]
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		// Общие настройки
		// $configuration['ctrl']['hideTable'] = 0;
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file', 'metadata', '', 'after:storage');
	}
	
	// Служебная функция...
	protected static function _getFileObject($idOrPath)
	{
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		if(strstr($idOrPath,'.')){
			$file = $resourceFactory->getFileObjectFromCombinedIdentifier($idOrPath); // '1:/foo.txt'
		} else {
			$file = $resourceFactory->getFileObject(intval($idOrPath)); // 1774
		}
		return $file;
	}
	
	// Служебная функция...
	protected static function _getFolderObject($pathFolder)
	{
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		$folder = $resourceFactory->getFolderObjectFromCombinedIdentifier($pathFolder);
		return $folder;
	}
	
	// Добавить файл...
    public static function cmdAddToIndex($path)
    {
		$file = self::_getFileObject($path);
		return $file->getProperty('uid');
	}
	
	// Загрузить файл из $_FILES
    public static function cmdUpload($uploadedFileData, $pathFolder = null, $conflictMode = \TYPO3\CMS\Core\Resource\DuplicationBehavior::RENAME)
    {
		$folder = self::_getFolderObject($pathFolder);
		$file = $folder->addUploadedFile($uploadedFileData, $conflictMode);
		return $file->getProperty('uid');
    }
	
	// Проверить наличие файла
	public static function cmdExists($idOrPath)
	{
		try {
			$file = self::_getFileObject($idOrPath);
			return $file->exists();
		} catch (\InvalidArgumentException $e) {
			return false;
		} catch (\TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException $e) {
			return false;
		}
	}
	
	// Переименовать файл...
    // const RENAME = 'rename';
    // const REPLACE = 'replace';
    // const CANCEL = 'cancel';
    public static function cmdRename($idOrPath, $newName, $conflictMode = \TYPO3\CMS\Core\Resource\DuplicationBehavior::RENAME)
    {
		$file = self::_getFileObject($idOrPath);
		return $file->rename($newName, $conflictMode)->getProperty('uid');
	}
	
	// Скопировать файл...
    // const RENAME = 'rename';
    // const REPLACE = 'replace';
    // const CANCEL = 'cancel';
    public static function cmdCopy($idOrPath, $path = null, $conflictMode = \TYPO3\CMS\Core\Resource\DuplicationBehavior::RENAME)
    {
		$file = self::_getFileObject($idOrPath);
		$folder = self::_getFolderObject(dirname($path.'/'));
		$newName = basename($path);
		return $file->copyTo($folder, $newName, $conflictMode)->getProperty('uid');
	}
	
	// Переместить файл файл...
    // const RENAME = 'rename';
    // const REPLACE = 'replace';
    // const CANCEL = 'cancel';
    public static function cmdMove($idOrPath, $pathFolder = null, $conflictMode = \TYPO3\CMS\Core\Resource\DuplicationBehavior::RENAME)
    {
		$file = self::_getFileObject($idOrPath);
		$folder = self::_getFolderObject($pathFolder);
		return $file->moveTo($folder, $newName, $conflictMode)->getProperty('uid');
	}
	
	// Удалить файл...
    public static function cmdDelete($idOrPath)
    {
		$file = self::_getFileObject($idOrPath);
		return $file->delete();
	}
	
	// Скачать файл... // https://gist.github.com/derhansen/c56ff4df72d6b83121bea99bd83271cd
    public static function cmdDownload($idOrPath, $asDownload = true)
    {
		$file = self::_getFileObject($idOrPath);
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		$storage = $resourceFactory->getDefaultStorage();
		$response = $storage->streamFile($file, true, 'test-filename.jpg');
		
		$response = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Mvc\Web\Response::class);
		$response->setHeader('Content-Type', 'application/json; charset=utf-8');
		$response->setContent(json_encode(['few'=>1]));
		$response->sendHeaders();
		$response->send();
		#$response->sendResponse($response);
		exit();
	}
	
	// Получить путь (По ID)...
    public static function getPathById($id)
    {
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		$file = $resourceFactory->getFileObject($id); // '1:/foo.txt'
		return \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL').$file->getPublicUrl();
	}
	
	// Получить путь (По path)...
    public static function getIdByPath($path)
    {
		$resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
		$file = $resourceFactory->getFileObjectFromCombinedIdentifier($path); // '1:/foo.txt'
		return $file->getProperty('uid');
	}
	
	// Миниатуры (Отказался пока, не поддерживает SVG)...
    public static function thumbnailUrl($id, $size = '320c:280c', $t = 'f')
    {
		$queryParameterArray = ['eID' => 'dumpFile', 't' => $t];
		$queryParameterArray[$t] = $id;
		$queryParameterArray['s'] = $size; // '320c:280c:320:280:320:280';
		// $queryParameterArray['cv'] = 'default'; // : In case of sys_file_reference, you can assign a cropping variant
		$queryParameterArray['token'] = \TYPO3\CMS\Core\Utility\GeneralUtility::hmac(implode('|', $queryParameterArray), 'resourceStorageDumpFile');
		// $publicUrl = GeneralUtility::locationHeaderUrl(PathUtility::getAbsoluteWebPath(Environment::getPublicPath() . '/index.php'));
		$publicUrl = '/?' . http_build_query($queryParameterArray, '', '&', PHP_QUERY_RFC3986);
		return $publicUrl;
	}
}
?>