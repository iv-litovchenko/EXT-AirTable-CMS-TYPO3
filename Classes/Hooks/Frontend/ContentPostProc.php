<?php
namespace Litovchenko\AirTable\Hooks\Frontend;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/*
 Update for TYPO3 9+

As of TYPO3 9, Hooks will be replaced in the future by PSR-15 Middlewares. So it may be a good idea to use this new way to implement html changes. Middlewares will be registered within the Configuration/RequestMiddlewares.php file. There you tell what class you want to use for your middleware and in what order they should be executed. As we want to change the final html response, we can place our middleware as far as possible to the end. You can find more information about that in the official documentation.

<?php
return [
    'frontend' => [
        'html_compress' => [
            'target' => Eisbehr\Test\Middleware\HtmlCompress::class,
            'disabled' => false,
            'before' => [],
            'after' => [
                'typo3/cms-frontend/output-compression',
            ],
        ],
    ],
];

php
Copy

The middleware class must be an instance of \Psr\Http\Message\ResponseInterface. Inside of the only required function process you can implement what ever you want your middleware to do. In this case, compress the html in a single line, as we already did above with hooks.

<?php
namespace Eisbehr\Test\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\HtmlResponse;
use WyriHaximus\HtmlCompress\Factory;

class HtmlCompress implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $body = $response->getBody()->__toString();

        $parser = Factory::construct();
        $html = $parser->compress($body);

        return new HtmlResponse($html);
    }
}

php
Copy

20.01.2018by eisbehr inProgrammierung#typo3,#programmierung
*/

class ContentPostProc
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Замена меток панелей администрирования и кнопок редактирования во внешнем интерфейсе (output страницы)',
		'description' => '',
		'onlyFrontend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_fe.php|contentPostProc-output::cleanUncachedContent',
			// 'TYPO3_CONF_VARS|SC_OPTIONS|tslib/class.tslib_fe.php|contentPostProc-all::cleanCachedContent',
		]
	];
	
    /**
     * clean cache content from FrontendRenderer, hook is called after Caching
     * for modification of pages with COA_/USER_INT objects
     * @param array $parameters
     * @return void
     */
    public function cleanUncachedContent(&$parameters)
    {
		# if (!$GLOBALS['TSFE']->isINTincScript()) { // If there are no INTincScripts to include
		# 	return; // stop
		# } 
		
        // implement your handling
        #if (!$this->isDefaultTypeNum()) {
        #    return;
        #}
    
        #$tsfe = &$parameters['pObj'];

        #if ($tsfe instanceof TypoScriptFrontendController && !$tsfe->isINTincScript()) {
        #    $tsfe->content = $this->tidyHtml($tsfe->content);
        #}
		
		$tsfe = &$parameters['pObj'];
		$content = $tsfe->content;
		
		if ($GLOBALS["BE_USER"]->user['uid'] > 0) {
			
			// Верхняя панель
			if(strstr($content,"<!--@@@ADMINPANEL@@@-->") || strstr($content,"<!--@@@ADMINPANEL-FOOTER-->")){
				$content = str_replace("<!--@@@ADMINPANEL@@@-->", \Litovchenko\AirTable\ViewHelpers\AdminPanelViewHelper::processOutput(), $content);
				$content = str_replace("<!--@@@ADMINPANEL-FOOTER@@@-->", \Litovchenko\AirTable\ViewHelpers\AdminPanelViewHelper::processOutput(1), $content);
			}
			
			// Нижняя панель
			if(strstr($content,"<!--@@@ADMINPANEL-TOOLS@@@-->")){
				$content = str_replace("<!--@@@ADMINPANEL-TOOLS@@@-->", \Litovchenko\AirTable\ViewHelpers\AdminPanelToolsViewHelper::processOutput(), $content);
			}
			
			// Кнопки редактирования
			if ($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2) {
				preg_match_all('/<!--@@@EDITICON (.*)@@@-->/iUs', $content, $matches);
				for ($i = 0; $i <= count($matches[1]); $i++) {
					$temp = str_replace('--@[--','{',trim($matches[1][$i]));
					$temp = str_replace('--]@--','}',$temp);
					$temp = unserialize($temp);
					$editIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper;
					foreach($temp as $k => $v){
						$editIcon->$k = $v;
					}
					$editIconContent = $editIcon->processOutput();
					$content = str_replace($matches[0][$i], $editIconContent,$content);
				}
			}
			
			// Специальное для "tt_content" (перемещение записей между колонками)
			// Удаляем событие перемещения элементов
			$GLOBALS['BE_USER']->uc['isMoveRecord_tt_content'] = 0;
			$GLOBALS['BE_USER']->uc['isMoveRecord_tt_content_varible'] = [];
			$GLOBALS['BE_USER']->overrideUC(); 
			$GLOBALS['BE_USER']->writeUC();		

		}
		
		// Плайсхолдеры ###time### и другие...
		#if($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] != 1){
		#	$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
		#	$parse = $objectManager->get(\Litovchenko\AirTable\Controller\ParsePlaceholderController::class);
		#	$content = $parse->parseContent($content);
		#}

		// Отправляем заменный контент
		$tsfe->content = $content;
    }

    /**
     * clean cache content from FrontendRenderer, hook is called before Caching
     * for modification of pages on their way in the cache
     * @param array $parameters
     * @return void
     */
    public function cleanCachedContent(&$parameters)
    {
		# if ($GLOBALS['TSFE']->isINTincScript()) { // If there are any INTincScripts to include
		# 	return; // stop
		# } 
		
        // implement your handling
        #if (!$this->isDefaultTypeNum()) {
        #    return;
        #}

        #$tsfe = &$parameters['pObj'];

        #if ($tsfe instanceof TypoScriptFrontendController && $tsfe->isINTincScript()) {
        #    $tsfe = &$parameters['pObj'];
        #    $tsfe->content = $this->tidyHtml($tsfe->content);
        #}
    }
	
    /**
     * check if current rendering is in default page type
     * @return bool
     */
    protected function isDefaultTypeNum()
    {
        $type = GeneralUtility::_GET('type');
        return $type === null || $type === 0;
    }
}