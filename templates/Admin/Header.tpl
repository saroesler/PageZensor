{include file='Header.tpl'}
{adminheader}

<div class="z-admin-content-pagetitle">
	{if $icon != ""}
   		{icon type=$icon size="small"}
   	{/if}
   	{if $titlename}
    	<h3>{$title}: {$titlename}</h3>
    {else}
    	<h3>{$title}</h3>
    {/if}
</div>
