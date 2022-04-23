<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidatorFactory;

trait FuncValidate
{
    /**
     * A set of rules for context-aware validation 
     * @return array
	*/
	// public function getRulesArray(): array
	public static function validationRules($params = [])
	{
		$rules = [
			// context pre insert
			'checkPreInsert' => [
				#'title' => [
					#'required' => 'MSG "required"',
					#'string' => 'MSG "string"',
					#'max:2' => 'MSG "max"',
					#function ($attribute, $value, $fail) {
					#	if ($value === 'foo') {
					#		$fail('The '.$attribute.' is invalid.');
					#	}
					#}
				#]
			],
			// context pre update
			'checkPreUpdate' => [
				// ...
			],
			// context pre context
			'checkPreDelete' => [
				// ...
			],
			// context other
			'checkOther' => [
				// ...
			]
		];
		return $rules;
	}
	
    /**
     * Validate model against rules
	 * context
	 * data
     */
    public static function validator($data = [], $context = '', $params = [], $called_class = '_self')
    {
		if(get_called_class() == 'Litovchenko\AirTable\Validation\Validator'){
			list(, $caller) = debug_backtrace(false);
			$parent_caller_class = $caller['class'];
			$called_class = $parent_caller_class;
		} elseif($called_class == '_self'){
			$called_class = get_called_class();
		}
		
		$validator = new \Litovchenko\AirTable\Validation\Factory();
		$validator = $validator->make($data, $context, $params, $called_class); // $rules
        return $validator;
    }
}