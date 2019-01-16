

<div id="parent_Sect">
	<?php $this->renderPartial('/default/leftside');?> 
        
    <div class="right_col"  id="req_res123">                                      
          
        <div id="parent_rightSect">
            <div class="parentright_innercon">
                 <h1><?php echo Yii::t('app','Payslip'); ?></h1>

                <div class="leave_con">
                
                
 
<?php 
   $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
   
   if($settings!=NULL)
    {
       $date=$settings->displaydate;
	   
    }
    else
	{
    	$date = 'd-m-Y';	 
		
	}
 ?>


<div class="cont_right formWrapper">
       
        
        <div class="emp_right_contner">
          <div class="emp_tabwrapper">
            
            <div class="clear"></div>
            <div class="emp_cntntbx">
              <div class="table_listbx">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  
                  <tr>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Salary Date');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->salary_date; ?></td>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Bank Name');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->bank_name; ?></td>
                  </tr>
                  <tr>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Bank Account No');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->bank_acc_no; ?></td>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Basic Pay');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->basic_pay; ?></td>
                  </tr>
                  <tr>
                  <td colspan="4">&nbsp;</td>
                  </tr>
                  
                  <tr>
                  <td height="30" colspan="4" style="border-bottom:1px solid #F2EFEF;"><h3><strong><?php echo Yii::t('app','Earnings');?></strong></h3></td>
                  </tr>
                  
                  <tr>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Basic Pay');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->basic_pay; ?></td>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','HRA');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->HRA; ?></td>
                  </tr>
                  <tr>
                   
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','DA');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->DA; ?></td>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Others');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->others1; ?></td>
                  </tr>
                  <tr>
                  <td colspan="4">&nbsp;</td>
                  </tr>
                  <tr>
                  <td height="30" colspan="4" style="border-bottom:1px solid #F2EFEF;"><h3><strong><?php echo Yii::t('app','Deduction');?></strong></h3></td>
                  </tr>
                  <tr>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','TDS');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->TDS; ?></td>
                    <td class="listbx_subhdng"><strong><?php echo Yii::t('app','Others');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->others1; ?></td>
                  </tr>
                  <tr>
                   <td class="listbx_subhdng"><strong><?php echo Yii::t('app','PF');?></strong></td>
                    <td class="subhdng_nrmal"><?php echo $model->PF; ?></td>
                    <td class="listbx_subhdng"></td>
                    <td class="subhdng_nrmal"></td>
                   </tr>
                </table>
                <div class="ea_pdf" style="top:4px; right:6px;">
                  <?php //echo CHtml::link('<img src="images/pdf-but.png">', array('Employees/pdf','id'=>$_REQUEST['id'])); ?>
                </div>
              </div>
            </div>
            <div class="tableinnerlist" style="margin-top:20px;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                <tr class="listbxtop_hdng">
                  <td colspan="3" style="text-align:center;"><?php echo Yii::t('app','Monthly Payslip');?></td>
                  
                 
                </tr>
                <tr class="">
                  <th><?php echo Yii::t('app','Date');?></th>
                  
                  <th><?php echo Yii::t('app','Amount');?></th>
                  <th><?php echo Yii::t('app','Print');?></th>
                 
                </tr>
                <?php					
										$documents = MonthlyPayslips::model()->findAllByAttributes(array('employee_id'=>$model->id,'is_approved'=>1)); 
                                    	if($documents) // If documents present
										{
											foreach($documents as $document) // Iterating the documents
											{
										?>
                <tr>
                  <td width="50%"><?php echo date($date,strtotime($document->salary_date));?></td>
                  <td width="50%"><?php echo $document->amount;?></td>
                  <td width="50%"><?php echo CHtml::link('<span>'.Yii::t('app','Print').'</span>', array('/employees/employeePayslip/printpdf','id'=>$document->id),array('class'=>'tt-print'));?></td>
                  
                </tr>
                <?php	
											} // End foreach($documents as $document)
										}
										else // If no documents present
										{
										?>
                <tr>
                  <td colspan="3" style="text-align:center;"><?php echo Yii::t('app','No Payslip Generated'); ?></td>
                </tr>
                <?php
										}
										?>
              </table>
            </div>
          </div>
        </div>
      </div>

                
                
                
                
                
                
                
                
                
                
                
                </div>    
        	</div>
        </div>
        <div class="clear"></div>
    </div>
</div>
