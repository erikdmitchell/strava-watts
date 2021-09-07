<?php $athlete = stwatt_athlete(); ?>

<div id="computer-wrapper" class="computer-wrapper">
    <div class="computer">
        <div class="computer-row">
            <div class="data align-center text-uppercase">2021 Stats</div>
        </div>
        <div class="computer-row">
            <div class="data-wrap">
                <div class="data-label">Time</div>
                <div class="data"><?php stwatt_str_wrap($athlete->stats->time, '<span>', '</span>'); ?></div>
            </div>
        </div>
        <div class="computer-row">
            <div class="data-wrap">
                <div class="data-label">Miles</div>
                <div class="data"><?php stwatt_str_wrap($athlete->stats->distance, '<span>', '</span>'); ?></div>
            </div>
        </div> 

        <div class="computer-row">
            <div class="data-wrap">
                <div class="data-label">Elev</div>
                <div class="data"><?php stwatt_str_wrap($athlete->stats->elevation, '<span>', '</span>'); ?></div>
            </div>
        </div> 
    </div>
    <div class="powered-by">
        <img src="<?php echo STWATT_ASSETS_URL; ?>/images/pb-strava-horz-color.png" alt="powered by strava" />
    </div>  
</div>