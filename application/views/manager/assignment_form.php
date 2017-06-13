<?php echo form_open($process,'id="additionalAssignFrom"');?>
<input type="hidden" name="TxtNIKBawahan" id="TxtNIKBawahan" value="<?php echo $NIKBawahan ;?>">
<input type="hidden" name="TxtPositionIDBawahan" id="TxtPositionIDBawahan" value="<?php echo $PositionIDBawahan ;?>">
<input type="hidden" name="TxtAssignmentID" id="TxtAssignmentID" value="<?php echo isset($old->AssignmentID)?$old->AssignmentID:''?>">
<input type="hidden" name="TxtAssignmentStatus" id="TxtAssignmentStatus" value="<?php echo isset($old->AssignmentStatus)?$old->AssignmentStatus:''?>">
<div class="row">
	<div class="span3">Additional Assignment Unit:</div>
	<div class="span9">

<?php 
	
	if(isset($disabled)!='')
	{
		foreach ($organizationTypeSAP as $rowSAP) 
		{		
			if(isset($old->OrganizationID) and $rowSAP->OrganizationID==$old->OrganizationID){
				echo '<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="'.$rowSAP->OrganizationID.'" readonly>';
				echo '<input type="text" name="TxtOrganizationID" id="TxtOrganizationID" value="'.$rowSAP->OrganizationName.'" readonly>';					
				}
		}

		foreach ($organizationTypenonSAP as $rowNonSAP) 
			{
				if(isset($old->OrganizationID) and $rowNonSAP->OrganizationID==$old->OrganizationID){
				echo '<input type="hidden" name="TxtOrganizationID" id="TxtOrganizationID" value="'.$rowNonSAP->OrganizationID.'" readonly>';					
				echo '<input type="text" name="TxtOrganizationID" id="TxtOrganizationID" value="'.$rowNonSAP->OrganizationName.'" readonly>';		
				}
			}	
	}
	else
	{
		foreach ($organizationTypeSAP as $rowSAP) 
		{		
			if(isset($OrganizationID) and $rowSAP->OrganizationID==$OrganizationID){
				echo '<input type="hidden" name="TxtOrganizationIDOld" id="TxtOrganizationID" value="'.$rowSAP->OrganizationID.'" readonly>';					
				}
		}

		foreach ($organizationTypenonSAP as $rowNonSAP) 
			{
				if(isset($old->OrganizationID) and $rowNonSAP->OrganizationID==$old->OrganizationID){
				echo '<input type="hidden" name="TxtOrganizationIDOld" id="TxtOrganizationID" value="'.$rowNonSAP->OrganizationID.'" readonly>';					
				}
			}
?>
		<select name="SlcOrgID" id="SlcOrgID" class="input-large" <?php echo isset($disabled)?$disabled:'' ?>>
		<option value=""></option><?php 
		foreach ($organizationTypeSAP as $rowSAP) 
		{		
			//if(isset($old->OrganizationID) and $rowSAP->OrganizationID==$old->OrganizationID){
			if(isset($OrganizationID) and $rowSAP->OrganizationID==$OrganizationID){
				echo '<option selected="selected" value="'.$rowSAP->OrganizationID.'">'.$rowSAP->OrganizationName.'</option>';
				}else{
					echo '<option value="'.$rowSAP->OrganizationID.'">'.$rowSAP->OrganizationName.'</option>';
				}
		}

		foreach ($organizationTypenonSAP as $rowNonSAP) 
			{
				if(isset($old->OrganizationID) and $rowNonSAP->OrganizationID==$old->OrganizationID){
				echo '<option selected="selected" value="'.$rowNonSAP->OrganizationID.'">'.$rowNonSAP->OrganizationName.'</option>';

				}else{
					echo '<option value="'.$rowNonSAP->OrganizationID.'">'.$rowNonSAP->OrganizationName.'</option>';
				}
			}
	?></select>
	<?php
	}
	?>
	</div>
</div>
<div class="row">
	<div class="span3">Additional Assignment Position:</div>
	<div id="hiddenDiv1" class="span9"></div>
</div>
<div class="row">
	<div class="span3">Bobot:</div>
	<div class="span9"><input type="text" class="input-small" name="TxtBobot" id="TxtBobot" value="<?php echo isset($old->Bobot)?$old->Bobot:''?>"> %</div>
</div>
<div class="row">
	<div class="span3">Keterangan:</div>
	<div class="span9"><textarea name="TxtKeterangan" id="TxtKeterangan"><?php echo isset($old->Description)?$old->Description:''?></textarea></div>
</div>
<div class="row">
	<div class="span3">Start Date :</div>
	<div class="span9"><input <?php echo isset($old->BeginDate)?'readonly="readonly"':''?> type="text" class="input-small" name="TxtStartDate" id="TxtStartDate" value="<?php echo isset($old->BeginDate)?substr($old->BeginDate,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span3">End Date :</div>
	<div class="span9"><input type="text" class="input-small" name="TxtEndDate" id="TxtEndDate" value="<?php echo isset($old->EndDate)?substr($old->EndDate,0,10) :'9999/12/31'?>"> </div>
</div>
<div class="row">
	<div class="span9 offset3">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php form_close();?>