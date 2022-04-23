<?php
namespace Litovchenko\AirTable\Hooks\Frontend;

class ExtFluxControllerViewInitialized implements \FluidTYPO3\Flux\Hooks\HookSubscriberInterface
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Смена путей для шаблонов элементов содержимого типа "элемент и сетка"',
		'description' => '',
		'onlyFrontend' => [
			// 'TYPO3_CONF_VARS|EXTCONF|flux|hooks|controllerViewInitialized'
		]
	];
	
	public function trigger(string $hook, array $data): array
	{
		#print "<pre>";
		##print_r($data['controller']->view);
		#exit();
		#$data->controller->view->setTemplatePathAndFilename('typo3conf/ext/temp/Resources/Private/Templates/Tt.html');
		#exit();
		#$this->view->setTemplatePathAndFilename('typo3conf/ext/temp/Resources/Private/Templates/Tt.html');
		#print 12321;
		#exit();
		$data['controller'] = 1;
		return $data;
	}

}
?>