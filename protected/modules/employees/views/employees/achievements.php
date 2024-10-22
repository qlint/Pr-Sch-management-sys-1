<?php
$this->breadcrumbs=array(
	Yii::t('app','Teacher'),
	Yii::t('app','Achievements'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('profileleft');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <!--<div class="searchbx_area">
                    <div class="searchbx_cntnt">
                        <ul>
                            <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
                            <li><input class="textfieldcntnt"  name="" type="text" /></li>
                        </ul>
                    </div>
                
                </div>-->
                
                <h1><?php echo Yii::t('app','Teacher Profile');?></h1> 
                <div class="button-bg">
                <div class="top-hed-btn-left"></div>
                <div class="top-hed-btn-right">
                <ul>                                    
                <li><?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('update', 'id'=>$_REQUEST['id']),array('class'=>'a_tag-btn')); ?><!--<a class=" edit last" href="">Edit</a>--></li>
                <li><?php echo CHtml::link('<span>'.Yii::t('app','Teachers').'</span>', array('employees/manage'),array('class'=>'a_tag-btn')); ?><!--<a class=" edit last" href="">Edit</a>--></li>                                  
                </ul>
                </div>
                </div>
                
                <div class="clear"></div>
                 <!-- END div class="emp_right_contner" -->
                  <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
						<?php $this->renderPartial('application.modules.employees.views.employees.tab');?>
                         <div class="clear"></div>
                          <div class="emp_cntntbx">
                            <div class="document_table">
                            	<?php
							    $employee=Employees::model()->findByAttributes(array('id'=>$_REQUEST['id']));
								$documents = Achievements::model()->findAllByAttributes(array('user_id'=>$employee->id,'user_type'=>'2'));
								?>
                                <table width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                       <td align="center"><h2><?php echo Yii::t('app','Achievement Details'); ?></h2></td>
                                    </tr>
                                </tbody>
                                </table>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top:none;">
                                    <tr>
                                    <td align="center" width="125"><strong><?php echo Yii::t('app','Achievement Title'); ?></strong></td>
                                    <td align="center" width="150"><strong><?php echo Yii::t('app','Description'); ?></strong></td>
                                    <td align="center" width="100"><strong><?php echo Yii::t('app','Document Name'); ?></strong></td>                                    
                                    <td align="center" width="150"><strong><?php echo Yii::t('app','Actions'); ?></strong></td>
                                    </tr>
                                    	<?php
										$valid_image_types = array('image/jpeg','image/png','GIF'); 
                                    	if($documents) // If documents present
										{
											foreach($documents as $document) // Iterating the documents
											{
												$document_status= DocumentUploads::model()->fileStatus(6, $document->id, $document->file);     
												        
                   							 if($document_status==true)
                   							 {
												
										?>
                                                <tr>
                                                    <td align="center"><?php echo ucfirst($document->achievement_title);?></td>
                                                    <td align="center"><?php echo ucfirst($document->description);?></td>
                                                    <td align="center"><?php echo ucfirst($document->doc_title);?></td>
                                                    
                                                    <td align="center">
                                                    	<ul class="tt-wrapper">
                                                        	
                                                           
															<li>
                                                           		<?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('achievements/download','id'=>$document->id,'employee_id'=>$_REQUEST['id']),array('class'=>'tt-download')); ?>
															</li>
                                                            <?php
														 
														  if(in_array($document->file_type,$valid_image_types)) 
														  {
														  ?>
														 <li>
                                                         <?php
			                                            $path = 'uploadedfiles/employee_achievement_document/'.$document->user_id.'/'.$document->file;
	 	                                                echo '<li>
                                                             	<a class="tt-image" href="#"><span style="width:170px;height:140px; left:-30px;"><img  src="'.$path.'" width="170" height="140" /></span></a>	</li>';
														  }?>
                                                         
														 </li>
														 
														
                                                           
															 <li>
                                                             	<?php	
																echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('achievements/update','id'=>$document->id,'employee_id'=>$_REQUEST['id']),array('class'=>'tt-edit')); 
																?>
															</li>
															
															
															<li>
                                                           
																<?php echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', array('achievements/deletes','id'=>$document->id,'employee_id'=>$_REQUEST['id']),array('class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'))); ?>
															</li>
                                                          
                                                        </ul>
                                                    </td>
												</tr>
                                        <?php
											 }
											} // End foreach($documents as $document)
										}
										else // If no documents present
										{
										?>
                                            <tr>
                                                <td colspan="3" style="text-align:center;"><?php echo Yii::t('app','No document(s) uploaded'); ?></td>
												<td></td>
                                            </tr>
                                        <?php
										}
										?>
                                    </table>
                              
                            </div>
                           </div> <!-- END div class="emp_cntntbx" -->
                            <div style="width:712px;">
                                    <?php
                                     $document = new Achievements;
                                     echo $this->renderPartial('/achievements/_form', array('model'=>$document)); 
                                    ?>
                              </div>
                             
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>

<script type="text/javascript">
/*
$( document ).ready(function() {
 
	$("#cmnt_button").click(function()
	{
    var dataString = $("#comment_text").val();

           $.ajax({
             type: "POST",
             url: <?php echo CJavaScript::encode(Yii::app()->createUrl('students/logcomment/create'))?>,
             data: {'data':$("#log-form").serialize(),'id':<?php echo $_REQUEST['id']; ?>,'user_id':<?php echo Yii::app()->user->id ?>},
             success: function(result){
			
		
             $("#outer_div").prepend(result);
			 
			 $("#comment_text").val('');
			 
			 			 
			 //$("#comment_count").html(result2);
			 
				
              }
           });   
    });
	
	
});*/

</script>      
