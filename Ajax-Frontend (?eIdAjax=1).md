###_GET
1

###_POST
1


4) if (посмотреть проверки VHS)
5) Формы _AJAX _POST
6) Обновить Laravel ORM до полседней
7) // Здесь неправильные названия переменных! additionalParams="{eIdAjax:1,ext:'projiv',controller:'RandPhotoController',action:'index'}"

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
