<h3>Monthly Target</h3>

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi->KPI ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $kpi->Description ?></div>
</div>
<div class="row">
	<div class="span2">YTD</div>
	<div class="span8"><?php echo $kpi->YTD ?></div>
</div>
<div class="row">
	<div class="span2">Count Type</div>
	<div class="span8"><?php echo $kpi->CaraHitung ?></div>
</div>
<div class="row">
	<div class="span2">Formula</div>
	<div class="span8"><?php echo $kpi->PCFormula ?></div>
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span8"><?php echo $kpi->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Measurement Unit</div>
	<div class="span8"><?php echo thousand_separator($kpi->TargetAkhirTahun) .' '.$kpi->Satuan?></div>
</div>
<hr>
<h4>Chief's KPI</h4>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $chief_kpi->KPI ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span8"><?php echo $chief_kpi->Description ?></div>
</div>
<div class="row">
	<div class="span2">YTD</div>
	<div class="span8"><?php echo $chief_kpi->YTD ?></div>
</div>
<div class="row">
	<div class="span2">Count Type</div>
	<div class="span8"><?php echo $chief_kpi->CaraHitung ?></div>
</div>
<div class="row">
	<div class="span2">Formula</div>
	<div class="span8"><?php echo $chief_kpi->PCFormula ?></div>
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span8"><?php echo $chief_kpi->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Measurement Unit</div>
	<div class="span8"><?php echo thousand_separator($chief_kpi->TargetAkhirTahun) .' '.$chief_kpi->Satuan?></div>
</div>
<div class="row">
	<div class="span10">
		<table class="table table-bordered">
			
			<?php 
			for ($y=0; $y < 3 ; $y++) { 
				echo '<tr>';
				for ($x=1; $x <=4 ; $x++) { 
					echo '<td><b>'.date('M',mktime(0,0,0,($x+$y*4),1,2000)).'</b></td>';
				}
				echo '</tr>';
				echo '<tr>';
				for ($x=1; $x <=4 ; $x++) { 
					echo '<td>';
					if (isset($Target[$x+$y*4]))
					{
						echo thousand_separator($Target[$x+$y*4]);
					}
					else
					{
						echo '-';
					}
					echo '</td>';
				}
				echo '</tr>';
			}
			?>

		</table>
	</div>
</div>
<div class="row">
	<div class="span8 offset2">
		<?php echo anchor('home','Close', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>