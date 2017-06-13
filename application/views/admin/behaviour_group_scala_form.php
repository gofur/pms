<?php echo form_open($process,'id="periodForm"');?>
<h3><?php echo $title ?></h3>
<input type="hidden" name="txt_behaviour_group_scala_id" id="txt_behaviour_group_scala_id" value="<?php echo isset($old->behaviour_group_scala_id)?$old->behaviour_group_scala_id:''?>">
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
	<div class="span2">Scala :</div>
	<div class="span10">
		<select name="slc_scala" id="slc_scala">
			<?php
				foreach ($scala as $row) {
					if(isset($old->scala_id) and $row->scala_id==$old->scala_id){
						echo '<option selected="selected" value="'.$row->scala_id.'">'.$row->label.'</option>';
					}else{
						echo '<option value="'.$row->scala_id.'">'.$row->label.'</option>';	
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
<?php echo form_close();?>