<?php
namespace Litovchenko\AirTable\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidatorFactory;

class Factory extends ValidatorFactory
{
	// use \Litovchenko\AirTable\Domain\Model\Traits\Specific\FuncValidate;
	
	/**
     * Create a new Validator factory instance.
     *
     * @return void
     */
    public function __construct()
    {
		$container = null;
		$translator = new Translator(new ArrayLoader(), 'en_US');
        $this->container = $container;
        $this->translator = $translator;
    }
	
    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $validationRulesParams
     * @param  array  $temp
     * @return \Illuminate\Validation\Validator
     */
    public function make(array $data, $context, array $validationRulesParams = [], $called_class = '')
    {
		#if($called_class != null){
		#	$parent_caller_class = $called_class;
		#} else {
		#	list(, $caller) = debug_backtrace(false);
		#	$parent_caller_class = $caller['class']; // get_called_class
		#}
		
		//  Litovchenko\AirTable\Domain\Form\ModelForm;
		if(is_array($context)){
			$validationRules = $context;
			
		// Litovchenko\AirTable\Domain\Model\ModelCrud;
		} else {
			
			// Приоритет отдаем теущему классу...
			if(method_exists($called_class,'validationRules')){
				$validationRules = $called_class::validationRules($validationRulesParams);
				$validationRules = $validationRules[$context];
			}
			
			// Второй приоритет отдаем звонящему родительскому калссу...
			#if(empty($validationRules)){
			#	if(method_exists($parent_caller_class,'validationRules')) {
			#		$validationRules = $parent_caller_class::validationRules($validationRulesParams);
			#		$validationRules = $validationRules[$context];
			#	}
			#}
			
		}
		
		$rules = [];
		$messages = [];
		$niceNames = [];
		foreach($validationRules as $k => $v){
			$temp = [];
			if(is_array($v)){
				foreach($v as $k2 => $v2){
					$k2WithoutParams = preg_replace('/:(.*)/is','',$k2);
					$k2Params = preg_replace('/(.*):/is','',$k2);
						
					if($k2WithoutParams == 'name'){ // label
						$niceNames[$k] = $v2;
					
					} elseif($k2WithoutParams == 'file' || $k2WithoutParams == 'image') {
					
						// Конвертируем данные
						// \Symfony\Component\HttpFoundation\File\UploadedFile $image
						$kWithoutSubElements = preg_replace('/\.(.*)/is','',$k); // "images.*", "images.1"
						if(isset($data[$kWithoutSubElements][0]['name'])){ // мультизагрузка
							foreach($data[$kWithoutSubElements] as $kTmpFile => $arTmpFile){
							if(!empty($data[$kWithoutSubElements][$kTmpFile]['tmp_name'])){
									$tempSymfony = new \Symfony\Component\HttpFoundation\File\UploadedFile(
										$data[$kWithoutSubElements][$kTmpFile]['tmp_name'], // sys_get_temp_dir()
										$data[$kWithoutSubElements][$kTmpFile]['name']
									);
									$data[$kWithoutSubElements][$kTmpFile] = $tempSymfony;
								} else {
									$data[$kWithoutSubElements][$kTmpFile] = [];
								}
							}
						} else {
							if(!empty($data[$kWithoutSubElements]['tmp_name'])){
								$tempSymfony = new \Symfony\Component\HttpFoundation\File\UploadedFile(
									$data[$kWithoutSubElements]['tmp_name'], // sys_get_temp_dir()
									$data[$kWithoutSubElements]['name']
								);
								$data[$kWithoutSubElements] = $tempSymfony;
							} else {
								$data[$kWithoutSubElements] = [];
							}
						}
						
						$temp[] = $k2;
						$messages[$k.'.'.$k2WithoutParams] = $v2;
						
					#} elseif ($k2WithoutParams == 'mimes' && strstr($k2Params,'@')) {
						
						#print "<pre>"; // TCA :: allowedFileExtensions
						#print_R($GLOBALS['TYPO3_CONF_VARS']['SYS']['textfile_ext']);
						#print '<br />';
						#print_R($GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']);
						#exit();
					
					} else {
						$temp[] = $k2;
						$messages[$k.'.'.$k2WithoutParams] = $v2;
					}
				}
			}
			$rules[$k] = implode('|',$temp);
		}
		
		// // Если существует ключ .*
		#print "<pre>";
		#print_r($rules);
		#print_r($data);
		#print_r($messages);
		#print "</pre>";
		#exit();
		
		$validator = parent::make($data, $rules, $messages, $niceNames);
		
		$class = $parent_caller_class; // get_called_class();
		$methods = get_class_methods($class);
		foreach($methods as $k => $method){
			if(preg_match('/^custom_rule_/is',$method)){
				$validator->addExtension($method, function ($attribute, $value, $parameters, $validator) use ($class, $method) {
					return call_user_func_array(array($class, $method), array_values(func_get_args()));
				});
			}
        }
		
		return $validator;
    }
}