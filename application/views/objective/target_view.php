<h3>Monthly Target</h3>

<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $KPI_head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">YTD</div>
	<div class="span8"><?php echo $KPI_head->YTD ?></div>
</div>
<div class="row">
	<div class="span2">Count Type</div>
	<div class="span8"><?php echo $KPI_head->CaraHitung ?></div>
</div>
<div class="row">
	<div class="span2">Formula</div>
	<div class="span8"><?php echo $KPI_head->PCFormula ?></div>
</div>
<div class="row">
	<div class="span2">Weight</div>
	<div class="span8"><?php echo $KPI_head->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Measurement Unit</div>
	<div class="span8"><?php echo thousand_separator($KPI_head->TargetAkhirTahun) .' '.$KPI_head->Satuan?></div>
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