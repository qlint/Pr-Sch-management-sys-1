<table width="300">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
    <tr>
        <td width="98"><label><?php echo Yii::t('app','Day of month'); ?> <span class="required">*</span></label></td>
        <td >
            <?php
				echo CHtml::activeDropDownList($subscription, 'monthday', array_combine(range(1,28),range(1,28)));
            ?>
        </td>
    </tr>       
</table>