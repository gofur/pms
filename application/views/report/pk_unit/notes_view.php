<?php 
	$this->load->view('template/top_popup_1_view', FALSE);
?>
<div class="row">
	<div class="span10">
		<h3>Adjustment Notes</h3>
	</div>
</div>

<div class="row">
	<div class="span6">
	<?php echo  $notes; ?>
	</div>
</div>


<?php 
	$this->load->view('template/bottom_popup_1_view', FALSE);
?>