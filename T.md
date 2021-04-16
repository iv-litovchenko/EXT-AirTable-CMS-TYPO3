```
В доку
Свойства это внешние, атрибуты внутренние
->whereRowValues(['column1', 'column2'], '=', ['foo', 'bar']); // orWhereRowValues()
->where created_at between (new DateTime("2021-01-13"))->getTimestamp() and (new DateTime("2021-01-14"))->getTimestamp()
->forPage() $page, $perPage;
{f:if(condition: file.properties.title, then: file.properties.title, else: file.properties.name)}
{f:if(condition: item.current, then: ' active')}
Понравилась идея делать для upload files - прямоугольник
Сделать загрузку items@ для select-ов из файла /typo3conf/ext/ext/Configuration/Items/Item.txt [Список значений]
Разобраться как работает: <flux:field.controllerActions name="switchableControllerActions"> - очень актуально что бы не плодить большое кол-во плагинов одного типа группы (как новости). Способ ограничения полей смотреть в расширении News (файл "public_html\typo3conf\ext\news\Classes\Backend\FormDataProvider\NewsFlexFormManipulation.php") getSwitchableControllerActions ($extensionName, $pluginName) http://man.hubwiz.com/docset/TYPO3.docset/Contents/Resources/Documents/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_configuration_1_1_frontend_configuration_manager.html

 laravel enum table
 Шоркоды - доработать алгоритм замены (1) перед заменой делать предварительно проверку есть ли плейсхолдеры [f: 2) если нет USER_INT-объектов - записывать в кэш страницы).]

cd demo.iv-litovchenko.ru/public_html/typo3conf/ext/
ln -s /home/i/ilitovfa/iv-litovchenko.ru/public_html/typo3conf/ext/flux  /home/i/ilitovfa/demo.iv-litovchenko.ru/public_html/typo3conf/ext/

---------
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.2.CreatingTemplateFiles/3.2.1.CommonFileStructure.md
https://github.com/FluidTYPO3/documentation/blob/rewrite/3.Templating/3.1.ProviderExtension/3.1.5.ConfigurationFiles.md
---------------

1) addData
2) Список что показать?



				/*
					/ ** @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools $flexFormTools * /
					// $flexFormTools = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Configuration\\FlexForm\\FlexFormTools');
					// $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData, TRUE);
				*/
			   



