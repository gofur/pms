<h3>Edit Monthly Target</h3>
<?php $attributes = array('id' => 'genFrom'); echo $action ?>
<input type="hidden" name="TxtRKKDetailID" id="TxtRKKDetailID" value="<?php echo  $KPI_head->RKKDetailID ?>">
<input type="hidden" name="TxtKPIID" id="TxtKPIID" value="<?php echo  $KPI_head->KPIID ?>">
<input type="hidden" name="TxtYTDID" id="TxtYTDID" value="<?php echo  $KPI_head->YTDID ?>">
<div class="row">
	<div class="span10">
  <div id="checked" class="switch" data-on-label="All" data-off-label="Off">
    <input type="checkbox" checked />
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
					echo '<th><input class="month_chk" type="checkbox" value="1" name="ChkMonthlyTarget_'.($x+$y*4);
					if(isset($Target[($x+$y*4)]['Target'])){
						echo '" checked="checked';
					}
					echo '"></th>';
					echo '<th><input class="input-small " type="text" name="TxtTarget_'.($x+$y*4);
					if(isset($Target[($x+$y*4)]['Target'])){
						echo '" value="'.thousand_separator($Target[($x+$y*4)]['Target']);
					}
					echo '">';
					echo '<input type="hidden" name="TxtTargetID_'.($x+$y*4);
					if(isset($Target[($x+$y*4)]['Target'])){
						echo '" value="'.$Target[($x+$y*4)]['RKKDetailTargetID'];
					}
					echo '"></th>';
				}
				echo '</tr>';
			}
			?>

		</table>
	</div>
</div>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary <?php echo $disabled ?>">Next</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
</form>