##########################################################################################################################
# Frontend edit icon for "CSS Content Styled"
##########################################################################################################################
	
tt_content.stdWrap.editPanel = 1
tt_content.stdWrap.editPanel.tableName = tt_content

##########################################################################################################################
# Frontend edit icon for "tt_news" (example)
##########################################################################################################################

plugin.tt_news {
		
	# LIST view
	displayList.title_stdWrap.editPanel = 1
	displayList.title_stdWrap.editPanel.tableName = tt_news
	displayList.title_stdWrap.editPanel.title = Edit // list

	# LATEST view
	displayLatest.title_stdWrap.editPanel = 1
	displayLatest.title_stdWrap.editPanel.tableName = tt_news
	displayLatest.title_stdWrap.editPanel.title = Edit // latest

	# SINGLE view
	displaySingle.title_stdWrap.editPanel = 1
	displaySingle.title_stdWrap.editPanel.tableName = tt_news
	displaySingle.title_stdWrap.editPanel.title = Edit // single
	displaySingle.title_stdWrap.editPanel.hideNewIcon = 1

}


##########################################################################################################################
# Test
##########################################################################################################################

lib.tx_airtable.test = TEXT
lib.tx_airtable.test.value = :: 123 ::

lib.tx_airtable_menu = TEXT
lib.tx_airtable_menu.value = :: 12345 ::

plugin.tx_airtable {
	settings {
		defaultBackground = {$plugin.tx_airtable.settings.defaultBackground}
	}
}

##########################################################################################################################
# Frontend page tree
##########################################################################################################################

lib.tx_airtable.pageTree = HMENU
lib.tx_airtable.pageTree {
  1 = TMENU
  1.wrap = <ul> | </ul>
  1.NO.wrapItemAndSub = <li> | </li>
  1.NO.stdWrap.wrap = <b class="caret"></b>   
  1.ACT = 1
  1.ACT.ATagParams = class="active"
  1.ACT.wrapItemAndSub = <li> |</li>  
  1.ACT.stdWrap.wrap = <b class="caret"></b>   
  1.IFSUB = 1
  1.IFSUB {
#	stdWrap.htmlSpecialChars = 1
	wrapItemAndSub = <li class="haschildren"> | </li>
	stdWrap.wrap = <b class="caret"></b>   
  }
  1.ACTIFSUB = 1
  1.ACTIFSUB { 
	wrapItemAndSub = <li class="haschildren">|</li> 
	ATagParams = class="active"
	stdWrap.wrap = <b class="caret"></b>   
  }
  2 < .1
  3 < .2
  4 < .3
  5 < .4
  6 < .5
  7 < .6
  8 < .7
  9 < .8
  10 < .9
}

lib.menu_zzz = HMENU
lib.menu_zzz {
    1 = TMENU
    1.NO {
        doNotLinkIt = 1
        wrapItemAndSub = <div>|</div>
        stdWrap.cObject = CONTENT
        stdWrap.cObject {
            table = tt_content
            select {
                pidInList.field = uid
            }
            renderObj = COA
            renderObj {

                10 = TEXT
                10.field = header
                10.typolink {
                    parameter.field = pid
                    section.field = uid
                }
            }
        }
    }
}

# ************************
# CUSTOM MENU
# ************************
lib.custommenu_zzz = HMENU
lib.custommenu_zzz {
   # special = userfunction
   # special.userFunc = Vendor\MyExtension\Userfuncs\CustomMenu->makeMenuArray

   1 = TMENU
   1.wrap = <ul class="level-1">|</ul>
   1.NO = 1
   1.NO {
      wrapItemAndSub = <li>|</li>
	  wrapItemAndSub.stdWrap.editPanel = 1
	  wrapItemAndSub.stdWrap.editPanel.tableName = pages
   }