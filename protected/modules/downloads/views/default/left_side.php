
 
<?php 
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
					
						  foreach($roles as $role)
						   if(sizeof($roles)==1 and $role->name == 'parent')
						   {
		
		$this->renderPartial('/default/parentleft');
							}else if(sizeof($roles)==1 and $role->name == 'student')
						   {
        $this->renderPartial('/default/studentleft');
						   }else if(sizeof($roles)==1 and $role->name == 'teacher')
						   {
        $this->renderPartial('/default/teacherleft');
						   }
						   else{ ?>
<div id="othleft-sidebar">
                   <!--<div class="lsearch_bar">
             	<input name="" type="text" class="lsearch_bar_left" value="Search" />
                <input name="" type="button" class="sbut" />
                <div class="clear"></div>
  </div>-->
                    <?php
					function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
							  {
								  return $message;
							  }
			$this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
			//The Welcome Link
			//array('label'=>''.t('Welcome'),  'url'=>array('/message/index') ,'linkOptions'=>array('class'=>'menu_1' ), 'itemOptions'=>array('id'=>'menu_1') 
					       //),
						   
				
			// The MailBox Link
		
						/*array('label'=>t('Mailbox('.MailboxModule::getNewMsgs(Yii::app()->user->id).')<span>All Received Messages</span>'), 'url'=>array('/mailbox'),
								'active'=> ((Yii::app()->controller->module->id=='mailbox' and  Yii::app()->controller->id!='news') ? true : false),'linkOptions'=>array('class'=>'inbox_ico')),
								
								array('label'=>t('News<span>All Site News</span>'), 'url'=>array('/mailbox/news'),
								'active'=> ((Yii::app()->controller->id=='news') ? true : false),'linkOptions'=>array('class'=>'inbox_ico')),*/
								
			
				
			//The Events Link
			//'label'=>''.t('Events'), 'url'=>'javascript:void(0);', 'itemOptions'=>array('id'=>'menu_2'),
			array('label'=>'<h1>'.Yii::t('app','File Uploads').'</h1>'),
			array('label'=>Yii::t('app','All Uploads').'<span>'.Yii::t('app','View All File Uploads').'</span>', 'url'=>array('/downloads'),'active'=> (((Yii::app()->controller->module->id=='downloads' and Yii::app()->controller->id=='fileUploads' and Yii::app()->controller->action->id=='index')) ? true : false),'linkOptions'=>array('class'=>'all-update_ico')),
			array('label'=>Yii::t('app','Create File Uploads').'<span>'.Yii::t('app','Create File Uploads').'</span>' , 'url'=>array('fileUploads/create'),'active'=> ((Yii::app()->controller->module->id=='downloads' and Yii::app()->controller->id=='fileUploads'and Yii::app()->controller->action->id=='create') ? true : false),'linkOptions'=>array('class'=>'createfile-upload_ico')),
			array('label'=>Yii::t('app','Manage File Uploads').'<span>'.Yii::t('app','Manage File Uploads').'</span>'  , 'url'=>array('fileUploads/admin'),'active'=> ((Yii::app()->controller->module->id=='downloads' and Yii::app()->controller->id=='fileUploads'and (Yii::app()->controller->action->id=='admin' or Yii::app()->controller->action->id=='view' or Yii::app()->controller->action->id=='update')) ? true : false),'linkOptions'=>array('class'=>'managfile-upload_ico')),
			array('label'=>'<h1>'.Yii::t('app','File Category').'</h1>'),
			array('label'=>Yii::t('app','Create File Category').'<span>'.Yii::t('app','Create File Categories').'</span>', 'url'=>array('/downloads/fileCategory/create'),'active'=> ((Yii::app()->controller->module->id=='downloads' and Yii::app()->controller->id=='fileCategory' and Yii::app()->controller->action->id=='create') ? true : false),'linkOptions'=>array('class'=>'createfile-catgry_ico ')),	
			array('label'=>Yii::t('app','Manage File Category').'<span>'.Yii::t('app','Manage File Categories').'</span>', 'url'=>array('/downloads/fileCategory/admin'),'active'=> ((Yii::app()->controller->module->id=='downloads' and Yii::app()->controller->id=='fileCategory' and (Yii::app()->controller->action->id=='admin' or Yii::app()->controller->action->id=='view' or Yii::app()->controller->action->id=='update')) ? true : false),'linkOptions'=>array('class'=>'managefile-catgry_icor_ico')),			   
				),
				
			)); ?>
		
		</div>
        
        <?php } ?>
        <script type="text/javascript">

	$(document).ready(function () {
            //Hide the second level menu
            $('#othleft-sidebar ul li ul').hide();            
            //Show the second level menu if an item inside it active
            $('li.list_active').parent("ul").show();
            
            $('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
                
                 if($(this).parent().children('ul').length>0){                  
                    $(this).parent().children('ul').toggle();    
                 }
                 
            });
          
            
        });
    </script>