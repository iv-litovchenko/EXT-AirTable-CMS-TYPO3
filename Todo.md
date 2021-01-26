``` 
Доработать поля ServerSelectFiles (выбрать файл из папки на сервере), ServerSelectFolders (выбрать папку на сервере)

<?php

Проверить и перенести это в документацию - новый шаблон страницы, новый элемент содержимого...
<f:debug>{_all}</f:debug>
<h2>Woohoo! My First TYPO3 Custom Element</h2>
<div class=”container”>
{data.bodytext -> f:format.html()}
</div>
```



## Внутренне виджеты состоят из собственного контроллера и представления.


		#Исходный текст
		#• $this->arguments is an associative array where you will find the values for all arguments you registered previously.
		#• $this->renderChildren() renders everything between the opening and closing tag of the view
		#helper and returns the rendered result (as string).
		#• $this->templateVariableContainer is an instance of TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer,
		#with which you have access to all variables currently available in the template, and can modify the variables
		#currently available in the template.

## Register View Helper

Step 1) EXT:myext/Classes/ViewHelpers/HelloWorldViewHelper.php

```php
<?php
namespace Mynamespace\Myext\ViewHelpers;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @AirTable\Label:<Пример хелпера Hellow world>
 * @AirTable\Description:<Вывод тектовой строки>
 * @AirTable\RegisterArguments\testArg1:<integer || string || mixed, req (Required  True || False)>
 * @AirTable\RegisterArguments\testArg2:<string,req>
 * @AirTable\RegisterArguments\testArg3:<string>
 */
class HelloWorldViewHelper extends Наследуемся от Litovchenko
--- \Litovchenko\AirTable\ViewHelpers\AbstractViewHelper - в ТАЙПО 3 поменялось в каких-то версиях сделать class_alias!
{
    public function render()
    {
        $testArg1 = $this->arguments['testArg1'];
        $testArg2 = $this->arguments['testArg2'];
        return 'Hello world - ' . $testArg1 . ',' . $testArg2;
    }
}
```

Step 2) How to use

```html
<h3>My ViewHelper</h3>

String: <u><vhsExtMyext:HelloWorld TestArg1='String 1' TestArg2='String 2' /></u> <hr />

(if condition): 
<f:if condition="{vhsExtMyext:HellowCondition(TestArg1:'1', TestArg3:'5')}"> 
    <f:then>YES</f:then>
    <f:else>NO</f:else>
</f:if>

```


## Register Widget (view helper with controller and template)

Step 1) EXT:myext/Classes/Controller/Widgets/TestWidgetController.php

```php
<?php
namespace Mynamespace\Myext\Controller\Widgets;

use Litovchenko\AirTable\Controller\Widgets\AbstractWidgetController;

/**
 * @AirTable\Label:<Тестовый виджет>
 * @AirTable\Description:<Виджет в отличие от хелпера имеет собственное представление>
 * @AirTable\NonСachedActions:<indexAction> // USER_INT
 * @AirTable\EidAjaxActions:<indexAction> // Todo http://your-site.com/ajax/ext/[controller]/[action]/
 * @AirTable\RegisterArguments\testArg1:<integer || string || mixed, req (Required  True || False)>
 * @AirTable\RegisterArguments\testArg2:<string,req>
 * @AirTable\RegisterArguments\testArg3:<string>
 */
class TestWidgetController extends AbstractWidgetController 
{
    public function indexAction() 
	{
		$this->view->assign('testArg1', $this->settings['testArg1']);
		$this->view->assign('testArg2', $this->settings['testArg2']);
		$this->view->assign('testArg3', $this->settings['testArg3']);
    }
}
```

Step 2) EXT:myext/Resources/Private/Templates/Widgets/TestWidget/Index.html

```html
TestWidget<br />
{TestArg1}, {TestArg2}, {TestArg3}
```

Step 3) How to use

```html
<h3>Include widget</h3>

My widget: <u><wgsExtAirTableExamples:Test testArg1='305' testArg2='301' /></u> <hr />




```



180, 210, 253 (aspect), 257 auth

plugin - то что расширяет, 
module это составляющее (не отделимое). по моему очевидно
виджет это видимый элемент, на сколько я понимаю, с внутренним функционалом, с точки зрения использования маленькая программка., 
а хелпер это просто вспомогательная функуция strtoupper к примеру




