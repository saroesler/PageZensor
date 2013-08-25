{include file='Admin/Header.tpl' __title='Main' icon='config'}

<!----------------------------show changes to confirm-------------------------------------------->
<div style="margin-left:10px">
	<form class="z-form" method="post" action="{modurl modname='PageZensor' type='Admin' func='Maincontroller'}">
		{checkpermission component="PageZensor::" instance="::" level=ACCESS_EDIT assign=Changes_allowed}
		{if $Changes_allowed}
			<h3>{gt text="Changes"}</h3>
			{if $changes}
				<table class="z-datatable">
					<thead>
						<tr>
							<th>{gt text='Title'}</th>
							<th>{gt text='Date'}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$changes item='change'}
							<tr><td><a  href="{modurl modname='page_Zensor' type='user' func='display' pid=$change.pid}">{$change.title} {$change.date}</a></td>
								<td>{$change.editdate->format('d.m.Y H:i')}</td>
								 <td><button onclick="document.getElementById('action').value = 'display_edit'; document.getElementById('id').value = {$change.pid};">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button></a></td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			{else}
				<p>{gt text="No changes to confirm"}</p>
			{/if}
			<br/><br/>
		{/if}

		<!-------------------------show refused pages---------------------------------->
		<h3>{gt text="Refused pages"}</h3>
		{if $myrefuses}
			<table class="z-datatable">
				<thead>
					<tr>
						<th>{gt text='Title'}</th>
						<th>{gt text='Date'}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$myrefuses item='myrefuse'}
						<tr><td><a  href="{modurl modname='page_Zensor' type='user' func='display' pid=$mypage.pid}">{$myrefuse.title}</a></td>
							<td>{$myrefuse.date}</td>
							<td>
							<button onclick="document.getElementById('action').value = 'display_edit'; document.getElementById('id').value = {$myrefuse.pid};">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button>
							<button onclick="document.getElementById('action').value = 'display_view'; document.getElementById('id').value = {$myrefuse.pid};">{img src='14_layer_visible.png' modname='core' set='icons/extrasmall'}{gt text="Display View"}</button>
							<button onclick="document.getElementById('action').value = 'edit'; document.getElementById('id').value = {$myrefuse.pid};">{img src='xedit.png' modname='core' set='icons/extrasmall'}{gt text="Edit"}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{gt text="No refused page"}
		{/if}
		<br/><br/>

		<!-------------------------show pending changes---------------------------------->
		<h3>{gt text="pending changes"}</h3>
		{if $mychanges}
			<table class="z-datatable">
				<thead>
					<tr>
						<th>{gt text='Title'}</th>
						<th>{gt text='Date'}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$mychanges item='mychange'}
						<tr><td><a  href="{modurl modname='page_Zensor' type='user' func='display' pid=$mypage.pid}">{$mychange.title}</a></td>
							<td>{$mychange.date}</td>
							<td>
							<button onclick="document.getElementById('action').value = 'display_edit'; document.getElementById('id').value = {$mychange.pid};">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button>
							<button onclick="document.getElementById('action').value = 'display_view'; document.getElementById('id').value = {$mychange.pid};">{img src='14_layer_visible.png' modname='core' set='icons/extrasmall'}{gt text="Display View"}</button>
							<button onclick="document.getElementById('action').value = 'edit'; document.getElementById('id').value = {$mychange.pid};">{img src='xedit.png' modname='core' set='icons/extrasmall'}{gt text="Edit"}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{gt text="No pending changes"}
		{/if}
		<br/><br/>

		<!-------------------------show my pages---------------------------------->
		<h3>{gt text="My pages"}</h3>
		<table class="z-datatable">
			<thead>
				<tr>
					<th>{gt text='Title'}</th>
					<th>{gt text='Date'}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$mypages item='mypage'}
					<tr><td><a  href="{modurl modname='page_Zensor' type='user' func='display' pid=$mypage.pid}">{$mypage.title}</a></td>
						<td>{$mypage.date}</td>
						<td>
						<button onclick="document.getElementById('action').value = 'display_edit'; document.getElementById('id').value = {$mypage.pid};">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button>
						<button onclick="document.getElementById('action').value = 'display_view'; document.getElementById('id').value = {$mypage.pid};">{img src='14_layer_visible.png' modname='core' set='icons/extrasmall'}{gt text="Display View"}</button>
						<button onclick="document.getElementById('action').value = 'edit'; document.getElementById('id').value = {$mypage.pid};">{img src='xedit.png' modname='core' set='icons/extrasmall'}{gt text="Edit"}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<br/><br/>

		<!-------------------------show all other pages---------------------------------->
		<h3>{gt text="Other pages"}</h3>
		<table class="z-datatable">
			<thead>
				<tr>
					<th>{gt text='Title'}</th>
					<th>{gt text='Date'}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$otherpages item='otherpage'}
					<tr>
					<td><a  href="{modurl modname='page_Zensor' type='user' func='display' pid=$otherpage.pid}">{$otherpage.title}</a></td>
					<td>{$otherpage.date}</td>
					<td><button onclick="document.getElementById('action').value = 'display_view'; document.getElementById('id').value = {$otherpage.pid};">{img src='14_layer_visible.png' modname='core' set='icons/extrasmall'}{gt text="Display View"}</button></a></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<input name="action" id="action" type="hidden" />
		<input name="id" id="id" type="hidden" />
	</form>
</div>
{include file='Admin/Footer.tpl'}
