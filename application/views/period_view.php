<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>Change Period</h3>
<?php
	echo form_open('period/change', '');
?>
	<div class="row">
		<div class="span2">Period</div>
		<div class="span2"><?php 
		echo form_dropdown('slc_period', $options, $default);

		?>
		</div>
	</div>
<?php
	echo "<div class='row'>";
	echo "<div class='span2 offset2'>";
	echo form_submit('btn_change', 'Change','class="btn"');
	echo '</div></div>';
	echo form_close();

?>
<?php $this->load->view('template/bottom_popup_1_view'); ?>
<script type="text/javascript">

</script>