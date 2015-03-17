<?php
/**
 * Version.
 */
class PageZensor_Version extends Zikula_AbstractVersion
{
    /**
     * Module meta data.
     *
     * @return array Module metadata.
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Page Zensor');
        $meta['description']    = $this->__("Page module with confirmation of every site by an administrator of the pages"); ///@todo description
        $meta['url']            = $this->__('PageZensor');
        $meta['version']        = '1.0.0';
        $meta['official']       = 0;
        $meta['author']         = 'Sascha Rösler';
        $meta['contact']        = 'sa-roesler@t-online.de';
        $meta['admin']          = 1;
        $meta['user']           = 1;
        $meta['securityschema'] = array(); ///@todo Security schema
        $meta['core_min'] = '1.3.0';
        $meta['core_max'] = '1.3.99';
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::SUBSCRIBER_CAPABLE] = array('enabled' => true);
        
        return $meta;
    }

 	/**
     * Set up hook subscriber and provider bundles 
     */
    protected function setupHookBundles()
    {
	$bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.pagezensor.ui_hooks.edit_page', 'ui_hooks', $this->__('pagezensor edit_page Hooks'));
	$bundle->addEvent('form_edit', 'pagezensor.ui_hooks.edit_page.form_edit');
	$this->registerHookSubscriberBundle($bundle);
	
	 $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.pagezensor.filter_hooks.users_view', 'filter_hooks', $this->__('Users view'));
        $bundle->addEvent('filter', 'pagezensor.filter_hooks.users_view');
        $this->registerHookSubscriberBundle($bundle);
    }
}
