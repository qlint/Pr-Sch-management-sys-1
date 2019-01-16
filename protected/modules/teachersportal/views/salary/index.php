<?php $this->renderPartial('/default/leftside');?> 
    <div class="right_col"  id="req_res123">                                      
       
          <div class="pageheader">
        <div class="col-lg-8 col-4-reqst">
        <h2><i class="fa fa-file-text-o"></i><?php echo Yii::t('app', 'Salary and Payslips');?><span><?php echo Yii::t('app', 'View your salary and payslips here');?> </span></h2>
        </div>
        <div class="col-lg-2 col-4-reqst">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app', 'Salary');?></li>
            </ol>
        </div>    
        <div class="clearfix"></div>    
    </div>
        <div class="contentpanel">
    
    
<div class="panel-heading">

<h3 class="panel-title"><?php echo Yii::t('app','View Salary and Payslips'); ?></h3></div>


<div class="people-item">

	<style type="text/css">
    a{ margin:0 2px;}
    </style>
    
    <div class="attendance-ul-block">
    <ul>
    <li>
   		<?php   echo CHtml::link('<span>'.Yii::t("app",'View My Payslips').'</span>',array('/teachersportal/payslip'),array('class'=>'btn btn-primary pull-right')); ?>
        </li>
        <li>
		<?php   echo CHtml::link('<span>'.Yii::t("app",'View My Salary Details').'</span>',array('/teachersportal/salary/view'),array('class'=>'btn btn-primary pull-right')); ?>
        </li>
        </ul>
    </div>
 <div class="yellow_bx">
                    <?php /*?><div class="y_bx_head" style="font-size:14px;">
                       &nbsp;
                    </div><?php */?>
                    <div class="y_bx_list timetable_list">
                    	<h5 class="subtitle"><?php echo Yii::t('app','View My salary details'); ?></h5>
                        <p><?php echo Yii::t('app','Displays your salary details.'); ?></p>
                    </div> 
                    <?php 
					
					?>
                    <div class="y_bx_list timetable_list">
                    	<h5 class="subtitle"><?php echo Yii::t('app','View My Payslips'); ?></h5>
                        <p><?php echo Yii::t('app','Displays your payslips.')?></p>
                    </div>
                     <div class="yb_attendance">&nbsp;</div> 
                    <div class="yb_teacher_attendance">&nbsp;</div>
       			</div>
        	</div>
        </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
