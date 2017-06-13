<h3>Cascade KPI</h3>
<div class="row">
	<div class="span10">
	  <ul class="breadcrumb">
		  <li  class="active">1. Select Subordinate <span class="divider">-></span></li>
		  <li>2. Cascading KPI</li>
	  </ul>
	</div>
</div>
<?php $attributes = array('id' => 'genForm'); echo form_open($process,$attributes)?>
<input type="hidden" name="TxtChief_KPIID" id="TxtChief_KPIID" value="<?php echo $Chief_KPI->KPIID ?>">
<input type="hidden" name="TxtChief_RKKDetailID" id="TxtChief_RKKDetailID" value="<?php echo $Chief_KPI->RKKDetailID ?>">

<input type="hidden" name="TxtSubordinate_num" value="<?php echo $subordinate_num ?>">
<div class="row">
	<div class="span2">Chief KPI</div>
	<div class="span10"><?php echo $KPI_head->KPI ?></div>
</div>
<div class="row">
	<div class="span2">Description</div>
	<div class="span10"><?php echo $KPI_head->Description ?></div>
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
	<div class="span2">Weight</div>
	<div class="span10"><?php echo $KPI_head->Bobot ?></div>
</div>
<div class="row">
	<div class="span2">Target</div>
	<div class="span10"><?php echo thousand_separator($KPI_head->TargetAkhirTahun) .' ('.$KPI_head->Satuan.')'?></div>
</div>
<div class="row">
	<div class="span2">Reference</div>
	<div class="span10"><select name="SlcRef" id="SlcRef" class="input-medium">
		<option value="">Select One</option>
		<?php
			foreach ($Reference_list as $row) {
				echo '<option value="'.$row->ReferenceID.'">'.$row->Reference.'</option>';
			}
		?>
	</select></div>
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
<hr>

<?php
	for ($i=0; $i <$subordinate_num ; $i++) { 
		echo '<b>'.$subordinate[$i]['NIK'].' - '.$subordinate[$i]['Fullname'].'</b>';
		echo '<input type="hidden" name="TxtUserID_'.$i.'" value="'.$subordinate[$i]['UserID'].'">';
		echo '<input type="hidden" name="TxtPositionID_'.$i.'" value="'.$subordinate[$i]['PositionID'].'">';
		echo '<input type="hidden" name="TxtisSAP_'.$i.'" value="'.$subordinate[$i]['isSAP'].'">';
		echo '<input type="hidden" name="TxtNum_'.$i.'" value="'.$subordinate[$i]['KPI_Num'].'">';

		for($x=0;$x<$subordinate[$i]['KPI_Num'];$x++){
			echo '<div class="row">';
			echo '<div class="span5">';

			echo '<div class="row">';
			echo '<div class="span2">'.($x+1).'. Generic KPI</div>';
			echo '<div class="span3"><select class="input-large" name="SlcGenKPI_'.$i.'_'.$x.'" id="SlcGenKPI_'.$i.'_'.$x.'">';
			echo '<option value="">Select Generic KPI</option>';
			foreach ($genericKPI as $row) {
				echo '<option value="'.$row->KPIGenericID.'">'.$row->KPI.'</option>';
			}
			echo '<option value="other">Other</option>';
			echo '</select></div>';
			echo '</div>'; //end of line 62

			echo '<div class="row proposionalDiv">';
			echo '<div class="span2">Weight Ref.</div>';
			echo '<div class="span3"><input class="input-small " type="text" name="TxtRW_'.$i.'_'.$x.'" id="TxtRW_'.$i.'_'.$x.'" value="0"></div>';
			echo '</div>';//end of line 73

			echo '<div id="Hidden_'.$i.'_'.$x.'">';
			echo '<div class="row">';
			echo '<div class="span2">KPI Name</div>';
			echo '<div class="span3"><input type="text" name="TxtKPIName_'.$i.'_'.$x.'" id="TxtKPIName_'.$i.'_'.$x.'" placeholder="KPI Name"> </div>';
			echo '</div>';//end of line 78

			echo '<div class="row">';
			echo '<div class="span2">Description</div>';
			echo '<div class="span3"><textarea name="TxtKPIDesc_'.$i.'_'.$x.'" id="TxtKPIDesc_'.$i.'_'.$x.'"></textarea> </div>';
			echo '</div>';//end of line 84

			/*echo '<div class="row">';
			echo '<div class="span2">Unit</div>';
			echo '<div class="span3"><select name="SlcUnit_'.$i.'_'.$x.'" id="SlcUnit_'.$i.'_'.$x.'">';
			echo '<option value="">Select Count Unit</option>';
			foreach ($Unit_list as $row) {
				echo '<option value="'.$row->SatuanID.'">'.$row->Satuan.'</option>';
			}
			echo '</select></div>';
			echo '</div>';//end of line 89*/

			/*echo '<div class="row">';
			echo '<div class="span2">Formula</div>';
			echo '<div class="span3"><select name="SlcFormula_'.$i.'_'.$x.'" id="SlcFormula_'.$i.'_'.$x.'">';
			echo '<option selected value="">Select Formula</option>';
			foreach ($Formula_list as $row) {
				echo '<option value="'.$row->PCFormulaID.'">'.$row->PCFormula.'</option>';
			}
			echo '</select></div>';
			echo '</div>';//end of line 99*/

			/*echo '<div class="row">';
			echo '<div class="span2">YTD</div>';
			echo '<div class="span3"><select name="SlcYTD_'.$i.'_'.$x.'" id="SlcYTD_'.$i.'_'.$x.'">';
			echo '<option value="">Select YTD</option>';
			foreach ($Ytd_list as $row) {
				echo '<option value="'.$row->YTDID.'">'.$row->YTD.'</option>';
			}
			echo '</select></div>';
			echo '</div>';//end of line 109*/

			echo '</div>';//end of line 78			
			
			echo '<div class="row">';
			echo '<div class="span2">Weight</div>';
			echo '<div class="span3"><input type="text" value="0" class="input-small " name="TxtWeight_'.$i.'_'.$x.'" id="TxtWeight_'.$i.'_'.$x.'"></div>';
			echo '</div>'; //end of line 121

			echo '<div class="row">';
			echo '<div class="span2">Baseline</div>';
			echo '<div class="span3"><input type="text" value="0" class="input-small " name="TxtBaseline_'.$i.'_'.$x.'" id="TxtBaseline_'.$i.'_'.$x.'"></div>';
			echo '</div>';//end of line 126

			/*echo '<div class="row">';
			echo '<div class="span2">End of Year Target</div>';
			echo '<div class="span3"><input type="text" class="input-small" name="TxtTarget_'.$i.'_'.$x.'" id="TxtTarget_'.$i.'_'.$x.'"></div>';
			echo '</div>';//end of line 131*/
			echo '</div>';//end of line 60;
			echo '<div class="span5">';
			echo '<div id="checked_'. $i.'_'.$x.'" class="switch" data-on-label="All" data-off-label="Off">';
    	echo '<input type="checkbox" />';
  		echo '</div>';
			echo '<table class="table table-striped table-condensed"><thead><tr><th>Month</th><th colspan="2">Target</th></tr></thead><tbody>';
			for($z=1;$z<=12;$z++){
				echo '<tr>';
				echo '<td>'.date('M', mktime(0,0,0,$z,1,2000)).'</td>';
				echo '<td><input type="checkbox" class="month_chk_'.$i.'_'.$x.'" name="ChkMonthlyTarget_'.$i.'_'.$x.'_'.$z.'"</td>';
				echo '<td><input type="text" class="input-small " name="TxtMonthlyTarget_'.$i.'_'.$x.'_'.$z.'"</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '</div>';//end of line 136;
			echo '</div>';//end of line 59;
		}

		echo '<hr>';
	}
?>
<div class="row">
	<div class="span8 offset2">
		<button type="submit" class="btn btn-primary">Save
		</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>

</form>