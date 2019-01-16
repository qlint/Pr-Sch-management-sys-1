
<style>
table.tableinnerlist{
	border-collapse:collapse;
	padding:0px;
	margin:0px;

}

table.tableinnerlist tr  td{
	
	 border:1px solid #b9c7d0;
	 padding:4px 10px;
	 font-size:12px;
	 font-weight:bold;
	 text-align:center;
}
.tableinnerlist th{
	 padding:4px 10px;
	 font-size:12px;
	 font-weight:bold;
	 text-align:center;
	 
	
}
</style>
<?php
 
    $list=MessFee::model()->findByAttributes(array('student_id'=>$studentid,'is_paid'=>1));
    $posts=Students::model()->findByAttributes(array('id'=>$studentid));
	$allot=Allotment::model()->findByAttributes(array('student_id'=>$studentid,'status'=>'S'));
	$register=Registration::model()->findByAttributes(array('student_id'=>$allot->student_id));
	$food=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
    $batch=Batches::model()->findByAttributes(array('id'=>$posts->batch_id));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	
	?>
<table width="600" border="1" bgcolor="#f9feff">
  <tr>
    <td>
    	<div style="padding:10px 20px;">
            <table width="600" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100">
                <?php $logo=Logo::model()->findAll();?>
                <?php
                if($logo!=NULL)
				{
					Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
				}
                ?>
               </td>
                <td width="500" valign="middle">
                <h1 style="font-size:20px; text-align:left;"><?php $college=Configurations::model()->findByPk(1); ?>
                <?php echo $college->config_value ; ?></h1>
                <?php $college=Configurations::model()->findByPk(2); ?>
                <strong><?php echo $college->config_value ; ?></strong><br />
                <?php $college=Configurations::model()->findByPk(3); ?>
                <strong><?php echo ''.$college->config_value ; ?></strong>
                </td>
              </tr>
            </table>
		</div>
	</td>
  </tr>
  <tr>
  
 
  <td width="650" style="border-bottom:#ccc 1px solid; padding:10px 20px;">
  <table  border="0" cellspacing="0" cellpadding="0">

  <tr>
  	<td colspan="3" align="center"><h4>Mess Fee Receipt</h4></td>
  </tr>
  
  <tr>
      <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
      { 
            $name='';
            $name=  $posts->studentFullName('forStudentProfile');
            //echo $name;
          ?>
  <td width="500" style="padding:10px 0px;" ><?php  echo Yii::t('app','Name').' : '.$name; ?></td>
      <?php } ?>
   <td width="100"> <?php echo Yii::t('app','Date').' : ';
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL)
						{	
							$date1=date($settings->displaydate,time());
							echo $date1;		
						}
						else
							echo date('d/m/Y');
			?>
       </td>
  </tr>
  
  <tr>
  <td style="padding:5px 0px;" colspan="2"><?php echo Yii::t('app','Course').' : '.$course->course_name; ?></td>
  </tr>
  <?php if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile'))
      { ?>
  <tr>
      
  <td style="padding:5px 0px;" colspan="2"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' : '.$batch->name; ?></td>
  </tr>
      <?php } ?>
  <tr>
  <td style=" padding:10px 0px;" colspan="2">
  <?php echo Yii::t('app','Address').' : '.$posts->address_line1.' , '.$posts->city.' , '.$posts->state;?>
  </td>
  </tr>
</table>

  </td>
  </tr>
  
  <tr>
  <td width="650" style="padding:10px 0px;">
  <div style="padding:20px 20px;">
<table width="760" border="0" cellspacing="0" cellpadding="0" class="tableinnerlist">
  <tr>
    <th style="border-top:#cad4dc 1px solid; border-left:#cad4dc 1px solid; background:#e4eaed;" width="190"><strong><?php echo Yii::t('app','Sl no.');?></strong></th>
    <th style="border-top:#cad4dc 1px solid; border-left:#cad4dc 1px solid; background:#e4eaed;" width="190"><strong><?php echo Yii::t('app','Particulars');?></strong></th>
    <th style="border-top:#cad4dc 1px solid; border-left:#cad4dc 1px solid; background:#e4eaed;" width="190"><strong><?php echo Yii::t('app','Amount');?></strong></th>
  </tr>
  <tr>
         <td style="border-left:#cad4dc 1px solid;"><?php echo '1'; ?></td>
         <td><?php echo Yii::t('app','Mess Fees'); ?></td>
          <td><?php echo $food->amount; ?></td>
        </tr>
   <?php
		  $amount = $amount + $food->amount;

		
		  ?>
          
          <tr>
        <td style="border-left:#cad4dc 1px solid;">&nbsp;</td>
        <td style="color:#333333; font-size:16px; text-align:right; background:#e4eaed;"><strong><?php echo Yii::t('app','Grand Total');?></strong></td>
        <td style="color:#333333; font-size:16px; background:#e4eaed;"><?php echo $amount;?> </td>
      </tr>
</table>


</div>

  </td>
  
  </tr>
  
  
  <tr>
  	<td><div>
<table width="750" border="0" cellspacing="0" cellpadding="0" style="padding:30px 0px;">
  <tr>
  	<td width="20"></td>
    <td width="200" align="left"><?php echo Yii::t('app','Month').' : '.date('F');?></td>
    <td width="200">&nbsp;</td>
    <td width="280" align="left"><?php echo Yii::t('app','Signature').' : ';?></td>
  </tr>
</table>

</div></td>
  </tr>
</table>

