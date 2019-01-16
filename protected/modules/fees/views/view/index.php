<?php
	$configuration	= Configurations::model()->findByPk(5);
	$feeconfig 		= FeeConfigurations::model()->find();	//fee cofigurations
?>
<style>
.invoice-table td{
	padding-left:10px !important;
}
</style>
<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
	);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Fees')." - #".$category->id;; ?></h1>            
                            <div class="edit_bttns" style="width:175px; top:15px;">
                            </div>                            
                            <div style="width:97%; padding-top:0px;" class="pdtab_Con" >
                                <div class="formCon">
                                    <div class="formConInner">
                                    	<div><span style="font-weight:bold;"><?php echo Yii::t('app','Name'); ?> :</span>  <?php echo $category->name;?></div><br />
                                        <div><span style="font-weight:bold;"><?php echo Yii::t('app','Description'); ?> :</span>  <?php echo $category->description;?></div><br />
                                        <?php
                                        if($category->start_date and $category->end_date){											
										?>
                                            <div>
												<span style="font-weight:bold;"><?php echo Yii::t('app','Start Date'); ?> : </span>
                                                <?php
                                                    $settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                                    if($settings!=NULL)
                                                        echo date($settings->displaydate, strtotime($category->start_date));
                                                    else
                                                        echo $category->start_date;
                                                ?>
                                            </div>   <br />                                     
                                            <div>
												<span style="font-weight:bold;"><?php echo Yii::t('app','End Date'); ?> : </span>
												<?php
													if($settings!=NULL)
                                                        echo date($settings->displaydate, strtotime($category->end_date));
                                                    else
                                                        echo $category->end_date;
												?>
                                            </div>
                                        <?php
										}
										?>
                                    </div>
                                </div>    
                                <div style="font-size:13px; padding:5px 0px">
                                	<strong><?php echo Yii::t('app','Fee Particulars');?></strong>
                                </div>
								<div class="invoice-table">  
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr class="pdtab-h">
                                            <td height="18" align="center">#</td>
                                            <td align="center"><?php echo Yii::t('app','Particular'); ?></td>
                                            <td height="18"><?php echo Yii::t('app','Description'); ?></td>
                                            <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
                                            <td height="18"><?php echo Yii::t('app','Tax'); ?></td>
                                            <?php }?>
                                            <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
                                            <td height="18"><?php echo Yii::t('app','Discount'); ?></td>
                                            <?php }?>
                                            <td align="center"><?php echo Yii::t('app','Group'); ?></td>                                       
                                            <td align="center"><?php echo Yii::t('app','Amount'); ?></td>
                                        </tr>
                                        <?php
                                        foreach($particulars as $key=>$particular){
											$criteria		= new CDbCriteria;
											$criteria->compare("particular_id", $particular->id);
											$accesses	= FeeParticularAccess::model()->findAll($criteria);
											$total		= count($accesses);
										?>
                                        <tr>
                                        	<td rowspan="<?php echo $total + 1;?>" align="center"><?php echo $key+1;?></td>
                                        	<td rowspan="<?php echo $total + 1;?>"><?php echo $particular->name;?></td>                                            
                                            <td rowspan="<?php echo $total + 1;?>"><?php echo ($particular->description!=NULL)?$particular->description:'-';?></td>
                                            <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
                                            <td rowspan="<?php echo $total + 1;?>">
												<?php
													if($particular->tax==0)
														echo '-';
													else{
														$tax	= FeeTaxes::model()->findByPk($particular->tax);
														if($tax)
															echo number_format($tax->value, 2)." %";
														else
                                                			echo '-';
													}
												?>
                                            </td>
                                            <?php }?>
                                            <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
                                            <td rowspan="<?php echo $total + 1;?>">
                                            	<?php
													if($particular->discount_type==0)
														echo '-';
													else{
														if($particular->discount_type==1)
															echo number_format($particular->discount_value, 2)." %";
														else if($particular->discount_type==2)
                                                			echo (($configuration!=NULL)?$configuration->config_value:'Amount')." ".number_format($particular->discount_value, 2);
														else
															echo '-';
													}
													
													
												?>
                                            </td>
                                            <?php }?>
                                        </tr>
                                            <?php
                                            foreach($accesses as $access){
											?>
                                            <tr>
                                            	<td>                                                
													<?php
														$access_for_all	= true;
														if($access->course!=NULL){
															$course	= Courses::model()->findByPk($access->course);
															if($course!=NULL)
                                                    			echo "<div>".Yii::t('app','Course')." : ".$course->course_name."</div>";
															$access_for_all	= false;
														}
														
														if($access->batch!=NULL){
															$batch	= Batches::model()->findByPk($access->batch);
															if($batch!=NULL)
                                                    			echo "<div>".Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")." : ".$batch->name."</div>";
															$access_for_all	= false;
														}
														
														if($access->student_category_id!=NULL){
															$student_category	= StudentCategories::model()->findByPk($access->student_category_id);
															if($student_category!=NULL)
                                                    			echo "<div>".Yii::t('app','Student Category')." : ".$student_category->name."</div>";
															$access_for_all	= false;
														}
														
														if($access->admission_no!=NULL){
                                                   			echo "<div>".Yii::t('app','Admission Number')." : ".$access->admission_no."</div>";
															$access_for_all	= false;
														}
														
														if($access_for_all)
															echo "<div>".Yii::t('app','All')."</div>";
													?>
                                                </td>
                                                <td><?php echo (($configuration!=NULL)?$configuration->config_value:'Amount')." ".number_format($access->amount, 2);?></td>
                                            </tr>
                                            <?php
											}
											?>
                                        <?php
										}
										if(count($particulars)==0){
										?>
                                        <tr><td colspan="7" align="center"><?php echo Yii::t("app", "No data found");?></td></tr>
                                        <?php
										}
										?>                                     
                                    </tbody>
                                </table>  
								</div>  
                            </div>                            
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>