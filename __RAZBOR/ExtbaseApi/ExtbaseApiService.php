<?php
namespace Litovchenko\Projiv\Controller\PagesElements\Elements;

use TYPO3\CMS\Core\Utility\GeneralUtility;


		$response = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Mvc\Web\Response::class);
		$response->setHeader('Content-Type', 'application/json; charset=utf-8');
		$response->setContent(json_encode(['few'=>1]));
		$response->sendHeaders();
		$response->send();


// http://man.hubwiz.com/docset/TYPO3.docset/Contents/Resources/Documents/class_t_y_p_o3_1_1_c_m_s_1_1_extbase_1_1_mvc_1_1_controller_1_1_abstract_controller.html

// tt_content.list.20.ext_projiv_randphotowidgetcontroller < tt_content.list.20.projiv_randphotowidgetcontroller
		$configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
		$extensionConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $extensionConfiguration = $extensionConfiguration['tt_content.']['list.']['20.'][$signature.'.']; // wgExtAirTableTestWidgetController
		

// $this->response->setStatus(500);
// $this->response->appendContent($this->unknownErrorMessage);


$uriBuilder = $this->uriBuilder;
$uri = $uriBuilder
  ->setTargetPageUid($pageUid)
  ->build();
$this->redirectToUri($uri, 0, 404);

# Internal redirect of request to another controller
$this->forward($actionName, $controllerName, $extensionName, array $arguments);



# External HTTP redirect to another controller
$this->redirect($actionName, $controllerName, $extensionName, array $arguments, $pageUid, $delay = 0, $statusCode = 303);


# Redirect to URI
$this->redirectToURI($uri, $delay=0, $statusCode=303);


# Send HTTP status code
$this->throwStatus($statusCode, $statusMessage, $content);
throw new \Exception('Invalid data'); // -> Еще вариант!





// Page Renderer
$this->pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Page\\PageRenderer');
$this->pageRenderer->addCssFile('/typo3conf/ext/extension/Resources/Public/Css/jquery.datatables.css');
$this->pageRenderer->addJsFile('/typo3conf/ext/extension/Resources/Public/Javascript/jquery.datatables.js', 'text/javascript', true, false);


// Debugging
// Innerhalb PHP:
\TYPO3\CMS\Core\Utility\DebugUtility::debug($var, 'Debug: ' . __FILE__ . ' in Line: ' . __LINE__);

// Fluid:
<f:debug>{var}</f:debug>



// Return success payload
		return $this->returnStatus(200, 'Upload successful', [
			'status' => 200,
			'message' => 'Upload successful',
			'file' => [
				'uid' => $fileObject->getUid(),
				'name' => $fileObject->getName(),
				'identifier' => $fileObject->getIdentifier(),
				'storage' => $fileObject->getStorage()->getUid(),
				'resourcePointerValue' => htmlspecialchars($this->hashService->appendHmac((string)$resourcePointerValue))
			]
		]);


class ExtbaseApiController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController  
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'FrontendContentElement',
		'name' 					=> 'Extbase Api',
		'nonСachedActions' 		=> 'indexAction',
	];
	
	# https://docs.typo3.org/m/typo3/book-extbasefluid/9.5/en-us/b-ExtbaseReference/Index.html
	# https://docs.typo3.org/m/typo3/book-extbasefluid/9.5/en-us/10-Outlook/6-dispatching.html
	// protected $defaultViewObjectName = JsonView::class;
	// $actionMethodName // Name of the executed action.
	// $argumentMappingResults // Results of the argument mapping. Is used especially in the errorAction.
	// $defaultViewObjectName // Name of the default view, if no fluid-view or an action-specific view was found.
	// $errorMethodName // Name of the action that is performed when generating the arguments of actions fail. Default is errorAction. In general, it is not sensible to change this.
	// $request // Request object of type \TYPO3\CMS\Extbase\Mvc\RequestInterface.
	
	/**
	* @param ViewInterface $view
	* @return void
	*/
	#protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view) {
	#	// $blog = $this->blogRepository->findActive();
	#	// $this->view->assign('blog', $blog);
	#}
	
	# Action
	# initializeAction()
	# initialize[ActionName]Action()
	# initializeView()
	# errorMethodName property
	# errorAction() method
	# getErrorFlashMessage() method
	# getFlattenedValidationErrorMessage()

	
	# public function initializeSpecialAction()
	# {
	# 	$this->defaultViewObjectName = \TYPO3\CMS\Extbase\Mvc\View\JsonView::class;
	# }
	
	/**
	 * keep trac if we have created a custom error.
	 * @var boolean
	 */
	protected $hasCustomError = false;
	
	

	
	/**
	 * resolveView
	 *
	 * @return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
	 */
	protected function resolveViewZzz() {
		$view = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);

		$view->setLayoutRootPaths([0=>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('projiv','Resources/Private/_Layouts/')]);
		$view->setPartialRootPaths([0=>\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('projiv','Resources/Private/_Partials/')]);
		$templatePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('projiv','Resources/Private/Templates/Content/Render.html');
		$view->setTemplatePathAndFileName($templatePath);

		#$view->assign('settings', $this->beautyofcodeSettings);

		return $view;
	}
	
    public function indexAction()
    {	
		/*
		<f:widget.paginate objects="{blogs}" as="paginatedBlogs" configuration="{itemsPerPage: 1, insertAbove: 0, insertBelow: 1, maximumNumberOfLinks: 3}">
		   <f:for each="{paginatedBlogs}" as="blog">
			  <h2>{blog.subject}</h2>
			  <div>{blog.message}</div>
		   </f:for>
		</f:widget.paginate>
		*/
		// $rows = \Litovchenko\AirTable\Domain\Model\SysNote::recSelect('get');
		// $this->view->assign('blogs',$rows);
		
		#*********************************************************************************************
		# + TYPO3_MODE
		#*********************************************************************************************
		if (TYPO3_MODE === 'BE') {}
		if (TYPO3_MODE === 'FE') {}
		
		#*********************************************************************************************
		# + Environment
		# \TYPO3\CMS\Core\Core\Environment
		#*********************************************************************************************
		if (\TYPO3\CMS\Core\Core\Environment::getContext() == 'Production') {}
		if (\TYPO3\CMS\Core\Core\Environment::isComposerMode()) {}
		if (\TYPO3\CMS\Core\Core\Environment::isCli()) {}
		if (\TYPO3\CMS\Core\Core\Environment::isUnix()) {}
		if (\TYPO3\CMS\Core\Core\Environment::isWindows()) {}
		\TYPO3\CMS\Core\Core\Environment::getProjectPath();
		
		#*********************************************************************************************
		# + Request varibles (_GET, _POST)
		# \TYPO3\CMS\Extbase\Mvc\Request
		#*********************************************************************************************
		$requestData = $this->request->getArguments();
		if($this->request->hasArgument('variable')){
			$variable = $this->request->getArgument('variable');
		}
		
		$_GET = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
		$_POST = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST('variable');
		
		#*********************************************************************************************
		# + Information
		#*********************************************************************************************
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class)->getActivePackages().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class)->getVersion().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class)->getBranch().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class)->getMajorVersion().'<br />';
		
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class)->getCopyrightYear().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class)->getHtmlGeneratorTagContent().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class)->getInlineHeaderComment().'<br />';
		#print __LINE__ . ':'.GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Information::class)->getCopyrightNotice().'<br />';
		
		#*********************************************************************************************
		# + About this controller  
		# \TYPO3\CMS\Extbase\Mvc\Request
		#*********************************************************************************************
		#print __LINE__ . ':'.$this->request->getRequestUri().'<br />';
		#print __LINE__ . ':'.$this->request->getFormat().'<br />';
		
		#print __LINE__ . ':'.$this->request->getControllerSubpackageKey().'<br />';
		#print __LINE__ . ':'.$this->request->getControllerExtensionName().'<br />';
		#print __LINE__ . ':'.$this->request->getControllerExtensionKey().'<br />';
		
		#print __LINE__ . ':'.$this->request->getPluginName().'<br />';
		#print __LINE__ . ':'.$this->request->getControllerName().'<br />';
		#print __LINE__ . ':'.$this->request->getControllerObjectName().'<br />';
		#print __LINE__ . ':'.$this->request->getControllerActionName().'<br />';
		
		#*********************************************************************************************
		# + FlashMessage
		# \TYPO3\CMS\Core\Messaging\FlashMessage
		# \TYPO3\CMS\Core\Messaging\AbstractMessage::NOTICE
		# \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO
		# \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
		# \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
		# \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
		#*********************************************************************************************
		$this->addFlashMessage('One Two Three', 'The title', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
		$this->controllerContext->getFlashMessageQueue()->enqueue(
		  $this->objectManager->get(
			\TYPO3\CMS\Core\Messaging\FlashMessage::class,
			'The message text.',
			'The title',
			\TYPO3\CMS\Core\Messaging\AbstractMessage::OK
		  )
		);
		$this->controllerContext->getFlashMessageQueue()->dequeue(); // Removes last FlashMessage from the queue
		$this->controllerContext->getFlashMessageQueue()->getAllMessages(); // Gets all FlashMessages
		$this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush(); // Get all FlashMessages and removes them from the session
		
		#*********************************************************************************************
		# + Debug
		#*********************************************************************************************
		# \TYPO3\CMS\Core\Utility\DebugUtility::debug('VAR','HEADER','Debug');
		# \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('VAR', 'FormObject:');
		
		#*********************************************************************************************
		# Assets (css, javascript)
		#*********************************************************************************************
		#$pageRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
		#$pageRenderer->addJsInlineCode('effects1', 'function hallo(text){alert(text);}hallo("Tag auch");');
		#$pageRenderer->addJsInlineCode('effects2', 'function hallo(text){alert(text);}hallo("Du mich auch");');
		#$pageRenderer->addJsInlineCode('effects3', 'function hallo(text){alert(text);}hallo("Nix da");');
		#$pageRenderer->addJsFile('EXT:my_ext_key/Resources/Public/Js/js.js', 'text/javascript');
		#$pageRenderer->addCssFile('EXT:my_ext_key/Resources/Public/Css/css.css');
		#$pageRenderer->addCssInlineBlock('default', '* { color: red !important;} '); // ???
		#$this->setMetaTag('name', 'keywords', 'seo, search engine optimisation, search engine optimization, search engine ranking');
		
		/*
		The API
		\TYPO3\CMS\Core\Page\AssetCollector::addJavaScript(string $identifier, string $source, array $attributes, array $options = []): self
		\TYPO3\CMS\Core\Page\AssetCollector::addInlineJavaScript(string $identifier, string $source, array $attributes, array $options = []): self
		\TYPO3\CMS\Core\Page\AssetCollector::addStyleSheet(string $identifier, string $source, array $attributes, array $options = []): self
		\TYPO3\CMS\Core\Page\AssetCollector::addInlineStyleSheet(string $identifier, string $source, array $attributes, array $options = []): self
		\TYPO3\CMS\Core\Page\AssetCollector::addMedia(string $fileName, array $additionalInformation): self
		\TYPO3\CMS\Core\Page\AssetCollector::removeJavaScript(string $identifier): self
		\TYPO3\CMS\Core\Page\AssetCollector::removeInlineJavaScript(string $identifier): self
		\TYPO3\CMS\Core\Page\AssetCollector::removeStyleSheet(string $identifier): self
		\TYPO3\CMS\Core\Page\AssetCollector::removeInlineStyleSheet(string $identifier): self
		\TYPO3\CMS\Core\Page\AssetCollector::removeMedia(string $identifier): self
		\TYPO3\CMS\Core\Page\AssetCollector::getJavaScripts(?bool $priority = null): array
		\TYPO3\CMS\Core\Page\AssetCollector::getInlineJavaScripts(?bool $priority = null): array
		\TYPO3\CMS\Core\Page\AssetCollector::getStyleSheets(?bool $priority = null): array
		\TYPO3\CMS\Core\Page\AssetCollector::getInlineStyleSheets(?bool $priority = null): array
		\TYPO3\CMS\Core\Page\AssetCollector::getMedia(): array
		GeneralUtility::makeInstance(AssetCollector::class)
		   ->addJavaScript('my_ext_foo', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', ['data-foo' => 'bar'], ['priority' => true]);
		
		# GeneralUtility::makeInstance(AssetCollector::class)
		# ->addJavaScript('my_ext_foo', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', ['data

		*/


		//----------------------------------------------------------------------------------------
		// $this->response->addAdditionalHeaderData('eeeeeeeeee'); ???
		
		
		
		// throw new \RuntimeException('few', 1363300072); // Unix-TS because of uniqueness
		
		
		$this->view->assign('getLanguage', $this->getLanguage()); //
		
		$controllerActionSettings = $this->settings;
		$this->view->assign('controllerActionSettings', $controllerActionSettings);
		
	}
	
	public function showAction()
    {
	}
	
	/**
	 * Displays the subscription form
	 *
	 */
    public function indexAction2()
    {	
		
	
		// Set TYPO3 Cookie
		#*********************************************************************************************
		# use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController\ SessionUtility ;
		#$GLOBALS['TSFE']->fe_user->setKey("ses","myCookie",”myValue”)
		#$GLOBALS['TSFE']->fe_user->storeSessionData();

		// Get TYPO3 Cookie
		#$GLOBALS["TSFE"]->fe_user->getKey("ses","myCookie");
		#*********************************************************************************************
		
		#*********************************************************************************************
		# Logger
		# \TYPO3\CMS\Core\Log\LogManager
		# \TYPO3\CMS\Core\Log\LogLevel::DEBUG
		# \TYPO3\CMS\Core\Log\LogLevel::INFO
		# \TYPO3\CMS\Core\Log\LogLevel::NOTICE
		# \TYPO3\CMS\Core\Log\LogLevel::WARNING
		# \TYPO3\CMS\Core\Log\LogLevel::ERROR
		# \TYPO3\CMS\Core\Log\LogLevel::CRITICAL
		# \TYPO3\CMS\Core\Log\LogLevel::ALERT
		# \TYPO3\CMS\Core\Log\LogLevel::EMERGENCY
		#*********************************************************************************************
		$logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
		$message = 'MYLOG';
		$data = array('1'=>'22');
		$logger->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, $message, $data);
		
		print 1;
		exit();
		
		

		
		#*********************************************************************************************
		# Template
		#*********************************************************************************************
		// $this->view->setTemplatePathAndFilename('typo3conf/ext/mykey/Resources/Private/Templates/Base/Anothertemplate.html');
		
		
		
		
			
			
		
		#*********************************************************************************************
		# FE_USER, BE_USER 
		#*********************************************************************************************
		# \TYPO3\CMS\Core\Authentication\BackendUserAuthentication 
		# \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
			/*
$check = FALSE;
$loginData = array(
'username' => "user",
'uident_text' => "pass",
'status' => 'login',
);
	
		$GLOBALS['TSFE']->fe_user->checkPid = ''; //do not use a particular pid
		$info = $GLOBALS['TSFE']->fe_user->getAuthInfoArray();
		$info['db_user']['checkPidList'] = 1;
		$info['db_user']['check_pid_clause'] = 'AND pid IN(362)';
		$user = $GLOBALS['TSFE']->fe_user->fetchUserRecord($info['db_user'], $loginData['username']);
	
	
	#$BE_USER->writelog(255,2,0,1,'User %s logged out from TYPO3 Backend',Array($BE_USER->user['username']));        // Logout written to log
	#$BE_USER->logoff();
	#header('Location: '.t3lib_div::locationHeaderUrl(t3lib_div::_GP('redirect')?t3lib_div::_GP('redirect'):'index.php'));
	
	$login_success = $GLOBALS['TSFE']->fe_user->compareUident($user,$loginData);
	if($login_success){

		#$GLOBALS['TSFE']->fe_user->createUserSession($user);
		#$GLOBALS['TSFE']->fe_user->start();
		#$GLOBALS['TSFE']->loginUser = 1;     

		  $GLOBALS["TSFE"]->fe_user->createUserSession($user);
          $GLOBALS["TSFE"]->fe_user->loginSessionStarted = TRUE;
          $GLOBALS["TSFE"]->fe_user->user = $GLOBALS["TSFE"]->fe_user->fetchUserSession();
			#$GLOBALS ["TSFE"] ->loginUser = 1;
            $GLOBALS ["TSFE"] ->fe_user->start();
		
	
}
	 $GLOBALS["TSFE"]->initFEuser();
		
		if ($ok) {

			//login successfull
			#$GLOBALS['TSFE']->fe_user->createUserSession($user);
			#$GLOBALS['TSFE']->fe_user->loginSessionStarted = true;
			#$GLOBALS['TSFE']->fe_user->fetchGroupData();
			#$check = TRUE;
		} 
		else 
		{
			//login failed
			#$check = FALSE;
		}

		
		
		
		
		#	print $GLOBALS["TSFE"]->loginUser;
		
	#	$user = tslib_eidtools::initFeUser(); 
	#$GLOBALS['TSFE']->loginUser = $user ? 1 : 0;
		
		#print  "<pre>[ ".$check." ]" . print_r($GLOBALS['TSFE'], true);
		*/
   
		$GLOBALS['TSFE']->loginUser;
		$GLOBALS['TSFE']->fe_user->user['uid'];
		$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'mykey');
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'mykey', $settings);	
		
		# $GLOBALS['TSFE']->fe_user->sesData_change = 1; // ??
		# $GLOBALS['TSFE']->fe_user->storeSessionData(); // ??

		
		#*********************************************************************************************
		# Utility
		#*********************************************************************************************
			
		#ExtensionManagementUtility::extPath() - to resolve the full path of an extension
		#ExtensionManagementUtility::siteRelPath() - to resolve the location of an extension relative to PATH_site
		#GeneralUtility::getFileAbsFileName() - to resolve a file/path prefixed with EXT:myext
		#PathUtility::getAbsoluteWebPath() - used for output a file location (previously resolved with GeneralUtility::getFileAbsFileName()) that is absolutely prefixed for the web folder

		$this->view->assign('extPath', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('air_table'));
		$this->view->assign('isLoaded', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('air_table'));
		$this->view->assign('getFileAbsFileName', \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:myext_key/Resources/Private/JSON/data.json'));
		// ??? $this->view->assign('explodeUrl2Array',\TYPO3\CMS\Core\Utility\GeneralUtility::explodeUrl2Array('https://www.domain.com/sub/sub'));
		
		// $externalUrl = 'https://www.google.ru/';
		// print \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Http\RequestFactory::class)->request($externalUrl)->getBody()->getContents();
		
		   
		
		#*********************************************************************************************
		# Data
		#*********************************************************************************************
		$this->configurationManager->getContentObject()->data['uid'];
		

		
		
		
		#*********************************************************************************************
		# Configuration
		#*********************************************************************************************
		#$this->configurationManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
		#$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		#$path = $extbaseFrameworkConfiguration['plugin.']['tx_pxccontent.']['view.']['partialRootPaths.'][0];
		#$path = GeneralUtility::getFileAbsFileName($path);
		#$extVarConf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('air_table', 'myVariable');

		 
		 // use TYPO3\CMS\Backend\Utility\BackendUtility;
		// $pagesTsConfig = BackendUtility::getRawPagesTSconfig(1, $rootLine = null);
		// $hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
		// $GLOBALS['BE_USER']->getTSConfig()['tx_news.']
		
		// Сделать более понятные названия
		$this->view->assign('getSiteConfig', $this->getSiteConfig()); // Настройки сайта...
		$this->view->assign('getDataRow', $this->getDataRow()); // Запиьс в БД (страница / элемента содержимого)
		
		// IncFrontend ???
		$this->view->assign('getPagesTSconfig', $this->getPagesTSconfig()); // PagesTSconfig
		
		// IncBackend ???
		$this->view->assign('getTSconfig', $this->getTSconfig()); // TSconfig

		

		

		
		#*********************************************************************************************
		# TS
		#*********************************************************************************************
		#$cConf = array(
		# 'tables' => 'tt_content',
		# 'source' => '234',	//single uid or komma separated uids
		# 'wrap' => '<div class="mydiv">|</div>',
		# 'dontCheckPid' => 1,
		#);
		#$content .= $GLOBALS['TSFE']->cObj->RECORDS($cConf);
		
	#$arrImgParams['file'] = 'uploads/tx_vpage/'.$tmp['image'];
		#	$arrImgParams['file.']['width'] = '171c';
		#	//$arrImgParams['file.']['height'] = '250c';
		#	$arrImgParams['alttext'] = 'Hellow Image';
		#	$arrImgParams['titleText'] ='Hellow Image'; 
		#	$img = $this->cObj->IMAGE($arrImgParams);
		
		$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
		$this->view->assign('cObjGetSingle', $cObj->cObjGetSingle('TEXT', ['value' => '123321']));
		
		#*********************************************************************************************
		# File Api
		#*********************************************************************************************
		// \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/var/transient/');
		
		#*********************************************************************************************
		# Cache Api
		#*********************************************************************************************
		// TypoScriptFrontendController->addCacheTags()
		$cacheIdentifier = 'Test_1'; // $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']
		$cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache('extbase');
		$cacheContent = $cacheManager->get($cacheIdentifier);
		if ($cacheContent) {
			$this->view->assign('cacheContent', $cacheContent);
		} else {
			$content = rand(1,100);
			$tags = []; // тэги кэша (оставляем пустыми)
			$lifetime = 5; // время жизни кэша в секундах - lifetime
			$cacheManager->set($cacheIdentifier, $content, $tags, $lifetime);
			$this->view->assign('cacheContent', $content);
		}
		
		/*
			$cacheManager->flushCachesInGroupByTags('extbase', [ 'Test_1' ]);
			$cacheManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Service\CacheService::class);
			$cacheManager->clearPageCache([1,2,3]);
			$this->cacheService->clearPageCache([$pageIds]);
		*/
		
		#*********************************************************************************************
		# Route
		#*********************************************************************************************
		# $this->forward($actionName, $controllerName = NULL, $extensionName = NULL,array $arguments = NULL)
		# $this->redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303)
		# $this->redirectToURI($uri, $delay=0, $statusCode=303)
		# $this->throwStatus($statusCode, $statusMessage, $content)
		
		
		#$controllerArguments[
		#	'a' => 123
		#];
		#$this->uriBuilder->uriFor($actionName = NULL, $controllerArguments = array(), $controllerName = NULL, $extensionName = NULL, $pluginName = NULL) 
		
		
		 #$moduleName = 'record_edit';
			#$params = ['pid' => 2];
			#$url = BackendUtility::getModuleUrl($moduleName, $params);
			#$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);
		//  . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
		// print $this->UriBuilder->buildUriFromRoute('record_edit') . $params . '&returnUrl=1';
		// exit();
		
		
		
		#print $GLOBALS['TSFE']->generatePageTitle();
		#exit();
		
			// $GLOBALS['TSFE']->pageUnavailableAndExit($message);
			// $response = GeneralUtility::makeInstance(ErrorController::class)->unavailableAction($GLOBALS['TYPO3_REQUEST'], $message);
		// throw new ImmediateResponseException($response);
		
		
		// $domainStartPage = $GLOBALS['TSFE']->domainStartPage;
		// $cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];
		// $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
		
		# $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
		#$rtehtmlparser->evalWriteFile();
		
            # use TYPO3\CMS\Core\Html\RteHtmlParser;
           #  $rteHtmlParser = new RteHtmlParser();
            #$rteHtmlParser->HTMLcleaner_db('arg1');
#$rteHtmlParser->getKeepTags('arg1');
           # \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl('http://domain.com');
           #  \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
		   
		   

	
		
		/*
		-$frontendUserIsLoggedIn = $GLOBALS['TSFE']->loginUser;
		-$groupList = $GLOBALS['TSFE']->gr_list;
		-$backendUserIsLoggedIn = $GLOBALS['TSFE']->beUserLogin;
		-$showHiddenPage = $GLOBALS['TSFE']->showHiddenPage;
		-$showHiddenRecords = $GLOBALS['TSFE']->showHiddenRecords;
		+$frontendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'isLoggedIn');
		+$groupList = implode(',', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'groupIds'));
		+$backendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('backend.user', 'isLoggedIn');
		+$showHiddenPage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenPages');
		+$showHiddenRecords = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenContent');
		$languageUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
		#$myvariable = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
		#$myvariable2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
		
		id 	$context->getPropertyFromAspect('backend.user', 'id'); 	uid of the currently logged in user, 0 if no user
username 	$context->getPropertyFromAspect('backend.user', 'username'); 	the username of the currently authenticated user. Empty string if no user.
isLoggedIn 	$context->getPropertyFromAspect('frontend.user', 'isLoggedIn'); 	whether a user is logged in, as boolean.
isAdmin 	$context->getPropertyFromAspect('backend.user', 'isAdmin'); 	whether the user is admin, as boolean. Only useful for BEuser.
groupIds 	$context->getPropertyFromAspect('backend.user', 'groupIds'); 	the groups the user is a member of, as array
groupNames 	$context->getPropertyFromAspect('frontend.user', 'groupNames'); 	the names of all groups the user belongs to, as array
		
		$context = GeneralUtility::makeInstance(Context::class);

// Checking if a user is logged in
$userIsLoggedIn = $context->getPropertyFromAspect('frontend.user', 'isLoggedIn');

includeHiddenPages 	$context->getPropertyFromAspect('visibility', 'includeHiddenPages'); 	whether hidden pages should be displayed, as boolean
includeHiddenContent 	$context->getPropertyFromAspect('visibility', 'includeHiddenContent'); 	whether hidden content should be displayed, as boolean
includeDeletedRecords 	$context->getPropertyFromAspect('visibility', 'includeDeletedRecords'); 	whether deleted records should be displayed, as boolean.

$context = GeneralUtility::makeInstance(Context::class);

// Checking if hidden pages should be displayed
$showHiddenPages = $context->getPropertyFromAspect('visibility', 'includeHiddenPages');
		
		isPreview 	$context->getPropertyFromAspect('frontend.preview', 'isPreview');
		forcedTemplateParsing 	$context->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
		id 	$context->getPropertyFromAspect('language', 'id'); 	the requested language of the current page as integer (uid)
		contentId 	$context->getPropertyFromAspect('language', 'contentId'); 	the language id of records to be fetched in translation scenarios as integer (uid)
		fallbackChain 	$context->getPropertyFromAspect('language', 'fallbackChain'); 	the fallback steps as array
		overlayType 	$context->getPropertyFromAspect('language', 'overlayType'); 	one of LanguageAspect::OVERLAYS_OFF, LanguageAspect::OVERLAYS_MIXED, LanguageAspect::OVERLAYS_ON, or LanguageAspect::OVERLAYS_ON_WITH_FLOATING (default)
		legacyLanguageMode 	$context->getPropertyFromAspect('language', 'legacyLanguageMode'); 	one of strict, ignore or content_fallback, kept for compatibility reasons. Don’t use if not really necessary, the option will be removed rather sooner than later.
		legacyOverlayType 	$context->getPropertyFromAspect('language', 'legacyOverlayType'); 	one of hideNonTranslated, 0 or 1, kept for compatibility reasons. Don’t use if not really necessary, the option will be removed rather sooner than later.
		*/
		
		
		#*********************************************************************************************
		# Cookie
		#*********************************************************************************************
		#$cookie = new Cookie('myCounter', 1);
		#$this->response->setCookie($cookie);
		
		#$httpRequest = $this->request->getHttpRequest();
		#if ($httpRequest->hasCookie('myCounter')) {
		#$cookie = $httpRequest->getCookie('myCounter');
		#} else {
		#$cookie = new Cookie('myCounter', 1);
		#}
		#$this->view->assign('counter', $cookie->getValue());
		#$cookie->setValue((integer)$cookie->getValue() + 1);
		#$this->response->setCookie($cookie);
		#}
		#$cookie->expire();


		#$headers = $this->request->getHttpRequest()->getHeaders();
		#print "<pre>";
		#print_r($headers);
		#exit();
		
		// $headers->setCacheControlDirective('max-age', 3600);
		
		#*********************************************************************************************
		# Website
		#*********************************************************************************************
		 # public function getWebsiteRootPid(){
			#  return $GLOBALS['TSFE']->rootLine[0]['uid'];
		# 	 }
		#$rootLevelPages = $GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false);
		#$GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1);
		
		#*********************************************************************************************
		# Flexform
		#*********************************************************************************************
		// use TYPO3\CMS\Core\Service\FlexFormService;
		#$flexFormArray = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($flexFormString);
		// $this->flexFormService->convertFlexFormContentToArray($originalValue);

		#$flexFormTools = new \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools();
		#$flexFormString = $flexFormTools->flexArray2Xml($flexFormArray, true);

		
		/*

Migrating from GeneralUtility::getIndpEnv()

Class NormalizedParams is a one-to-one transition of GeneralUtility::getIndpEnv(), the old arguments can be substituted with these calls:

    SCRIPT_NAME is now ->getScriptName()
    SCRIPT_FILENAME is now ->getScriptFilename()
    REQUEST_URI is now ->getRequestUri()
    TYPO3_REV_PROXY is now ->isBehindReverseProxy()
    REMOTE_ADDR is now ->getRemoteAddress()
    HTTP_HOST is now ->getHttpHost()
    TYPO3_DOCUMENT_ROOT is now ->getDocumentRoot()
    TYPO3_HOST_ONLY is now ->getRequestHostOnly()
    TYPO3_PORT is now ->getRequestPort()
    TYPO3_REQUEST_HOST is now ->getRequestHost()
    TYPO3_REQUEST_URL is now ->getRequestUrl()
    TYPO3_REQUEST_SCRIPT is now ->getRequestScript()
    TYPO3_REQUEST_DIR is now ->getRequestDir()
    TYPO3_SITE_URL is now ->getSiteUrl()
    TYPO3_SITE_PATH is now ->getSitePath()
    TYPO3_SITE_SCRIPT is now ->getSiteScript()
    TYPO3_SSL is now ->isHttps()

Some further old getIndpEnv() arguments directly access $request->serverParams() and do not apply any normalization. These have been transferred to the new class, too, but will be deprecated later if the core does not use them anymore:

    PATH_INFO is now ->getPathInfo(), but better use ->getScriptName() instead
    HTTP_REFERER is now ->getHttpReferer(), but better use $request->getServerParams()['HTTP_REFERER'] instead
    HTTP_USER_AGENT is now ->getHttpUserAgent(), but better use $request->getServerParams()['HTTP_USER_AGENT'] instead
    HTTP_ACCEPT_ENCODING is now ->getHttpAcceptEncoding(), but better use $request->getServerParams()['HTTP_ACCEPT_ENCODING'] instead
    HTTP_ACCEPT_LANGUAGE is now ->getHttpAcceptLanguage(), but better use $request->getServerParams()['HTTP_ACCEPT_LANGUAGE'] instead
    REMOTE_HOST is now ->getRemoteHost(), but better use $request->getServerParams()['REMOTE_HOST'] instead
    QUERY_STRING is now ->getQueryString(), but better use $request->getServerParams()['QUERY_STRING'] instead


	
	//https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/ApiOverview/MetaTagApi/Index.html
	use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$metaTagManager = GeneralUtility::makeInstance(MetaTagManagerRegistry::class)->getManagerForProperty('og:image');
$metaTagManager->addProperty('og:image', '/path/to/image.jpg', ['width' => 400, 'height' => 400]);
$metaTagManager->removeProperty('og:title');
	
	
		*/
		
		/*
		
		public function __construct(TypoScriptFrontendController $frontendController)
	{
		$this->frontendController = $frontendController;
	}

	public function showAction(ExampleModel $example): ResponseInterface
	{
	   // ...

	   $this->frontendController->addCacheTags([
		   sprintf('tx_myextension_example_%d', $example->getUid()),
	   ]);
	}
	*/
    }
	
	/**
	 * Get the current language
	 */
	protected function getLanguage() 
	{
		// $GLOBALS['TSFE']->sL(); // Зависит от языка выбранного пользователем на переключателе языков во Frontend.
		// $GLOBALS['LANG']->sL(); // Зависит от выбранного языка у пользователя в административной панели (и если Backend-пользователь авторизован).
		# use TYPO3\CMS\Core\Localization\LanguageService;
		# $languageService = new LanguageService();
		#$languageService->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf:pages_1');
		#if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
		#$GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());

		if (TYPO3_MODE === 'FE') {
			if (isset($GLOBALS['TSFE']->config['config']['language'])) {
				return $GLOBALS['TSFE']->config['config']['language'];
			}
		} elseif (strlen($GLOBALS['BE_USER']->uc['lang']) > 0) {
			return $GLOBALS['BE_USER']->uc['lang'];
		}
		return 'en'; //default
	}
	

    /**
     * Displays a list of files for a chosen storage and folder.
     *
     * @return void
     */
    public function listFilesAction()
    {
        $resourceFactory = ResourceFactory::getInstance();
        $defaultStorage = $resourceFactory->getDefaultStorage();
        $folder = $defaultStorage->getFolder('/user_upload/images/galerie/');
        $files = $defaultStorage->getFilesInFolder($folder);
        $this->view->assignMultiple(
            [
                'folder' => $folder,
                'files' => $files,
            ]
        );
    }
	


    /**
     * Parses some HTML using TYPO3's HTML parser and sends the result to debug output.
     *
     * @return void
     */
	#use TYPO3\CMS\Core\Html\HtmlParser;
    public function index2sAction()
    {
        $testHTML = '
			<DIV>
				<IMG src="welcome.gif">
				<p>Line 1</p>
				<p>Line <B class="test">2</B></p>
				<p>Line <b><i>3</i></p>
				<img src="test.gif" />
				<BR><br/>
				<TABLE>
					<tr>
						<td>Another line here</td>
					</tr>
				</TABLE>
			</div>
			<B>Text outside div tag</B>
			<table>
				<tr>
					<td>Another line here</td>
				</tr>
			</table>
		';

        // Splitting HTML into blocks defined by <div> and <table> block tags
        /** @var HtmlParser $parseObj */
        $parseObj = GeneralUtility::makeInstance(HtmlParser::class);
        $this->view->assign(
            'result1',
            $parseObj->splitIntoBlock('div,table', $testHTML)
        );

        // Splitting HTML into blocks defined by <img> and <br> single tags
        $this->view->assign(
            'result2',
            $parseObj->splitTags('img,br', $testHTML)
        );

        // Cleaning HTML
        $tagCfg = [
            'b' => [
                'nesting' => 1,
                'remap' => 'strong',
                'allowedAttribs' => 0,
            ],
            'img' => [],
            'div' => [],
            'br' => [],
            'p' => [
                'fixAttrib' => [
                    'class' => [
                        'set' => 'bodytext',
                    ],
                ],
            ],
        ];
        $this->view->assign(
            'result3',
            $result = $parseObj->HTMLcleaner(
                $testHTML,
                $tagCfg,
                false,
                false,
                ['xhtml' => 1]
            )
        );
    }

	
	/*
	--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
$configuration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
$view = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
$view->getRequest()->setControllerExtensionName($this->extensionName);
$view->setFormat('txt');
$view->setLayoutRootPaths($configuration['view']['layoutRootPaths']);
$view->setPartialRootPaths($configuration['view']['partialRootPaths']);
$view->setTemplateRootPaths($configuration['view']['templateRootPaths']);
$view->setTemplate('Mail/MyTemplate');
$view->assign('myParam', $myParam);
$result = $view->render();

$templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($configuration['view']['templateRootPath']);
$view->setTemplatePathAndFilename($templateRootPath . 'Mail/MyTemplate.txt');

$standaloneView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
$templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
$templatePathAndFilename = $templateRootPath . 'StandAloneViews/StandAloneView.html';
$extensionName = $this->request->getControllerExtensionName();
$standaloneView->getRequest()->setControllerExtensionName($extensionName);
$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
$standaloneView->assignMultiple(array(
'foo' => 'bar',
'foo2' => 'bar2'
));
$result = $standaloneView->render();









--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
/ **
 * Set up the doc header properly here
 *
 * @param ViewInterface $view
 * @return void
 * /
protected function initializeView(ViewInterface $view)
{
    / ** @var BackendTemplateView $view * /
    parent::initializeView($view);
    if ($this->actionMethodName == 'indexAction'
        || $this->actionMethodName == 'onlineAction'
        || $this->actionMethodName == 'compareAction') {
        $this->generateMenu();
        $this->registerDocheaderButtons();
        $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());
    }
    if ($view instanceof BackendTemplateView) {
        $view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
    }
}


--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------

$GLOBALS['BE_USER']->modAccess($MCONF);
$GLOBALS['BE_USER']->check('modules', 'web_list');
$GLOBALS['BE_USER']->check('tables_modify', 'pages');
$GLOBALS['BE_USER']->check('tables_select', 'tt_content');
$GLOBALS['BE_USER']->check('non_exclude_fields', $table . ':' . $field);
$GLOBALS['BE_USER']->isAdmin();
$GLOBALS['BE_USER']->doesUserHaveAccess($pageRec, 1);
$GLOBALS['BE_USER']->isInWebMount($id)
$GLOBALS['BE_USER']->getPagePermsClause(1);

$compareFlags = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('compareFlags');
$GLOBALS['BE_USER']->pushModuleData('tools_beuser/index.php/compare', $compareFlags);
$compareFlags = $GLOBALS['BE_USER']->getModuleData('tools_beuser/index.php/compare', 'ses');

$tsconfig = $GLOBALS['BE_USER']->getTSConfig();
$clipboardNumberPads = $tsconfig['options.']['clipboardNumberPads'] ?? '';

$GLOBALS['BE_USER']->user['username']
$GLOBALS['BE_USER']->uc['emailMeAtLogin']



--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class MyClass
{
    / * *
     * @var FrontendInterface
     * /
    private $cache;

    public function __construct(FrontendInterface $cache)
    {
        $this->cache = $cache;
    }

    protected function getCachedValue()
    {
        $cacheIdentifier = / * ... logic to determine the cache identifier ... * /;

        // If $entry is false, it hasn't been cached. Calculate the value and store it in the cache:
        if (($value = $this->cache->get($cacheIdentifier)) === false) {
            $value = / * ... Logic to calculate value ... * /;
            $tags = / * ... Tags for the cache entry ... * /
            $lifetime = / * ... Calculate/Define cache entry lifetime ... * /

            // Save value in cache
            $this->cache->set($cacheIdentifier, $value, $tags, $lifetime);
        }

        return $value;
    }

*/
}