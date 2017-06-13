<script type="text/javascript">
  $('.data-slave').hide();
  define();

  function define() {


    $('.data-master').children().children('.icon-folder-close').toggle(function() {
      var rkk = $(this).parent().parent().data('rkk');
      $('.data-slave[data-rkk='+rkk+']').show();
      $(this).attr('class', 'icon-folder-open icon');

      $.ajax({
        url: '<?php echo base_url();?>index.php/manager/achievement/show_sub',
        type: 'post',
        dataType: 'html',
        data: {rkk: rkk}
      })
      .done(function(respond) {
        $('.data-slave[data-rkk='+rkk+']').children().html(respond);
        $('.data-slave[data-rkk='+rkk+']').children().children('div').children('table').children('tbody').children('.data-slave').hide();
        define();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });


    }, function() {
      var rkk = $(this).parent().parent().data('rkk');

      $(this).attr('class', 'icon-folder-close icon');

      $('.data-slave[data-rkk='+rkk+']').hide();

    });

  }

</script>
