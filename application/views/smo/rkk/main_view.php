<?php $this->load->view('template/top_1_view'); ?>
<h3>Maintain RKK</h3>
<div class="row">
  <div class="span2"> Period </div>
  <div class="span1"> {period}  </div>
  <div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
</div>
<div class="row">
  <div class="span2"> Organization </div>
  <div class="span4">
    <div class="input-append">
      <input type="text" class="input-small disabled" disabled="disabled" id="txt_org_id" value=""/>
      <input type="text" class="input disabled" disabled="disabled" id="txt_org_name" value=""/>

      <a href="#myModal" role="button" class="btn" data-toggle="modal"><i class="icon icon-list-alt"></i></a>

    </div>
  </div>
</div>

<hr/>
<div class="alert alert-success" id="alert-success">

  <strong>Success</strong> <span id="count_rkk">???</span> RKK <span id="action_rkk">???</span>
</div>
<div class="alert alert-error" id="alert-error">

  <strong>Warning!</strong> Best check yo self, you're not looking too good.
</div>
<div class="row">
  <div class="span2 ">
    Bulk Action
  </div>
  <div class="span2 ">
      <select name="slc_action" id="slc_action" class="input ">
        <option value=""></option>
        <option value="create">Create</option>
        <option value="edit">Edit</option>
        <option value="rev">Revision</option>
      </select>
  </div>
</div>
<form action="" id="form">
<div id="div_date">
  <div class="row" >
    <div class="span2 ">
      Begin
    </div>
    <div class="span2 ">
      <input type="text" name="dt_begin" id="dt_begin" class="input-small datepicker" value="{begin}"/>
    </div>
  </div>

  <div class="row" >
    <div class="span2 ">
      End
    </div>
    <div class="span2 ">
      <input type="text" name="dt_end" id="dt_end" class="input-small datepicker" value="{end}"/>
    </div>
  </div>

</div>



<div class="row">
  <div class="span12">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>NIK</th>
          <th>Name</th>
          <th>Position</th>
          <th>Status</th>
          <th>Begin</th>
          <th>End</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody id="list-holder">

      </tbody>
    </table>
  </div>
</div>
<div class="row">
	<div class="span12">
		<div class="form-actions">
		  <button id="btn_process" type="submit" class="btn">Process</button>
		</div>
	</div>
</div>
</form>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Organization Explorer</h3>
  </div>
  <div class="modal-body" id="orgExplorer">


  </div>

</div>

<div id="rkkRelModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="rkkRelLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="rkkRelLabel">RKK Relation</h3>
  </div>
  <div class="modal-body" id="rkkRelManage">

    <table class="table table-striped " id="rkkRelTable">
      <thead>
        <tr>
          <th>NIK</th>
          <th>Name</th>
          <th>Position</th>
          <th>Rel. Begin</th>
          <th>Rel. End</th>
        </tr>
      </thead>
      <tbody id="rkkRelList"></tbody>
    </table>
  </div>

</div>
<?php $this->load->view('template/bottom_1_view'); ?>
<script>
  var baseUrl = '<?php echo base_url() ?>' ;
</script>
<script>
  $('#div_date').hide();
  $('.alert').hide();
  $( ".datepicker" ).datepicker({
    dateFormat: 'yy/mm/dd',
    changeMonth: true,
    changeYear: true
  });
  $('#slc_action').change(function(event) {
    $('.chk_rkk').prop( "checked", false );
    $('.chk_rkk').attr( "disabled", 'disabled' );
    $('#div_date').hide();
    $('.alert').hide();

    if ($(this).val() === 'create') {
      $('.chk_blank').prop( "checked", true );
      $('.chk_blank').removeAttr('disabled');
      $('#div_date').show();

    } else if ($(this).val() === 'edit') {
      $('#div_date').show();
      $('.chk_close').removeAttr('disabled');
      $('.chk_open').removeAttr('disabled');

    } else if ($(this).val() === 'rev') {
      $('.chk_close').prop( "checked", true );
      $('.chk_close').removeAttr('disabled');
    } else {
      $('.chk_rkk').removeAttr('disabled');
    }

  });

  function refreshView() {
    $('#div_date').hide();
    $('.alert').hide();

    var org_id = $('#txt_org_id').val();

    if (org_id != '') {
      $.ajax({
        url: '<?php echo base_url();?>index.php/smo/rkk/show_person',
        type: 'POST',
        data: {org_id: $('#txt_org_id').val()}
      })
      .done(function(respond) {
        $('#list-holder').html(respond);
        $('.chk_rkk').attr( "disabled", 'disabled' );

        $('.btn-detail').click(function(event) {
          event.preventDefault();
          var rkk_id = $(this).data('id');
          $.ajax({
            url: '<?php echo base_url()?>index.php/smo/rkk/fix_rel',
            type: 'POST',
            dataType: 'json',
            data: {rkk_id: rkk_id}
          }).always(function() {

            $.ajax({
              url: '<?php echo base_url()?>index.php/smo/rkk/show_rel_to',
              type: 'POST',
              dataType: 'json',
              data: {rkk_id: rkk_id}
            })
            .done(function(respond) {
              $('#rkkRelList').empty();
              $.each(respond.result,function(index, row) {
                $('#rkkRelList').append('<tr>'+
                '<td>' + row.nik +'</td>'+
                '<td>' + row.name +'</td>'+
                '<td>' + row.post_name +'</td>'+
                '<td>' + row.begin +'</td>'+
                '<td>' + row.end +'</td>'+
                '</tr>');

              });
            });

          });
        });

        $('.btn-kpi').click(function(event) {
          event.preventDefault();
          var rkk_id = $(this).data('id');

          window.location.href =  baseUrl+'index.php/smo/kpi/plan/'+rkk_id;
        });
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        $('#slc_action').val('');
      });

    }
  }

  $('#btn_process').click(function(event) {
    event.preventDefault();
    var action = $('#slc_action').val();
    if (action === 'create') {
      $.ajax({
        url: baseUrl + 'index.php/smo/rkk/create_rkk',
        type: 'POST',
        dataType: 'json',
        data: $('#form').serialize()
      })
      .done(function(respond) {
        $('#count_rkk').html(respond.rkk);
        $('#action_rkk').html('created');
        $('#alert-success').show();
        refreshView();
      })
      .fail(function() {
        $('#alert-error').show();
        console.log("error");
      });

    } else if (action === 'edit') {
      $.ajax({
        url: baseUrl + 'index.php/smo/rkk/edit_rkk',
        type: 'POST',
        dataType: 'json',
        data: $('#form').serialize()
      })
      .done(function(respond) {
        $('#count_rkk').html(respond.rkk);
        $('#action_rkk').html('edited');

        $('#alert-success').show();
      })
      .fail(function() {
        $('#alert-error').show();
        console.log("error");
      });
    } else if (action === 'rev') {
      $.ajax({
        url: baseUrl + 'index.php/smo/rkk/rev_rkk',
        type: 'POST',
        dataType: 'json',
        data: $('#form').serialize()
      })
      .done(function(respond) {
        $('#count_rkk').html(respond.rkk);
        $('#action_rkk').html('opened and ready to revision');

        $('#alert-success').show();
        refreshView();
      })
      .fail(function() {
        $('#alert-error').show();
        console.log("error");
      });
    }
    refreshView();


  });

</script>

<script type="text/babel" src="<?php echo base_url(); ?>js/react.orgExp.js"></script>
