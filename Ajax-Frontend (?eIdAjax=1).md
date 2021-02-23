###_GET
1

###_POST
1


4) if (посмотреть проверки VHS)
5) Формы _AJAX _POST
6) Обновить Laravel ORM до полседней
7) // Здесь неправильные названия переменных! additionalParams="{eIdAjax:1,ext:'projiv',controller:'RandPhotoController',action:'index'}"

```
--------------------------------------------------------------------------------------------------------------------
Другие хелперы
--------------------------------------------------------------------------------------------------------------------

	\Typo3Helpers::IsEditMode();
	\Typo3Helpers::IsAjaxMode();
	
	
	/**
		Работа по сценарию Ajax
	**/
	static function IsAjaxMode()
    {
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('eIdAjax');
		if(!empty($eIdAjax)){
			return true;
		}else{
			return false;
		}
	}
```


```
1 $this->validate($request, [
2 'title' => 'required|unique:posts|max:255',
3 'author.name' => 'required',
4 'author.description' => 'required',
5 ]);

return $validator->errors()->all();

1 $validator = Validator::make($request->all(), [
2 'person.*.email' => 'email|unique:users',
3 'person.*.first_name' => 'required_with:person.*.last_name',
4 ]);

1 'custom' => [
2 'person.*.email' => [
3 'unique' => 'Each person must have a unique e-mail address',
4 ]
5 ],




 {{ $errors->login->first('email') }}
 
$validator = Validator::make(...);

 		$validator->after(function($validator) {
 		if ($this->somethingElseIsInvalid()) {
 			$validator->errors()->add('field', 'Something is wrong with this field!'\
 		);
 		}
 		});

		if ($validator->fails()) {
1 //
 }


 /**
 * Get the validation rules that apply to the request.
3 *
4 * @return array
5 */
6 public function rules()
7 {
8 return [
9 'title' => 'required|unique:posts|max:255',
10 'body' => 'required',
11 ];
12 }


6 public function messages()
7 {
8 return [
9 'title.required' => 'A title is required',
10 'body.required' => 'A message is required',
11 ];
12 }


 $messages = $validator->errors();
 echo $messages->first('email');
 
foreach ($messages->get('email') as $message) {}
foreach ($messages->all() as $message) {}

if ($messages->has('email')) {}
 
 echo $messages->first('email', '<p>:message</p>');
foreach ($messages->all('<li>:message</li>') as $message) {}


$messages = [ 'required' => 'The :attribute field is required.', ];
$validator = Validator::make($input, $rules, $messages);

$messages = [
 'same' => 'The :attribute and :other must match.',
 'size' => 'The :attribute must be exactly :size.',
 'between' => 'The :attribute must be between :min - :max.',
'in' => 'The :attribute must be one of the following types: :values',
 ];
 
 
 ```
