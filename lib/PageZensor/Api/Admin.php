<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class PageZensor_Api_Admin extends Zikula_AbstractApi
{
	/**
	 * @brief Get available admin panel links
	 *
	 * @return array array of admin links
	 */
	public function getlinks()
	{
		$links = array();
		$links[] = array(
			'url'=> ModUtil::url('PageZensor', 'admin', 'main'),
			'text'  => $this->__('Main'),
			'title' => $this->__('Main'),
			'class' => 'z-icon-es-config',
		);
		
		$links[] = array(
			'url'=> ModUtil::url('PageZensor', 'admin', 'history'),
			'text'  => $this->__('History'),
			'title' => $this->__('History'),
			'class' => 'z-icon-es-cubes',
		);
		
		if (SecurityUtil::checkPermission('PageZensor::', '::', ACCESS_ADMIN)) {
			$links[] = array(
				'url'=> ModUtil::url('PageZensor', 'admin', 'pagemanager'),
				'text'  => $this->__('Page Manager'),
				'title' => $this->__('Add and delete pages'),
				'class' => 'z-icon-es-display',
			);
		}
		
		$links[] = array(
				'url'=> ModUtil::url('PageZensor', 'admin', 'html_school_main'),
				'text'  => $this->__('Little HTLM- School'),
				'title' => $this->__('Learn HTML, to edit pages professionally'),
				'class' => 'z-icon-es-help',
			);
			
		$links[] = array(
				'url'=> ModUtil::url('PageZensor', 'admin', 'help'),
				'text'  => $this->__('Help'),
				'title' => $this->__('Help'),
				'class' => 'z-icon-es-help',
			);
			
		return $links;
	}	
}
