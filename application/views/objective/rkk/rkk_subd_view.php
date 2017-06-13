<hr>
<?php
	if ($is_sub==TRUE) {
		echo '<div class="row"><div class="span12">';
		echo anchor('manager/rkk_transfer/main/'.$rkk_id, '<i class="icon-truck icon-flip-horizontal"></i> RKK', 'class="fancybox btn" data-fancybox-type="iframe" title="Transfer RKK"'); 
		echo '</div></div>';
		echo '<hr/>';
	}
?>
<input type="hidden" id="rkk_id" value="<?php echo isset($rkk_id)?$rkk_id:'' ?>" >

<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span2">RKK Start</div>
			<div class="span4"><?php echo substr($rkk->BeginDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">RKK End</div>
			<div class="span4"><?php echo substr($rkk->EndDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">Status</div>
			<div class="span4"><?php 
			$status = array(
				'<span class="label">Draft</span>',
				'<span class="label label-warning">Assigned</span>',
				'<span class="label label-important">Rejected</span>',
				'<span class="label label-success">Agreed</span>',
				'<span class="label label-success">Agreed</span>',
				'<span class="label label-success">Agreed</span>',
			);
			echo $status[$rkk->statusFlag]; 
			?></div>
		</div>
	</div>
	<div class="span6">
		<div class="row">
			<div class="span2">Last Superior</div>
			<div class="span4"><?php echo $spr_person->NIK ; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php echo $spr_person->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php echo $spr_post->PositionName; ?></div>
		</div>
	</div>
</div>
<?php 
	
	foreach ($persp_ls as $persp) {
		$data['persp']  = $persp;
		$data['weight'] = $persp_weight[$persp->PerspectiveID];
		$data['so_ls']  = $so_ls[$persp->PerspectiveID];
		$this->load->view('objective/rkk/so_list',$data);
	}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".so-kpi").hide();
		$(".toggle-so").toggle(function() {
			$(this).attr('class', 'icon-chevron-down icon-large btn btn-link toggle-so');
			var so_id = $(this).data('so');
			$("#so-kpi-"+so_id).show();

			var base_url = '<?php echo base_url(). "index.php/" ?>';
			$('#so-kpi-'+so_id+" td").load(base_url+'objective/rkk/show_kpi',{
				so_id  : so_id, 
				rkk_id : $("#rkk_id").val(),
				begin  : $('#dt_filter_start').val(),
				end    : $('#dt_filter_end').val(),
			} ,
				function(){
				/* Stuff to do after the page is loaded */
			});
			

		}, function() {
			$(this).attr('class', 'icon-chevron-right icon-large btn btn-link toggle-so');
			var so_id = $(this).data('so')
			$("#so-kpi-"+so_id).hide();
		});

		
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var base_url = '<?php echo base_url(). "index.php/" ?>';
		$(".fancybox-kpi").fancybox({
			closeClick  : false,
			afterClose  : function(){
				var so_id    = $(this.element).data('so');
				
				$('#so-kpi-'+so_id+" td").load(base_url+'objective/rkk/show_kpi',{
					so_id  : so_id, 
					rkk_id : $("#rkk_id").val(),
					begin  : $('#dt_filter_start').val(),
					end    : $('#dt_filter_end').val(),
				} ,
					function(){
					/* Stuff to do after the page is loaded */
				});
      },       
			helpers   : { 
				overlay : {closeClick: false}
			}
		});
	});
</script>
