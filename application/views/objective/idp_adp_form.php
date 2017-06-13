<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3>Add Development Plan</h3>
<input type="hidden" name="TxtRKKID" id="TxtRKKID" value="<?php echo isset($RKKID)?$RKKID:'' ?>">
<input type="hidden" name="TxtOrgID" id="TxtOrgID" value="<?php echo isset($OrgID)?$OrgID:'' ?>">
<div class="row">
	<div class="span3">Development Area Type 1</div>
	<div class="span9"><select name="SlcDevArea" id="SlcDevArea">
		<option value=""></option>
	<?php
		foreach ($Development_Area_List as $row) {
			if(isset($Old->DevelopmentAreaType1ID) and $row->DevelopmentAreaType1ID==$Old->DevelopmentAreaType1ID){
				echo '<option selected="selected" value="'.$row->DevelopmentAreaType1ID.'">'.$row->DevelopmentAreaType1.'</option>';
			}else{
				echo '<option value="'.$row->DevelopmentAreaType1ID.'">'.$row->DevelopmentAreaType1.'</option>';
				
			}
		}
	?>
	</select></div>
</div>
<div class="row">
	<div class="span3"></div>
	<div id="hiddenDivAreaType" class="span9"></div>
</div>

<div class="row">
	<div class="span3">Development Program</div>
	<div class="span9"><select name="SlcDevProgram" id="SlcDevProgram">
		<option value=""></option>
	<?php
		foreach ($Development_Program_List as $row) {
			if(isset($Old->DevelopmentProgramID) and $row->DevelopmentProgramID==$Old->DevelopmentProgramID){
				echo '<option selected="selected" value="'.$row->DevelopmentProgramID.'">'.$row->DevelopmentProgram.'</option>';
			}else{
				echo '<option value="'.$row->DevelopmentProgramID.'">'.$row->DevelopmentProgram.'</option>';
				
			}
		}
	?>
	</select></div>
</div>
<div class="row">
	<div class="span3">Description Development Program</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtDevProgram" id="txtDevProgram"></textarea></div>
</div>
<div class="row">
	<div class="span3">-- Planned Time --</div>
	<div class="span9"></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtBeginDate" id="TxtBeginDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">Planned Investment</div>
	<div class="span9">Rp. <input type="text" class="input-large " name="TxtPlanInvestment" id="TxtPlanInvestment" value=""></div>
</div>
<div class="row">
	<div class="span3">Notes</div>
	<div class="span9"><textarea rows="4" cols="100" name="txtNotesADP" id="txtNotesADP"></textarea></div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>