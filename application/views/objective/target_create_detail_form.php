<input type="hidden" name="hdn_rkk_detail_id_<?php echo $num_code; ?>" id="hdn_rkk_detail_id_<?php echo $num_code; ?>" value="<?php echo $KPI_head->RKKDetailID ?>">
<input type="hidden" name="hdn_kpi_id_<?php echo $num_code; ?>" id="hdn_kpi_id_<?php echo $num_code; ?>" value="<?php echo $KPI_head->KPIID ?>">

<input type="hidden" name="hdn_ytd_id_<?php echo $num_code; ?>" id="hdn_ytd_id_<?php echo $num_code; ?>" value="<?php echo $KPI_head->YTDID ?>">
<div class="row">
	<div class="span2">KPI</div>
	<div class="span10"><?php echo $KPI_head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">YTD</div>
	<div class="span10"><?php echo $KPI_head->YTD ?></div>
</div>
<div class="row">
	<div class="span2">Count Type</div>
	<div class="span10"><?php echo $KPI_head->CaraHitung ?></div>
</div>
<div class="row">
	<div class="span2">Formula</div>
	<div class="span10"><?php echo $KPI_head->PCFormula ?></div>
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span10"><?php echo $KPI_head->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Measurement Unit</div>
	<div class="span10"><?php echo thousand_separator($KPI_head->TargetAkhirTahun) .' '.$KPI_head->Satuan?></div>
</div>
<div class="row">
	<div class="span10">
  <div id="checked_<?php echo $num_code; ?>" class="switch" data-on-label="All" data-off-label="Off">
    <input type="checkbox"/>
  </div>
</div>
</div>
<div class="row">
	<div class="span10">
		<table class="table table-bordered">
			
			<?php 
			for ($y=0; $y < 3 ; $y++) { 
				echo '<tr>';
				for ($x=1; $x <=4 ; $x++) { 
					echo '<th colspan="2">'.date('M',mktime(0,0,0,($x+$y*4),1,2000)).'</th>';
				}
				echo '</tr>';
				echo '<tr>';
				for ($x=1; $x <=4 ; $x++) { 
					echo '<th><input class="month_chk_'.$num_code.'" type="checkbox" value="1" name="ChkMonthlyTarget_'.$num_code.'_'.($x+$y*4).'"></th>';
					echo '<th><input class="input-small " type="text" name="TxtMonthlyTarget_'.$num_code.'_'.($x+$y*4).'"></th>';
				}
				echo '</tr>';
			}
			?>

		</table>
	</div>
</div>