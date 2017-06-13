<h3><?php echo $title ?></h3>
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
		echo anchor($process_add,'<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add Behaviour Group"');?> 
	</div>
</div>

<?php 	echo form_open('admin/behaviour_group_scala/search','id="behaviour_group_scala_view"'); ?>
<div class="row">
	<div class="span2">
		Behaviour Group :
	</div>
	<div class="span10">
		<select name="slc_behaviour_group" id="slc_behaviour_group">
			<option value=''>all</option>
			<?php
				foreach ($behaviour_group as $row) {
					echo '<option value="'.$row->behaviour_group_id.'">'.$row->label.'</option>';
				}
			?>
	</select>
	
	</div>
		<div class="span2">
		</div>
		<div class="span10">
			<?php echo '<input type="submit" value="Search" class="btn">'; ?>
		</div>
	
</div>
	
	</form>



<div class="row">
	<div class="span12">
		<table class="table table-bordered table-striped table-hover" >
			<thead><tr><th>Scala</th><th>Sort Number</th><th>Description</th><th width="150">Begin Date</th><th width="150">End Date</th><th width="10">Edit</th><th width="10">Delimit</th></tr></thead>
			<tbody><?php
			foreach ($behaviour_group_scala as $row) {
				echo '<tr>';
				echo '<td style="text-align:right">'.$row->label.'</td>';
				echo '<td style="text-align:right">'.$row->sort_number.'</td>';
				echo '<td style="text-align:right">'.$row->description.'</td>';
				echo '<td>'.substr($row->begin_date, 0,10).'</td>';
				echo '<td>'.substr($row->end_date, 0,10).'</td>';
				echo '<td>'.anchor('admin/behaviour_group_scala/edit/'.$row->behaviour_group_scala_id,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/behaviour_group_scala/delimit/'.$row->behaviour_group_scala_id,'<i class="icon-ban-circle"></i>', 'title="Delimit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '</tr>';
			}
			?></tbody>
		</table>

		<div class="pagination pagination_left"><?php echo $links; ?></div>
	</div>
</div>
