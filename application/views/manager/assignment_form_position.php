
<?php
if(isset($disabled)!='')
	{
		foreach ($PositionList as $row) 
		{
			if(isset($OrganizationID) and $row->OrganizationID==$OrganizationID){
				echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->PositionID.'" readonly>';
				echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->PositionName.'" readonly>';			
			}else{
				echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->PositionID.'" readonly>';
				echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->PositionName.'" readonly>';			
			}	
		}
	}
	else
	{

		foreach ($PositionList as $row) 
		{
			if(isset($OrganizationID) and $row->OrganizationID==$OrganizationID){
				echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->PositionID.'" readonly>';	
			}
			else
			{
				echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->PositionID.'" readonly>';	
			}		
		}
?>
<select name="SlcPosition" id="SlcPosition" class="input-medium" <?php echo isset($disabled)?$disabled:'' ?>>
	<?php
	foreach ($PositionList as $row) {
		//if(isset($old->OrganizationID) and $row->OrganizationID==$current){
		if(isset($OrganizationID) and $row->OrganizationID==$OrganizationID){
				echo '<option selected="selected" value="'.$row->PositionID.'">'.$row->PositionName.'</option>';			
			}else{
				echo '<option value="'.$row->PositionID.'">'.$row->PositionName.'</option>';
			}	
	}
?>
</select>
<?php
}
?>