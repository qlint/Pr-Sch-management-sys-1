<style type="text/css">
.upload{
     height: 19px;
    left: 8px;
    position: absolute;
    top: 76px;
    width: 25px;
	background: url(images/camera_hover.png) no-repeat ;}
	
.upload:hover{ text-decoration:none;
  background-image: url(images/cmr.png);
  }	
  
  .document_table th{ color:#333;
  	font-size:14px !important;}

.loading_app{ background-image:url(images/loading_app.gif);
		height:30px;
		float:left;
		width:30px;
		margin-left:10px;
		display:none}
		
.prof_img{ margin:10px;
	position:relative;
	left: -8px;}

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js_plugins/jupload/jupload.js"></script>

        <?php $this->renderPartial('leftside');?> 
        <?php
		$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$employee_id = $employee->id;
		
		/*$student=Students::model()->findByAttributes(array('id'=>$guard->ward_id));*/
		
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		?>
        
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-user"></i><?php echo Yii::t('app','Profile');?><span><?php echo Yii::t('app','View your profile here');?></span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Profile');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    
        <div class="contentpanel">
        	
            	
       		  <div class="people-item">
              		<div class="media">
                      
                   <div class="prof_img"> 
                  
                
                    <a href="javascript:void(0);" class="pull-left">
                     <?php
                     if($employee->photo_file_name!=NULL)
                     { 
					 	$path = Employees::model()->getProfileImagePath($employee->id);
                        echo '<img class=" thumbnail"  src="'.$path.'" width="100" height="103" />';
                    }
                    elseif($employee->gender=='M')
                    {
                        echo '<img class="thumbnail media-object"  src="images/portal/prof-img_male.png" alt='.$employee->first_name.' width="100" height="103" />'; 
                    }
                    elseif($employee->gender=='F')
                    {
                        echo '<img class="thumbnail media-object"  src="images/portal/prof-img_female.png" alt='.$employee->first_name.' width="100" height="103" />';
                    }
                    ?>                           
                            </a>
                           
                            
                            
                            
                            
                             <a href="javascript:void(0)" id="emp_image" data-url=""><div class="upload"></div></a><div id="displayPercentage" style="position:absolute;
                    top:30px; left:30px"><div class="loading_app" ></div><div id="percentage" style="color:#FFF !important; font-size:14px; text-shadow:0px 0px 2px #000; color:#fff"></div></div>
                  </div>  
                   
                            <div class="media-body">
                              <h4 class="person-name"><?php echo ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);?></h4>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Job Title').' :';?></strong>
                                        <?php 
					//$posts=Batches::model()->findByPk($employee->job_title);
					echo $employee->job_title;
					?></div>
                              <div class="text-muted"> <strong><?php echo Yii::t('app','Department').' :';?></strong> <?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
		 				echo $department->name;?></div>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Teacher No').' :';?></strong> <?php echo $employee->employee_number; ?></div>
                             
                              
                            </div>
                            <div class="edit_bttns pull-right">
                                <ul>
                                    <li>
                                        <?php echo CHtml::link('<span>'.Yii::t('app','Edit Profile').'</span>',array('editprofile'),array('class'=>'addbttn last'));?>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                          </div>
    
               	</div>
                <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Profile Details');?></h3>
                        </div>
                
                <div class="people-item">
         			<div class="table-responsive">
                	<table width="100%" class="table table-hover mb30" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                      <tr>
                        <td ><strong><?php echo Yii::t('app','Joining Date');?></strong></td>
                        <td><?php 
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($employee->joining_date));
									echo $date1;
								}
								else
								echo $employee->joining_date;?></td>
                        <td><strong><?php echo Yii::t('app','Category');?></strong></td>
                        <td><?php $cat=EmployeeCategories::model()->findByAttributes(array('id'=>$employee->employee_category_id));
							  if($cat!=NULL)
							  {
							  echo $cat->name;	
							  }
							  ?>
						</td>
                        <?php /*?><td><strong><?php echo Yii::t('teachersportal','City');?></strong></td>
                        <td><?php echo $employee->home_city; ?></td><?php */?>
                      </tr>
                    
                      <tr>
                        <td ><strong><?php echo Yii::t('app','Position');?></strong></td>
                        <td><?php $pos=EmployeePositions::model()->findByAttributes(array('id'=>$employee->employee_position_id));
							  if($pos!=NULL)
							  {
							  echo $pos->name;	
							  }
							  ?>
						</td>
                         <td ><strong><?php echo Yii::t('app','Grade');?></strong></td>
                        <td><?php $grd=EmployeeGrades::model()->findByAttributes(array('id'=>$employee->employee_grade_id));
							  if($grd!=NULL)
							  {
							  echo $grd->name;	
							  }
							  ?>
						</td>
                        
                      </tr>
                      <tr>
                      	<td><strong><?php echo Yii::t('app','Date of Birth');?></strong></td>
                        <td><?php 
						if($settings!=NULL)
								{
								$date1=date($settings->displaydate,strtotime($employee->date_of_birth));
								echo $date1;
								}
								else
								echo $employee->date_of_birth;
						?></td>
                        <td><strong><?php echo Yii::t('app','Gender');?></strong></td>
                        <td>
                        <?php if($employee->gender=='M')
							echo 'Male';
							else 
							echo 'Female'; ?></td>
                        <?php /*?><td> <strong><?php echo Yii::t('teachersportal','Birth Place');?></strong></td>
                        <td><?php echo $student->birth_place; ?></td><?php */?>
                        
                      </tr>
                      <tr>
                      	<td><strong><?php echo Yii::t('app','Blood Group');?></strong></td>
                        <td><?php echo $employee->blood_group; ?></td>
                        <td><strong><?php echo Yii::t('app','Nationality');?></strong></td>
                        <td><?php $natio_id=Nationality::model()->findByAttributes(array('id'=>$employee->nationality_id));
								echo $natio_id->name; ?></td>
                        <?php /*?><td><strong><?php echo Yii::t('teachersportal','State');?></strong></td>
                        <td><?php echo $employee-> 	home_state; ?></td><?php */?>
                        <?php /*?><td><strong><?php echo Yii::t('teachersportal','Country');?></strong></td>
                        <td><?php $count = Countries::model()->findByAttributes(array('id'=>$student->country_id));
							if(count($count)!=0)
							echo $count->name;  ?></td><?php */?>
                      </tr>
                      <tr>
                      	
                       
                        <td><strong><?php echo Yii::t('app','Qualification');?></strong></td>
                        
                        <td><?php echo $employee->qualification; ?></td>
                     
                      
                      	<td><strong><?php echo Yii::t('app','Experience');?>  </strong></td>
                        <td><?php 	if($employee->experience_year!=NULL and $employee->experience_year!=0){
										echo $employee->experience_year.' '.Yii::t('app','Year(s)').' ';
									}
									if($employee->experience_month!=NULL and $employee->experience_month!=0){
										echo $employee->experience_month.' '.Yii::t('app','Month(s)');
									}
							?>
                        </td>
                        </tr>
                          <tr>
                       <?php /*?> <td><strong><?php echo Yii::t('teachersportal','Pin Code');?>  </strong></td>
                        <td><?php echo $student->pin_code; ?></td><?php */?>
                        <?php 
						if($employee->experience_detail!=NULL){ ?>
						<td><strong><?php echo Yii::t('app','Experience Detail');?>  </strong></td>	
                        <td><?php echo $employee->experience_detail;?></td>	
						<?php }else{
						?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <?php } ?>
                     
                        <td><strong><?php echo Yii::t('app','Home Address Line 1');?>  </strong></td>
                        <td><?php echo $employee->home_address_line1; ?></td>
                         </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Home Address Line 2');?></strong></td>
                        <td><?php echo $employee->home_address_line2; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Home City');?>  </strong></td>
                        <td><?php echo $employee->home_city; ?></td>
                         </tr>
                       <tr>
                        <td><strong><?php echo Yii::t('app','Home State');?></strong></td>
                        <td><?php echo $employee->home_state; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Home Country');?>  </strong></td>
                        <td><?php $count = Countries::model()->findByAttributes(array('id'=>$employee->home_country_id));
							if(count($count)!=0)
							echo $count->name;  ?></td>
                             </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Home Pin');?></strong></td>
                        <td><?php echo $employee->home_pin_code; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Office Address Line 1');?>  </strong></td>
                        <td><?php echo $employee->office_address_line1; ?></td>
                         </tr>
                       <tr>
                        <td><strong><?php echo Yii::t('app','Office Address Line 2');?></strong></td>
                        <td><?php echo $employee->office_address_line2; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Office City');?>  </strong></td>
                        <td><?php echo $employee->office_city; ?></td>
                         </tr>
                       <tr>
                        <td><strong><?php echo Yii::t('app','Office State');?></strong></td>
                        <td><?php echo $employee->office_state; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Office Country');?>  </strong></td>
                        <td><?php $count = Countries::model()->findByAttributes(array('id'=>$employee->office_country_id));
							if(count($count)!=0)
							echo $count->name;  ?></td>
                             </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Office Pin');?></strong></td>
                        <td><?php echo $employee->office_pin_code; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Office Phone 1');?></strong></td>
                        <td><?php echo $employee->office_phone1; ?></td>
                         </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Office Phone 2');?></strong></td>
                        <td><?php echo $employee->office_phone2; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Mobile Phone');?></strong></td>
                        <td><?php echo $employee->mobile_phone; ?></td>
                         </tr>
                     <tr>
                        <td><strong><?php echo Yii::t('app','Home Phone');?></strong></td>
                        <td><?php echo $student->home_phone; ?></td>
                    
                        <td><strong><?php echo Yii::t('app','Email');?></strong></td>
                        <td><?php echo $employee->email; ?></td>
                          </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Fax');?></strong></td>
                        <td><?php echo $employee->fax; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Marital Status');?></strong></td>
                        <td><?php echo $employee->marital_status; ?></td>
                         </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Children');?></strong></td>
                        <td><?php echo $employee->children_count; ?></td>
                     
                        <td><strong><?php echo Yii::t('app','Father\'s Name');?></strong></td>
                        <td><?php echo $employee->father_name; ?></td>
                         </tr>
                      <tr>
                        <td><strong><?php echo Yii::t('app','Mother\'s Name');?></strong></td>
                        <td><?php echo $employee->mother_name; ?></td>
                    
                        <td><strong><?php echo Yii::t('app','Husband\'s Name');?></strong></td>
                        <td><?php echo $employee->husband_name; ?></td>
                          </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    
                      
                      
                      </tbody>
                     </table>
					</div>
                </div>
                  <!-- END div class="profile_details"-->
    
    <div class="panel-heading"> 
      <!-- Document Area -->
      <h3 class="panel-title"><?php echo Yii::t('app','Document Name'); ?></h3>
    </div>
    <div class="people-item">
      <div class="table-responsive">
        <?php
                    $documents = EmployeeDocument::model()->findAllByAttributes(array('employee_id'=>$employee->id)); // Retrieving documents of student with id $_REQUEST['id'];
                    ?>
        <table class="table table-hover mb30">
          <?php
                            if($documents) // If documents present
                            {
                                foreach($documents as $document) // Iterating the documents
                                {
									//$document_status= DocumentUploads::model()->fileStatus(4, $document->id, $document->file);
									
									   
                            ?>
          <tr>
            <td width="90%"><?php echo ucfirst($document->title);?></td>
            <td width="10%"><?php
            $status_data="";
											// Setting class for status label
											if($document->is_approved == -1)
											{
												$class = 'tag_disapproved';
                                                                                                $status_data=Yii::t('app',"Disapproved");
											}
											elseif($document->is_approved == 0)
											{
												$class = 'tag_pending';
                                                                                                $status_data=Yii::t('app',"Pending");
											}
											elseif($document->is_approved == 1)
											{
												$class = 'tag_approved';
                                                                                                $status_data=Yii::t('app',"Approved");
											}
											echo '<div style="width:127px">';
											echo '<div class="'.$class.'">'.$status_data.'</div>';
											echo '</div>';
											?></td>
            <td width="10%"><ul class="tt-wrapper">
                <li>
                  <?php 
                                                    if($document->is_approved == 1)
                                                    {
                                                    echo CHtml::link('<span>'.Yii::t('app','You cannot edit an approved document.').'</span>', array('documentupdate','id'=>$document->employee_id,'document_id'=>$document->id),array('class'=>'tt-edit-disabled','onclick'=>'return false;')); 
                                                    }
                                                    else
                                                    {
                                                        echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('documentupdate','id'=>$document->employee_id,'document_id'=>$document->id),array('class'=>'tt-edit')); 
                                                    }
                                                    ?>
                </li>
                <li>
                  <?php 
                                                 echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('download','id'=>$document->id,'employee_id'=>$document->employee_id),array('class'=>'tt-download')); 
                                                 ?>
                </li>
                <li>
                  <?php 
													if($document->is_approved == 1)
													{
														echo CHtml::link('<span>'.Yii::t('app','You cannot delete an approved document.').'</span>', array('deletes','id'=>$document->id,'employee_id'=>$document->employee_id),array('class'=>'tt-delete-disabled','onclick'=>'return false;')); 
													}
													else
													{
														echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('deletes','id'=>$document->id,'student_id'=>$document->employee_id), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true));
													}
													?>
                </li>
              </ul></td>
          </tr>
          <?php	
                                }
							
                                
                            }
                            else // If no documents present
                            {
                            ?>
          <tr>
            <td colspan="2" style="text-align:center;"><?php echo Yii::t('app','No document(s) uploaded'); ?></td>
          </tr>
          <?php
                            }
                            ?>
        </table>
      </div>
    </div>
    <!-- END div class="document_table" -->
    <!-- END div class="document_table" -->
    <div class="panel-heading">
      <h5 class="panel-title"><?php echo Yii::t('app','Upload Documents'); ?> 
        <!-- Document form --></h5>
    </div>
   
    <div class="people-item">
      <div class="form-group">
        <div class="form">
          <?php
                    if($documents==NULL) 
                    {
                        $document = new EmployeeDocument;
                    }
                      echo $this->renderPartial('documentform', array('model'=>$document,'sid'=>$employee->id)); 
                    ?>
        </div>
      </div>
      <!-- form --> </div>
      
        
        </div>
<script type="text/javascript">
$('#emp_image').jupload({	 
	url:<?php echo CJavaScript::encode(Yii::app()->createUrl('/teachersportal/default/employeepicupload',array('id'=>$employee_id)))?>,
	data:{"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
	select:function(files){
		$.each(files, function(index, file){
			var reader = new FileReader();
			reader.onload = function (e) {      			
				//$('.prof_img img').attr('src', e.target.result);				
			}
			reader.readAsDataURL(file);
		});
	},
	uploadProgress: function(event, position, total, percentComplete){		 
		$('#displayPercentage').show();
		$('.loading_app').show();		
		$('#percentage').html(parseInt(percentComplete));
	},
	complete: function(response){  
		$('#displayPercentage').hide();
		alert('<?php echo Yii::t('app', 'Image will be changed only after approval from Administrator!'); ?>');		
	}	 	 
});
</script>
<script type="text/javascript">
	$('#displayPercentage').hide();
</script>

