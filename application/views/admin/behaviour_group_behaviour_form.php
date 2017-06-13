<?php echo form_open($process,'id="periodForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="txt_behaviour_group_behaviour_id" id="txt_behaviour_group_behaviour_id" value="<?php echo isset($old->behaviour_group_behaviour_id)?$old->behaviour_group_behaviour_id:''?>">
<?php if($do_act=='edit' || $do_act=='add') { ?>

<div class="row">
	<div class="span2">Behaviour Group :</div>
	<div class="span10">
		
		<select name="slc_behaviour_group" id="slc_behaviour_group">
			<?php
				foreach ($behaviour_group as $row) {	
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
	<div class="span2">Behaviour :</div>
	<div class="span10">
		<select name="slc_behaviour" id="slc_behaviour">
			<?php
				foreach ($behaviour as $row) {
					if(isset($old->behaviour_id) and $row->behaviour_id==$old->behaviour_id){
						echo '<option selected="selected" value="'.$row->behaviour_id.'">'.$row->label.'</option>';
						//echo 'aaa';
					}else{
						echo '<option value="'.$row->behaviour_id.'">'.$row->label.'</option>';	
						//echo 'bb';
					}
				}
			?>
		</select>
	</div>
</div>


<div class="row">
	<div class="span2">Sort number :</div>
	<div class="span10"><input type="text" <?php echo isset($old->sort_number)?$old->sort_number:''?> class="input-mini" length="5" name="txt_sort" id="txt_sort" value="<?php echo isset($old->sort_number)?$old->sort_number:''?>"> </div>
</div>

<div class="row">
	<div class="span2">Weight :</div>
	<div class="span10"><input type="text" <?php echo isset($old->weight)?$old->weight:''?> class="input-mini" length="5" name="txt_weight" id="txt_weight" value="<?php echo isset($old->weight)?$old->weight:''?>"> </div>
</div>


<div class="row">
	<div class="span2">Description :</div>
	<div class="span10"><textarea name="txt_description" id="txt_description"><?php echo isset($old->description)?$old->description:''?></textarea></div>
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
<?php form_close();?>