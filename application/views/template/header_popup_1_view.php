<?php 
	if (isset($link['view_self']))
	{
?>
<div class="row">
	<div class="span10">
		<ul class="breadcrumb">
			<li><?php echo anchor ($link['view_self'],'Me')?><span class="divider">/</span></li>
			<li class="active"><?php echo $user_dtl->NIK .' - '. $user_dtl->Fullname  ?></li>
		</ul>
	</div>
</div>
<?php
}
?>
<div class="row">
	<div class="span5">
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span3"><?php echo
 $user_dtl->NIK ;?></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span3"><?php echo
 $user_dtl->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span3"><?php
			if(isset($PositionName))
			{
				echo $PositionName; 
				
			}
			else
			{
				$attributes = array('class' => 'form-inline', 'id' => 'genForm'); 
				echo form_open($action_filter,$attributes);
				echo '<select class="input-large" name="SlcPost" id="SlcPost">';
				foreach ($post_ls_SAP as $row) {
					if($holder == ('1.'.$row->HolderID)){
						echo '<option selected="selected" value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

				foreach ($post_ls_nonSAP as $row) {
					if($holder == ('0.'.$row->HolderID)){
						echo '<op`tion selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

				foreach ($assign_ls_nonSAP as $row) {
					if($holder == ('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}

				foreach ($assign_ls_SAP as $row) {
					if($holder == ('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}
				echo '</select>';
				echo ' <input type="submit" value="View" class="btn"></form>';

			}

			?>
			</div>
		</div>
		<div class="row">
			<div class="span2">Period</div>
			<div class="span3"><?php echo $period->Tahun; ?></div>
		</div>
		<div class="row">
			<div class="span2">Period Start</div>
			<div class="span3"><?php echo $period->BeginDate; ?></div>
		</div>
		<div class="row">
			<div class="span2">Period End</div>
			<div class="span3"><?php echo $period->EndDate; ?></div>
		</div>
	</div>
	<div class="span5">
		<?php

		
if(isset($sub_ls)){
	echo '<div class="row">';
	echo '<div class="span2">Subordinate</div>';
	echo '<div class="span4" style="height:210px;overflow:scroll;overflow-x:hidden;"><ul>';
	foreach ( $sub_ls as $row) {
		echo '<li>'.anchor($link['view_sub'].$row->HolderID.'/'.$row->isSAP.'/',$row->NIK.' - '.$row->Fullname.' ('.$row->PositionName.')').'</li>';
	}
	echo '</ul></div></div>';	
}

?>

</div>
</div>