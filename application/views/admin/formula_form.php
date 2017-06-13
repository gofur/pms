<?php echo form_open($process,'id="genFrom"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtPCFormulaID" id="TxtPCFormulaID" value="<?php echo isset($old->PCFormulaID)?$old->PCFormulaID:''?>">

<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Formula Name :</div>
	<div class="span10"><input type="text" class="input-medium" name="TxtPCFormula" id="TxtPCFormula" value="<?php echo isset($old->PCFormula)?$old->PCFormula:''?>"> </div>
</div>
<div class="row">
	<div class="span2">Counting Type :</div>
	<div class="span10"><select name="SlcCaraHitungID" id="SlcCaraHitungID" class="input-medium">
		<option value=""></option><?php 
		foreach ($countType as $row) 
		{
			if(isset($old->CaraHitungID) and $row->CaraHitungID==$old->CaraHitungID){
				echo '<option selected="selected" value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';

			}else{
				echo '<option value="'.$row->CaraHitungID.'">'.$row->CaraHitung.'</option>';
			}
		}
	?></select></div>
</div>
<div class="row">
	<div class="span2">Preception :</div>
	<div class="span10"><select name="SlcPerception" id="SlcPreception" class="input-medium">
		<option value=""></option>
		<?php 
			$opt = array('max'=>'Maximum','min'=>'Minimum');

			foreach ($opt as $key => $value) {
				if ($key == $old->Perception) {
					echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
						
				} else {
					echo '<option value="'.$key.'">'.$value.'</option>';
					
				}
			}
		?>
		
	</select></div>
</div>
<hr>
<div class="row">
	<div class="span12">
		Digunakan untuk memberikan nilai <i>default</i> jika pengisian Pencapaian di <i>Skip</i>.
	</div>
</div>
<div class="row">
	<div class="span2">Operator :</div>
	<div class="span10"><select class="input-medium" name="SlcOperator" id="SlcOperator">
		<option value=""></option>
		<?php
			$option = array('A'=>'Add (+)','S'=>'Substract (-)','M'=>'Multiply (x)','D'=>'Divide (:)');
			foreach ($option as $key => $value) {
				if (isset($old->Operator)and $key==$old->Operator ){
					echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
				}else{
					echo '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		?>
	</select></div>
</div>
<div class="row">
	<div class="span2">Skip Constancy :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtSkipConstancy" id="TxtSkipConstancy" value="<?php echo isset($old->SkipConstancy)?$old->SkipConstancy:''?>"></div>
</div>
<hr>
<div class="row">
	<div class="span2">Notes :</div>
	<div class="span10"><textarea name="TxtNotes" id="TxtNotes"><?php echo isset($old->Notes)?$old->Notes:''?></textarea></div>
</div>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>