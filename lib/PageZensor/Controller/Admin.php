<?php

/**
 * This is the admin controller class providing navigation and interaction functionality.
 */
class PageZensor_Controller_Admin extends Zikula_AbstractController
{
    /**
     * @brief Main function.
     * @throws Different views according to the access
     * @return template Admin/Main.tpl
     * 
     * @author Sascha Rösler
     */
     
     /*
     *Security: access ADmin: add and remove sites
     *			access Moderate: confirm pages
     *			access Edit: edit pages
     */
    public function main()
    {
    //look for unconfirmed pages
    $changes=0;
    	if(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_EDIT))
    	{
			$edit_flag = 1;
			$em = $this->getService('doctrine.entitymanager');
			$qb = $em->createQueryBuilder();
			$qb->select('p')
			->from('PageZensor_Entity_PageZenEdit', 'p')
			->where('p.edit_flag = :edit_flag')
			->setParameter('edit_flag', $edit_flag)
			->orderBy('p.editdate', 'DESC')
			->setMaxResults(100);
			$changes = $qb->getQuery()->getArrayResult();
		}

	
			
		/*
		*look for pages, witch are refused for this user
		*/
		$edit_flag = 2;
		$em = $this->getService('doctrine.entitymanager');
		$qb = $em->createQueryBuilder();
		$qb->select('p')
		->from('PageZensor_Entity_PageZenEdit', 'p')
		->where('p.edit_flag = :edit_flag')
		->setParameter('edit_flag', $edit_flag)
		->orderBy('p.editdate', 'DESC')
		->setMaxResults(100);
		$refuses = $qb->getQuery()->getResult();
		$myrefuses=array();
		if($refuses)
			foreach($refuses as $refuse)
			{
				$pid=$refuse-> getpid();
				$permission='PageZensor::'.$pid;
				if(SecurityUtil::checkPermission($permission, '::', ACCESS_MODERATE))
				{
					$myrefuses[]=array(
					'pid'=> $pid,
					'title'=> $refuse-> gettitle(),
					'date'=> $refuse-> geteditdateFormatted()
					);
		
				}
			}
		
		/*
		*look for pages, witch changes are pending for this user
		*/
		$edit_flag = 1;
		$em = $this->getService('doctrine.entitymanager');
		$qb = $em->createQueryBuilder();
		$qb->select('p')
		->from('PageZensor_Entity_PageZenEdit', 'p')
		->where('p.edit_flag = :edit_flag')
		->setParameter('edit_flag', $edit_flag)
		->orderBy('p.editdate', 'DESC')
		->setMaxResults(100);
		$pending_changes = $qb->getQuery()->getResult();
		$mypending_changes=array();
		if($pending_changes)
			foreach($pending_changes as $pending_change)
			{
				$pid=$pending_change->getpid();
				$permission='PageZensor::'.$pid;
				if(SecurityUtil::checkPermission($permission, '::', ACCESS_MODERATE))
				{
					$mypending_changes[]=array(
					'pid'=> $pid,
					'title'=> $pending_change-> gettitle(),
					'date'=> $pending_change-> geteditdateFormatted()
					);
				}
			}
			
		//divide all pages in pages, the user is allowed to write and pages the user is only allowed to read
    	$pages = $this->entityManager->getRepository('PageZensor_Entity_PageZenDisplay')->findBy(array(),array('title'=>'ASC'));
    	$mypage= array();
		$otherpage= array();
    	foreach($pages as $page)
    	{
			
			$pid=$page->getpid();
			$permission='PageZensor::'.$pid;
			if(SecurityUtil::checkPermission($permission, '::', ACCESS_MODERATE))
			{
				$mypage[]=array(
				'pid'=> $pid,
				'title'=> $page-> gettitle(),
				'date'=> $page-> getpublishingdateFormatted()
				);
		
			}
			else
			{
				$otherpage[]=array(
				'pid'=> $pid,
				'title'=> $page-> gettitle(),
				'date'=> $page-> getpublishingdateFormatted()
				);		
			}
		}
     	return $this->view
     	->assign('mychanges',$mypending_changes)
     	->assign('myrefuses',$myrefuses)
     	->assign('changes',$changes)
     	->assign('mypages',$mypage)
		->assign('otherpages',$otherpage)
		->fetch('Admin/Main.tpl');
    }
    
    /**
     * @brief Maincontroller function.
     * @throws process the user commands and redirect to other pages
     * @return different templates
     * 
     * @author Sascha Rösler
     */
    public function Maincontroller()
    {
    	$action = FormUtil::getPassedValue('action', null, 'POST');
    	$pid = FormUtil::getPassedValue('id', null, 'POST');
    	$permission='PageZensor::'.$pid;
    	switch ($action)
    	{
    		case('display_edit'):
    			if((SecurityUtil::checkPermission($permission, '::', ACCESS_MODERATE))||(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_MODERATE)))
    				$this->redirect(ModUtil::url($this->name, 'admin', 'display_edit',array('pid'=>$pid)));
    			break;
    			
    		case('display_view'):
    				$this->redirect(ModUtil::url($this->name, 'user', 'display',array('pid'=>$pid)));
    			break;
    			
    		case('edit'):
    			if((SecurityUtil::checkPermission($permission, '::', ACCESS_MODERATE)))
    				$this->redirect(ModUtil::url($this->name, 'admin', 'edit',array('pid'=>$pid)));
    			break;
    	}
    }
    
    /**
     * @brief Pagemanager function.
     * @throws help to administrate the pages. only ACCESS_ADMIN
     * @return template Admin/page_manager.tpl
     * 
     * @author Sascha Rösler
     */
    public function pagemanager()
    {
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('PageZensor::', '::', ACCESS_ADMIN));
    	
    	$mainpage = $this->entityManager->find('PageZensor_Entity_PageZenMainredirect', 1);
    	$pages = $this->entityManager->getRepository('PageZensor_Entity_PageZenEdit')->findBy(array());
    	return $this->view
    		->assign('pages', $pages)
    		->assign('mainpage', $mainpage)
    		->fetch('Admin/page_manager.tpl');
    }
    
     /**
     * @brief pagee add function.
     * @throws Zikula_Forbidden If not ACCESS_ADMIN
     * @return redirect self::pageesView()
     */
    public function Pageadd()
    {
    	//userid to write it down in the colum
    	$uid = SessionUtil::getVar('uid');
		Loader::loadClass('UserUtil');
		
    	$this->throwForbiddenUnless(SecurityUtil::checkPermission('PageZensor::', '::', ACCESS_ADMIN));
    	$action = FormUtil::getPassedValue('action', null, 'POST');
    	switch($action)
    	{
    	//add a page and writes down a standart text
    	//both colums have to have the same pid for every page, so the manager creats both entities
    	case 'add':
    		$title = FormUtil::getPassedValue('intitle', null, 'POST');
    	
			if($title == "")
				return LogUtil::RegisterError($this->__("The added Page has no title."), null, ModUtil::url($this->name, 'admin', 'pagemanager'));
			
			$page = new PageZensor_Entity_PageZenEdit();
			$page->settitle($title);
			$page->settext('<h1>'.$title.'</h1>');
			$page->seteditdate(date("Y-m-d H:i:s"));
			$page->setuid_edit($uid);
			$page->setedit_flag(1);
			$page->setrefuse_comment("");
			$page->setedit_comment("New page");
			$this->entityManager->persist($page);
			$this->entityManager->flush();
			
			$page = new PageZensor_Entity_PageZenDisplay();
			$page->settitle($title);
			$page->settext('<h1>'.$title.'</h1>');
			$page->seteditdate(date("Y-m-d H:i:s"));
			$page->setpublishingdate(date("Y-m-d H:i:s"));
			$page->setuid_edit($uid);
			$page->setuid_publish($uid);
			$this->entityManager->persist($page);
			$this->entityManager->flush();
			
			LogUtil::RegisterStatus($this->__("page has been added successfully."));
			break;
			
			//deleate a page 
		case 'del':
			
			break;
			
			//set a page to the mainpage		
		case 'main':
			$actionid = FormUtil::getPassedValue('id',null,'POST');
			$page_main = $this->entityManager->find('PageZensor_Entity_PageZenMainredirect', 1);
			//if db- entry is set
			if(!$page_main)
				$page_main = new PageZensor_Entity_PageZenMainredirect();
			$page_main->setpid($actionid);
			$this->entityManager->persist($page_main);
			$this->entityManager->flush();
				
		}
    	$this->redirect(ModUtil::url($this->name, 'admin', 'pagemanager'));
    } 
    
    public function del_request()
    {
    	if (!SecurityUtil::checkPermission('PageZensor::', '::', ACCESS_ADMIN)) {
    	    return LogUtil::registerPermissionError();
    	}
    	$actionid = FormUtil::getPassedValue('id',null,'GET');
    	$page = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $actionid);
     	return $this->view
     	->assign('page',$page)
		->fetch('Admin/del_request.tpl');
    }
    
    public function delete()
    {
    if (!SecurityUtil::checkPermission('PageZensor::', '::', ACCESS_ADMIN)) {
    	return LogUtil::registerPermissionError();
	}
    $actionid = FormUtil::getPassedValue('id',null,'GET');
	if( $actionid=="")
		return LogUtil::RegisterError($this->__("ID is missing."), null, ModUtil::url($this->name, 'admin','pagemanager'));
	$pageedit = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $actionid);
	$pagedisplay = $this->entityManager->find('PageZensor_Entity_PageZenDisplay', $actionid);
	
	$this->entityManager->remove($pageedit);
	$this->entityManager->remove($pagedisplay);
	$this->entityManager->flush();
	LogUtil::RegisterStatus($this->__("page has been removed successfully."));
	$this->redirect(ModUtil::url($this->name, 'admin', 'pagemanager'));
    }
     /**
     * @brief edit function.
     * @throws Zikula_Forbidden If not ACCESS_MODERATE
     * @return template Admin/edit.tpl
     */
     //activate the edithandler
    public function edit()
	{
		$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
    	if (!SecurityUtil::checkPermission('PageZensor::'.$pid, '::', ACCESS_MODERATE)) {
    	    return LogUtil::registerPermissionError();
    	}
	
		
	    $form = FormUtil::newForm('PageZensor', $this);
	    return $form->execute('Admin/edit.tpl', new PageZensor_Handler_Edit());
	}
    
     /**
     * @brief display_edit function.
     * @throws Zikula_Forbidden If not ACCESS_EDIT or ACCESS_MODERATE
     * @return template Admin/display_page_edit.tpl
     */    
     /*This funktion shows the page as preview. It shows the changed site in case of not comfirmation, too*/
    public function display_edit()
    {
    	$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
    	//show edit, if it is allowed 
    	if((SecurityUtil::checkPermission("PageZensor::".$pid, '::', ACCESS_MODERATE))||(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_EDIT)))
			if($pid!=0)
			{
				$page = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $pid);
				$uid = $page->getuid_edit();
				$user = UserUtil::getPNUser($uid);
				$permission="PageZensor::".$pid;
				return $this->view
				->assign('user', $user)
				->assign('page',$page)
				->fetch('Admin/display_page_edit.tpl');
			}
    }
    
     /**
     * @brief Pageconfirm function.
     * @throws Zikula_Forbidden If not ACCESS_EDIT
     * @return redirect self::main()
     */
     //This function confirmes the edited site and publish it, putting in Page_Zensor_Display and reset the Edit-flag to 0
    public function Pageconfirm()
    {	
    	if(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_EDIT))
    	{
			$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
			$page_edit = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $pid);
			$page_edit->setrefuse_comment("");
			$page_edit->setedit_comment("");
			$page_edit->setedit_flag(0);

			$this->entityManager->persist($page_edit);

			$uid = SessionUtil::getVar('uid');
			Loader::loadClass('UserUtil');
		
			$page_display = $this->entityManager->find('PageZensor_Entity_PageZenDisplay', $pid);
			
			$page_display->settitle($page_edit->gettitle());
			$page_display->settext($page_edit->gettext());
			$page_display->seteditdate($page_edit->geteditdateFormatted());
			$page_display->setpublishingdate(date("Y-m-d H:i:s"));
			$page_display->setuid_edit($page_edit->getuid_edit());
			$page_display->setuid_publish($uid);
			$this->entityManager->persist($page_display);
			$this->entityManager->flush();

		}

		$this->redirect(ModUtil::url($this->name, 'admin', 'main'));
    }
    
     /**
     * @brief Pagerefuse function.
     * @throws Zikula_Forbidden If not ACCESS_Edit
     * @return template Admin/display_page_refuse.tpl
     */
     //this function generates the refuse template
     public function Pagerefuse()
    {
        	if(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_EDIT))
		    {
				$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
					if($pid!=0)
					{
						$page = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $pid);
						$permission="PageZensor::".$pid;
						return $this->view
						->assign('page',$page)
						->fetch('Admin/display_page_refuse.tpl');
					}
			}
    }
    /**
     * @brief Refusemanager function.
     * @throws Zikula_Forbidden If not ACCESS_Edit
     * @return template Admin/display_page_refuse.tpl or self:main()
     */
     //this function set the editflag to refuse (=2) and writes the refuse command
    public function Refusemanager()
    {	
    	if(SecurityUtil::checkPermission("PageZensor::", '::', ACCESS_EDIT))
    	{
    		$action = FormUtil::getPassedValue('action', null, 'POST');
    		$refuse_comment = FormUtil::getPassedValue('refuse_comment', null, 'POST');
    		$pid = FormUtil::getPassedValue('pid', null, 'POST');
    		if($action=="OK")
    		{
    			//you have to write a comment, why the change isn't allowed
    			if(!$refuse_comment)
    			{
    				LogUtil::RegisterError($this->__("Please write the editor something about his problems."));
    				$this->redirect(ModUtil::url($this->name, 'admin', 'Pagerefuse', array('pid'=>$pid)));
    			}
				
				//send back
				$page_edit = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $pid);
				$page_edit->setedit_flag(2);
				$page_edit->setrefuse_comment($refuse_comment);

				$this->entityManager->persist($page_edit);

				$uid = SessionUtil::getVar('uid');
				Loader::loadClass('UserUtil');
	
				$this->entityManager->flush();
			}

		}

		$this->redirect(ModUtil::url($this->name, 'admin', 'main'));

    }
    
    public function history()
    {
    	
    	$datas = $this->entityManager->getRepository('PageZensor_Entity_PageZenEdit')->findBy(array(),array('editdate'=>'DESC'));
    	
    	$today = new DateTime();
    	
    	$week = array();
    	$month = array();
    	
    	foreach($datas as $data)
    	{
    		$dif  = $data->getEditdate()->diff($today);
    		$difnum = $dif->format('%a');
    		if($difnum <= 7)
    			$week[] = $data;
    		if($difnum <= 30)
    			$month[] = $data;
    	}
    	return $this->view
    		->assign('week', $week)
    		->assign('month', $month)
    		->assign('datas', $datas)
    		->fetch('Admin/history.tpl');
    }
    
     /**
     * @brief Html- school in edit function.
     * @throws 
     * @return template Admin/html_school.tpl
     */
    public function html_school_edit()
    {
    	$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
    	return $this->view
		->assign('pid',$pid)
		->fetch('Admin/html_school _edit.tpl');
    }
    
     /**
     * @brief Html- school in main function.
     * @throws 
     * @return template Admin/html_school.tpl
     */
    public function html_school_main()
    {
    	return $this->view
		->fetch('Admin/html_school _main.tpl');
    }
    
     /**
     * @brief Help function.
     * @throws 
     * @return template Admin/html_school.tpl
     */
    public function help()
    {
    	return $this->view
		->fetch('Admin/help.tpl');
    }
    
    /**
    * @copy the comfirmed Text into the edit text
    *
    **/
    
    public function ResetConfirm()
    {
    	if (!SecurityUtil::checkPermission('PageZensor::'.$pid, '::', ACCESS_MODERATE)) {
    	    return LogUtil::registerPermissionError();
    	}
    	
    	$pid = FormUtil::getPassedValue('pid', NULL, 'GET');
    	
    	if(!$pid)
		{
			LogUtil::RegisterError($this->__("There is no valid $pid"));
			$this->redirect(ModUtil::url($this->name, 'admin', 'main'));
		}
    	
    	//get database
    	$page_display = $this->entityManager->find('PageZensor_Entity_PageZenDisplay', $pid);
		$page_edit = $this->entityManager->find('PageZensor_Entity_PageZenEdit', $pid);
		
		//reset change info
		$page_edit->setrefuse_comment("");
		$page_edit->setedit_comment("");
		$page_edit->setedit_flag(0);
		
		$uid = SessionUtil::getVar('uid');
		Loader::loadClass('UserUtil');
		
		$page_edit->settext($page_display->gettext());
		$page_display->seteditdate(date("Y-m-d H:i:s"));
		$page_display->setuid_edit($uid);

		$this->entityManager->persist($page_edit);
		$this->entityManager->flush();
		
    	$this->redirect(ModUtil::url($this->name, 'admin', 'main'));
    }
}

