<!-- 
 * vtigerCRM YiiPortal - web based vtiger CRM Customer Portal
 * Copyright (C) 2012 Gizur.
 *
 * This file is part of YiiPortal.
 * Create a new trouble tickets
 -->
<?php
$this->pageTitle=Yii::app()->name . ' - New Ticket for Survey ';

echo CHtml::metaTag($content='My page description', $name='decription');

$this->breadcrumbs=array(
        'Trouble Ticket / Survey',
);

?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'troubleticketsurvey',
	'enableClientValidation'=>true,
	'htmlOptions' => array(
    'enctype' => 'multipart/form-data'),
	'clientOptions'=>array(
	 'validateOnSubmit'=>true,
	 

	),
)); ?>
   <?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
<div style="background:#E5E5E5"><strong>Create New Damage Ticket</strong></div>	
<div align="center">
<table style="width:100%">
<tr>
 <td>

   <?php echo $form->labelEx($model,'Title'); ?>
   </td><td>
    <?php echo $form->textField($model,'Title'); ?>
    <?php echo $form->error($model,'Title'); ?>
   
  </td>

  <td>
  <?php echo $form->labelEx($model,'Ticket Category'); ?>
  </td><td>
  <?php echo $form->dropDownList($model,'Category', $category); ?>
  <?php echo $form->error($model,'Category'); ?>
  
  </td>
  </tr>
<tr>
 <td>

   <?php echo $form->labelEx($model,'Type of damage'); ?>
   </td><td>
   <?php echo $form->dropDownList($model,'Typeofdamage',$damagetype);?>
   <?php //echo $form->hiddenField($model,'Typeofdamage',array('type'=>"hidden",'size'=>2,'maxlength'=>6 ,'value'=>'abc')); ?>
    <?php echo $form->error($model,'Typeofdamage'); ?>
   
  </td>
  <td>
  <?php echo $form->labelEx($model,'Position on trailer for damage'); ?>
  </td><td>
     <?php echo $form->dropDownList($model,'Damageposition',$damagepos);?>
        <?php //echo $form->hiddenField($model,'Damageposition',array('type'=>"hidden",'size'=>2,'maxlength'=>60 ,'value'=>'Höger sida (Right side)')); ?>
    <?php echo $form->error($model,'Damageposition'); ?>
  
  </td>
  </tr>
    
  <tr>
   <td>
  <?php echo $form->labelEx($model,'Upload Pictures'); ?>
  </td>
  <td>
  <?php
    echo $form->fileField($model, 'image');
  ?>
  <?php echo $form->error($model,'image'); ?>

  </td>

    <td>
  <?php echo $form->labelEx($model,'Create Date'); ?>
  </td><td>
   <?php echo date('Y-m-d'); ?>
   <?php echo $form->hiddenField($model,'TroubleTicketType',array('type'=>"hidden",'size'=>2,'maxlength'=>2 ,'value'=>'damagereport')); ?>
  </td>
       
</tr>

</table>
 <?php echo CHtml::submitButton('Save', array('id'=>'submit','name'=>'submit')); ?>

<?php echo CHtml::endForm(); ?>
<?php $this->endWidget(); ?>