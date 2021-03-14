```
1) Пересмотреть второй ассигн formValidationResults!
$this->view->assign('form', $postArgs);
$this->view->assign('validationResults', ['form' => $validator]);
2) Отказатьс от "validationResults" - заменить его на "propertyErrors"

            <  f:form.validationResults for="form.agree">
               < f: if condition="{validationResults.flattenedErrors}">
                  <div class="alert alert-danger" role="alert">
                     <ul class="errors">
                        < f :for each="{validationResults.errors}" as="error">
                           <li>{error.message}</li>
                        < /f :for>
                     </ul>
                  </div>
               < / f:if>
            <  / f:form.validationResults>
			
3) Продумать название "propertyErrors"
4) propertyErrors - правильнй массив ошибок!
[nameAttr]
[error1]
[error2]
5) Задокументировать!
```
