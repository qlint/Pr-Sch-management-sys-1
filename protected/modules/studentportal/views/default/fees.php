
	<?php $this->renderPartial('leftside');?> 
    <?php
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $res=FinanceFees::model()->findAll(array('condition'=>'student_id=:vwid AND is_paid=:vpid','params'=>array(':vwid'=>$student->id, ':vpid'=>0)));
    ?>
    
  <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-money"></i><?php echo Yii::t('app','Fees'); ?><span><?php echo Yii::t('app','View your Fees here'); ?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Fees'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    
    
    
  <div class="contentpanel">
     <!--<div class="col-sm-9 col-lg-12">-->
     <div>
     <div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                     if($student->photo_file_name!=NULL)
                     {
						$path = Students::model()->getProfileImagePath($student->id);  
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
                    }
                    ?>                            
                            </a>
                            <div class="media-body">
                              <h4 class="person-name"><?php echo ucfirst($student->last_name).' '.ucfirst($student->first_name);?></h4>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
                                        <?php 
                        $batch = Batches::model()->findByPk($student->batch_id);
                        echo $batch->course123->course_name;
                        ?></div>
                              <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong><?php echo $batch->name;?></div>
                              <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
                             
                              
                            </div>
                          </div>
                        </div>
                        <!-- END div class="profile_top" -->
     
    <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Pending Fees');?></h3>
                <?php 
					$currency=Configurations::model()->findByPk(5);
                	$res=FinanceFees::model()->findAll(array('condition'=>'student_id=:vwid AND is_paid=:vpid','params'=>array(':vwid'=>$student->id, ':vpid'=>0)));
                ?>
                        </div>
     <div class="people-item">
                         <div class="table-responsive">
                        
                        <table class="table table-hover mb30">
    
                
                
                    <tr>
                        <th><?php echo Yii::t('app','Category Name');?></th>
                        <th><?php echo Yii::t('app','Collection Name');?></th>
                       
                        <th><?php echo Yii::t('app','Last Date');?></th>
                         <th><?php echo Yii::t('app','Amount');?></th>
                        <th><?php echo Yii::t('app','Fees Paid');?></th>
                        <th><?php echo Yii::t('app','Balance');?></th>
                        <th><?php echo Yii::t('app','Action');?></th>
                    </tr>
                    <?php
                    if(count($res)==0)
                    {
                    	echo '<tr><td align="center" colspan="7"><i>'.Yii::t('app','No Pending Fees').'</i></td></tr>';	
                    }
                    else
                    {
						foreach($res as $res_1)
						{
							$posts = FinanceFeeCollections::model()->findByAttributes(array('id'=>$res_1->fee_collection_id));
							if($posts!=NULL)
							{
							$cat = FinanceFeeCategories::model()->findByAttributes(array('id'=>$posts->fee_category_id));
					
							?>
					
							<tr>
							  <td><?php if(@$cat) echo $cat->name; ?></td>
							   <td><?php echo $posts->name; ?></td>
							   <td>
									<?php 
										$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
										if($settings!=NULL)
										{	
											echo date($settings->displaydate,strtotime($posts->due_date));
											
										}
										else
										echo $posts->due_date; 
									?>
								</td>
								<td>
									<?php
									$check_admission_no = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'admission_no'=>$student->admission_no));
									if(count($check_admission_no)>0){ // If any particular is present for this student
										$adm_amount = 0;
										foreach($check_admission_no as $adm_no){
											$adm_amount = $adm_amount + $adm_no->amount;
										}
										$fees = $adm_amount;
										//echo $adm_amount.' '.$currency->config_value;
										$balance = 	$adm_amount - $res_1->fees_paid;
									}
									else{ // If any particular is present for this student category
										$check_student_category = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'student_category_id'=>$student->student_category_id,'admission_no'=>''));
										if(count($check_student_category)>0){
											$cat_amount = 0;
											foreach($check_student_category as $stu_cat){
												$cat_amount = $cat_amount + $stu_cat->amount;
											}
											$fees = $cat_amount;
											//echo $cat_amount.' '.$currency->config_value;
											$balance = 	$cat_amount - $res_1->fees_paid;				
										}
										else{ //If no particular is present for this student or student category
											$check_all = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'student_category_id'=>NULL,'admission_no'=>''));
											if(count($check_all)>0){
												$all_amount = 0;
												foreach($check_all as $all){
													$all_amount = $all_amount + $all->amount;
												}
												$fees = $all_amount;
												//echo $all_amount.' '.$currency->config_value;
												$balance = 	$all_amount - $res_1->fees_paid;
											}
											else{
												echo '-'; // If no particular is found.
											}
										}
									}
									if($fees)	
										echo $fees.' '.$currency->config_value;
									else
										echo '-';
								 
								?>
								</td>
								<td>
									<?php
									if($res_1->is_paid == 0)
									{
										echo $res_1->fees_paid.' '.$currency->config_value;
									}
									else
									{
										echo $fees.' '.$currency->config_value; 
									}
									?>
								</td>
								<td>
									<?php
									if($res_1->is_paid == 0)
									{	
										echo $balance.' '.$currency->config_value;
									}
									else
									{
										echo '-';
									}
									?>
								 </td>
								<td> 
									<?php
									if($res_1->fees_paid!=0 and $res_1->fees_paid!=NULL)
									{ 
										echo Yii::t('app','Paid Partially'); 
									}
									else
									{
										echo Yii::t('app','Not Paid'); 
									}
									?>
								</td>
							</tr>
						  
							<?php 
							}
						}
                    }?> 
                </table>
                </div>
                
                <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Paid Fees');?></h3>
                <?php 
                $res=FinanceFees::model()->findAll(array('condition'=>'student_id=:vwid AND is_paid=:vpid','params'=>array(':vwid'=>$student->id, ':vpid'=>1)));
                ?>
                </div>
                <div class="table-responsive">
                        
                        <table class="table table-hover mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Category Name');?></th>
                        <th><?php echo Yii::t('app','Collection Name');?></th>
                        <th><?php echo Yii::t('app','Amount');?></th>
                    </tr>
                    <?php
                    foreach($res as $res_1)
                    {
							$posts = FinanceFeeCollections::model()->findByAttributes(array('id'=>$res_1->fee_collection_id));
							if($posts!=NULL)
							{
							$cat = FinanceFeeCategories::model()->findByAttributes(array('id'=>$posts->fee_category_id));
					
							?>
					
							<tr>
							  <td><?php if(@$cat) echo $cat->name; ?></td>
							   <td><?php echo $posts->name; ?></td>
								<td>
									<?php
									$check_admission_no = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'admission_no'=>$student->admission_no));
									if(count($check_admission_no)>0){ // If any particular is present for this student
										$adm_amount = 0;
										foreach($check_admission_no as $adm_no){
											$adm_amount = $adm_amount + $adm_no->amount;
										}
										$fees = $adm_amount;
										//echo $adm_amount.' '.$currency->config_value;
										$balance = 	$adm_amount - $res_1->fees_paid;
									}
									else{ // If any particular is present for this student category
										$check_student_category = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'student_category_id'=>$student->student_category_id,'admission_no'=>''));
										if(count($check_student_category)>0){
											$cat_amount = 0;
											foreach($check_student_category as $stu_cat){
												$cat_amount = $cat_amount + $stu_cat->amount;
											}
											$fees = $cat_amount;
											//echo $cat_amount.' '.$currency->config_value;
											$balance = 	$cat_amount - $res_1->fees_paid;				
										}
										else{ //If no particular is present for this student or student category
											$check_all = FinanceFeeParticulars::model()->findAllByAttributes(array('finance_fee_category_id'=>$posts->fee_category_id,'student_category_id'=>NULL,'admission_no'=>''));
											if(count($check_all)>0){
												$all_amount = 0;
												foreach($check_all as $all){
													$all_amount = $all_amount + $all->amount;
												}
												$fees = $all_amount;
												//echo $all_amount.' '.$currency->config_value;
												$balance = 	$all_amount - $res_1->fees_paid;
											}
											else{
												echo '-'; // If no particular is found.
											}
										}
									}
									if($fees)	
										echo $fees.' '.$currency->config_value;
									else
										echo '-';
								 
								?>
								</td>
							</tr>
						  
							<?php 
							}
						}
                    ?>
                </table>
                
             </div>
           </div>
            
</div>
  </div>
