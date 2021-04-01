```
1) 
// Заменить xClass?
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typolinkProcessing']['typolinkModifyParameterForPageLinks'][] = \Your\Namespace\Hooks\MyBeautifulHook::class;
public functionmodifyPageLinkConfiguration(array $linkConfiguration, array $linkDetails,array $pageRow)  {
   $linkConfiguration['additionalParams'] .= $pageRow['myAdditionalParamsField'];
   return $linkConfiguration;
}
2) Иконка редактирования с ворот? с vorot

3) <core:iconidentifier="my-icon-identifier" /> (переделать авторегистрацию иконок)

4) Em_Conf?
```
