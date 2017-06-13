<?php $this->load->view('template/top_popup_1_view');?>
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
<table class="table">
	<thead><tr><th width="200px">NIK - Name</th><th width="100px">KPI Num.</th><th width="200px">NIK - Name</th><th>KPI Num.</th></thead>
<?php
	$i=1;
	foreach ($Subordinate as $row) {
		if($i%2==1){
			echo '<tr>';
		}
		echo '<td><label class="checkbox"><input type="checkbox" name="ChkSubordinate_'.$row->HolderID.'" value="1">'.$row->Fullname.' ('.$row->NIK.')</label></td>';

		echo '<td><input type="text" value="1" class="very-small" id="TxtKPI_Num_'.$row->HolderID.'" name="TxtKPI_Num_'.$row->HolderID.'"></td>';
		if($i%2==0){
			echo '<//tr>';
		}
		$i++;
	}
?>
</table>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Next
		</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>

<?php $this->load->view('template/bottom_popup_1_view'); ?>