<?php
namespace Litovchenko\AirTable\Xclass;

class LanguageService
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
		# 'object' => 'TYPO3\CMS\Core\Localization\LanguageService'
	];
	
    /**
     * Returns the label with key $index from the $LOCAL_LANG array used as the second argument
     *
     * @param string $index Label key
     * @param array $localLanguage $LOCAL_LANG array to get label key from
     * @return string
     */
    protected function getLLL($index, $localLanguage)
    {
        return 333;
    }
}
