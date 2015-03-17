
{pageaddvar name="stylesheet" value="modules/PageZensor/styles/scribite_config.css"}

{pageaddvar name="javascript" value="modules/PageZensor/javascript/Editor.js"}

{include file='Admin/Header.tpl' __title='Editor' icon='xedit' titlename=$page.title}
{checkpermission component="PageZensor::" instance="::" level=ACCESS_EDIT assign=confirmator}
{form cssClass="z-form"}
    <fieldset>
        <legend>{gt text='Page information'}</legend>
        {formvalidationsummary}
        <div class="z-formrow">
            <label for="title">{gt text='Title'}</label>
            {formtextinput id="title" maxLength=300 mandatory=true text=$page.title}
        </div>

        <div class="z-formrow" id="editor_maininput">
            <label for="text">{gt text='Content'}</label>
            <div class="z-formnote">
            	{formtextinput id="text" mandatory=true rows=10 textMode='multiline' text=$page.text}
            </div>
        </div>
        <div class="z-formnote" id="html_school_link">
            <a href="{modurl modname='PageZensor' type='admin' func='html_school_edit'  pid=$page.pid}" class="z-button">{img src='help.png' modname='core' set='icons/extrasmall'} {gt text='little html - school'}</a>
        </div>
<div class="z-formrow">
            <label for="text">{gt text='What has changed?'}</label>
            {formtextinput id="edit_comment"  cssClass="noeditor" maxLength=3000 mandatory=true rows=10 textMode='multiline' text=$page.edit_comment mandatory=true}
        </div>
        {if $page.refuse_comment}
   <h2>{gt text="criticism"}</h2>
   {$page.refuse_comment}
{/if}
   <div class="z-formbuttons z-buttons">
       {formbutton class="z-bt-ok" commandName="save" __text="Save"}
       {if $confirmator}
       	{formbutton class="z-bt-ok" commandName="saveredaktion" __text="Save redactionaly"}
       {/if}
       {formbutton class="z-bt-cancel" commandName="cancel" __text="Cancel"}
       <br />
       <a onclick="ResetConfirm({$page.pid})" class="z-button" href="javascript:void(0)">{img src='agt_reload.png' modname='core' set='icons/extrasmall'}{gt text="Reset to confirming"} </a>
   </div>
    </fieldset>
{/form}
{notifydisplayhooks eventname='pagezensor.ui_hooks.edit_page.form_edit' id=1 assign='hooks'}
{if is_array($hooks) && count($hooks)}
	<script>
		document.getElementById("html_school_link").style="display:none";
	</script>
    {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
    {/foreach}
{/if}
{include file='Admin/Footer.tpl'}


