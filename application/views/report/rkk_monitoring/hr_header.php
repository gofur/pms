<?php $this->load->view('template/top_1_view'); ?>
<h3>RKK Monitoring</h3>
<div id="box-notif">

</div>
<div class="row">
	<div class="span2">Period</div>
	<div class="span1">
		<?php echo $period->Tahun ?>
	</div>
	<div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
</div>
<div class="row">
	<div class="span2">Organization</div>
	<div class="span4" id="box-org">

	</div>
</div>
<div class="row">
	<div class="span2">Scope</div>
	<div class="span4">
		<label class="radio">
		  <input type="radio" name="rd_scope" id="rd_scope_2" value="1" checked> With Child Org
		</label>
		<label class="radio">
		  <input type="radio" name="rd_scope" id="rd_scope_1" value="0" > Only this Org
		</label>
	</div>
</div>
<div class="row">
	<div class="span4 offset2">
		<div class="span4"><button class="btn" id="btn_view"><i class="icon-refresh"></i> View</button></div>
	</div>
</div>

<div class="row">
	<div class="span12" id="box-list">

	</div>
</div>

<?php $this->load->view('template/bottom_1_view'); ?>
<script type="text/javascript" src="<?php echo base_url().'js/jqxcore.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxchart.core.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxdraw.js'; ?>" ></script>
<script type="text/javascript" src="<?php echo base_url().'js/jqxdata.js'; ?>" ></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';
		refresh_view();
		$('#spin-data').hide();

		$('#btn_view').click(function(event) {
			$('#spin-data').show();
			
			$("#box-list").empty();
			var scope = $("input[name=rd_scope]:checked").val()
			var org_id = $('#slc_org_0').val();
			var post_id = $('#slc_post').val();
			var max_el = $('.slc_org').length;
			for (var i = 1; i < max_el; i++) {
				if ($('#slc_org_'+i).val() != '') {
					org_id = $('#slc_org_'+i).val();
				};
			};
			// alert('test');
			$.ajax({
      	url: base_url + 'report/rkk_monitoring/show_list',
      	type: 'POST',
      	data: {
      		org_id : org_id,
      		post_id : post_id,
      		scope : scope,
      	},
      })
      .done(function(msg) {
      	$("#box-list").html(msg);
      	

      });
		});

		function refresh_view () {
			var base_url = '<?php echo base_url(). "index.php/" ?>';
			var nik = '<?php echo $this->session->userdata("NIK"); ?>';
			$('#spin-root-org').show();
			$('#spin-data').show();

			$.ajax({
        type: "POST",
        url: base_url+'report/rkk_monitoring/show_root_org',
        data: {
        	post_id : $('#slc_post').val(),
					month  : $('#slc_month').val()
				}
      }).done(function( msg ) {
        $("#box-org").html(msg);
				$('#spin-root-org').hide();


	    });

	    $.ajax({
        url: base_url+'hr/pk_adjust/check_notif',
      }).done(function( msg ) {
        $("#box-notif").html(msg);



	    });



		}
	});
</script>
 