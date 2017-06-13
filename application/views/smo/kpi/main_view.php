<?php $this->load->view('template/top_1_view'); ?>
<h3>Maintain RKK</h3>
<input id="hdn_rkk_id" type="hidden" value="{rkk_id}" />
<div class="row">
  <div class="span6">
    <div class="row">
      <div class="span2"> Period </div>
      <div class="span1"> {period}  </div>
      <div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
    </div>
    <div class="row">
      <div class="span2"> Person </div>
      <div class="span4">
        <a href="#myModal" role="button" class="btn" data-toggle="modal" title="Search Person"><i class="icon icon-list-alt"></i></a>
      </div>
    </div>

    <div class="row">
      <input type="hidden" id="hdn_nik" value='{nik}' />
      <div class="span6" id="emp">
        {emp}
      </div>
    </div>

    <div class="row">
      <input type="hidden" id="hdn_post_id" value={post_id} />
      <div class="span6" id="post">
        {post}
      </div>
    </div>

    <div class="row">
      <div class="span6" id="org">
        {org}

      </div>
    </div>
  </div>
  <div class="span6">
    <div class="row">
      <div class="span2"> Begin - End </div>
      <div class="span4 " id="begin_end">
        {begin_end}
      </div>
    </div>
    <div class="row">
      <div class="span2"> Status </div>
      <div class="span4 " id="status">
        {status}
      </div>
    </div>

    <div class="row">
      <div class="span2"> Total Weight </div>
      <div class="span4 " id="total_weight">
        {weight}
      </div>
    </div>

    <div class="row">
      <div class="span2"> Report to </div>

    </div>
    <div class="row">
      <div class="span6 " id="report_to">
        {report_to}
      </div>
    </div>

  </div>
</div>
<hr />
<div id="box-rkk">

</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Person Explorer</h3>
  </div>
  <div class="modal-body" id="Explorer">


  </div>

</div>
<?php $this->load->view('template/bottom_1_view'); ?>
<script>
	var baseUrl = '<?php echo base_url()?>index.php/';
</script>
<script>
  function refreshView() {
    showRkk();
  }

  function recalTotalWeight() {
    var nik = $('#hdn_nik').val();
    var postId = $('#hdn_post_id').val();
    $.ajax({
      url: baseUrl + "smo/kpi/weightTotal",
      dataType: 'json',
      type: 'POST',
      data: { nik: nik, postId: postId},
    })
    .done(function(respond) {
      $('#total_weight').html(respond.totalWeight + '%');

    })

  }

  function showRkk() {
    var nik = $('#hdn_nik').val();
    var postId = $('#hdn_post_id').val();
    $('#begin_end').empty();
    $('#total_weight').empty();
    $('#status').empty();
    $('#report_to').empty();
    $.ajax({
      url: baseUrl + "smo/kpi/checkRkk",
      dataType: 'json',
      type: 'POST',
      data: { nik: nik, postId: postId},
    })
    .done(function(respond) {
      $('#status').html('<span class="label'+respond.status.label+'">'+ respond.status.text+'</span>');
      $('#begin_end').html(respond.date.begin + ' - ' + respond.date.end);
      $('#total_weight').html(respond.weight + '%');
      $( "#report_to" ).append( '<ul>' );
      $.each(respond.reportTo,function(index, el) {
        $( "#report_to" ).append( '<li>'+ el.nik + ' - '+ el.name+ ' ['+el.postName+'] '+ el.begin + ' - '+ el.end +'</li>' );

      });
      $( "#report_to" ).append( '</ul>' );

      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

    $.ajax({
      url: baseUrl + "smo/kpi/detailRkk",
      type: 'POST',
      data: { nik: nik, postId: postId},
    })
    .done(function(respond) {
      $('#box-rkk').html(respond);

    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  }

  $(document).ready(function() {
    if ($('#hdn_rkk_id').val() != '-') {
      showRkk();
    }

  });



</script>
<script type="text/babel" src="<?php echo base_url(); ?>js/react.personOrgExp.js">
