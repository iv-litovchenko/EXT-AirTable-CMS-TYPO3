<?php
namespace Litovchenko\AirTable\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\VisibilityAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\FrontendEditing\Utility\FrontendEditingUtility;

class FrontendEditingAspect implements MiddlewareInterface
{
    /**
     * Dispatches the request to the corresponding eID class or eID script
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws Exception
     */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		// * @param bool $includeHiddenPages whether to include hidden=1 in pages tables
		// * @param bool $includeHiddenContent whether to include hidden=1 in tables except for pages
		// * @param bool $includeDeletedRecords whether to include deleted=1 records (only for use in recycler)
		if($GLOBALS['BE_USER']->uc['phptemplate_showHiddenRecords'] == 1){
			$includeHiddenPages = true;
			$includeHiddenContent = true;
			$context = GeneralUtility::makeInstance(Context::class);
			$context->setAspect(
				'visibility',
				GeneralUtility::makeInstance(VisibilityAspect::class, $includeHiddenPages, $includeHiddenContent, false)
			);
		}
		return $handler->handle($request);
	}
}
