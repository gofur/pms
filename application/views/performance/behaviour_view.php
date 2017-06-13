<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script>
function RadionButtonSelectedValueSet(name, SelectdValue) {
    $('input[name="' + name+ '"][value="' + SelectdValue + '"]').prop('checked', true);
}
$(function(){

})
</script>
<div class="row">
	<div class="span12">
		<?php 
		if ($notif_text!='')
		{
			echo '<div class="alert '.$notif_type.'">';
			echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo $notif_text;
			echo '</div>';

		}
		?>
	</div>
</div>

<div class="row">
	<div class="span6">
		<div class="gauge-container">
		<div id="gaugeBehavior_1" class="gauge_double_1 gauge-double"></div>
		<div id="gaugeBehavior_2" class="gauge_double_2 gauge-double"></div>
		</div>
	</div>
	<div class="span6">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>TOTAL NILAI BEHAVIOUR</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
					<?php 
						if(isset($total_behaviour))
						{
							echo '<strong>'.$total_behaviour.'</strong>';
						}
					?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<br>
<div class="row">
	<div class="span12"><strong>Behaviour Achievement</strong>
	</div>
</div>

<?php
if(isset($aspect_setting)!='')
{
	
	foreach ($aspect_setting as $key) 
	{
					
	if(isset($detail_aspect_setting)==1)
	{

		foreach ($detail_aspect_setting[$key] as $row) {
			$sum_weight=0;
			foreach ($data_behaviour[$row->behaviour_group_id]  as $row_behaviour) {
				$sum_weight+=$row_behaviour->weight;
			}	
			if($sum_weight==100)
			{

?>	
		
			<div class="row">
				<div class="span12"><strong><i class="icon-info-sign"></i> 
					<?php echo $row->percentage ?>% <?php echo $row->label ?> Achievement <?php echo $bulan_terpilih; ?> <?php echo $Periode->Tahun; ?></strong>
				</div>
			</div>
		<?php 
		$attributes = array('id' => 'genFrom'); 
	$readonly ='';	
		//echo isset($status_flag);
		if(isset($status_flag)!=NULL)
		{
			if($status_flag == 1)
			{
				$readonly = 'disabled';
			}
		}
	
		echo form_open($process,$attributes);
		
		echo '<input type="hidden" name="txt_periode_aspect" value="'.$periode_aspect.'">';
				echo '<input type="hidden" name="txt_aspect_setting_id" value="'.$row->aspect_setting_id.'">';
				$i=0;
		foreach ($data_behaviour[$row->behaviour_group_id]  as $row_behaviour) {
			echo '<div class="row">
					<div class="span12">
						<strong>'.$row_behaviour->sort_number.'. '.$row_behaviour->label.'</strong> '.' - '.$row_behaviour->description;

						echo '<table class="table">
									<tr>';
					
						foreach ($data_behaviour_scala[$row->behaviour_group_id] as $row_scala) 
						{
							echo '<input type="hidden" name="txt_behaviour_id" value="'.$row_behaviour->behaviour_id.'">';
							echo '<td><input '.$readonly.' type="radio" style="vertical-align:top;" class="'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'" id="'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'-'.(int) $row_scala->value.'" name="group['.$row_behaviour->behaviour_id.']" value="'.$row_scala->value.'" required> <strong>'.$row_scala->label.'</strong><br> 
							<font style="margin-left:20px;font-size:13px;float:right;font-style:italic;">'.$row_scala->description.'</font></td>';
						}
						echo '</tr>';
						
						echo '</table></div>';

						
						if(isset($note_eviden[$i])=='')
						{
							echo '<div class="span12">Evidence : <textarea '.$readonly.'  name="txt_notes_eviden[]" style="width:100%" class="'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'" id="text_area'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'-'.(int) $row_scala->value.'"></textarea></div>';
						}
						else
						{
							echo '<div class="span12">Evidence : <textarea name="txt_notes_eviden[]" style="width:100%" class="'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'" id="text_area'.$pilihan_bulan.'-'.$row_behaviour->behaviour_id.'-'.(int) $row_scala->value.'">'.$note_eviden[$i].'</textarea></div>';	
						}
						echo '</div><br>';
						$i++;
				}
				if(isset($status_flag)!=NULL)
				{
					if($status_flag!=1)
					{
						echo '<div class="row" id="btn_save">
						<div class="span12"><center>
						<button type="submit" class="btn btn-primary">Save</button> ';
						echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"');
						echo '</center></div></div>';
					}
				}else{
				  echo '<div class="row" id="btn_save">                                                 <div class="span12"><center>
                                                 <button type="submit" class="btn btn-primary">Save</button> ';
                                                 echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"');
                                                echo '</center></div></div>';
	
				}
			}
			else
			{
				echo '<div class="alert alert-danger" role="alert">weight must equal to 100%, please contact your administrator</div>';
			}
		}
	}
}

	
?>

</form>
<?php
}
?>
