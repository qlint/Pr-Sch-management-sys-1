<style>
@media screen and (max-width: 390px) {
	.btn-demo .btn{
		float:none;
	}
}
</style>                   
<div id="parent_Sect">
    <?php $this->renderPartial('/settings/studentleft');?>
    <div class="pageheader">
  <div class="col-lg-8">
    <h2><i class="fa fa-group"></i><?php echo Yii::t('app','Hostel')?><span><?php echo Yii::t('app','View Hostel');?> </span></h2>
  </div>
  <div class="col-lg-2"> </div>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here');?>:</span>
    <ol class="breadcrumb">
      <li class="active"><?php echo Yii::t('app','Hostel');?></li>
    </ol>
  </div>
  <div class="clearfix"></div>
</div>
    
 <?php 
	$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$register=Registration::model()->findByAttributes(array('student_id'=>$student->id,'status'=>'S'));
 ?>   
    <div class="contentpanel">
    	<?php
			if($register!=NULL)
			{ 
		?>
        <div class="col-sm-9 col-lg-12">
        <div class="panel-heading">
         <h3 class="panel-title">Hostel Details</h3>
	</div>
              <?php
			}
			?>
            <div id="parent_rightSect">
                    <div class="people-item">
                         <div class="profile_details" style="position:relative;">
                         <?php
                    
                     if($register!=NULL)
                     {
                         $foodinfo=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
                         $mess=MessFee::model()->findByAttributes(array('student_id'=>$student->id));
                    
                ?>  
                       
                       
                    
                 
          <div class="emp_tabwrapper">
          <?php
          
          $link_1='';
          $link_2='';
          $link_3='';
          $link_index='';
          
                    if(!isset($_REQUEST['id']))
                    {
                        $link_index='active';
                    }
                    else
                    {
                    
                    if($_REQUEST['id']==='1')
                    {
                        $link_1='active';
                        
                        
                    }
                    else if($_REQUEST['id']=='2')
                    {
                        
                        $link_2='active';
                        
                        
                    }
                    else if($_REQUEST['id']=='3')
                    {
                        
                        $link_3='active';
                        
                        
                    }
                    }
          ?>
<div class="opnsl_headerBox">
	<div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
            <?php //echo CHtml::link(Yii::t('hostel','Home'),array('/portalhostel'),array('class'=>'btn btn-success'),array('class'=>$link_index));?>
            <?php /*?><?php  echo CHtml::link(Yii::t('hostel','Mess Fee'),array('/hostel','id'=>'1'),array('class'=>'btn btn-success'),array('class'=>$link_1));?><?php */?>
            <?php //echo CHtml::link(Yii::t('hostel','Hostel Fee'),array('/portalhostel','id'=>'2'),array('class'=>'btn btn-success'),array('class'=>$link_2));?>
            <?php 
            if(!isset($_REQUEST['id']) and $_REQUEST['id']==NULL){
                echo CHtml::link(Yii::t('app','Mess Dues'),array('/hostel','id'=>'3'),array('class'=>'btn btn-success'),array('class'=>$link_3));
            }
            ?>
        </div>
        <div class="opnsl_actn_box2">
            <?php echo CHtml::link(Yii::t('app','Change Room'),array('/hostel/room/change','id'=>$student->id),array('class'=>'btn btn-primary')); ?>
        </div>
    </div>
</div>
          
          <?php
          if(!isset($_REQUEST['id']))
          {
              ?>
              <?php
          $allot=Allotment::model()->findByAttributes(array('student_id'=>$student->id));  
          $floordetails=Floor::model()->findByAttributes(array('id'=>$allot->floor));
          
          $hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floordetails->hostel_id));
          ?>
<div class="table-responsive">
    <table class="table table-bordered mb30" width="100%" cellspacing="0" cellpadding="0">
    <thead>
                  <tr >
                                <?php
                                if($student->studentFullName("forStudentPortal")!=""){
                                ?>
                                <th ><?php echo Yii::t('app','Student Name');?></th>
                                <?php
                                }
                                ?>
                                <th ><?php echo Yii::t('app','Hostel Name');?></th>
                                <th ><?php echo Yii::t('app','Floor No');?></th>
                                <th ><?php echo Yii::t('app','Room No');?></th>
                                <th ><?php echo Yii::t('app','Bed');?></th>
                            
                            </tr>
                            </thead>
                            <?php
                            if($allot!=NULL)
                            {
								$room = Room::model()->findByAttributes(array('id'=>$allot->room_no));
                            ?>
                            <tr>
                                        <?php
                                        if($student->studentFullName("forStudentPortal")!=""){
                                        ?>
                                        <td >
                                          <?php
                                            if($student!=NULL){ echo $student->studentFullName("forStudentPortal"); }
                                            else{ echo Yii::t('app','Not allotted'); }
                                          ?>
                                        </td>
                                        <?php
                                        }
                                        ?>
                                        <td ><?php echo $hostel->hostel_name;?></td>
                                        <td ><?php echo $floordetails->floor_no;?></td>
                                        <td ><?php echo ucfirst($room->room_no);?></td>
                                        <td ><?php echo $allot->bed_no;?></td>
                                        
                                </tr>
                                <?php 
                            }
                            else
                            {
                                echo '<tr><td colspan="5">'.Yii::t('app','No data available now!').'</td></tr>';
                            }
                                ?>
                                </table>
                                </div>
              <?php
          }
          else if(isset($_REQUEST['id']) && ($_REQUEST['id']==1))
          {
              $foodinfo=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
              $mess=MessFee::model()->findByAttributes(array('student_id'=>$student->id));
              ?>
              <div class="table-responsive">
          <table class="table mb30">
          <thead>
                            <tr >
                                <?php
                                if($student->studentFullName("forStudentPortal")!=""){
                                ?>
                                <th ><?php echo Yii::t('app','Student Name');?></th>
                                <?php 
                                }
                                ?>
                                <th ><?php echo Yii::t('app','Mess Fee');?></th>
                                <th ><?php echo Yii::t('app','Status');?></th>
                            
                            </tr>
                            </thead>
                            <?php
                            if($mess->allotment_id!=NULL)
							{
								?>
                            <tr>
                                      <?php
                                      if($student->studentFullName("forStudentPortal")!=""){
                                      ?>
                                      <td ><?php
                                                
                                                        echo $student->studentFullName("forStudentPortal");
                                                
            
                                             ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td ><?php echo $foodinfo->amount;?></td>
                                        <td ><?php 
                                        if($mess!=NULL)
                                        {
                                        if($mess->is_paid=='1')
                                        echo Yii::t('app','Paid');
                                        else if($mess->is_paid=='0')
                                        echo Yii::t('app','Not Paid');
                                        }
                                        else
                                        echo Yii::t('app','Nil');?></td>
                                        
                                </tr>
                                <?php
							}?>
                                </table>
                                </div>
              <?php
          }
          
           else if(isset($_REQUEST['id']) && ($_REQUEST['id']==2))
          {
              $foodinfo=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
              $mess=MessFee::model()->findByAttributes(array('student_id'=>$student->id));
              ?>
              <div class="table-responsive">
          <table class="table mb30">
                            <thead>
                            <tr >
                            	<?php
							  if($student->studentFullName("forStudentPortal")!=""){
							  ?>
                                <th ><?php echo Yii::t('app','Student Name');?></th>
                                <?php } ?>
                                <th ><?php echo Yii::t('app','Mess Fee');?></th>
                                <th ><?php echo Yii::t('app','Status');?></th>
                            
                            </tr>
                            </thead>
                            <tr>
                            			<?php
                                      if($student->studentFullName("forStudentPortal")!=""){
                                      ?>
                                     <td ><?php
                                                
                                                        $student->studentFullName("forStudentPortal");
                                                
            
                                             ?></td>
                                        <?php } ?>
                                        <td ><?php echo $foodinfo->amount;?></td>
                                        <td ><?php 
                                        if($mess!=NULL)
                                        {
                                        if($mess->is_paid=='1')
                                        echo Yii::t('app','Paid');
                                        else if($mess->is_paid=='0')
                                        echo Yii::t('app','Not Paid');
                                        }
                                        else
                                        echo Yii::t('app','Nil');?></td>
                                        
                                </tr>
                                </table>
                                </div>
              <?php
          }
           else if(isset($_REQUEST['id']) && ($_REQUEST['id']==3))
          {
              $foodinfo=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
              $mess=MessFee::model()->findByAttributes(array('student_id'=>$student->id,'is_paid'=>'0'));
              ?>
              <div class="table-responsive">
          <table class="table mb30">
                <thead>
                            <tr >
                            	<?php
								  if($student->studentFullName("forStudentPortal")!=""){
								  ?>
                                <th ><?php echo Yii::t('app','Student Name');?></th>
                                <?php } ?>
                                <th ><?php echo Yii::t('app','Mess Fee');?></th>
                                <th ><?php echo Yii::t('app','Status');?></th>
                            
                            </tr>
                            </thead>
                            <?php 
                            if($mess==NULL)
                            {
                                echo '<tr><td align="center" colspan="3">'.Yii::t('app','No dues').'</td></tr>';
                            }
                            else
                            {
                            ?>
                            <tr>
                            			<?php
                                      if($student->studentFullName("forStudentPortal")!=""){
                                      ?>
                                     <td ><?php
                                                
                                                        echo $student->studentFullName("forStudentPortal");
                                                
            
                                             ?></td>
                                           <?php } ?>
                                             
                                        <td ><?php echo $foodinfo->amount;?></td>
                                        <td><?php 
                                        if($mess->is_paid=='1')
                                        echo Yii::t('app','No dues');
                                        else if($mess->is_paid=='0')
                                        echo Yii::t('app','Due');?></td>
                                        
                                </tr>
                                <?php } ?>
                                </table>
                                </div>
              <?php
          }
          ?>
          
         
                
        <?php 
            }
        
            else
            {
				$studentlist=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
				$registerjust=Registration::model()->findByAttributes(array('student_id'=>$studentlist->id,'status'=>'C'));
				$reject=Registration::model()->findByAttributes(array('student_id'=>$studentlist->id,'status'=>'R'));
				if($registerjust!=NULL)
				{
                ?>
                <?php echo Yii::t('app','You are Registered and waiting for the Admin Approval.');?>
                <?php }
				else if($reject!=NULL)
				{
					?>
					<span style="color:#F00"><?php echo Yii::t('app','Your Request is Rejected!!.');?> </span><?php echo Yii::t('app','If You Need hostel facility?  Click here to ');?>&nbsp;
					<?php 	
					echo '&nbsp&nbsp'.CHtml::link(Yii::t('app','Request'),array('/hostel/registration/request','studentid'=>$studentlist->id),array('class'=>'btn btn-primary', 'style'=>'margin:0 5px;')) ; 
					?>
					<?php
				}
                else
                {
				   ?>
					<?php echo Yii::t('app','Need hostel facility? Click here to');?>&nbsp;<?php echo CHtml::link(Yii::t('app','Register'),array('/hostel/registration/create'),array('class'=>'btn btn-primary', 'style'=>'margin:0 5px;')); ?><?php echo Yii::t('app','now');?> .
					<?php
            	}
			}
        ?>
        
         
        </div>
        
        
        
            </div>
            
             <div class="clear"></div> 
             </div>
             </div>
     </div>
     <div class="clear"></div> 
    </div>
                           
                           
                           