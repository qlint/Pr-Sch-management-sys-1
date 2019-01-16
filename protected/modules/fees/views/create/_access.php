
<?php
	$access		= new FeeParticularAccess;
?>
   
<div class="particular-access" data-row="<?php echo $acrow;?>">
<div class="applicable-to">
	<table>
		<tr>
			<td>
				<?php echo CHtml::activeDropDownList($access, "[".$ptrow."]access_type[".$acrow."]", array(1=>Yii::t("app", 'Default'), 2=>Yii::t("app", 'Admission number')), array('class'=>'particular-access-type', 'style'=>'width:120px !important;'));?>
			</td>                
			<td class="access-datas">
            	<?php $this->renderPartial('_access_1',array('ptrow'=>$ptrow, 'acrow'=>$acrow, 'access'=>$access));?>				
			</td>
            <td>
				<?php echo CHtml::activeTextField($access, "[".$ptrow."]amount[".$acrow."]", array('placeholder'=>Yii::t("app", "Amount"), 'style'=>'width:70px !important; padding-top:6px; padding-bottom:6px;'));?>
            </td>
            <td>
			<div style="position:relative;">
            	<a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove access");?>" class="remove-access fees-trash" style="top:-9px; right:-25px;" data-row="<?php echo $ptrow;?>"><?php echo Yii::t("app", "");?></a>
				</div>
            </td>
		</tr>
	</table>
</div>
</div>