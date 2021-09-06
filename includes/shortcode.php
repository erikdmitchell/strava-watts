<?php $athlete = stwatt_athlete(); ?>
  
<div class="stwatt-wrapper">
    <div class="row">
        <div class="col-xs-2 time">
            <i class="far fa-clock"></i>
        </div>
        <div class="col-xs-2 elevation">
            <i class="fas fa-building"></i>
        </div>
        <div class="col-xs-4 distance">        
            <div class="odometer">
                <?php stwatt_str_wrap($athlete->stats->distance, '<span class="digit">', '</span>'); ?>
            </div>
        </div>
    </div>
</div>