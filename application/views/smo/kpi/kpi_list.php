
<table class="table" style="background-color:#dff0d8;">
	<thead>
		<tr>
			<th width="300">KPI</th>
			<th >Begin<br/>End</th>
			<th>YTD</th>
			<th>Formula</th>
			<th>Weight (%)</th>
			<th>Target</th>
			<th>Ref.</th>
			<th width="120">Action</th>
		</tr>
	</thead>
	<tbody>
		{kpiLs}
			<tr>
				<td>{kpiLabel}</td>
				<td>{kpiBegin}<br />{kpiEnd}</td>
				<td>{kpiYtd}</td>
				<td>{kpiCounting}<br/>{kpiFormula}</td>
				<td>{kpiWeight}%</td>
				<td>{kpiYearTarget}</td>
				<td>{kpiRef}</td>
				<td>
					<a href="<?php echo base_url()?>index.php/objective/rkk/detail_kpi/{kpiId}" class="btn btn-kpi-detail fancybox-nonrefresh" title="View KPI Detail" data-fancybox-type="iframe" data-kpiid={kpiId}><i class="icon-list"></i></a>
					<a href="#modal-kpi" class="btn btn-kpi-edit" title="Edit KPI" data-kpiid={kpiId} data-toggle="modal" ><i class="icon-pencil "></i></a>

					<a href="<?php echo base_url()?>index.php/objective/rkk/relation_kpi_AB/{kpiId}" class="btn btn-kpi-rel fancybox-nonrefresh" title="Manage KPI Relation" data-kpiid={kpiId} data-fancybox-type="iframe"><i class="icon-link"></i></a>

					<a href="<?php echo base_url()?>index.php/objective/rkk/cascade_kpi/{kpiId}" class="btn btn-kpi-cascade fancybox-nonrefresh" title="Cascade KPI" data-kpiid={kpiId} data-fancybox-type="iframe" ><i class="icon-code-fork icon-flip-vertical icon-large"  ></i></a>

					<a class="btn btn-kpi-remove" title="Remove KPI" data-soid={soId} data-kpiid={kpiId}><i class="icon-trash "></i></a>

				</td>

			</tr>
		{/kpiLs}
	</tbody>
</table>

<script type="text/javascript">
$('.btn-kpi-edit').click(function(event) {
	resetFormKPI();

	$('#form-kpi').attr('action', baseUrl+'smo/kpi/editKpi');
	var kpiId = $(this).data('kpiid');
	$.ajax({
		url: baseUrl+'smo/kpi/getKpi',
		type: 'POST',
		dataType: 'json',
		data: {kpiId: kpiId}
	})
	.done(function(resp) {

		$('#txt_kpi_so').val(resp.kpi.SasaranStrategis);
		$('#hdn_kpi_so').val(resp.kpi.SasaranStrategisID);
		$('#hdn_kpi_kpiid').val(resp.kpi.KPIID);
		$('#txt_kpi').val(resp.kpi.KPI);
		$('#txt_kpi_desc').val(resp.kpi.Description);
		$('#slc_unit').val(resp.kpi.SatuanID);
		$('#slc_formula').val(resp.kpi.PCFormulaID);
		$('#slc_ytd').val(resp.kpi.YTDID);
		$('#nm_weight').val(resp.kpi.Bobot);
		$.each(resp.target,function(index, el) {
			$('#chk_month_'+el.Month).prop( "checked", true );
			$('#nm_target_'+el.Month).val(el.Target);
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

});

$('.btn-kpi-remove').click(function(event) {
	var kpiId = $(this).data('kpiid');
	var soId = $(this).data('soid');
	swal({
		title: "Are you sure to delete?",
		text: "You will not be able to recover this KPI!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes",
		cancelButtonText: "No",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function(isConfirm){
		if (isConfirm) {
			$.ajax({
				url: baseUrl+'smo/kpi/removeKpi',
				type: 'POST',
				dataType: 'json',
				data: {kpiId: kpiId}
			})
			.done(function() {
				swal("Deleted!", "KPI has been deleted.", "success");
				recalTotalWeight();
				recalPerspWeight();
				$('.so-kpi[data-soid='+soId+'] td').load(baseUrl+'smo/kpi/showKpi',{
					so_id  : soId,
					rkk_id : $("#hdn_rkk").val()
				})
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});


		} else {
			swal("Cancelled", "KPI is safe :)", "error");
		}
	});
});
</script>
