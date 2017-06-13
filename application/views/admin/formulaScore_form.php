<?php echo form_open($process,'id="genFrom"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="TxtPCFormulaID" id="TxtPCFormulaID" value="<?php echo isset($old->PCFormulaID)?$old->PCFormulaID:$PCFormulaID?>">
<input type="hidden" name="TxtPCFormulaScoreID" id="TxtPCFormulaScoreID" value="<?php echo isset($old->PCFormulaScoreID)?$old->PCFormulaScoreID:''?>">
<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span2">Score PA :</div>
	<div class="span10"><select name="SlcPCFormulaScore" id="SlcPCFormulaScore" class="input-small">
		<option value=""></option><?php 
		foreach ($score as $row) {
			if(isset($old->PCFormulaScore) and $row->PAScore==$old->PCFormulaScore){
				echo '<option selected="selected" value="'.$row->PAScore.'">'.$row->PAScore.'</option>';

			}else{
				echo '<option value="'.$row->PAScore.'">'.$row->PAScore.'</option>';
			}
		}
	?></select></div>
</div>
<div class="row">
	<div class="span2">Lower Bound :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtPCLow" id="TxtPCLow" value="<?php echo isset($old->PCLow)?$old->PCLow:''?>"> </div>
</div>
<div class="row">
	<div class="span2">Upper Bound :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtPCHigh" id="TxtPCHigh" value="<?php echo isset($old->PCHigh)?$old->PCHigh:''?>"> </div>
</div>
<div class="row">
	<div class="span2">Percentage :</div>
	<div class="span10"><input type="text" class="input-small" name="TxtPercentage" id="TxtPercentage" value="<?php echo isset($old->Percentage)?$old->Percentage:''?>"> </div>
</div>

<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>