<?php 
	$this->load->view('template/top_popup_1_view');
?>
<h3>Cascade KPI</h3>
<div class="accordion" id="accordion1">
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				KPI Detail
			</a>
		</div>
		<div id="collapseOne" class="accordion-body collapse in">
			<div class="accordion-inner">
				<div class="row">
					<div class="span2">Strategic Objective</div>
					<div class="span8"><?php echo $kpi_A->SasaranStrategis; ?></div>
				</div>

				<div class="row">
					<div class="span2">KPI Start</div>
					<div class="span8"><?php echo substr($kpi_A->KPI_BeginDate,0,10); ?></div>
				</div>

				<div class="row">
					<div class="span2">KPI End</div>
					<div class="span8"><?php echo substr($kpi_A->KPI_EndDate,0,10); ?></div>
				</div>

				<div class="row">
					<div class="span2">KPI</div>
					<div class="span8" id="kpi_text_A"><?php echo $kpi_A->KPI; ?></div>
				</div>
				<div class="row">
					<div class="span2">Description</div>
					<div class="span8" id="kpi_desc_A"><?php echo $kpi_A->Description; ?></div>
				</div>
				<div class="row">
					<div class="span2">Satuan</div>
					<div class="span8"><?php echo $kpi_A->Satuan; ?></div>
				</div>

				<div class="row">
					<div class="span2">Formula</div>
					<div class="span8"><?php echo $kpi_A->PCFormula; ?></div>
				</div>

				<div class="row">
					<div class="span2">Year to Date</div>
					<div class="span8"><?php echo $kpi_A->YTD; ?></div>
				</div>

				<div class="row">
					<div class="span2">Weight</div>
					<div class="span8"><?php echo $kpi_A->Bobot; ?></div>
				</div>
				<div class="row">
					<div class="span2">Baseline</div>
					<div class="span8"><?php echo $kpi_A->Baseline; ?></div>
				</div>
			</div>
		</div>
			
	</div>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a href="#collapseTwo" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1">
				Target
			</a>
		</div>
		<div id="collapseTwo" class="accordion-body collapse ">
			<div class="accordion-inner">
				<div class="row">
					<div class="span10">
						<table class="table table-bordered" width="780px">
							<tbody>
								<?php 
								for ($y=0; $y < 3 ; $y++) { 
									echo '<tr>';
									for ($x=1; $x <=4 ; $x++) { 
										echo '<th witdth="195px">'.date('M',mktime(0,0,0,($x+$y*4),1,2000)).'</th>';
									}
									echo '</tr>';
									echo '<tr>';
									for ($x=1; $x <=4 ; $x++) {
										$month_id  = $x+$y*4;
										echo '<td id="target_A_'.$month_id.'">';
										echo $targets_A[$month_id];
										echo '</td>';
										unset($month_id);
									}
									echo '</tr>';
								}
								?>
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
		</div>
			
	</div>
</div>
<div class="row">
	<div class="span10">
		<!-- <input type="button" id="btn_copy_text" class="btn" title="Copy KPI Text & Description to All Cascading" value="<i class='icon-copy'></i> Text"> -->
		<button id="btn_copy_text" class="btn" title="Copy KPI Text & Description to All Cascading"><i class="icon-copy"></i> Text</button>
		<button id="btn_copy_target" class="btn" title="Copy KPI Target to All Cascading"><i class="icon-copy"></i> Target</button>
		<!-- <button id="btn_copy_target" class="btn"><i class="icon-copy"></i> Target</button> -->
	</div>
</div>
<?php echo form_open($process, '', $hidden); ?>
<div class="row">
	<div class="span2">Cascading Number</div>
	<div class="span8"><?php echo form_number('nm_cascd', 1, 'id="nm_cascd" class="input-small" min=1'); ?></div>
</div>

<div class="row">
	<div class="span2">Reference Type</div>
	<div class="span8"><?php echo form_dropdown('slc_ref', $ref_ls, '','class="input-medium"'); ?></div>
</div>
<div id="div_field">

</div>
<div class="row">
	<div class="span10">
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Save</button>
		  <?php echo anchor('home','Cancel', 'class="btn" onClick="parent.$.fancybox.close();"'); ?>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<?php
	$this->load->view('template/bottom_popup_1_view');
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		load_field();
		
		$('#nm_cascd').change(function(event) {
			if($.isNumeric($(this).val())){
				load_field();
			}
		});
		$('#btn_copy_text').click(function(event) {
			$('.txt_kpi').val($('#kpi_text_A').text());
			$('.txt_desc').val($('#kpi_desc_A').text());
		});

		$('#btn_copy_target').click(function(event) {
			for (var i = 1; i <= 12; i++) {
				$('.chk_target_B_'+i).attr('checked', false);
				$('.nm_target_B_'+i).val(''); 
				var target_A = $('#target_A_'+i).text();
				if (target_A !='-') {
					$('.chk_target_B_'+i).attr('checked', true);
					$('.nm_target_B_'+i).val(target_A); 
				};
			};
			
		});

		$('#slc_ref').change(function(event) {
			if ($(this).val=3) {
				$('.nm_ref_weight').show();
			} else {
				$('.nm_ref_weight').hide();
			};
		});
		function load_field () {
			var base_url = '<?php echo base_url()?>'+'index.php/';
			var kpi_id = '<?php echo $this->uri->segment(4)?>';
			$('#div_field').load( base_url+'objective/rkk/ajax_cascade_field/'+ kpi_id +'/'+$('#nm_cascd').val());
		}
	});
</script>