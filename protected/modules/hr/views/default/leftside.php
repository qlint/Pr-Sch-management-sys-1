<div id="othleft-sidebar"> 
  <?php
	$level = Configurations::model()->findByPk(41);
	if($level->config_value !=1)
	{
		$visible = true;
	}
	else
	{
		$visible = false;
	}
	
	function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
	{
		return $message;
	}
	$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
	
	//custom
	if(Yii::app()->controller->id=='leaveRequests' and Yii::app()->controller->action->id=='view'){
		$model	= LeaveRequests::model()->findByPk($_REQUEST['id']);
		if($model!=NULL){
			$pendingActive	= $approvedActive	= $cancelledActive	= false;
			if($model->status==0)
				$pendingActive		= true;
			else if($model->status==1)
				$approvedActive		= true;
			else if($model->status==2)
				$cancelledActive	= true;
		}
	}
	
        $this->widget('zii.widgets.CMenu',array(
            'encodeLabel'=>false,
            'activateItems'=>true,
            'activeCssClass'=>'list_active',
            'items'=>array(
				array('label'=>''.'<h1>'.Yii::t('app','Staff').'</h1>', 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.staff.create')  or Yii::app()->user->checkAccess('hr.staff.*')),
				
				array('label'=>Yii::t('app','Create Staff').'<span>'.Yii::t('app','Create Non-Teaching Staff').'</span>', 'url'=>array('/hr/staff/create'),'linkOptions'=>array('class'=>'hr-crtstaf-icon'),'active'=> (Yii::app()->controller->action->id=='create'),'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.staff.create') or Yii::app()->user->checkAccess('hr.staff.*')),
				
			array('label'=>Yii::t('app','Manage Staff').'<span>'.Yii::t('app','Manage Non-Teaching Staff').'</span>', 'url'=>array('/hr/staff/index'),'linkOptions'=>array('class'=>'hr-mangstaf-icon'),'active'=> ((Yii::app()->controller->id=='staff' and (in_array(Yii::app()->controller->action->id,array("index","view","update")))) ? true : false), 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.staff.*')),
		
                array(
                    'label'=>''.'<h1>'.Yii::t('app', 'Leaves').'</h1>',
                    'active'=> ((Yii::app()->controller->module->id=='hr') ? true : false)
                ),
                array(
                    'label'=>Yii::t('app', 'Leave Types').'<span>'.Yii::t('app', 'Create and Manage Leave types').'</span>',
                    'url'=>array('/hr/leaveTypes/index'),
                    'active'=> ((Yii::app()->controller->id=='leaveTypes') ? true : false),
                    'linkOptions'=>array('class'=>'hr-leave-icon'), 'visible'=>key($roles) == 'Admin'or Yii::app()->user->checkAccess('hr.leaveTypes.*')
                ),
				array(
                    'label'=>Yii::t('app', 'Leave Requests').'<span>'.Yii::t('app', 'Pending Leave Requests').'</span>',
                    'url'=>array('/hr/leaveRequests/pending'),
                    'active'=> (($pendingActive or (Yii::app()->controller->id=='leaveRequests' and Yii::app()->controller->action->id=='pending')) ? true : false),
                    'linkOptions'=>array('class'=>'hr-leave-reqst-icon'),'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.leaveRequests.*')
                ),
				array(
                    'label'=>Yii::t('app', 'Approved Requests').'<span>'.Yii::t('app', 'Approved Leave Requests').'</span>',
                    'url'=>array('/hr/leaveRequests/approved'),
                    'active'=> (($approvedActive or (Yii::app()->controller->id=='leaveRequests' and Yii::app()->controller->action->id=='approved')) ? true : false),
                    'linkOptions'=>array('class'=>'hr-aprove-leave-icon'),'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.leaveRequests.*')
                ),
				array(
                    'label'=>Yii::t('app', 'Cancelled Requests').'<span>'.Yii::t('app', 'Cancelled Leave Requests').'</span>',
                    'url'=>array('/hr/leaveRequests/cancelled'),
                    'active'=> (($cancelledActive or (Yii::app()->controller->id=='leaveRequests' and Yii::app()->controller->action->id=='cancelled')) ? true : false),
                    'linkOptions'=>array('class'=>'hr-reqst-cancl-icon'),'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.leaveRequests.*')
                ),
				array(
                    'label'=>Yii::t('app', 'My Leaves').'<span>'.Yii::t('app', 'Request Leaves').'</span>',
                    'url'=>array('/hr/leaves/index'),
                    'active'=> ((Yii::app()->controller->id=='leaves') ? true : false),
                    'linkOptions'=>array('class'=>'hr-myleave-icon'),'visible'=>key($roles) != 'Admin'
                ),
           array('label'=>''.'<h1>'.Yii::t('app','Salary'), 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.payslip.*').'</h1>'),
		
		
			array('label'=>Yii::t('app','Salary Details').'<span>'.Yii::t('app','Employees Salary Details').'</span>', 'url'=>array('/hr/staff/salarydetails'),'active'=>(in_array(Yii::app()->controller->action->id,array('salarydetails','addsalarydetails')) ? true : false),'linkOptions'=>array('class'=>'hr-salry-dtls-icon'), 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.staff.*')),
			
			array('label'=>Yii::t('app','Generate Payslip').'<span>'.Yii::t('app','Generate Payslip for Employees').'</span>', 'url'=>array('/hr/payslip/index'),'active'=>((Yii::app()->controller->id=='payslip' and (in_array(Yii::app()->controller->action->id,array("index","generate","payslips")))) ? true : false),'linkOptions'=>array('class'=>'hr-genert-icon'), 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.payslip.*')),
			
			array('label'=>Yii::t('app','Reports').'<span>'.Yii::t('app','Payslip report').'</span>', 'url'=>array('/hr/payslip/report'),'active'=>(Yii::app()->controller->action->id=='report' and Yii::app()->controller->id=='payslip' ? true : false),'linkOptions'=>array('class'=>'hr-report-icon'), 'visible'=>key($roles) == 'Admin' or Yii::app()->user->checkAccess('hr.payslip.*')),
			
			array('label'=>Yii::t('app','My Salary Details').'<span>'.Yii::t('app','Employees Salary Details').'</span>', 'url'=>array('/hr/salary/view'),'active'=>(in_array(Yii::app()->controller->action->id,array('salary','view')) ? true : false),'linkOptions'=>array('class'=>'hr-salry-dtls-icon'), 'visible'=>key($roles) != 'Admin'),
			array('label'=>Yii::t('app','My Payslips').'<span>'.Yii::t('app','My Payslip Details').'</span>', 'url'=>array('/hr/myPayslip'),'active'=>(Yii::app()->controller->id=='myPayslip' ? true : false),'linkOptions'=>array('class'=>'hr-my-slip-icon'), 'visible'=>key($roles) != 'Admin'),
		),
		)); 
    ?>
</div>

<script type="text/javascript">

$(document).ready(function () {
	//Hide the second level menu
	$('#othleft-sidebar ul li ul').hide();            
	//Show the second level menu if an item inside it active
	$('li.list_active').parent("ul").show();
	
	$('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
		
		 if($(this).parent().children('ul').length>0){                  
			$(this).parent().children('ul').toggle();    
		 }
		 
	});
	
});
</script>