<?php
/**
 * Datasheete.
 *
 * @copyright Sascha Rösler (SR)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package PageZensor
 * @author Sascha Rösler <i-loko@t-online.de>.
 * @link http://github.com/sarom5
 * @link http://zikula.org
 */

/**
 * Account api class.
 */
class PageZensor_Api_Account extends Zikula_AbstractApi
{
    /**
     * Return an array of items to show in the your account panel.
     *
     * @param array $args List of arguments.
     *
     * @return array List of collected account items
     */
    public function getall(array $args = array())
    {
        // collect items in an array
        $items = array();
        $permission=0;
        if(SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_EDIT))
        	$permission=1;
        	
        else
        {
        	$pages = $this->entityManager->getRepository('PageZensor_Entity_PageZenDisplay')->findBy(array());
        	$mypermission;
        	foreach($pages as $page)
        	{
        		$pid=$page->getpid();
        		$mypermission='PageZensor::'.$pid;
        		if(SecurityUtil::checkPermission($mypermission, '::', ACCESS_MODERATE))
        			$permission=1;
        	}
        }
        // Create an array of links to return
        if ($permission==1) {
            $items[] = array(
                'url'   => ModUtil::url($this->name, 'admin', 'main'),
                'title' => $this->__('Page creation'),
                'icon'   => 'admin.png',
                'module' => 'PageZensor'
            );
        }
    
        // return the items
        return $items;
    }
}
