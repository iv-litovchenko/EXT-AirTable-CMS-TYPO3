<?php
namespace Litovchenko\AirTable\ViewHelpers;

class NewIconViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = ''; // Block, Abs, Inline, Center
	public $panelType = 'onlyNewRecord';
}

class NewIconCenterViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Center'; // Block, Abs, Inline, Center
	public $panelType = 'onlyNewRecord';
}

class NewIconInlineViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Inline'; // Block, Abs, Inline, Center
	public $panelType = 'onlyNewRecord';
}

class NewIconAbsViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Abs'; // Block, Abs, Inline, Center
	public $panelType = 'onlyNewRecord';
}

class NewIconW100ViewHelper extends \Litovchenko\AirTable\ViewHelpers\EditIconViewHelper
{
	public $cssClass = 'Width100'; // Block, Abs, Inline, Center
	public $panelType = 'onlyNewRecord';
}