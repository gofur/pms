<hr>
<div class="row">
	<div class="span2">KPI Generic</div>
	<div class="span8"><select name="slc_generic_<?php echo $num_code ;?>" id="slc_generic_<?php echo $num_code ;?>" class="input-large">
		<option value=""></option>
		<?php
		foreach ($generic_kpi as $row) {
			if(isset($old->KPIGenericID) and $old->KPIGenericID==$row->KPIGenericID){
				echo '<option selected="selected" value="'.$row->KPIGenericID.'"">'.$row->KPI.'</option>';
			}else{
				echo '<option value="'.$row->KPIGenericID.'"">'.$row->KPI.'</option>';
			}
		}
		if(isset($old->KPIGenericID) and $old->KPIGenericID==0){
			echo '<option selected="selected" value="other">Other</option>';
		}else{
			echo '<option value="other">Other</option>';
		}
		?>
	</select></div>
</div>
<div id="section_gen_<?php echo $num_code ;?>">
	<div class="row">
		<div class="span2">KPI</div>
		<div class="span3"><input type="text" name="txt_kpi_<?php echo $num_code ;?>" id="txt_kpi_<?php echo $num_code ;?>" class="input-large" value="<?php echo isset($old->KPI)?$old->KPI:'' ;?>"></div>
		<div class="span2">Description</div>
		<div class="span3"><textarea name="txt_desc_<?php echo $num_code ;?>" id="txt_desc_<?php echo $num_code ; ?>" class="input-large" rows="3"><?php echo isset($old->Description)?$old->Description:'' ;?></textarea></div>
	</div>
	<div class="row">
		<div class="span2">Unit</div>
		<div class="span3"><select name="slc_satuan_<?php echo $num_code ;?>" id="slc_satuan_<?php echo $num_code ;?>" class="input-medium">
			<?php
			foreach ($Unit_list as $row) {
				if(isset($old->SatuanID) and $old->SatuanID== $row->SatuanID){
					echo '<option selected="selected" value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';

				}else{
					echo '<option value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';
					
				}
			}
			?>
		</select></div>

		<div class="span2">Formula</div>
		<div class="span3"><select name="slc_formula_<?php echo $num_code ;?>" id="slc_formula_<?php echo $num_code ;?>" class="input-medium">
			<?php
			foreach ($Formula_list as $row) {
				if(isset($old->PCFormulaID) and $old->PCFormulaID==$row->PCFormulaID){
					echo '<option selected="selected" value="'.$row->PCFormulaID.'">'.$row->PCFormula.'</option>';

				}else{
					echo '<option value="'.$row->PCFormulaID.'">'.$row->PCFormula.'</option>';
					
				}
			}
			?>
		</select></div>
	</div>
	<div class="row">
		<div class="span2"></div>
		<div class="span8"><select name="slc_ytd_<?php echo $num_code ;?>" id="slc_ytd_<?php echo $num_code ;?>" class="input-medium">
			<?php
			foreach ($Ytd_list as $row) {
				if(isset($old->YTDID) and  $old->YTDID==$row->YTDID){
					echo '<option selected="selected" value="'.$row->YTDID.'">'.$row->YTD.'</option>';
				}else{
					echo '<option value="'.$row->YTDID.'">'.$row->YTD.'</option>';
				}
			}
			?>
		</select></div>
	</div>
	
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span3"><input type="text" name="txt_weight_<?php echo $num_code ;?>" id="txt_weight_<?php echo $num_code ;?>" class="input-small " value="<?php echo isset($old->Bobot)?$old->Bobot:'0' ;?>"></div>
	<div class="span2">Baseline</div>
	<div class="span3"><input type="text" name="txt_baseline_<?php echo $num_code ;?>" id="txt_baseline_<?php echo $num_code ;?>" class="input-small " value="<?php echo isset($old->Baseline)?$old->Baseline:'0' ;?>"></div>
</div>