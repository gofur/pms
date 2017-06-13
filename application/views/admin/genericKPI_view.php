<?php echo form_open($process,'id="objectiveFrom"');?>
<h3>Generic KPI</h3>
<div class="row">
	<div class="span1">Perspective:</div>
	<div class="span11">
		<select name="SlcPerspectiveID" id="SlcPerspectiveID" class="input-large">
		<option value="">All</option><?php 
		foreach ($perspectiveType as $row) 
		{
			if(isset($old->PerspectiveID) and $row->PerspectiveID==$old->PerspectiveID){
				echo '<option selected="selected" value="'.$row->PerspectiveID.'">'.$row->Perspective.'</option>';

			}else{
				echo '<option value="'.$row->PerspectiveID.'">'.$row->Perspective.'</option>';
			}
		}
	?></select>
	<button type="submit" class="btn" style="vertical-align:top;">Search</button>
	</div>
</div>

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
		echo anchor('admin/genericKpi/add','<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Tambah"');?>
	</div>
</div>
<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-striped table-hover">
			<thead><tr><th width="250">Perspective</th><th width="250">KPI Name</th><th width="200">Cara Hitung</th><th width="200">Satuan Name</th><th width="200">YTD Name</th><th width="150">Begin</th><th width="150">End</th><th width="10"></th><th width="10"></th></tr></thead>
			<tbody><?php
			foreach ($rows as $row) {
				echo '<tr>';
				echo '<td>'.$row->Perspective.'</td>';
				echo '<td>'.$row->KPI.'</td>';
				echo '<td>'.$row->CaraHitung.'</td>';
				echo '<td>'.$row->Satuan.'</td>';
				echo '<td>'.$row->YTD.'</td>';
				echo '<td>'.substr($row->BeginDate, 0,10).'</td>';
				echo '<td>'.substr($row->EndDate, 0,10).'</td>';
				echo '<td>'.anchor('admin/genericKpi/edit/'.$row->KPIGenericID,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/genericKpi/delete_process/'.$row->KPIGenericID,'<i class="icon-trash"></i>', array('title="Delete"','onClick' => "return confirm('Are you sure you want to delete?')")).'</td>';
				echo '</tr>';
			}
			?></tbody>
		</table>
	</div>
</div>
<?php form_close();?>