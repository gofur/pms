<h3>Target & Achievement</h3>
<div class="row">
	<div class="span2">Objective</div>
	<div class="span8"><?php echo $header->SasaranStrategis?></div>
</div>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $header->KPI?></div>
</div>
<div class="row">
	<div class="span5">
		<div class="row">
			<div class="span2">Weight</div>
			<div class="span3"><?php echo $header->Bobot?>%</div>
		</div>
		<div class="row">
			<div class="span2">Measurement Unit</div>
			<div class="span3"><?php echo  $header->Satuan?></div>
		</div>
	</div>
	<div class="span5">
		<div class="row">
			<div class="span2">Formula</div>
			<div class="span3"><?php echo '('.$header->CaraHitung.') '.$header->PCFormula?></div>
		</div>
		<div class="row">
			<div class="span2">YTD</div>
			<div class="span3"><?php echo $header->YTD?></div>
		</div>
	</div>
</div>

	


	
<div class="row">
	<div class="span10">
<table class="table table-bordered table-striped">
	<thead><tr><th>Month</th><th>Target</th><th>Achievement</th></tr></thead>
	<tbody>
		<?php 
		for ($i=0; $i < 12 ; $i++) { 
			echo '<tr>';
			echo '<td>'.date('M',mktime(0,0,0,($i+1),1,2000)).'</td>';
			echo '<td>'.thousand_separator($list[$i]['target']).'</td>';
			echo '<td>'.thousand_separator($list[$i]['achievement']).'</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
</div>
</div>