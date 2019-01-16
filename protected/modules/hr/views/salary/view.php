<style>
.prof-view-col ul {
    padding: 0px;
    margin: 0px;
    list-style: none;
    float: left;
     width: auto !important; 
     height: auto !important; 
    border-bottom: #eaeef1 solid 1px;
}
.prof-view-col li.l-col {
    padding: 10px;
    margin: 0px;
    float: left;
    height: auto !important;
    box-sizing: border-box;
    color: #333333;
    font-size: 12px;
    font-weight: bold;
    width: 178px;
}
.prof-view-col li.r-col {
    padding: 10px;
    margin: 0px;
    float: left;
    height:auto !important;
    box-sizing: border-box;
    color: #333333;
    font-size: 14px;
    width: 178px;
}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Hr')=>array('/hr'),
	Yii::t('app','Salary Details')=>array('/hr/salary/view'),
	Yii::t('app','View')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="247" valign="top"><?php $this->renderPartial('/default/leftside');?></td>
		<td valign="top"><div class="cont_right formWrapper"> 
				<h1 style="margin-top:.67em;">
					<?php 
						echo Yii::t('app','Salary Details');
	$salary_details 	= Staff::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));?>				
					<br />
				</h1>
				<div class="clear"></div>
				<div class="emp_right_contner">
					<div class="emp_tabwrapper">
						<div class="clear"></div>
						<div class="emp_cntntbx" >
							<div class="table_listbx">
								<div class="listbxtop_hdng"><?php echo Yii::t('app','Salary Details');?></div>
<?php ?>
                                <div class="prof-view-col">
                                	<ul>
                                        <li class="l-col"><?php echo Yii::t('app','Basic Pay');?></li>
                                        <li class="r-col"><?php if($salary_details->basic_pay){
																	echo $salary_details->basic_pay;
																}else{ 
																	echo '-'; 
																}?></li>
                                        <li class="l-col"><?php echo Yii::t('app','TDS');?></li>
                                        <li class="r-col"><?php if($salary_details->TDS){
																	if($salary_details->tds_type == 0){
																				echo $salary_details->TDS;
																	}
																	else{
																		echo $salary_details->TDS.' '.'%';
																	}
																			}else{ 
																				echo '-'; 
																			}?></li>
                                    </ul>
                                    
                                    <ul>
                                        <li class="l-col"><?php echo Yii::t('app','ESI');?></li>
                                        <li class="r-col"><?php if($salary_details->ESI){
																	echo $salary_details->ESI;
																}else{ 
																	echo '-'; 
																}?></li>

                                        <li class="l-col"><?php echo Yii::t('app','EPF');?></li>
                                        <li class="r-col"><?php if($salary_details->EPF){
																	echo $salary_details->EPF;
																}else{ 
																	echo '-'; 
																}?></li>
                                    </ul>
                                    
                          	 <div class="clear"></div>
                        	</div> 
						</div>
					</div>
				</div>
			</div>
         </td>
	</tr>
</table>
