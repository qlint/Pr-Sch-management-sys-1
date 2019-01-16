<tr <?php if($transaction->is_deleted==1 and $transaction->deleted_by!=NULL){?>style="text-decoration:line-through;" title="Removed by `<?php echo $transaction->deletedUser;?>`" <?php } ?>>
    <td align="center"><?php echo $count;?></td>
    <td height="18">
    	<?php
			if($settings!=NULL)
				echo date($settings->displaydate, strtotime($transaction->date));
			else
				echo $transaction->date;
		?>
    </td>                                            
    <td align="center"><?php echo $transaction->transactionType;?></td>
    <td align="center"><?php echo ($transaction->transaction_id!=NULL)?$transaction->transaction_id:"-";?></td>
    <td align="center"><?php echo ($transaction->description!=NULL)?$transaction->description:"-";?></td>                                            
    <td align="center"><?php echo number_format($transaction->amount, 2);?></td>
    <td align="center">
        <?php			
            if($transaction->proof!=NULL){
				if($transaction->is_deleted==1 and $transaction->deleted_by!=NULL)
					echo Yii::t("app", "Yes");
				else
             		echo CHtml::link(Yii::t("app", "Download"), array("/fees/transactions/download", "id"=>$transaction->id));
			}
            else
                echo Yii::t("app", "No");
		?>
    </td>
    <td align="center">
        <?php
            if($transaction->status==0){
                echo Yii::t('app', 'Pending');
            }
            else if($transaction->status==1){
                echo Yii::t('app', 'Completed');
            }
            else if($transaction->status==-1){
                echo Yii::t('app', 'Failed');
            }
            else{
                echo '-';
            }
        ?>
    </td>
    <td align="center">
    	<?php
		
		if($transaction->is_deleted==1 and $transaction->deleted_by!=NULL)
			echo "-";
		else{
		?>
    	<a href="javascript:void(0);" data-transaction-id="<?php echo $transaction->id;?>" class="remove-transaction"><?php echo Yii::t("app", "Remove");?></a>
        <?php
		}
		?>
  	</td>
</tr>