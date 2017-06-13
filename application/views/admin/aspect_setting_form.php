<?php echo form_open($process,'id="periodForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="txt_aspect_setting_id" id="txt_aspect_setting_id" value="<?php echo isset($old->aspect_setting_id)?$old->aspect_setting_id:''?>">
<?php if($do_act=='edit' || $do_act=='add') { ?>
<div class="row">
	<div class="span2">Organization :</div>
	<div class="span10">
		<div class="span10">
		<input type="hidden" id="txt_organization_id" name="txt_organization_id" value="<?php echo isset($org_id)?$org_id:''?>" readonly> 
		<input type="hidden" id="txt_org_name" name="txt_org_name" value="<?php echo isset($name_org)?$name_org:''?>"> 
		<textarea rows="4" cols="100" name="txt_organization_name" id="txt_organization_name" disabled><?php echo isset($name_org)?$name_org:''?>
		</textarea>
		<input type="button" class="popup_v_align" value="Choose" onclick="window.open('<?php echo base_url() ?>index.php/admin/aspect_setting/pop_up_org','','width=800,height=400,top=300,left=500')">
	</div>
	</div>
</div>

<div class="row">
	<div class="span2">Periode :</div>
	<div class="span10" id="state">
		<input type="text" id="txt_start_date_org" class="input-small" name="txt_start_date_org" value="<?php echo isset($old->org_begin_date)?substr($old->org_begin_date,0,10):''?>" readonly> 

		- 
		<input type="text" id="txt_end_date_org" class="input-small" name="txt_end_date_org" value="<?php echo isset($old->org_end_date)?substr($old->org_end_date,0,10):''?>" readonly> 
	</div>
</div>


<div class="row">
	<div class="span2">Aspect :</div>
	<div class="span10">
		<select name="slc_aspect" id="slc_aspect">
			<option value=''>--Please select--</option>
			<?php
				foreach ($aspect_list as $row) {
					
					if(isset($old->aspect_id) and $row->aspect_id==$old->aspect_id){
						echo '<option selected="selected" value="'.$row->aspect_id.'">'.$row->label.'</option>';
					}else{
						echo '<option value="'.$row->aspect_id.'">'.$row->label.'</option>';
					}
				}
			?>
		</select>
	</div>
</div>


<div class="row">
	<div class="span2">Behaviour Group :</div>
	<div class="span10">
		<select name="slc_behaviour_group" id="slc_behaviour_group" >
			<option value=''>--Please select--</option>
			<?php
				foreach ($behaviour_group_list as $row) {
					
					if(isset($old->behaviour_group_id) and $row->behaviour_group_id==$old->behaviour_group_id){
						echo '<option selected="selected" value="'.$row->behaviour_group_id.'">'.$row->label.'</option>';
					}else{
						echo '<option value="'.$row->behaviour_group_id.'">'.$row->label.'</option>';
					}
				}
			?>
		</select>
	</div>
</div>

<div class="row">
	<div class="span2">Layer :</div>
	<div class="span10">
		<select name="slc_layer" id="slc_layer" >
			<?php
				foreach ($layer_list as $row) {
					
					if(isset($old->layer_id) and $row->layer_id==$old->layer_id){
						echo '<option selected="selected" value="'.$row->layer_id.'">'.$row->label.'</option>';
					}else{
						echo '<option value="'.$row->layer_id.'">'.$row->label.'</option>';
					}
				}
			?>
		</select>
	</div>
</div>


<div class="row">
	<div class="span2">Frequency :</div>
	<div class="span10">
		<input type="text" <?php echo isset($old->frequency)?$old->frequency:''?> class="input-small" length="5" name="txt_frequency" id="txt_frequency" value="<?php echo isset($old->frequency)?$old->frequency:''?>"> 
	</div>
</div>

<div class="row">
	<div class="span2">Percentage :</div>
	<div class="span10"><input type="text" <?php echo isset($old->percentage)?$old->percentage:''?> class="input-small" length="5" name="txt_percentage" id="txt_percentage" value="<?php echo isset($old->percentage)?$old->percentage:''?>"> </div>
</div>

<div class="row">
	<div class="span2">Begin Date :</div>
	<div class="span10"><input type="text" <?php echo isset($old->begin_date)?'':''?> class="input-small" name="txt_begin_date" id="txt_begin_date" value="<?php echo isset($old->begin_date)?substr($old->begin_date,0,10):date('Y/m/d')?>"> </div>
</div>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="txt_end_date" id="txt_end_date" value="<?php echo isset($old->end_date)?substr($old->end_date,0,10) :'9999/12/31'?>"> </div>
</div>

<?php }else{ ?>
<div class="row">
	<div class="span2">End Date :</div>
	<div class="span10"><input type="text" class="input-small" name="txt_end_date" id="txt_end_date" value="<?php echo isset($old->end_date)?substr($old->end_date,0,10) :'9999/12/31'?>"> </div>
</div>
<?php } ?>
<div class="row">
	<div class="span10 offset2">
		<button type="submit" class="btn btn-primary">Save</button>
		<?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
	</div>
</div>
<?php echo form_close();?>