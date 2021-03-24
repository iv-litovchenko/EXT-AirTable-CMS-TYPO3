```html
{fluidanotherdelimiter}

<div style="text-align: center;" class="">

	[[[f:for each=`{{ $images }}` as=`image`]]]
		...
	[[[/f:for]]]
	
	[[f:for each=`{{ $images }}` as=`image`]]
		<div style="float: left; width: 33.3%;" class="">
			<a href="[[f:uri.image src=`{{ $image.uid_local }}` /]]" data-fancybox="gallery">
				<img src="[[f:uri.image src=`{{ $image.uid_local }}` width=`272` height=`300c` /]]">
			</a>
		</div>
	[[/f:for]]
	
</div>
<br style="clear: left;" />
```
