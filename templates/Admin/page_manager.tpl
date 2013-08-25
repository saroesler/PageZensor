{include file='Admin/Header.tpl' __title='Page Manager' icon='display'}

<form class="z-form" method="post" action="{modurl modname='PageZensor' type='Admin' func='Pageadd'}">
	<table class="z-datatable">
		<thead>
			<tr>
				<th>{gt text='ID'}</th>
				<th>{gt text='Title'}</th>
				<th>{gt text='Date'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$pages item='page'}
				<tr>
					<td>{$page->getpid()}</td>
					<td>{$page->gettitle()}</td>
					<td>{$page->geteditdateformatted()}</td>
					<td><button onclick="document.getElementById('action').value = 'del'; document.getElementById('id').value = {$page->getpid()};">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall'}</button>
					{if $mainpage}
						{if $mainpage->getpid()!=$page->getpid()}
							<button onclick="document.getElementById('action').value = 'main'; document.getElementById('id').value = {$page->getpid()};">{img src='favorites.png' modname='core' set='icons/extrasmall'} {gt text="Mainpage"}</button>
						{/if}
					{else}
						<button onclick="document.getElementById('action').value = 'main'; document.getElementById('id').value = {$page->getpid()};">{img src='favorites.png' modname='core' set='icons/extrasmall'} {gt text="Mainpage"}</button>
					{/if}
					
					</td>
				</tr>
			{/foreach}
			<tr> 
				<td></td>
				<td><input type="text" name="intitle" /></td>
				<td>
					<button onclick="document.getElementById('action').value = 'add'">{img src='button_ok.png' modname='core' set='icons/extrasmall'}</button>
					<button onclick="document.getElementById('action').value = ''">{img src='button_cancel.png' modname='core' set='icons/extrasmall'}</button>
				</td>
			</tr>
		</tbody>
	</table>
	<input name="action" id="action" type="hidden" />
	<input name="id" id="id" type="hidden" />
</form>

{include file='Admin/Footer.tpl'}
