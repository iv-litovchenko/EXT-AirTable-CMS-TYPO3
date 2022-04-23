<?php
namespace Litovchenko\AirTable\PageRender;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

class ExtensionsPageTitleProvider extends AbstractPageTitleProvider
{
    public function __construct()
    {
		$p = $GLOBALS['TSFE']->pageRenderer->getTitle();
        if($p != ''){
			$this->title = $p;
		} else {
			$this->title = $GLOBALS['TSFE']->page['title'];
		}
    }
	
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
       $this->title = $title;
    }
}
?>