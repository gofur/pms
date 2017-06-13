<hr>
    <footer>
        <p class="span5 up">PM System ver.1.6 &copy; Corporate Human Resources - Kompas Gramedia 2012
			<br /> best view Firefox, Chrome, Internet Explorer 8+
        </p>
        <p class="footer-right"><?php echo img('img/footer.png')?>
        </p>
    </footer>
  </div>
</body>
</html>
<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootbox.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-datepicker.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dataTables.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/DT_bootstrap.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrapSwitch.js" ></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/sweetalert/sweetalert.min.js" ></script>

<script src="<?php echo base_url()?>js/reactjs/react.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/reactjs/react-dom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/reactjs/babel.browser.min.js" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function () {
		$(".fancybox").fancybox({
			closeClick  : false,
			afterClose	: function() {
          parent.location.reload(true);
      },
			helpers   : {
				overlay : {closeClick: false}
			}

		});
		$(".fancybox-nonrefresh").fancybox({
			closeClick  : false,
			helpers   : {
				overlay : {closeClick: false}
			}

		});


	});
</script>
