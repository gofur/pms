<select name="SlcFormula" id="SlcFormula" class="input-medium"><?php
	foreach ($formulaList as $row) {
		if(isset($old->SatuanID) and $row->SatuanID==$current){
				echo '<option selected="selected" value="'.$row->PCFormulaID.'">'.$row->PCFormula.'</option>';
			}else{
				echo '<option value="'.$row->PCFormulaID.'">'.$row->PCFormula.'</option>';
			}
		
	}
?>
</select>