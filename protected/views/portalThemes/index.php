<?php
$this->breadcrumbs=array(
        Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Portal Themes')
	
);

$roles=Rights::getAssignedRoles(Yii::app()->user->Id);
foreach($roles as $data)
{
$role= $data->name;
}
if($role=='Admin'){ $user_id= 0; } else { $user_id= Yii::app()->user->id; }

$left= '/configurations/left_side';
    $user_role="admin";
            $role= Rights::getAssignedRoles(Yii::app()->user->id);
            if(sizeof($role)==1 && key($role)=="student")
            {
                $user_role="student";
                $left= 'application.modules.studentportal.views.default.leftside'; 
            }
            if(sizeof($role)==1 && key($role)=="parent")
            {
                $user_role="parent";
                $left= 'application.modules.parentportal.views.default.leftside'; 
            }
            if(sizeof($role)==1 && key($role)=="teacher")
            {
                $user_role="teacher";
                $left= 'application.modules.teachersportal.views.default.leftside'; 
            }

?>
<?php
if($user_role=="admin")
{
    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td width="247" valign="top">
            <?php $this->renderPartial('/configurations/left_side');?>        
        </td>
        <td valign="top">
          <div class="cont_right"> 
              <div class="edit_bttns last"><ul><li><li>
                         
                <?php 
                $themes_model= PortalThemes::model()->findByAttributes(array('user_id'=>$user_id));
                if($themes_model)
                {  
                    echo CHtml::link('<span>'.Yii::t('app','Set Default Theme').'</span>', array('delete', 'id'=>$themes_model->id),array('class'=>'','confirm'=>Yii::t('app','Are You Sure?')));
                } 
                ?>
                    </li>
                    </ul>
            </div>
            <h1><?php echo Yii::t('app','Manage Portal Themes'); ?></h1>
            <?php 
            $themes_model= PortalThemes::model()->findByAttributes(array('user_id'=>$user_id));
            if($themes_model)
                {  
                    echo $this->renderPartial('_form', array('model'=>$themes_model,'status'=>1));
                }
                else
                {
                    echo $this->renderPartial('_form', array('model'=>new PortalThemes,'status'=>0));
                }
                ?>
          </div>
        </td>
        </tr>
</table>
<?php
}
else
{
    ?>
        <div class="pageheader">
          <div class="col-lg-8">
            <h2><i class="fa fa-tint"></i><?php echo Yii::t("app",'Themes');?></h2>
          </div>
          <div class="col-lg-2">
              </div>
          <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t("app",'You are here:');?></span>
            <ol class="breadcrumb">
              <!--<li><a href="index.html">Home</a></li>-->

              <li class="active"><?php echo Yii::t("app",'Themes')?></li>
            </ol>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php $this->renderPartial($left); ?>
        <div class="contentpanel">
            <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t("app",'Manage Theme');?></h3>


            </div>
            <div class="people-item">
            
             <div class="opnsl_headerBox">
             <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
                        	

                            <div class="opnsl_actn_box1">
                             <?php 
                $themes_model= PortalThemes::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                if($themes_model)
                {  
                    echo CHtml::link('<span>'.Yii::t('app','Set Default Theme').'</span>', array('delete', 'id'=>$themes_model->id),array('class'=>"btn btn-primary",'confirm'=>Yii::t('app','Are You Sure?')));
                } 
				?>
                            </div>
							
							
							
                            <div class="opnsl_actn_box1">
                            <?php
                echo CHtml::link('<span>'.Yii::t('app','Set Admin Theme').'</span>', array('set'),array('class'=>"btn btn-primary",'confirm'=>Yii::t('app','Are You Sure?')));
                ?>
                            </div>
                               	</div>
                       		 
                   	 </div>
                     
<div class="row">
<div class="col-md-12">
            <?php 
            $themes_model= PortalThemes::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
            if($themes_model)
                {  
                    echo $this->renderPartial('portal_form', array('model'=>$themes_model,'status'=>1));
                }
                else
                {
                    echo $this->renderPartial('portal_form', array('model'=>new PortalThemes,'status'=>0));
                }
                ?>
                </div>
                </div>
                    
                </div>
            </div>
  
        <?php
}


?>







