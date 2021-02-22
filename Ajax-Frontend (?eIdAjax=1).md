###_GET
1

###_POST
1

+ 1) no_cache=1 параметр
+ 2) Ошибка дейсвтие не разрешено для данноого контроллера
+ 3) $parentObject->pSetup['10.']['action'] = 'fewfew';
4) if (посмотреть проверки VHS)


```
--------------------------------------------------------------------------------------------------------------------
Другие хелперы
--------------------------------------------------------------------------------------------------------------------

	\Typo3Helpers::IsEditMode();
	\Typo3Helpers::IsAjaxMode();
	
	
	/**
		Работа по сценарию Ajax
	**/
	static function IsAjaxMode()
    {
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('eIdAjax');
		if(!empty($eIdAjax)){
			return true;
		}else{
			return false;
		}
	}
```
