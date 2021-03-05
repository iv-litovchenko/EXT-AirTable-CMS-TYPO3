```
https://extensions.typo3.org/extension/div2007
##2. smarty.configLoad для Smarty (нужен ли он?);
https://kronova.net/tutorials/typo3/extbase-fluid/get-all-constants-with-extbase-extension.html
Constants: 	plugin.myext.settings.detailPid = 123
Setup: 		plugin.myext.settings.detailPid = {$plugin.myext.settings.detailPid}
			$this->settings['detailPid']
			{settings.detailPid}
			
	
vhs:		{v:variable.typoscript(path: 'settings.pageUid')}
<v:variable.set name="userLoginPID" value="{v:variable.typoscript(path: 'site.pid.userLogin')}"/>
<v:variable.set name="userRegisterPID" value="{v:variable.typoscript(path: 'site.pid.userRegister')}"/>

plugin.tx_yourplugin {
  ...
  settings{
    somePage = {$PID_SOME_PAGE}
  }
  ...
}
--------------------

	
--------------------------------------------------------------------------------------------------------------------
Отправка писем, предварительно нужно настроить адрес с которого 
будет производится отправка писем в настройках расширения "yii2"
--------------------------------------------------------------------------------------------------------------------

	Настройки расширения "yii2":
	basic.swiftmailer_from (string) 
	T3Club|noreply@t3club.com

	\app\components\special\Typo3SwiftMailer::sendText(
		'i-litovan@yandex.ru',
		'На сайте по разработке сайтов поступила заявка',
		'EXT:sitet3club/mail/Template1.php',
		[
			'username'=&gt;$this-&gt;username,
			'telephone'=&gt;$this-&gt;telephone,
			'bodytext'=&gt;$this-&gt;bodytext
		]
	);

--------------------------------------------------------------------------------------------------------------------
Получение настроек и данных о странице (элементе содержимого)
--------------------------------------------------------------------------------------------------------------------

	\Typo3Helpers::GetTsConstant('plugin.sitet3club.PID'); // TS-константы (строка)
	\Typo3Helpers::GetPageData('uid'); // массив, или данные записи страницы (строка)
	\Typo3Helpers::GetData('title'); // массив или данные текущей записи (строка)
	\Typo3Helpers::GetConf(); // переданные настройки для текущего контроллера и действия (массив)
	\Typo3Helpers::GetExtParams('sitet3club'); // PHP-настройки расширения (массив)


	
--------------------------------------------------------------------------------------------------------------------
Описание функции парсинга атрибутов "postUserFunc=" для "lib.parseFunc_RTE"
--------------------------------------------------------------------------------------------------------------------
https://gist.github.com/ogrosko/5126ebe7249066b26007b46970327475
https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Functions/HtmlparserTags.html


	wrap | Создать обертку для тэга (только для блочных элементов)
	allowedAttribs	| Разрешенные атрибуты для тэга
	disallowAttribs	| Запрещенные атрибуты для тэга
	fixAttrib | Точечная настрока значений атрибутов тэга
		set (=value) - принудительная установка значения атрибута
		default (=value) - значение по умолчанию, если пусто
		fixed (=value) - фиксированное значение (добавляется всегда в начало)
		list (=value1, value2 , value3) - разрешенные значений
		unset (=1) - удалить атрибут





https://t3terminal.com/blog/typo3-community/
https://t3terminal.com/blog/typo3-blogger/
https://t3terminal.com/blog/free-typo3-support/
https://t3terminal.com/blog/earn-typo3/

https://t3terminal.com/blog/typo3-site-configuration/
https://t3terminal.com/blog/typo3-routing/
!!! https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/

Просмотреть все функции Extbase и расширений core в папке typo3/sysext/
https://kronova.net/tutorials/typo3/extbase-fluid/use-middlewares-with-multilanguage.html

config.tx_extbase {
	mvc {
		requestHandlers {
			TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler = TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler
			TYPO3\CMS\Extbase\Mvc\Web\BackendRequestHandler = TYPO3\CMS\Extbase\Mvc\Web\BackendRequestHandler
			TYPO3\CMS\Extbase\Mvc\Cli\RequestHandler = TYPO3\CMS\Extbase\Mvc\Cli\RequestHandler
		}
		throwPageNotFoundExceptionIfActionCantBeResolved = 0
	}


