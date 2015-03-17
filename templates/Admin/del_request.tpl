{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="delete" size="small"}
    <h3>{gt text="Delete page"} - {$page->gettitle()}</h3>
</div>

<h2>{gt text="Do you realy want to delete the page?"} </h2>

<p class="z-warningmsg">{gt text="Warning! Deleting of the page is permanently and not removable!"}</p>

<div class="z-buttons z-formbuttons">
				<a href="{modurl modname=PageZensor type=admin func=delete id=$page->getpid()}" class="z-button">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'} {gt text="Delete"}</a>
				
                <a class="z-btred" href="{modurl modname='PageZensor' type='admin' func='pagemanager'}">{img modname=core src=button_cancel.png set=icons/extrasmall  __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>

{include file='Admin/Footer.tpl'}
