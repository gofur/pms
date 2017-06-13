<h3>Setting Additional Assigment</h3>
<?php 
	if (isset($link['view_self']))
	{
?>
<div class="row">
	<div class="span12">
    <ul class="breadcrumb">
		  <li><?php echo anchor ($link['view_self'],'Chief')?><span class="divider">/</span></li>
		  <li class="active"><strong><?php echo $userDetail->NIK .' - '. $userDetail->Fullname  ?></strong></li>
    </ul>
	</div>
</div>
<?php
}
?>
<div class="row">
	<div class="span12">

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
		}
		?>
	</div>
</div>

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
				<?php 
				$attributes = array('class' => 'form-inline', 'id' => 'genForm'); 
				echo form_open('manager/assignment/view',$attributes)?>
				<select class="input-medium" name="SlcPost" id="SlcPost">
					<?php
				foreach ($positionList as $row) 
				{
					if($holder==$row->HolderID){
						echo '<option selected="selected" value="'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}else{
						echo '<option value="'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}
			?></select> <input type="submit" value="View" class="btn"></form></div>
		</div>
		<div class="row">
			<div class="span2">Period</div>
			<div class="span4"><?=$periode->Tahun; ?></div>
		</div>
		<div class="row">
			<div class="span2">Start Date</div>
			<div class="span4"><?=format_timedate($periode->BeginDate); ?></div>
		</div>
		<div class="row">
			<div class="span2">End Date</div>
			<div class="span4"><?=format_timedate($periode->EndDate); ?></div>
		</div>
	</div>

		<div class="span6">
				<?php

				if(isset($subordinate)){
					echo '<div class="row">';
					echo '<div class="span2">Subordinate</div>';
					echo '<div class="span4" style="height:210px;overflow:scroll;overflow-x:hidden;"><ul>';
					foreach ( $subordinate as $row) {
						echo '<li>'.anchor($link['view_subordinateAssign'].$holder.'/'.$row->isSAP.'/'.$row->HolderID.'/'.$row->NIK,$row->NIK. ' - '. $row->Fullname).'</li>';
					}
					echo '</ul></div></div>';	
				}

				?>
		
		</div>
	
</div>

<!-- pertama get table data dari perspective -->
<?php 
	if(isset($Chief)and $Chief!=0)
	{
		/*echo '<div class="row">';
		echo '<div class="span2">Subordinate</div>';
		echo '<div class="span10"><ul>';
		foreach ($subordinate as $row) {
			echo '<li>'.anchor('manager/assignment/subordinate/'.$holder.'/'.$row->isSAP.'/'.$row->HolderID.'/'.$row->NIK,$row->NIK. ' - '. $row->Fullname).'</li>';
		}
		echo '</ul></div></div>';*/

		if(isset($userDetailBawahan->NIK) and $userDetailBawahan->NIK!='')
		{
			echo '<div class="row">
				<div class="span12"><strong><u>Data Karyawan</u></strong></div>
			</div>';

		

			echo form_open('','id="additionalAssignFrom"');
			echo 	'<div class="row">
						<div class="span3">NIK:</div>
						<div class="span9">'.$userDetailBawahan->NIK.'</div>
					</div>
					<div class="row">
						<div class="span3">Nama:</div>
						<div class="span9">'.$userDetailBawahan->Fullname.'</div>
					</div>';

			echo '<div class="row">';
			echo '<div class="span12">';
			echo anchor('manager/assignment/add/'.$row->PositionID.'/'.$userDetailBawahan->isSAP.'/'.$userDetailBawahan->NIK,'<i class="icon-plus"></i> More Assigment',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add More Assigment"').'<br><br>';
			echo '<table id="table-tree1-1" class="table table-bordered table-striped">';
			echo '<thead><tr><th width="150px">Assigment Type</th><th>Organization</th><th>Position ID</th><th>Position Name</th><th>Bobot(%)</th><th>Keterangan</th><th>BeginDate</th><th>EndDate</th><th>Action</th></tr></thead>';
			echo '<tbody>';	

			foreach ($assignmentDetailbyHolder as $row) 
			{
		
			echo '<tr id="node-1_1">';
			if($row->isMain!='1')
			{ echo '<td>Assigment</td>';}
			else{ echo '<td>Main</td>';}
			echo '<td>'.$row->OrganizationName.'</td>';
			echo '<td>'.$row->PositionID.'</td>';
			echo '<td>'.$row->PositionName.'</td>';
			echo '<td>'.$row->Bobot.'</td>';
			echo '<td>'.$row->Description.'</td>';
			echo '<td>'.format_timedate($row->BeginDate).'</td>';
			echo '<td>'.format_timedate($row->EndDate).'</td>';
				if($row->AssignmentID!='')
				{
					echo '<td>'.anchor('manager/assignment/edit/'.$row->AssignmentID.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP.'/'.$row->AssignmentStatus,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}else
				{
					$AssignmentStatus=0;
					echo '<td>'.anchor('manager/assignment/edit/0'.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP.'/'.$AssignmentStatus,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}
			echo form_close();		
			}

			foreach ($assignmentDetail as $row) 
			{

			echo '<tr id="node-1_1">';
			if($row->AssignmentStatus=='1')
			{ echo '<td>Assigment</td>';}
			else{ echo '<td>Main</td>';}
			echo '<td>'.$row->OrganizationName.'</td>';
			echo '<td>'.$row->PositionID.'</td>';
			echo '<td>'.$row->PositionName.'</td>';
			echo '<td>'.$row->Bobot.'</td>';
			echo '<td>'.$row->Description.'</td>';
			echo '<td>'.format_timedate($row->BeginDate).'</td>';
			echo '<td>'.format_timedate($row->EndDate).'</td>';
			if($row->AssignmentID!='')
				{
					echo '<td>'.anchor('manager/assignment/edit/'.$row->AssignmentID.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP.'/'.$row->AssignmentStatus,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}else
				{
					echo '<td>'.anchor('manager/assignment/edit/0'.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}
			echo '</tr>';
			

			echo form_close();		
			}

			foreach ($assignmentDetailnonSAPtoSAP as $row) 
			{

			
			echo '<tr id="node-1_1">';
			if($row->AssignmentStatus=='1')
			{ echo '<td>Assigment</td>';}
			else{ echo '<td>Main</td>';}
			echo '<td>'.$row->OrganizationName.'</td>';
			echo '<td>'.$row->PositionID.'</td>';
			echo '<td>'.$row->PositionName.'</td>';
			echo '<td>'.$row->Bobot.'</td>';
			echo '<td>'.$row->Description.'</td>';
			echo '<td>'.format_timedate($row->BeginDate).'</td>';
			echo '<td>'.format_timedate($row->EndDate).'</td>';
			if($row->AssignmentID!='')
				{
					echo '<td>'.anchor('manager/assignment/edit/'.$row->AssignmentID.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP.'/'.$row->AssignmentStatus,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}else
				{
					echo '<td>'.anchor('manager/assignment/edit/0'.'/'.$row->PositionID.'/'.$userDetailBawahan->NIK.'/'.$userDetailBawahan->isSAP,'<i class="icon-pencil"></i>',''.$notif_type.'title="Ubah"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				}
			echo '</tr>';
			

			echo form_close();		
			}
			echo'</tbody>';
			echo '</table>';
			echo '</div>';
			echo '</div>';
		}
	}

		
?>





