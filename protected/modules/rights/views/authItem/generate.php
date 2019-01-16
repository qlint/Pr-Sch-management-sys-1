<?php $this->breadcrumbs = array(
	Yii::t('app','Rights')=>Rights::getBaseUrl(),
	Yii::t('app', 'Generate items'),
); ?>

<div id="generator">

	<h1><?php echo Yii::t('app', 'Generate items'); ?></h1>

	<p><?php echo Yii::t('app', 'Please select which items you wish to generate.'); ?></p>

	<div class="form">

		<?php $form=$this->beginWidget('CActiveForm'); ?>

			<div class="row">

				<table class="items generate-item-table" border="0" cellpadding="0" cellspacing="0">

					<tbody>

						<tr class="augen-heading-row">
							<th colspan="3"><?php echo Yii::t('app', 'Application'); ?></th>
						</tr>

						<?php $this->renderPartial('_generateItems', array(
							'model'=>$model,
							'form'=>$form,
							'items'=>$items,
							'existingItems'=>$existingItems,
							'displayModuleHeadingRow'=>true,
							'basePathLength'=>strlen(Yii::app()->basePath),
						)); ?>

					</tbody>

				</table>

			</div>

			<div class="row">

   				<?php echo CHtml::link(Yii::t('app', 'Select all'), '#', array(
   					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); return false;",
   					'class'=>'selectAllLink')); ?>
   				/
				<?php echo CHtml::link(Yii::t('app', 'Select none'), '#', array(
					'onclick'=>"jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); return false;",
					'class'=>'selectNoneLink')); ?>

			</div>

   			<div style="padding-top:10px;">

				<?php echo CHtml::submitButton(Yii::t('app', 'Generate'),array('class'=>'formbut')); ?>

			</div>

		<?php $this->endWidget(); ?>

	</div>

</div>