
<?php
$this->breadcrumbs=array(
	Yii::t('app','Exam Groups')=>array('/examination'),
	Yii::t('app',$model->name),
);
?>

<h1><?php echo Yii::t('app','Exam Group Details');?></h1>

<?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id)); ?>
<div class="tableinnerlist">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td><?php echo Yii::t('app','Exam Group Name');?></td>
    <td><?php echo $model->name; ?></td>
  </tr>
    <tr>
    <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
    <td><?php
    $posts=Batches::model()->findByAttributes(array('id'=>$model->batch_id));
	echo $posts->name;
	?></td>
  </tr>
   <tr>
    <td><?php echo Yii::t('app','Term');?></td>
    <td><?php if($model->term_id == 1)
				{
					echo "Term 1";
				}
				else
				{
					echo "Term 2";
				} ?></td>
  </tr>
    <tr>
    <td><?php echo Yii::t('app','Exam Type');?></td>
    <td><?php echo $model->exam_type; ?></td>
  </tr>
  <tr>
    <td><?php echo Yii::t('app','Date Published');?></td>
    <td><?php if($model->date_published == 1)
				{
					echo "Yes";
				}
				else
				{
					echo "No";
				}?></td>
  </tr>
   <tr>
    <td><?php echo Yii::t('app','Result Published');?></td>
    <td><?php if($model->result_published == 1)
				{
					echo "Yes";
				}
				else
				{
					echo "No";
				} ?></td>
  </tr>
  <tr>
    <td><?php echo Yii::t('app','Exam Date');?></td>
    <td><?php  if($settings!=NULL)
				{
					$date1 = date($settings->displaydate,strtotime($model->date));
					echo $date1;
				}
				else
				{
					echo $model->date;
				}?> </td>
  </tr>
</table>
</div>