<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3><?=$title?></h3>
<input type="hidden" name="TxtIDPDetailID" id="TxtIDPDetailID" value="<?php echo isset($old->IDPDetailID)?$old->IDPDetailID:''?>">
<input type="hidden" name="TxtIDPDevelopmentProgramID" id="TxtIDPDevelopmentProgramID" value="<?php echo isset($old->IDPDevelopmentProgramID)?$old->IDPDevelopmentProgramID:''?>">
<div class="row">
	<div class="span3">Development Area Type 1</div>
	<div class="span9"><input <?php echo isset($old->DevelopmentAreaType1ID)?'readonly="readonly"':'readonly="readonly"'?> type="text" class="input-large" name="txtDevAreaType" id="txtDevAreaType" value="<?=$DevelopmentAreaType1?>"> </div>
</div>
<div class="row">
	<div class="span3"></div>
	<div class="span9"><textarea readonly="readonly" name="txtDevAreaTypeDetail" id="txtDevAreaTypeDetail"><?=$DevelopmentAreaType?></textarea></div>
</div>

<div class="row">
	<div class="span3">Development Program</div>
	<div class="span9">
		<input readonly="readonly" type="text" class="input-large" name="txtDevProgram" id="txtDevProgram" value="<?=$DevelopmentProgram?>">
		<input readonly="readonly" type="hidden" class="input-large" name="txtDevProgramID" id="txtDevProgramID" value="<?=isset($old->DevelopmentProgramID)?>">
	</div>
</div>
<div class="row">
	<div class="span3">Description Development Program</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtDevProgram" id="txtDevProgram" readonly="readonly"><?php echo isset($old->Description)?$old->Description:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">-- Planned Time --</div>
	<div class="span9"></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input readonly="readonly" type="text" class="input-small" name="TxtBeginDate" id="TxtBeginDate" value="<?php echo isset($old->Planned_BeginDate)?substr($old->Planned_BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input readonly="readonly" type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->Planned_EndDate)?substr($old->Planned_EndDate,0,10) :date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">Planned Investment</div>
	<div class="span9">Rp. <input readonly="readonly" type="text" class="input-large" name="TxtPlanInvestment" id="TxtPlanInvestment" value="<?php echo thousand_separator(isset($old->Planned_Investment))?thousand_separator($old->Planned_Investment):''?>"></div>
</div>
<div class="row">
	<div class="span3">-- Realization Time --</div>
	<div class="span9"></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtRealizationBeginDate" id="TxtRealizationBeginDate" value="<?php echo isset($old->Realization_BeginDate)?substr($old->Realization_BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input type="text" class="input-small" name="TxtRealizationEndDate" id="TxtRealizationEndDate" value="<?php echo isset($old->Realization_EndDate)?substr($old->Realization_EndDate,0,10) :date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">Realization Investment</div>
	<div class="span9">Rp. <input type="text" class="input-large" name="TxtRealizationInvestment" id="TxtRealizationInvestment" value="<?php echo isset($old->Realization_Investment)?$old->Realization_Investment:''?>"></div>
</div>
<div class="row">
	<div class="span3">Notes</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtNotesADP" id="txtNotesADP"><?php echo isset($old->Notes)?$old->Notes:''?></textarea></div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>