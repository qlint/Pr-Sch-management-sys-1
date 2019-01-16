<?php 
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('app',"Profile");
$this->breadcrumbs=array(
	Yii::t('app',"Profile"),
);
?>
<?php 
 echo $this->renderPartial('application.modules.teachersportal.views.default.leftside');
 ?>

<div class="pageheader">
      <h2><i class="fa fa-file-text-o"></i> <?php echo Yii::t('app','Salary Details'); ?> <span><?php echo Yii::t('app','View your salary details'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Salary Details'); ?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
    	<div class="panel-heading">
        <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">
        <div class="edit_bttns">
    <div class="clear"></div>
</div>
</div>

    <h3 class="panel-title"><?php echo Yii::t('app','Salary Details'); ?></h3>
    </div>
  <?php 
  $salary_details 	= Staff::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));?>

	<div class="people-item">
		<div class="table-responsive">
          <div class="row">
  	<div class="col-md-8 col-4-reqst">      
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                         <td valign="top">
                        <div class="cont_right formWrapper usertable" >
                                <table class="table table-hover mb30 salary-table">
                                    <tr>
                                        <th><?php echo Yii::t('app','Basic Pay'); ?></th>
                                        <td><?php if($salary_details->basic_pay){
														echo $salary_details->basic_pay;
													}else{ 
														echo '-'; 
													}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo Yii::t('app','TDS'); ?></th>
                                        <td><?php if($salary_details->TDS){
											if($salary_details->tds_type == 0){
														echo $salary_details->TDS;
											}
											else{
												echo $salary_details->TDS.' '.'%';
											}
													}else{ 
														echo '-'; 
													}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo Yii::t('app','ESI'); ?></th>
                                        <td><?php if($salary_details->ESI){
														echo $salary_details->ESI;
													}else{ 
														echo '-'; 
													}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo Yii::t('app','EPF'); ?></th>
                                        <td><?php if($salary_details->EPF){
														echo $salary_details->EPF;
													}else{ 
														echo '-'; 
													}?>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>
                         </td>
                         </tr>
                </table>
                </div></div>
		</div>
	</div>
    
</div>

