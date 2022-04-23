// Отметить все checkbox
function toggleCheckboxes(source,cbName){
	checkboxes = document.getElementsByName(cbName);
	for(var i=0, n=checkboxes.length;i<n;i++){
		if(checkboxes[i].disabled){
			// 
		} else {
			checkboxes[i].checked = source.checked; 
		}
	}
}

// Собрать отмеченные поля на редактирование
function toggleFields(source,cbName){
	var columnsList = 'pid,RType';
	checkboxes = document.getElementsByName(cbName);
	for(var i=0, n=checkboxes.length;i<n;i++){
		if(checkboxes[i].checked){
			if(checkboxes[i].value == 'uid'){
				continue;
			}
			if(checkboxes[i].value == 'pid'){
				continue;
			}
			if(checkboxes[i].value == 'RType'){
				continue;
			}
			columnsList = columnsList + ',' + checkboxes[i].value;
		}
	}
	document.getElementById("columnsList").innerText = columnsList;
}

// Кол-во выбранных записей
function countCheckboxes(source,cbName){
	checkboxes = document.getElementsByName(cbName);
	var j = 0;
	var idList = [];
	for(var i=0, n=checkboxes.length;i<n;i++){
		if(checkboxes[i].checked === true && checkboxes[i].value > 0){
			idList[j] = checkboxes[i].value;
			j++;
		}
	}
	if(document.getElementById("countCheckboxesLabel")){
		document.getElementById("countCheckboxesLabel").text='С отмеченными ('+j+'):';
		document.getElementById("idList").innerText=idList.join();
	}
	if(j == 0){
		document.getElementById("performSend").disabled='disabled';
		} else {
		document.getElementById("performSend").disabled='';
	}
}

// Выбрать отмеченные
function recordSelection(source,cbName,recordSelectionFieldname){
	var objParentField = window.opener.document.getElementsByName(recordSelectionFieldname)[0];
	
	var j = 0;
	var values = [];
	checkboxes = document.getElementsByName(cbName);
	for(var i=0, n=checkboxes.length;i<n;i++){
		if(checkboxes[i].checked === true && checkboxes[i].value > 0){
			values[j] = checkboxes[i].value;
			j++;
		}
	}
	
	objParentField.value = values.join();
	window.close();
}

// Переключатель веточек (раскрыть/скрыть)
function branchSwitch(formId = '', id = 0, switchValue = ''){
	document.getElementById(formId+"Apply").name = formId+"Apply_"+id;
	document.getElementById(formId+"ApplyBranch").name = formId+"Apply_"+id+"_branch";
	document.getElementById(formId+"ApplyBranch").value = switchValue;
	document.getElementById(formId).submit();
}

// Применить тип записи (tx_data)
// function filterDataType(value, url){
// 	document.getElementsByName("form1Field_RType")[0].value = 'INCLUDE|'+value;
// 	document.getElementsByName("form1Apply")[0].checked = true;
// 	document.getElementById("formFilter").action = url;
// 	document.getElementById("formFilter").submit();
// }

// Применить фильтр по категории
function filterCategory(field_1 = '', field_2 = '', value_1 = '', value_2 = ''){
	// filterCategoryReset(field_1);
	if(field_1 && document.getElementsByName("form1Field_"+field_1)[0]){
		document.getElementsByName("form1Field_"+field_1)[0].value = '';
		document.getElementsByName("form1FieldValue_"+field_1)[0].value = '';
	}
	if(field_1 && value_1 != '' && document.getElementsByName("form1Field_"+field_1)[0]){
		document.getElementsByName("form1Field_"+field_1)[0].value = 'REL_INCLUDE_uid';
		document.getElementsByName("form1FieldValue_"+field_1)[0].value = value_1;
	}
	// filterCategoryReset(field_2);
	if(field_2 && document.getElementsByName("form1Field_"+field_2)[0]){
		document.getElementsByName("form1Field_"+field_2)[0].value = '';
		document.getElementsByName("form1FieldValue_"+field_2)[0].value = '';
	}
	if(field_2 && value_2 != '' && document.getElementsByName("form1Field_"+field_2)[0]){
		document.getElementsByName("form1Field_"+field_2)[0].value = 'REL_INCLUDE_uid';
		document.getElementsByName("form1FieldValue_"+field_2)[0].value = value_2;
	}
	document.getElementsByName("form1Apply")[0].checked = true;
	document.getElementById("formFilter").submit();
}

// Сбросить фильтр по категориям
function filterCategoryReset(field_1 = '', field_2 = ''){
	if(field_1 && document.getElementsByName("form1Field_"+field_1)[0]){
		document.getElementsByName("form1Field_"+field_1)[0].value = '';
		document.getElementsByName("form1FieldValue_"+field_1)[0].value = '';
	}
	if(field_2 && document.getElementsByName("form1Field_"+field_2)[0]){
		document.getElementsByName("form1Field_"+field_2)[0].value = '';
		document.getElementsByName("form1FieldValue_"+field_2)[0].value = '';
	}
	document.getElementsByName("form1Apply")[0].checked = true;
	document.getElementById("formFilter").submit();
}

// Загрузка файла
function fileUpload(){
	event.preventDefault();
	document.getElementById("file").click();
}

// Загрузка файла (изменение)
function fileChange(){
	var fileBasename = document.getElementById("file").value;
	var obj = document.getElementById("info");
	obj.value = fileBasename.replace(/^.*[\\\/]/, '');
}

// Поиск строчек (в фильтре, сортировке, списке колонок)
function tagFilterSelection(obj, c) {
  var x, i;
  x = document.getElementsByClassName(obj);
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
	x[i].style.display = "none"; 
    // if (x[i].className.indexOf(c) > -1) {
	// if (tagContains('div','c') > -1) {
		// w3AddClass(x[i], "show");
	// }
  }
  var search = tagContains(obj,c);
  for (i = 0; i < search.length; i++) {
	search[i].style.display = ""; 
  }
}

// tagContains('div', 'sometext'); // find "div" that contain "sometext"
// tagContains('div', /^sometext/); // find "div" that start with "sometext"
// tagContains('div', /sometext$/i); // find "div" that end with "sometext", case-insensitive
function tagContains(selector, text) {
  var elements = document.getElementsByClassName(selector);
  return Array.prototype.filter.call(elements, function(element){
    return RegExp(text,'i').test(element.textContent);
  });
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}

// Add active class to the current button (highlight it)
// var btnContainer = document.getElementById("myBtnContainer");
// var btns = btnContainer.getElementsByClassName("btn");
// for (var i = 0; i < btns.length; i++) {
//   btns[i].addEventListener("click", function(){
//     var current = document.getElementsByClassName("active");
//     current[0].className = current[0].className.replace(" active", "");
//     this.className += " active";
//   });
// }