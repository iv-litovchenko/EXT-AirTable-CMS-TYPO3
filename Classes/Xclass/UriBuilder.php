<?php
namespace Litovchenko\AirTable\Xclass;

class UriBuilder extends \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Xclass',
		'name' => '',
		'description' => '',
		'object' => 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
	];

    /**
     * @var bool
     */
    protected $isModule = false;

    /**
     * Creates an URI used for linking to an Extbase action.
     * Works in Frontend and Backend mode of TYPO3.
     *
     * @param string|null $actionName Name of the action to be called
     * @param array|null $controllerArguments Additional query parameters. Will be "namespaced" and merged with $this->arguments.
     * @param string|null $controllerName Name of the target controller. If not set, current ControllerName is used.
     * @param string|null $extensionName Name of the target extension, without underscores. If not set, current ExtensionName is used.
     * @param string|null $pluginName Name of the target plugin. If not set, current PluginName is used.
     * @return string the rendered URI
     * @see build()
     */
    public function uriFor(
        ?string $actionName = null,
        ?array $controllerArguments = null,
        ?string $controllerName = null,
        ?string $extensionName = null,
        ?string $pluginName = null
    ): string {
		
		// <f:link.action route="routeExtProjiv.Pages.PageDefaultController.travelViewAction" arguments="{num:5}">--TEXT--</f:link.action>
        // <f:link.action action="travelViewAction" arguments="{num:5}">--TEXT--</f:link.action>
		// <f:link.action 
		// 		action="travelView" 
		// 		extensionName="Projiv" 
		// 		pluginName="PagesPageDefaultController"
		// 		controller="Pages\PageDefault"
		// 		arguments="{num:5}"
		// >
		//	-- TEXT --
		// </f:link.action>
		
		$actionName = preg_replace('/Action$/is','',$actionName);
		if(preg_match('/Ext\.([^\.]*)\.([^\.]*).([^\.]*).([^\.]*)/is',$actionName, $matches))
		{
			if($matches[2] == 'Modules'){
				$this->isModule = true;
				$extensionName = $matches[1];
				foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['modules'] as $k => $v){
					if(strstr($k,'_'.$extensionName.'Modules'.$matches[3])){
						$this->arguments['route'] = $k;
						$pluginName = $k; // .'Controller'
						$controllerName = $matches[2].'\\'.$matches[3];
						break;
					}
				}
			} else {
				$extensionName = $matches[1];
				$pluginName = $matches[2].'_'.$matches[3]; // .'Controller'
				if($matches[2] == 'Pages') {
					$controllerName = $matches[2].'\\'.$matches[3];
				} elseif($matches[2] == 'Plugins') {
					$controllerName = 'PagesElements\\'.$matches[2].'\\'.$matches[3];
				}
			}
			$actionName = preg_replace('/Action$/is','',$matches[4]);
		}
		
        return parent::uriFor($actionName, $controllerArguments, $controllerName, $extensionName, $pluginName);
    }
	
    /**
     * Builds the URI
     * Depending on the current context this calls buildBackendUri() or buildFrontendUri()
     *
     * @return string The URI
     * @see buildBackendUri()
     * @see buildFrontendUri()
     */
    public function build(): string
    {
        if($this->isModule == true){
            return $this->buildBackendUri();
        }
        return parent::build();
    }
}
