<h3><?php echo $Title ?></h3>

<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span4"><strong><?=$this->session->userdata('NIK');?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><strong><?=$userDetail->Fullname; ?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4">
				<?php $attributes = array('class' => 'form-inline', 'id' => 'genForm'); echo form_open($action,$attributes)?>
				<select class="input-medium" name="SlcPost" id="SlcPost">
					<?php
				foreach ($PositionList_SAP as $row) {
					if($Holder==('1.'.$row->HolderID)){
						echo '<option selected="selected" value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}foreach ($PositionList_nonSAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

				foreach ($PositionAssignmentList_nonSAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

				foreach ($PositionAssignmentList_SAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

			?></select> <input type="submit" value="View" class="btn"></form></div>
		</div>
		<div class="row">
			<div class="span2">Period</div>
			<div class="span4"><?=$Periode->Tahun; ?></div>
		</div>
		<div class="row">
			<div class="span2">Start Date</div>
			<div class="span4"><?=format_timedate($Periode->BeginDate); ?></div>
		</div>
		<div class="row">
			<div class="span2">End Date</div>
			<div class="span4"><?=format_timedate($Periode->EndDate); ?></div>
		</div>
</div>
		<div class="span6">
		<?php
		if(isset($subordinate)){
			echo '<div class="row">';
			echo '<div class="span2">Subordinate</div>';
			echo '<div class="span4" style="height:210px;overflow:scroll;overflow-x:hidden;"><ul>';
			foreach ( $subordinate as $row) {
				echo '<li>'.anchor($link['view_subordinate'].$row->UserID.'/'.$row->PositionID.'/'.$Chief_RKKID,$row->NIK.' - '.$row->Fullname.' ('.$row->PositionName.')').'</li>';
			}
			echo '</ul></div></div>';	
		}

		?>

		</div>

</div>
<?php

if(isset($notif_text)){
	echo '<div class="alert alert-info">';
  echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
  echo $notif_text;
  echo '</div>';
}
if(isset($link['finish_rkk']))
{
echo '<div class="row"><div class="span12 header-grid-button">';
echo anchor($link['finish_rkk'].$RKK->RKKID,'Finish RKK','class="fancybox btn btn-primary"');
echo '</div></div>';
	
}

?>
