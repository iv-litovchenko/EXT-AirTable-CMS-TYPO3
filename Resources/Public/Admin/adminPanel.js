// Fix bug - после удаления расширения, будет ошибка если была
// открыта папка с таблицами удаленного расширения, которые были записаны в "$GLOBALS['BE_USER']->uc"
var TYPO3 = TYPO3 || {};  // require = function() {};
TYPO3.settings = {
   ajaxUrls: {},
   Textarea: { RTEPopupWindow: {  height: 600 } },
   DateTimePicker: { DateFormat: ["DD-MM-YYYY", "HH:mm DD-MM-YYYY"] }
};

var globalEditType = 'edit';
var globalRecordTable = '';
var globalRecordId = 0;

function adminPanelPopupClose() 
{
	var tagAdminPanelBody = document.getElementById('tagAdminPanelBody');
	var tagAdminPanelIframeWrap = document.getElementById('tagAdminPanelIframeWrap');
	tagAdminPanelBody.style.display = 'none';
	tagAdminPanelIframeWrap.style.display = 'none';
	if (globalRecordTable == 'tt_content' && globalRecordId !== "") {
		
		if(globalEditType == 'edit'){
			var ajaxRecordId = globalRecordId;
			var contentElementWrap = document.getElementById('c'+globalRecordId+'_wrap');
			// var contentElement = document.getElementById('c'+globalRecordId);
			// contentElement.innerHTML = '-- Загружаем изменения --';
		}
		
		if(globalEditType == 'new'){
			// Данные об ID берем из формы редактирования снизу Page Content [723] 
			var contentElementWrap = document.getElementById('c'+globalRecordId+'_newwrap');
			var tagAdminPanelIframe = document.getElementById("tagAdminPanelIframe");
			var innerDoc = tagAdminPanelIframe.contentDocument || tagAdminPanelIframe.contentWindow.document;
			var temp = innerDoc.getElementsByClassName("typo3-TCEforms-recUid")[0].innerHTML;
				temp = temp.replace("[", "");
				temp = temp.replace("]", "");
				var ajaxRecordId = temp;
		}
				
		if(globalEditType == 'newTop'){
			// Данные об ID берем из формы редактирования снизу Page Content [723] 
			var contentElementWrap = document.getElementById(globalRecordId+'_wrap');
			var tagAdminPanelIframe = document.getElementById("tagAdminPanelIframe");
			var innerDoc = tagAdminPanelIframe.contentDocument || tagAdminPanelIframe.contentWindow.document;
			var temp = innerDoc.getElementsByClassName("typo3-TCEforms-recUid")[0].innerHTML;
				temp = temp.replace("[", "");
				temp = temp.replace("]", "");
				var ajaxRecordId = temp;
		}
		
		const request = new XMLHttpRequest();
		const url = "?eIdAjaxContentById="+ajaxRecordId;
		const params = "";
		request.open("POST", url, true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.addEventListener("readystatechange", () => {
			if(request.readyState === 4 && request.status === 200) {
				if(globalEditType == 'edit'){
					contentElementWrap.outerHTML = request.responseText;
				}
				if(globalEditType == 'new'){
					contentElementWrap.outerHTML += request.responseText;
				}
				if(globalEditType == 'newTop'){
					contentElementWrap.outerHTML += request.responseText;
					
					// Если существует плашка "пустой контент" - удаляем ее
					var contentElementEmpty = document.getElementById(globalRecordId+'_empty');
					if (contentElementEmpty) {
						// alert(1);
						contentElementEmpty.remove();
					} else {
						
					}
				}
			}
		});
		request.send(params);
	} else {
		if (confirm("Reset page cache?")) {
			parent.document.forms['adminPanel_clearCacheCurrentPage'].submit();				
		}
	}
}

function adminPanelPopup(editType = 'edit', recordTable = '', recordId = 0, myURL, popupTitle, myWidth, myHeight) 
{
	// alert(editType + '/' + recordId);
	globalEditType = editType;
	globalRecordTable = recordTable;
	globalRecordId = recordId;
	
	var tagAdminPanelBody = document.getElementById('tagAdminPanelBody');
	var tagAdminPanelIframeWrap = document.getElementById('tagAdminPanelIframeWrap');
	var tagAdminPanelIframeTitle = document.getElementById('tagAdminPanelIframeTitle');
	var tagAdminPanelIframe = document.getElementById('tagAdminPanelIframe');
	
	tagAdminPanelBody.style.display = 'block';
	tagAdminPanelIframeWrap.style.display = 'block';
	tagAdminPanelIframeTitle.innerText = popupTitle;
	
	var innerDoc = tagAdminPanelIframe.contentDocument || tagAdminPanelIframe.contentWindow.document;
	innerDoc.open();
	innerDoc.write('-- Загружаем --');
	innerDoc.close();
	
	tagAdminPanelIframe.onload = function() {
		// var loader = innerDoc.getElementById('t3js-ui-block');
	}
	
	tagAdminPanelIframe.src = myURL;
    return false;
}