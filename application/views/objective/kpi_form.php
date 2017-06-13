<h3><?php echo $title?> KPI</h3>
<?php if ($title=='Create') {?>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li >1. <?php echo $title ?> KPI <span class="divider">-></span></li>
		  <li class="active">2. <?php echo $title ?> Target</li>
	  </ul>
	</div>
</div>
<?php } ?>
<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<input type="hidden" name="TxtSOID" id="TxtSOID" value="<?php echo isset($SOID)?$SOID:'' ?>">
<input type="hidden" name="hdn_RKKDetailID" id="hdn_RKKDetailID" value="<?php echo isset($RKKDetailID)?$RKKDetailID:'' ?>">

<input type="hidden" name="TxtRKKID" id="TxtRKKID" value="<?php echo isset($RKKID)?$RKKID:'' ?>">
<input type="hidden" name="TxtRKKPositionID" id="TxtRKKPositionID" value="<?php echo isset($RKKPositionID)?$RKKPositionID:'' ?>">
<input type="hidden" name="TxtKPIID" id="TxtKPIID" value="<?php echo isset($old->KPIID)?$old->KPIID:'' ?>">
<div class="row">
	<div class="span2">Begin Date</div>
	<div class="span3"><input type="text" name="TxtBeginDate" id="TxtBeginDate" class="input-small" value="<?php echo isset($old->KPI_BeginDate)?substr($old->KPI_BeginDate,0,10):substr($Periode->BeginDate,0,10)?>"></div>
	<div class="span2">End Date</div>
	<div class="span3"><input type="text"  name="TxtEndDate" id="TxtEndDate" class="input-small"  value="<?php echo isset($old->KPI_EndDate)?substr($old->KPI_EndDate,0,10):substr($Periode->EndDate,0,10)?>"></div>
</div>
<div class="row">
	<div class="span2">KPI Generic</div>
	<div class="span8"><select name="SlcGeneric" id="SlcGeneric" class="input-large">
		<option value=""></option>
		<?php
		foreach ($genericKPI as $row) {
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
<div id="SectionGen">
	<div class="row">
		<div class="span2">KPI</div>
		<div class="span3"><input type="text" name="TxtKPI" id="TxtKPI" class="input-large" value="<?php echo isset($old->KPI)?$old->KPI:'' ;?>"></div>
		<div class="span2">Description</div>
		<div class="span3"><textarea name="TxtDesc" id="TxtDesc" class="input-large" rows="3"><?php echo isset($old->Description)?$old->Description:'' ;?></textarea></div>
	</div>
	<div class="row">
		<div class="span2">Satuan</div>
		<div class="span3"><select name="SlcSatuan" id="SlcSatuan" class="input-medium">
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
		<div class="span3"><select name="SlcFormula" id="SlcFormula" class="input-medium">
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
		<div class="span2">YTD</div>
		<div class="span8"><select name="SlcYTD" id="SlcYTD" class="input-medium">
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
	<div class="span3"><input type="text" name="TxtWeight" id="TxtWeight" class="input-small " value="<?php echo isset($old->Bobot)?number_format($old->Bobot,2):'0' ;?>"></div>
	<div class="span2">Baseline</div>
	<div class="span3"><input type="text" name="TxtBaseline" id="TxtBaseline" class="input-small " value="<?php echo isset($old->Baseline)?$old->Baseline:'0' ;?>"></div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>
