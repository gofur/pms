  </div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootbox.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-datepicker.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dataTables.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/dataTables-bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrapSwitch.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.price_format.1.8.min.js" ></script>
<script type="text/javascript">
	$(document).ready(function () {
  $('form').submit(function() {
      $(this).find("button[type='submit']").prop('disabled',true);
    });
		/*$("input[type='submit']").click(function () {
        var $this = $(this);
        $this.prop('disabled', true);
        $('form').submit()
        setTimeout(function() {
          $this.prop('disabled', false);
        },2000);
    	});
  	$("button[type='submit']").click(function () {
      var $this = $(this);
      $this.prop('disabled', true);
      $('form').submit()
      setTimeout(function() {
        $this.prop('disabled', false);
      },2000);
  	});*/
	});
</script>


<script type="text/javascript">
  $(document).ready(function(){
    $(".datepicker").datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true
    });
    $('.disabled').attr('disabled',true);
    $('#rd_delimit').click(function(event) {
      $('#dt_end').attr('disabled',false);
      /* Act on the event */
    });

    $('#rd_remove').click(function(event) {
      $('#dt_end').attr('disabled',true);
      /* Act on the event */
    });
  });
</script>

