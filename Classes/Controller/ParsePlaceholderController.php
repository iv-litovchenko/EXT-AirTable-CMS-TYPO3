<?php
namespace Litovchenko\AirTable\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class ParsePlaceholderController
{
	/**
	 * Parses a plain content string with fluid
	 * 
	 * @param string $content
	 * @return string 
	 */
	public function parseContent($content)
	{
		#if(!strstr($content,'[f:')){ ???
		#	return $content;
		#}
		
		// Initializing the object manager
		// $this->objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
		$view = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		
		$templateVariables = [
			#"feUser" 	=> $feUser,
			#"beUser"	=> $beUser,
			#"page" 	=> $GLOBALS["TSFE"]->page,
			#"baseUrl" 	=> $baseUrl,
			#"timestamp" => time(),
			#"year" 	=> date("Y"),
			#"month" 	=> strftime("%B"),
			#"week" 	=> date("W"),
			#"day" 		=> strftime("%A"),
			#"time" 	=> date("H:i"),
		];

		// Assigning all variables to the template
		$view->assignMultiple($templateVariables);

		// Rendering the template view by source
		$view->setTemplateSource("{fluidanotherdelimiter}\n".$content);
		$renderedContent = $view->render();

		return $renderedContent;
	}
	
}


