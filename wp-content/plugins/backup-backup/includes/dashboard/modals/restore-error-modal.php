<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

?>

<div class="modal" id="restore-error-modal">

  <div class="modal-wrapper no-hpad" style="max-width: 900px; max-width: min(900px, 80vw)">
    <a href="#" class="modal-close">×</a>
    <div class="modal-content center">

      <div class="mm60 f30 bold black flex flexcenter mb">
        <img src="<?php echo $this->get_asset('images', 'red-cross.svg'); ?>" alt="red-cross" width="110px">
        <?php _e('Restore failed', 'backup-backup') ?>
      </div>

      <div class="mm60 f20 left-align">
        <?php _e('The restoring process ran into some difficulties. Error report:', 'backup-backup') ?>
      </div>

      <div class="mm60 relative">
        <div class="log-wrapper">
          <pre id="restore-error-pre" style="min-height: 32px;"></pre>
        </div>

        <div class="mm60 right-align" style="position: absolute; top: 15px; right: 30px;">
          <a href="#" class="btn inline btn-with-img btn-img-low-pad btn-pad bmi-copper" data-copy="restore-error-pre">
            <div class="text">
              <img src="<?php echo $this->get_asset('images', 'copy-icon.png'); ?>" alt="copy-img">
              <div class="f18 semibold"><?php _e('Copy', 'backup-backup') ?></div>
            </div>
          </a>
        </div>
      </div>

      <div class="mm60 f20 mbl mtl lh30 left-align">
        <?php
          _e('Please copy this and post into a new support thread you open in the Support Forum - we are happy to help you quickly (100% free)!', 'backup-backup');
        ?>
      </div>

      <div class="mm60 flex flexcenter mtl">
        <div class="flex1 f16">
          <a href="<?php echo get_site_url(); ?>/?backup-migration=PROGRESS_LOGS&progress-id=latest_migration.log&backup-id=current&t=<?php echo time(); ?>"
            class="hoverable nodec secondary" download="restore_error_log">
            <?php _e('Download logs', 'backup-backup') ?>
          </a>
        </div>
        <div class="flex2">
          <a class="btn inline semibold mm60 f16" href="https://wordpress.org/support/plugin/backup-backup/" target="_blank">
            <?php _e('Go to Support Forum', 'backup-backup') ?>
          </a>
        </div>
        <div class="flex1 f16 tooltip-html info-cursor" tooltip="<?php _e("Your account on Wordpress.org (where you open a new support thread) is different to the one you login to your WordPress dashboard (where you are now). If you don't have a WordPress.org account yet, please sign up at the top right on here. It only takes a minute :) Thank you!", 'backup-backup') ?>">
          <?php _e('Cannot login there?', 'backup-backup') ?>
        </div>
      </div>

    </div>
  </div>

</div>
