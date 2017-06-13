<div class="row">
	<div class="span12">
		<?php 
      echo anchor('manager/rkk_copy/main/'.$rkk_id, '<i class="icon-copy"></i> RKK', 'class="fancybox-nonrefresh btn" data-fancybox-type="iframe" title="Copy RKK"'); 
      if (isset($is_sub) && $is_sub==TRUE) {

        echo anchor('manager/rkk_transfer/main/'.$rkk_id, '<i class="icon-truck icon-flip-horizontal"></i> RKK', 'class="fancybox btn" data-fancybox-type="iframe" title="Transfer RKK"'); 
        
      }

    ?>
	</div>
</div>