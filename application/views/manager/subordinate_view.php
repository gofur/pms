<div class="row">
	<div class="span12">

<?php 
	if (isset( $notif_text) && $notif_text!=''){
		echo '<div class="alert alert-info">';
		echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    echo $notif_text;
    echo '</div>';
	}
?>
	</div>
</div>
<?php echo form_open($action) ?>
<input type="hidden" name="Hdn_isSAP" id="Hdn_isSAP" value="<?php echo isset($Chief_isSAP)?$Chief_isSAP:'' ?>">
<input type="hidden" name="Hdn_HolderID" id="Hdn_HolderID" value="<?php echo isset($Chief_HolderID)?$Chief_HolderID:'' ?>">
<table class="table table-bordered">
	<thead>
		<tr>
			<th>NIK - FullName</th>
			<th>Position</th>
			<th>RKK Weight</th>
			<th>KPI Num.</th>
			<th>IDP</th>
			<th>RKK</th>
			<th></th>
		</tr>
	</thead>
<?php
	if(isset($subordinate))
	{
		foreach ($subordinate as $row) 
		{
			echo '<tr>';
			echo '<td>';
			echo isset($subordinate_data[$row->UserID]['RKKID'])?form_hidden('hdn_rkkid_'.$row->UserID, $subordinate_data[$row->UserID]['RKKID']):'';
			echo isset($subordinate_data[$row->UserID]['IDPID'])?form_hidden('hdn_idpid_'.$row->UserID, $subordinate_data[$row->UserID]['IDPID']):'';
			echo $row->NIK.' - '.$row->Fullname;
			echo '</td>';
			echo '<td>'.$row->PositionName.'</td>';
			echo '<td>'.$subordinate_data[$row->UserID]['Weight'].'</td>';
			echo '<td>'.$subordinate_data[$row->UserID]['KPI_Num'].'</td>';
			echo '<td><span class="label '.$subordinate_data[$row->UserID]['status_idp_label'].'">'.$subordinate_data[$row->UserID]['status_idp'].'</span></td>';
			echo '<td><span class="label '.$subordinate_data[$row->UserID]['status_label'].'">'.$subordinate_data[$row->UserID]['status'].'</span></td>';
			
			echo '<td>'.$subordinate_data[$row->UserID]['checkbox'].'</td>';

			echo '</tr>';
		}
		
	}
?>
</table>
<div class="row">
	<div class="span12">
		<input type="submit" value="Process" class="btn btn-primary pull-right">
	</div>
</div>

</form>