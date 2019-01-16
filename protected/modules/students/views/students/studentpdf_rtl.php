<style>
table.studenace_table{
	border-top:1px #CCC solid;
	margin:30px 0px;
	font-size:12px;
	border-right:1px #CCC solid;
	
}
.studenace_table td{
	border:1px #CCC solid;
	padding:5px 6px;
	border-bottom:1px #CCC solid;
	
}
table{ border-collapse:collapse;}

hr{ border-bottom:1px solid #ccc;
	border-top:0px solid #000}
	
h5{ margin:0px;
	font-size:14px;
	padding:0px;}


</style>


<!-- Header -->
	
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first">
                           <?php
						    $filename=  Logo::model()->getLogo();
							if($filename!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                //echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td align="center" valign="middle" class="first" style="width:300px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->

<?php
                            if($students)
                            {
                            ?>
       <h5 align="center"><?php echo Yii::t('app','STUDENTS LIST');?></h5>
      
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="studenace_table" >
                            <tr style="background:#dfdfdf;">
                            <td align="center" style=" width:10%; " ><?php echo '#';?></td>
                            <?php
								if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
						  ?>	
                          		<td align="center"  style=" width:30%; " ><?php echo Yii::t('app','Student Name');?></td>
                            <?php } ?>    
                            <td  align="center"  style=" width:20%; "><?php echo Yii::t('app','Admission No');?></td>
                            <?php if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){?>
                            	<td  align="center"  style=" width:30%; "><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></td>
                            <?php } ?>
                            <?php if(FormFields::model()->isVisible('gender','Students','forStudentProfile')){?>     
                             	<td align="center"  style=" width:10%; "><?php echo Yii::t('app','Gender');?></td>
                            <?php } ?>    
                            </tr>
                           
							
                            <?php
								$i=1;
								foreach($students as $studitem)
								{
									?>
                                    <tr>
                                    <td align="center" style=" width:10%; " ><?php echo $i;?></td>
                                    <?php
										if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
									?>
                                    	<td align="center" style=" width:30%; "><?php echo $studitem->studentFullName('forStudentProfile');?></td>
                                    <?php } ?>    
                                    <td align="center" style=" width:20%; "><?php echo $studitem->admission_no;?></td>
                                    <?php
									if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile')){
										$batc = Batches::model()->findByAttributes(array('id'=>$studitem->batch_id,'is_active'=>1,'is_deleted'=>0)); 
										if($batc!=NULL)
										{
											$cours = Courses::model()->findByAttributes(array('id'=>$batc->course_id)); ?>
											<td align="center" style=" width:30%; "><?php echo $cours->course_name.' / '.$batc->name; ?></td
									><?php }
										else
										   {
									?> 
										<td align="center">-</td> 
											<?php 
											}
									}
								?>
                                <?php if(FormFields::model()->isVisible('gender','Students','forStudentProfile')){?> 
                                     <td align="center" style=" width:10%; ">
                                        <?php 
                                        if($studitem->gender=='M')
                                        {
                                            echo Yii::t('app','Male');
                                        }
                                        elseif($studitem->gender=='F')
                                        {
                                            echo Yii::t('app','Female');
                                        }
                                        ?>
                                    </td>
                               <?php } ?>
                               </tr>
                                    <?php
									$i++;
									
							}?>
							
						 </table>
                            
                            
  <?php
	}
	else
	{?>
	    <h5 align="center"><?php echo Yii::t('app','Nothing Found!!!');?></h5>
	<?php
	}
	?>
	
