
<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<h3><?=$title?></h3>
<input type="hidden" name="TxtIDPDetailID" id="TxtIDPDetailID" value="<?php echo isset($old->IDPDetailID)?$old->IDPDetailID:''?>">
<input type="hidden" name="TxtIDPDevelopmentProgramID" id="TxtIDPDevelopmentProgramID" value="<?php echo isset($old->IDPDevelopmentProgramID)?$old->IDPDevelopmentProgramID:''?>">
<input type="hidden" name="TxtIDPDevAreaTypeID" id="TxtIDPDevAreaTypeID" value="<?php echo isset($oldDetail->DevelopmentAreaType1ID)?$oldDetail->DevelopmentAreaType1ID:''?>">
<div class="row">
	<div class="span3">Development Area Type 1</div>
	<div class="span9">
		<select name="SlcDevArea" id="SlcDevArea" <?php echo isset($add_realization)?'disabled="disable"':''?>>
		<option value=""></option>
	<?php
		foreach ($Development_Area_List as $row) {
			if(isset($oldDetail->DevelopmentAreaType1ID) and $row->DevelopmentAreaType1ID==$oldDetail->DevelopmentAreaType1ID){
				echo '<option selected="selected" value="'.$row->DevelopmentAreaType1ID.'">'.$row->DevelopmentAreaType1.'</option>';
			}else{
				echo '<option value="'.$row->DevelopmentAreaType1ID.'">'.$row->DevelopmentAreaType1.'</option>';
				
			}
		}
	?>
	</select>
		<!--<input <?php echo isset($old->DevelopmentAreaType1ID)?'readonly="readonly"':''?> type="text" class="input-large" name="txtDevAreaType" id="txtDevAreaType" value="<?=$DevelopmentAreaType1?>"> -->
		</div>
</div>

<div class="row">
	<div class="span3"></div>
	<div id="hiddenDivAreaType" class="span9"></div>
</div>
<!--
<div class="row">
	<div class="span3"></div>
	<div class="span9"><textarea readonly="readonly" name="txtDevAreaTypeDetail" id="txtDevAreaTypeDetail"><?php echo $DevelopmentAreaType; ?></textarea></div>
</div>
-->

<div class="row">
	<div class="span3">Development Program</div>
	<div class="span9"><select name="SlcDevProgram" id="SlcDevProgram" <?php echo isset($add_realization)?'disabled="disable"':''?>>
		<option value=""></option>
	<?php
		foreach ($Development_Program_List as $row) {
			if(isset($old->DevelopmentProgramID) and $row->DevelopmentProgramID==$old->DevelopmentProgramID){
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
	<div class="span9"><textarea <?php echo isset($add_realization)?'readonly="readonly"':''?>  rows="4" cols="100" name="txtDevProgram" id="txtDevProgram"><?php echo isset($old->Description)?$old->Description:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">-- Planned Time --</div>
	<div class="span9"></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input <?php echo isset($add_realization)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtBeginDate" id="TxtBeginDate" value="<?php echo isset($old->Planned_BeginDate)?substr($old->Planned_BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input <?php echo isset($add_realization)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->Planned_EndDate)?substr($old->Planned_EndDate,0,10) :date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">Planned Investment</div>
	<div class="span9">Rp. <input <?php echo isset($add_realization)?'readonly="readonly"':''?> type="text" class="input-large " name="TxtPlanInvestment" id="TxtPlanInvestment" value="<?php echo isset($old->Planned_Investment)?$old->Planned_Investment:''?>"></div>
</div>


<div class="row">
	<div class="span3">-- Realization Time --</div>
	<div class="span9"></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input type="text" class="input-small" name="txt_begindate_realization" id="txt_begindate_realization" value="<?php echo isset($old->Realization_BeginDate)?substr($old->Realization_BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input type="text" class="input-small" name="txt_enddate_realization" id="txt_enddate_realization" value="<?php echo isset($old->Realization_BeginDate)?substr($old->Realization_BeginDate,0,10) :date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">Realization Investment</div>
	<div class="span9">Rp. <input type="text" class="input-large " name="txt_realization_investment" id="txt_realization_investment" value="<?php echo isset($old->Realization_Investment)?$old->Realization_Investment:''?>"></div>
</div>

<div class="row">
	<div class="span3">Notes</div>
	<div class="span9"><textarea <?php echo isset($add_realization)?$old->Realization_Investment:''?> rows="4" cols="100" name="txtNotesADP" id="txtNotesADP"><?php echo isset($old->Notes)?$old->Notes:''?></textarea></div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>