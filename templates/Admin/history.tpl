{include file='Admin/Header.tpl' __title='History' icon='cubes'}

<script type="text/javascript">
	function Go (select) {
	  var wert = select.options[select.options.selectedIndex].value;
	  document.getElementById('history_week').style.display = "none";
	  document.getElementById('history_month').style.display = "none";
	  document.getElementById('history_all').style.display = "none";
	  
	  document.getElementById('history_'+wert).style.display = "block";
	  }
</script>

	<p>
		<select size="1" name="Auswahl" onchange="Go(this)" width="250">
			<option selected="selected" value="week">{gt text="Last 7 days"}</option>
			<option value="month">{gt text="Last 30 days"}</option>
			<option value="all">{gt text="All pages"}</option>
		</select>
	</p>
	<div style="display:block" id="history_week">
		<h3>{gt text="Last 7 days"}</h3>
		{if $week}
			<table class="z-datatable">
				<thead>
					<tr>
						<th>{gt text='Title'}</th>
						<th>{gt text='Date'}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$week item='day'}
						<tr><td><a  href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{$day->getTitle()} </a></td>
							<td>{$day->geteditdateFormatted()}</td>
							<td><a class="z-button" href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button></a></td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			<p>{gt text="No changes in the last 7 days!"}</p>
		{/if}
	</div>
	
	<div style="display:none" id="history_month">
		<h3>{gt text="Last 30 days"}</h3>
		{if $month}
			<table class="z-datatable">
				<thead>
					<tr>
						<th>{gt text='Title'}</th>
						<th>{gt text='Date'}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$month item='day'}
						<tr><td><a  href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{$day->getTitle()}</a></td>
							<td>{$day->geteditdateFormatted()}</td>
							<td><a class="z-button" href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button></a></td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			<p>{gt text="No changes in the last 30 days!"}</p>
		{/if}
	</div>
	
	<div style="display:none" id="history_all">
		<h3>{gt text="Changes"}</h3>
		{if $datas}
			<table class="z-datatable">
				<thead>
					<tr>
						<th>{gt text='Title'}</th>
						<th>{gt text='Date'}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$datas item='day'}
						<tr><td><a  href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{$day->getTitle()}</a></td>
							<td>{$day->geteditdateFormatted()}</td>
							<td><a class="z-button" href="{modurl modname='PageZensor' type='admin' func='display_edit' pid=$day.pid}">{img src='demo.png' modname='core' set='icons/extrasmall'}{gt text="Display Edit"}</button></a></td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			<p>{gt text="No pages!"}</p>
		{/if}
	</div>
	<br/><br/>
</div>
{include file='Admin/Footer.tpl'}
