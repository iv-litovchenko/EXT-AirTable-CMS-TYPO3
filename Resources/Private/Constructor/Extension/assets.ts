// See: "TypoScript template reference"
// https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/

config {
}

# headTag (
# 	<head>
# 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
# 	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
# )
	
# headerData.10 = TEXT 
# headerData.10.value ( 
# 	<!-- Hello from the comment! -->
# )

meta.description 				= -- TEXT --
meta.keywords 					= -- TEXT --
meta.X-UA-Compatible 			= IE=edge
meta.X-UA-Compatible.attribute 	= http-equiv
meta.og:site_name 				= -- TEXT --
meta.og:site_name.attribute 	= property
meta.page-topic 				= -- TEXT --
meta.dc.title 					= -- TEXT --
meta.dc.description 			= -- TEXT --
meta.abstract 					= -- TEXT --
meta.author 					= -- TEXT --
meta.robots 					= index,follow

# bodyTag = <body>
# bodyTagAdd = class="example"

includeCSS.main 		= EXT:###EXTKEY###/Resources/Public/Css/print.css
includeCSS.print 		= EXT:###EXTKEY###/Resources/Public/Css/print.css
includeCSS.print.media 	= print

includeJS {}

includeJSFooter.main = EXT:###EXTKEY###/Resources/Public/Javascript/main.js
includeJSFooter.bootstrap = EXT:###EXTKEY###/Resources/Public/Javascript/...js

# footerData.10 = TEXT
# footerData.10.value = <!-- Hello from the comment! -->