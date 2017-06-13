<h3>Create Monthly Target</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li  class="active">1. Create KPI <span class="divider">-></span></li>
		  <li>2. Create Target</li>
	  </ul>
	</div>
</div>
<div class="row">
	<div class="span10">
		<?php 
		if ($notif_text!='' AND $notif_type!='')
		{
			echo '<div class="alert alert-block '.$notif_type.'">';
  		echo '<a class="close" data-dismiss="alert" href="#">x</a>';
  		if(isset($notif_title) and $notif_title!=''){
  			echo '<h4>'.$notif_title.'</h4>';
  		}
  		echo $notif_text;
			echo '</div>';
		}?>
	</div>
</div>
<?php $attributes = array('id' => 'genFrom'); echo form_open($process,$attributes)?>
<input type="hidden" name="TxtRKKDetailID" id="TxtRKKDetailID" value="<?php echo $KPI_head->RKKDetailID ?>">
<input type="hidden" name="TxtKPIID" id="TxtKPIID" value="<?php echo $KPI_head->KPIID ?>">

<input type="hidden" name="TxtYTDID" id="TxtYTDID" value="<?php echo $KPI_head->YTDID ?>">
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
  <div id="checked" class="switch" data-on-label="All" data-off-label="Off">
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
					echo '<th><input class="month_chk" type="checkbox" value="1" name="ChkMonthlyTarget_'.($x+$y*4).'"></th>';
					echo '<th><input class="input-small " type="text" name="TxtMonthlyTarget_'.($x+$y*4).'"></th>';
				}
				echo '</tr>';
			}
			?>

		</table>
	</div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Next</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>