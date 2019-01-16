<?php 	$this->breadcrumbs=array(
		Yii::t('app','Notify'),
		Yii::t('app','Sent Emails'),);
	
?>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">        
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">                                   
                    	<div  class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Sent Emails');?></h1> 
                              
                        
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>
                                    <?php echo CHtml::link('<span>'.Yii::t('app','Delete All').'</span>', "#", array("submit"=> array('default/deleteallsent'),array(),'confirm'=>Yii::t('app', 'Are You Sure?'),'class'=>'a_tag-btn' , 'csrf'=>true));?>
                                    
                                    </li>                                   
</ul>
</div> 

</div>

<div class="os-table  tablebx">
 <div class="tbl-grd"></div> 
<table class="inner-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
	<tr>
    	<th width="50"><?php echo Yii::t('app', 'Sl.No');?></th>
        <th><?php echo Yii::t('app', 'Subject');?></th>
        <th><?php echo Yii::t('app', 'Created On');?></th>
        <th><?php echo Yii::t('app', 'Actions');?></th>
    </tr>
    <?php
		if($model){
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			if(isset($_REQUEST['page'])){
				$k = ($pages->pageSize*$_REQUEST['page'])-14;
			}
			else{
				$k = 1;
			}
			foreach($model as $models){
				$sub		= $models->subject;if (strlen($sub) > 40)
   				$sub		= substr($sub, 0, 7) . '...';
				
				$timezone	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));										
				date_default_timezone_set($timezone->timezone);
				$date 		= date($settings->displaydate,strtotime($models->created_on));	
				$time 		= date($settings->timeformat,strtotime($models->created_on));
	?>
    			<tr>
                	<td><?php echo $k; ?></td>
                    <td><?php echo CHtml::link(ucfirst($sub), array('default/viewsent','id'=>$models->id)); ?></td>
                    <td><?php echo CHtml::encode($date.' '.$time); ?></td>
                    <td>
                    	<?php 
							echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array("submit"=>array('default/deletesent','id'=>$models->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));
						?>	
                    </td>
                </tr>
    <?php	
				$k++;		
			}
		}
		else{
	?>
    		<tr>
            	<td class="nothing-found" colspan="4"><?php echo Yii::t('app', 'No sent emails!'); ?></td>
            </tr>
    <?php	
		}
	?>
                           
</tbody>

</table> 
</div>

                        
                         
                          
                             <div class="clear"></div>
                            <div class="pagecon">
							<?php 
                              $this->widget('CLinkPager', array(
                              'currentPage'=>$pages->getCurrentPage(),
                              'itemCount'=>$item_count,
                              'pageSize'=>15,
                              'maxButtonCount'=>5,
                              //'nextPageLabel'=>'My text >',
                              'header'=>'',
                              'htmlOptions'=>array('class'=>'pages',"style"=>"margin:0px;"),
                            ));?>
                        </div>
                        </div>
    				</td></tr>
    			</tbody>
    		</table>
    	</td>
   </tr>
</table>
