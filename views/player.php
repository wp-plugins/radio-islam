<div id="rii-player" class="rii-<?php echo esc_attr( $skin ); ?>-skin clearfix" data-interval="<?php echo esc_attr( $interval ); ?>">
    <div id="rii-current-played" class="clearfix" data-info>
        <p class="intro"><?php _e( 'Radio Islam Indonesia', 'rii' ); ?></p>
    </div>
    <!-- PLAYER BEGIN -->
    <div id="rii-player-interface" class="jp-jplayer"></div>
    <!-- PLAYER END -->
    <!-- CONTROLS BEGIN -->
    <div id="rii-controls" class="clearfix">
        <div id="rii-prev" class="rii-control"><i class="fa fa-step-backward"></i></div>
        <div id="rii-play-pause">
            <div id="rii-play" class="rii-control"><i class="fa fa-play"></i></div>
            <div id="rii-pause" class="rii-control"><i class="fa fa-pause"></i></div>
        </div>
        <div id="rii-next" class="rii-control"><i class="fa fa-step-forward"></i></div>
        <div id="rii-timeremain" class="clearfix">
            <div id="rii-currentTime"></div>
            <!--<div id="duration"></div>-->
        </div>
        <?php if ( $equalizer ) { ?>
        <div id="equalizer"></div>
        <?php } ?>
    </div>
    <!-- CONTROLS END -->
    <!-- PLAYLIST BEGIN -->
    <div id="rii-playlist" class="clearfix">
        <div id="rii-playlist-content"></div>
    </div>
    <!-- PLAYLIST END -->
</div>
<?php if ( $credits ) { ?>
<div class="rii-copyright"><span><?php printf( __( 'Designed by <a href="%s" target="_blank">Oasemedia Dev.</a>', 'rii' ), esc_url( 'http://radioislam.or.id' ) ) ?></span></div>
<?php } ?>