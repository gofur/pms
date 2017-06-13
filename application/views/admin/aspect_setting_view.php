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
		echo anchor($process_add,'<i class="icon-plus"></i>',' class="fancybox btn pull-right" data-fancybox-type="iframe" title="Add Aspect Setting"');?> 
	</div>
</div>

<?php 	echo form_open('admin/aspect_setting/search','id="behaviour_group_scala_view"'); ?>
<div class="row">

	<div class="span2">
		Organization :
	</div>
	<div class="span10">
		<input type="hidden" id="txt_organization_id" name="txt_organization_id" readonly> 
		<textarea rows="4" cols="100" name="txt_organization_name" id="txt_organization_name" disabled></textarea>
		
		<input type="button" class="popup_v_align" value="Choose" onclick="window.open('<?php echo base_url() ?>index.php/admin/aspect_setting/pop_up_org','','width=800,height=400,top=300,left=500')">
	</div>

</div>

<div class="row" >
	<div class="span2">
		Periode :
	</div>
	<div class="span10" id="state">
		<input type="text" id="txt_start_date_org" class="input-small" name="txt_start_date_org" readonly> 

		- 
		<input type="text" id="txt_end_date_org" class="input-small" name="txt_end_date_org" readonly> 
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
			<thead><tr><th>Aspect</th><th width="200">Organization</th><th>Behaviour Group</th><th>Layer</th><th>Frequency</th><th>Percentage</th><th width="150">Begin Date</th><th width="150">End Date</th><th width="10">Edit</th><th width="10">Delimit</th></tr></thead>
			<tbody><?php 
			foreach ($aspect_setting as $row) {
				echo '<tr>';
				echo '<td style="text-align:right">'.$row->aspect.'</td>';
				echo '<td style="text-align:right">'.$row->org_name_full.'</td>';
				if($row->aspect_id!=1)
				{
					echo '<td style="text-align:right">'.$row->behaviour_group.'</td>';
					echo '<td style="text-align:right">'.$row->layer.'</td>';
					echo '<td style="text-align:right">'.$row->frequency.'</td>';
				}
				else
				{
					echo '<td style="text-align:right"></td>';
					echo '<td style="text-align:right"></td>';
					echo '<td style="text-align:right"></td>';
				}
				echo '<td style="text-align:right">'.$row->percentage.'</td>';
				echo '<td>'.substr($row->begin_date, 0,10).'</td>';
				echo '<td>'.substr($row->end_date, 0,10).'</td>';
				echo '<td>'.anchor('admin/aspect_setting/edit/'.$row->aspect_setting_id,'<i class="icon-pencil"></i>','title="Edit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '<td>'.anchor('admin/aspect_setting/delimit/'.$row->aspect_setting_id,'<i class="icon-ban-circle"></i>', 'title="Delimit"  class="fancybox" data-fancybox-type="iframe"').'</td>';
				echo '</tr>';
			}
			?></tbody>
		</table>

		<div class="pagination pagination_left"><?php echo $links; ?></div>
	</div>
</div>
