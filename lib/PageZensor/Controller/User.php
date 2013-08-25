<?php
/**
 * This is the User controller class providing navigation and interaction functionality.
 */
class PageZensor_Controller_User extends Zikula_AbstractController
{
    /**
     * @brief Main function.
     * @return string
     * 
     * @authorSascha Rösler
     */
    public function main()
    {
         $page = $this->entityManager->find('PageZensor_Entity_PageZenMainredirect', 1);
         if($page)
         	$pid=$page->getpid();
         else
         	$pid=1;
         $this->redirect(ModUtil::url($this->name, 'user', 'display',array('pid'=>$pid)));
    }
    
    public function display()
    {
    	$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
    	if($pid!=0)
    	{
    		$page = $this->entityManager->find('PageZensor_Entity_PageZenDisplay', $pid);
    		$permission="PageZensor::".$pid;
    		return $this->view
    		->assign('permission',$permission)
    		->assign('page',$page)
    		->fetch('User/display_page.tpl');
    	}
    	
    	else
		{
		   $pages = $this->entityManager->getRepository('PageZensor_Entity_PageZenDisplay')->findBy(array());
		   return $this->view
    		->assign('pages',$pages)
    		->fetch('User/display_all.tpl');
		}
    	return true;
    }
}
