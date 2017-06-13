<h3>Cascade KPI</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li >1. Select Subordinate <span class="divider">-></span></li>
		  <li class="active">2. Cascading KPI</li>
	  </ul>
	</div>
</div>
<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<input type="hidden" name="TxtChief_KPIID" id="TxtChief_KPIID" value="<?php echo $Chief_KPI->KPIID ?>">

<div class="row">
	<div class="span2">Chief KPI</div>
	<div class="span10"><?php echo $KPI_head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">Count Type</div>
	<div class="span10"><?php echo $KPI_head->CaraHitung ?></div>
</div>
<div class="row">
	<div class="span2">YTD</div>
	<div class="span10"><?php echo $KPI_head->YTD ?></div>
</div>
<div class="row">
	<div class="span2">Formula</div>
	<div class="span10"><?php echo $KPI_head->PCFormula ?></div>
</div>
<div class="row">
	<div class="span2">Bobot</div>
	<div class="span10"><?php echo $KPI_head->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Target</div>
	<div class="span10"><?php echo thousand_separator($KPI_head->TargetAkhirTahun) .' ('.$KPI_head->Satuan.')'?></div>
</div>
<div class="row">
	<div class="span2">Reference</div>
	<div class="span10"><select name="SlcRef" id="SlcRef" class="input-medium">
		<?php
			foreach ($Reference_list as $row) {
				echo '<option value="'.$row->ReferenceID.'">'.$row->Reference.'</option>';
			}
		?>
	</select></div>
</div>


<?php
	$i=1;
	foreach ($Subordinate as $row) {
		if($i%2==1){
			echo '<div class="row">';
		}
		echo '<div class="span3"><label class="checkbox"><input type="checkbox" name="ChkSubordinate_'.$row->UserID.'" value="1">'.$row->Fullname.' ('.$row->NIK.')</label></div>';

		echo '<div class="span2"><input type="text" value="1"class="input-small " id="TxtKPI_Num_'.$row->UserID.'" name="TxtKPI_Num_'.$row->UserID.'"></div>';
		if($i%2==0){
			echo '</div>';
		}
		$i++;
	}
?>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Next</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>