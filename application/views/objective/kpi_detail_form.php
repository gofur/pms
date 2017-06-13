<h3>Create KPI</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li >Number<span class="divider"> / </span></li>
		  <li class="active">Detail<span class="divider"> / </span></li>
		  <li >Target</li>
	  </ul>
	</div>
</div>

<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>

<input type="hidden" name="hdn_rkk_id" id="hdn_rkk_id" value="<?php echo $rkk_id; ?>">
<input type="hidden" name="hdn_rkk_position_id" id="hdn_rkk_position_id" value="<?php echo $rkk_position_id; ?>">
<input type="hidden" name="hdn_so_id" id="hdn_so_id" value="<?php echo $so_id; ?>">
<input type="hidden" name="hdn_num" id="hdn_num" value="<?php echo $kpi_num; ?>">

<div class="row">
	<div class="span2">Begin Date</div>
	<div class="span3"><input type="text" <?php echo isset($old->KPI_BeginDate)?'readonly="readonly"':'' ?> name="TxtBeginDate" id="TxtBeginDate" class="input-small" value="<?php echo isset($old->KPI_BeginDate)?substr($old->KPI_BeginDate,0,10):substr($Periode->BeginDate,0,10)?>"></div>
	<div class="span2">End Date</div>
	<div class="span3"><input type="text"  name="TxtEndDate" id="TxtEndDate" class="input-small"  value="<?php echo isset($old->KPI_EndDate)?substr($old->KPI_EndDate,0,10):substr($Periode->EndDate,0,10)?>"></div>
</div>

