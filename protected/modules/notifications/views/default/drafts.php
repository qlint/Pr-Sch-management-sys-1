<?php 	$this->breadcrumbs=array(
		Yii::t('app','Notify'),
		Yii::t('app','Drafts'),);
	
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
                                              
                    	<div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Drafts');?></h1> 
                         <?php 
						 /*$criteria = new CDbCriteria;
						 $criteria->condition = 'is_mailshot=:x AND status=:y';
						 $criteria->params = array(':x'=>0,':y'=>0); 
						 $criteria->order = ('id DESC');
						 $model = EmailDrafts::model()->findAll($criteria);*/ ?>
                             
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
 <li>
                                    <?php echo CHtml::link(Yii::t('app','Delete All'), "#",array("submit" =>array('default/deletealldrafts'),array(),'confirm'=>Yii::t('app','Are You Sure?'), 'class'=>'a_tag-btn','csrf'=>true));?>
                                    </li>                                    
</ul>
</div> 
</div>
<div class="os-table  tablebx">
 <div class="tbl-grd"></div> 
<table class="inner-table" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
	<tr>
    	<th width="50"><?php echo Yii::t('app', 'Sl.No');?></th>
        <th><?php echo Yii::t('app', 'Subject');?></th>
        <th><?php echo Yii::t('app', 'Created on');?></th>
        <th><?php echo Yii::t('app', 'Actions');?></th>
    </tr>
    <?php
    if($model){
		if(isset($_REQUEST['page'])){
			$k	= ($pages->pageSize*$_REQUEST['page'])-14;
		}
		else{
			$k	= 1;
		}
		foreach($model as $models){
	?>
            <tr>
                <td><?php echo $k; ?></td>
                <td>
                	<?php 
						$sub	= $models->subject; 
						if(strlen($sub) > 40){
   							$sub = substr($sub, 0, 7) . '...'; 
						}
						echo CHtml::link(ucfirst($sub),array('default/senddraft','id'=>$models->id));
   					?>
                </td>
                <td>
                	<?php 
						$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
						if($settings != NULL){	
							$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));							
							date_default_timezone_set($timezone->timezone);
							$date 		= date($settings->displaydate,strtotime($models->created_on));	
							$time 		= date($settings->timeformat,strtotime($models->created_on));					
						}
						echo CHtml::encode($date.' '.$time);
					?>
                </td>
                <td>
                	<?php
						echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array("submit"=>array('default/deletedraft','id'=>$models->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));
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
        	<td class="nothing-found" colspan="4"><?php echo Yii::t('app', 'Nothing Found'); ?></td>
        </tr>
    <?php	
	}
	?>       
</tbody>

</table> 
</div>                       
                        
                         
                             
                            <!-- End div class="pagecon" --> 
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
<script type="text/javascript"> /* Checking and unchecking the SMS checkboxes. */
	$(document).ready(function(){
	
	 
	 $("#checkall").change(function(){ /* Check/Uncheck all SMS functions on enabling/disabling of SMS All */
		  if (this.checked) {
			$('.check').attr('checked', true);
		  }
		  else{
			$('.check').attr('checked', false);
		  }
	  });
	  
	}); 
</script>