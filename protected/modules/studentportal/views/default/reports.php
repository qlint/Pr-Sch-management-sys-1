
	 <!--navigation ends here-->
     <!--banner starts here-->
     <!--<section id="innerbanner"><img src="images/innerbanner.png" width="1000" height="168"></section>-->
      <!--banner ends here-->
      <!--midsection starts here-->
      
      <!--midsection ends here-->
      <!--innersection starts here-->
      <div id="parent_Sect">
        <?php $this->renderPartial('leftside');?> 
        <?php
		$student=Students::model()->findByAttributes(array('id'=>12));
		$exam = ExamScores::model()->findAll("student_id=:x", array(':x'=>12));
		
		?>
        <div id="parent_rightSect">
        	<div class="parentright_innercon">
            <h1><?php echo Yii::t('app','Reports'); ?></h1>
            <div class="profile_top">
               	<div class="prof_img">
                <?php
				 if($student->photo_file_name){
					$path = Students::model()->getProfileImagePath($student->id);  
   				 	echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="64" />';
	 }else{
		echo '<img  src="images/portal/prof-img001.png" alt='.$student->first_name.' width="100" height="103" />'; 
	 }
				?>
                </div>
                <h2><?php echo $student->last_name.' '.$student->first_name;?></h2>
                <ul>
                	<li class="rleft"><?php echo Yii::t('app','Course'); ?> :</li>
                    <li class="rright">
                    <?php 
					$posts=Batches::model()->findByPk($student->batch_id);
					echo $posts->course123->course_name;
					?>
                  	</li>
                    <li class="rleft"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></li>
                    <li class="rright"><?php $batch=Batches::model()->findByAttributes(array('id'=>$student->batch_id));
		 				echo $batch->name;?></li>
                    <li class="rleft"><?php echo Yii::t('app','Admission No'); ?> :</li>
                    <li class="rright"><?php echo $student->admission_no; ?></li>
                </ul>
               	</div>
       		            
              
         
                
            </div>
        </div>
        <div class="clear"></div>
      </div>
      <!--innersection ends here-->
