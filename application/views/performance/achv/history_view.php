<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>History Achievement</h3>
<div class="row">
	<div class="span2">SO</div>
	<div class="span8"><?php echo $kpi->SasaranStrategis ?></div>
</div>
<div class="row">
	<div class="span2">KPI</div>
	<div class="span8"><?php echo $kpi->KPI ?></div>
</div>
<div class="row">
	<div class="span10">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Month</th>
					<th>Target</th>
					<th>Achievement</th>
				</tr>
			</thead>
			<tbody>
				<?php
				for ($month=1; $month <= 12 ; $month++) {
					if ($month == $cur_month) {
						echo '<tr class="success">';
					} else {
						echo '<tr>';
					} 
					echo '<td>'.date("M", mktime(0, 0, 0, $month, 1, 2000)).'</td>';
					echo '<td>'.$target_ls[$month].'</td>';
					echo '<td>'.$achv_ls[$month].'</td>';
					echo '</tr>';
				}
				?>
			</tbody>

		</table>
	</div>
</div>


<div class="row">
	<div class="span10">
		<div class="form-actions">
		  <?php echo anchor('home','Close', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>

<?php $this->load->view('template/bottom_popup_1_view'); ?>