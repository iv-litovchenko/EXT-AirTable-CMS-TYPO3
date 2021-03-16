		$tsCode = trim('

plugin.tx_projiv_randphotocontroller.settings.example_configuration_value = 123

');
		ExtensionManagementUtility::addTypoScript('air_table', 'setup', $tsCode, 43);
