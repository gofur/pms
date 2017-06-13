<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>RKK Copy</h3>
<div class="row">
	<div class="span10">
		Bawahan yang menjadi target Copy RKK sudah memiliki RKK sebelumnya. Apakah anda ingin melanjutkan? Jika Ya, maka RKK sebelumnya akan dianggap tidak valid dan akan dihapus. 
	</div>
</div>
<div class="row">
	<div class="span10">
		<center>
			<?php echo anchor($next, 'Ya', 'class="btn btn-success"');?>
			<?php echo anchor($cancel, 'Tidak', 'class="btn btn-danger"');?>
		</center>
	</div>
</div>
<?php $this->load->view('template/bottom_popup_1_view'); ?>