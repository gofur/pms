
<?php

if($devAreaType==1)
{
	if(isset($disabled)!='')
	{
			foreach ($Kompetensi_List as $row) 
			{
				if(isset($KompetensiID) and $row->KompetensiID==$KompetensiID){
					echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->KompetensiID.'" readonly>';
					echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->Nama.'" readonly>';			
				}else{
					echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->KompetensiID.'" readonly>';
					echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->Nama.'" readonly>';			
				}	
			}
		}
		else
		{
			foreach ($Kompetensi_List as $row) 
			{
				if(isset($KompetensiID) and $row->KompetensiID==$KompetensiID){
					echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->KompetensiID.'" readonly>';	
				}
				else
				{
					echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->KompetensiID.'" readonly>';	
				}		
			}
	?>
	<select name="SlcSoftComp" id="SlcSoftComp" class="input-xlarge" <?php echo isset($disabled)?$disabled:'' ?>>
		<?php
		foreach ($Kompetensi_List as $row) {
			//if(isset($old->OrganizationID) and $row->OrganizationID==$current){
			if(isset($KompetensiID) and $row->KompetensiID==$KompetensiID){
					echo '<option selected="selected" value="'.$row->KompetensiID.'">'.$row->Nama.'</option>';			
				}else{
					echo '<option value="'.$row->KompetensiID.'">'.$row->Nama.'</option>';
				}	
		}
	?>
	</select>
	<?php
	}
}
else if($devAreaType==2)
{
	if(isset($DevelopmentAreaType1ID)!='')
	{
		echo '<textarea rows="4" cols="100" name="txtHardComp" id="txtHardComp">'.isset($Old->DevelopmentAreaType)?$Old->DevelopmentAreaType:''.'</textarea>';
	}
	else
	{
		echo '<textarea rows="4" cols="100" name="txtHardComp" id="txtHardComp" required></textarea>';
	}
}
else
{
	if(isset($disabled)!='')
	{
			foreach ($CV_Value_List as $row) 
			{
				if(isset($ValuesID) and $row->values_id==$ValuesID){
					echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->values_id.'" readonly>';
					echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->value_name.'" readonly>';			
				}else{
					echo '<input type="hidden" name="TxtPositionID" id="TxtPositionID" value="'.$row->values_id.'" readonly>';
					echo '<input type="text" name="TxtPositionName" id="TxtPositionName" value="'.$row->value_name.'" readonly>';			
				}	
			}
		}
		else
		{
			foreach ($CV_Value_List as $row) 
			{
				if(isset($ValuesID) and $row->values_id==$ValuesID){
					echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->values_id.'" readonly>';	
				}
				else
				{
					echo '<input type="hidden" name="TxtPositionIDOld" id="TxtPositionIDOld" value="'.$row->value_name.'" readonly>';	
				}		
			}
	?>
	<select name="SlcValuesComp" id="SlcValuesComp" class="input-xlarge" <?php echo isset($disabled)?$disabled:'' ?>>
		<?php
		foreach ($CV_Value_List as $row) {
			if(isset($ValuesID) and $row->values_id==$ValuesID){
					echo '<option selected="selected" value="'.$row->values_id.'">'.$row->value_name.'</option>';			
				}else{
					echo '<option value="'.$row->values_id.'">'.$row->value_name.'</option>';
				}	
		}
	?>
	</select>
	<?php
	}
}
?>