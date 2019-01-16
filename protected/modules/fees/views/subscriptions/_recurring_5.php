<table width="300">
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
    <tr>
        <td  width="98"><label><?php echo Yii::t('app','Day of week'); ?> <span class="required">*</span></label></td>
        <td>
            <?php
				$weekdays	= array(
					1=>Yii::t("app", "Sunday"),
					2=>Yii::t("app", "Monday"),
					3=>Yii::t("app", "Tuesday"),
					4=>Yii::t("app", "Wednesday"),
					5=>Yii::t("app", "Thursday"),
					6=>Yii::t("app", "Friday"),
					7=>Yii::t("app", "Saturday"),
				);
				echo CHtml::activeDropDownList($subscription, 'weekday', $weekdays);
            ?>
        </td>
    </tr>       
</table>