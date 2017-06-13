
<?php 
	$this->load->view('template/top_popup_1_view');
	$attributes = array('id' => 'genFrom', 'style'=>'min-height:600px'); 
?>
<h3>Stategic Objective</h3>
<?php echo form_open($process,$attributes,$hidden); ?>
<div class="row">
	<div class="span2">Perspective</div>
	<div class="span8"><?php echo $persp->Perspective ?></div>
</div>

<div class="row">
	<div class="span2">Begin</div>
	<div class="span8"><?php echo form_input('dt_begin', $begin, 'id="dt_begin" class="input-small datepicker"'); ?></div>
</div>

<div class="row">
	<div class="span2">End</div>
	<div class="span8"><?php echo form_input('dt_end', $end, 'id="dt_end" class="input-small datepicker"'); ?></div>
</div>

<div class="row">
	<div class="span2">Strategic Objective </div>
	<div class="span3">
		<?php 
			echo form_input('txt_so', $so_text, 'id="txt_so" class="input-large"'); 
		?>
	</div>
	<div class="span5">
		<?php 
			echo form_textarea('txt_desc', $so_desc, 'id="txt_desc" class="input-large" rows="3"');
		?>
	</div>
</div>

<div class="row">
	<div class="span10">
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Save</button>
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>

</form>
<?php
	$this->load->view('template/bottom_popup_1_view');
?>
