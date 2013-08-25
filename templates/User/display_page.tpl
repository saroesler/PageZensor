{if $page}
	{checkpermission component=$permission instance="::" level=ACCESS_MODERATE assign=editor}
	{checkpermission component="PageZensor::" instance="::" level=ACCESS_EDIT assign=confirmator}
	{if $editor||$confirmator}
		<a href="{modurl modname='PageZensor' type='admin' func='main'}" >{img src='configure.png' modname='core' set='icons/extrasmall'}{gt text='Admin page'}</a>
	{/if}
	<div style=" margin-top :20px;">{$page->gettext()}</div>
	<p>{gt text="Last update:"} {$page->getpublishingdateFormatted()}</p>
	{checkpermission component=$permission instance="::" level=ACCESS_MODERATE assign=displayeditlink}
	{if $displayeditlink}
		<a href="{modurl modname='PageZensor' type='admin' func='edit' pid=$page->getpid()}" class="z-button" style="margin-top:20px">{img src='xedit.png' modname='core' set='icons/extrasmall'}{gt text='Edit'}</a>
	{/if}
{else}
	<p>{gt text="Could not find a site with this id!"}
{/if}
