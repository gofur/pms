<?php $this->load->view('template/top_popup_1_view'); ?>
<h3>RKK Relation</h3>
<?php echo form_open($process,'',$hidden); ?>
<div class="row">
	<div class="span2">Begin</div>
	<div class="span8"><?php echo form_input('dt_begin', $begin, 'id="dt_begin" class="input-small datepicker"'); ?></div>
</div>

<div class="row">
	<div class="span2">End</div>
	<div class="span8"><?php echo form_input('dt_end', $end, 'id="dt_end" class="input-small datepicker"'); ?></div>
</div>
<div class="row">
	<div class="span10">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>RKK ID</th>
					<th>NIK - Fullname</th>
					<th>Position</th>
					<th>RKK Begin</th>
					<th>RKK End</th>
					<th>Select</th>
				</tr>
			</thead>
			<tbody>
				<?php

				for ($i=0; $i < $max_i; $i++) { 
					echo '<tr>';
				 	echo '<td>'.$rkk_B_ls[$i]['RKKID'].'</td>';
				 	echo '<td>'.$rkk_B_ls[$i]['emp'].'</td>';
				 	echo '<td>'.$rkk_B_ls[$i]['post'].'</td>';
				 	echo '<td>'.$rkk_B_ls[$i]['begin'].'</td>';
				 	echo '<td>'.$rkk_B_ls[$i]['end'].'</td>';
					echo '<td>'.form_checkbox('chk_rkk[]', $rkk_B_ls[$i]['RKKID'], FALSE);'</td>';

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
		  <button type="submit" class="btn btn-primary">Save</button>
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>
<?php echo form_close();?>
<?php $this->load->view('template/bottom_popup_1_view'); ?>