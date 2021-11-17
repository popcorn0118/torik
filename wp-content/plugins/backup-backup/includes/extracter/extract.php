<?php

  // Namespace
  namespace BMI\Plugin\Extracter;

  // Use
  use BMI\Plugin\BMI_Logger as Logger;
  use BMI\Plugin\Dashboard as Dashboard;
  use BMI\Plugin\Database\BMI_Database as Database;
  use BMI\Plugin\Database\BMI_Database_Importer as BetterDatabaseImport;
  use BMI\Plugin\Progress\BMI_ZipProgress as Progress;
  use BMI\Plugin\Backup_Migration_Plugin as BMP;
  use BMI\Plugin\Zipper\Zip as Zip;
  use BMI\Plugin\Zipper\BMI_Zipper as ZipManager;
  use BMI\Plugin\Database\BMI_Database_Sorting as SmartDatabaseSort;

  // Exit on direct access
  if (!defined('ABSPATH')) {
    exit;
  }

  /**
   * BMI_Extracter
   */
  class BMI_Extracter {

    public function __construct($backup, &$migration, $tmptime = false, $isCLI = false, $options = []) {

      // Globals
      global $table_prefix;

      // Requirements
      require_once BMI_INCLUDES . '/database/manager.php';
      require_once BMI_INCLUDES . '/database/better-restore.php';
      require_once BMI_INCLUDES . '/database/smart-sort.php';

      // IsCLI?
      $this->isCLI = $isCLI;

      // Backup name
      $this->backup_name = $backup;

      // Logger
      $this->migration = $migration;

      // Temp name
      $this->tmptime = time();

      // Use specified name if it is in batching mode
      if (is_numeric($tmptime)) $this->tmptime = $tmptime;

      // Splitting enabled?
      $this->splitting = Dashboard\bmi_get_config('OTHER:RESTORE:SPLITTING') ? true : false;

      // Restore start time
      $this->start = microtime(true);

      // File amount by default 0 later we replace it with scan
      $this->fileAmount = 0;
      $this->recent_export_seek = 0;
      $this->processData = [];
      $this->conversionStats = [];

      // Options
      $this->batchStep = 0;
      if (isset($options['amount'])) {
        $this->fileAmount = intval($options['amount']);
      }
      if (isset($options['start'])) {
        $this->start = floatval($options['start']);
      }
      $this->continueFile = false;
      if (isset($options['continueFile'])) {
        $this->continueFile = $options['continueFile'];
      }
      $this->continueSeek = false;
      if (isset($options['continueSeek'])) {
        $this->continueSeek = $options['continueSeek'];
      }
      if (isset($options['step'])) {
        $this->batchStep = intval($options['step']);
      }
      $this->databaseExist = false;
      if (isset($options['databaseExist'])) {
        $this->databaseExist = (($options['databaseExist'] == 'true' || $options['databaseExist'] === '1' || $options['databaseExist'] === 1 || $options['databaseExist'] === true) ? true : false);
      }
      $this->firstDB = true;
      if (isset($options['firstDB'])) {
        $this->firstDB = (($options['firstDB'] == 'true' || $options['firstDB'] === '1' || $options['firstDB'] === 1 || $options['firstDB'] === true) ? true : false);
      }
      $this->firstExtract = true;
      if (isset($options['firstExtract'])) {
        $this->firstExtract = (($options['firstExtract'] == 'false' || $options['firstExtract'] === '1' || $options['firstExtract'] === 1 || $options['firstExtract'] === false) ? false : true);
      }

      $this->db_xi = 0;
      $this->ini_start = 0;
      $this->table_names_alter = [];

      if (isset($options['db_xi'])) {
        $this->db_xi = ((is_numeric($options['db_xi'])) ? intval($options['db_xi']) : 0);
      }
      if (isset($options['ini_start'])) {
        $this->ini_start = ((is_numeric($options['ini_start'])) ? intval($options['ini_start']) : microtime(true));
      }
      if (isset($options['table_names_alter'])) {
        $this->table_names_alter = $options['table_names_alter'];
      }
      if (isset($options['recent_export_seek'])) {
        $this->recent_export_seek = intval($options['recent_export_seek']);
      }
      if (isset($options['processData'])) {
        $this->processData = $options['processData'];
      }
      if (isset($options['conversionStats'])) {
        $this->conversionStats = $options['conversionStats'];
      }

      // Name
      $this->tmp = untrailingslashit(ABSPATH) . '/backup-migration_' . $this->tmptime;
      $GLOBALS['bmi_current_tmp_restore'] = $this->tmp;
      $GLOBALS['bmi_current_tmp_restore_unique'] = $this->tmptime;

      // Scan file
      $this->scanFile = untrailingslashit(BMI_INCLUDES) . '/htaccess/.restore_scan_' . $this->tmptime;

      // Prepare database connection
      $this->db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // Save current wp-config to replace (only those required)
      $this->DB_NAME = DB_NAME;
      $this->DB_USER = DB_USER;
      $this->DB_PASSWORD = DB_PASSWORD;
      $this->DB_HOST = DB_HOST;
      $this->DB_CHARSET = DB_CHARSET;
      $this->DB_COLLATE = DB_COLLATE;

      $this->AUTH_KEY = AUTH_KEY;
      $this->SECURE_AUTH_KEY = SECURE_AUTH_KEY;
      $this->LOGGED_IN_KEY = LOGGED_IN_KEY;
      $this->NONCE_KEY = NONCE_KEY;
      $this->AUTH_SALT = AUTH_SALT;
      $this->SECURE_AUTH_SALT = SECURE_AUTH_SALT;
      $this->LOGGED_IN_SALT = LOGGED_IN_SALT;
      $this->NONCE_SALT = NONCE_SALT;

      $this->ABSPATH = ABSPATH;
      $this->WP_CONTENT_DIR = trailingslashit(WP_CONTENT_DIR);

      $this->WP_DEBUG_LOG = WP_DEBUG_LOG;
      $this->table_prefix = $table_prefix;
      $this->code = get_option('z__bmi_xhria', false);
      if (isset($options['code']) && $this->code == false) {
        $this->code = $options['code'];
      }

      $this->siteurl = get_option('siteurl');
      $this->home = get_option('home');

      $this->src = BMI_BACKUPS . '/' . $this->backup_name;

    }

    public function replacePath($path, $sub, $content) {
      $path .= DIRECTORY_SEPARATOR . 'wordpress' . $sub;

      // Handle only database backup
      if (!file_exists($path)) return;

      $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

      $clent = strlen($content);
      $sublen = strlen($path);
      $files = [];
      $dirs = [];

      foreach ($rii as $file) {
        if (!$file->isDir()) {
          $files[] = substr($file->getPathname(), $sublen);
        } else {
          $dirs[] = substr($file->getPathname(), $sublen);
        }
      }

      for ($i = 0; $i < sizeof($dirs); ++$i) {
        $src = $path . $dirs[$i];
        if (strpos($src, $content) !== false) {
          $dest = $this->WP_CONTENT_DIR . $sub . substr($dirs[$i], $clent);
        } else {
          $dest = $this->ABSPATH . $sub . $dirs[$i];
        }

        $dest = untrailingslashit($dest);
        if (!file_exists($dest)/* || !is_dir($dest)*/) {
          @mkdir($dest, 0755, true);
        }
      }

      for ($i = 0; $i < sizeof($files); ++$i) {
        if (strpos($files[$i], 'debug.log') !== false) {
          array_splice($files, $i, 1);

          break;
        }
        if (strpos($files[$i], 'wp-config.php') !== false && $this->same_domain != true) {
          array_splice($files, $i, 1);

          break;
        }
      }

      $max = sizeof($files);
      for ($i = 0; $i < $max; ++$i) {
        $src = $path . $files[$i];
        if (strpos($src, $content) !== false) {
          $dest = $this->WP_CONTENT_DIR . $sub . substr($files[$i], $clent);
        } else {
          $dest = $this->ABSPATH . $sub . $files[$i];
        }

        if (file_exists($src)) rename($src, $dest);

        if ($i % 100 === 0) {
          $this->migration->progress(25 + intval((($i / $max) * 100) / 4));
        }
      }
    }

    public function replaceAll($content) {
      $this->replacePath($this->tmp, DIRECTORY_SEPARATOR, $content);
    }

    public function cleanup() {

      $dir = $this->tmp;

      if (is_dir($dir) && file_exists($dir)) {

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        $this->migration->log(__('Removing ', 'backup-backup') . iterator_count($files) . __(' files', 'backup-backup'), 'INFO');
        foreach ($files as $file) {
          if ($file->isDir()) {
            @rmdir($file->getRealPath());
          } else {
            @unlink($file->getRealPath());
          }
        }

        @rmdir($dir);

      }

      if (file_exists($this->scanFile)) {
        @unlink($this->scanFile);
      }

      $sc = BMI_INCLUDES . '/htaccess/.restore_secret';
      if (file_exists($sc)) {
        @unlink($sc);
      }

    }

    public function makeUnZIP() {

      // Source
      $src = $this->src;

      // Extract
      $this->zip = new Zip();

      if ($this->isCLI) {

        $isOk = $this->zip->unzip_file($src, $this->tmp, $this->migration);

      } else {

        $last_seek = $this->recent_export_seek;

        $file = new \SplFileObject($this->scanFile);
        $file->seek($file->getSize());
        $total_lines = $file->key() + 1;
        $files = [];
        $seek_begin = 0;
        $recent_seek = $last_seek;
        $shouldRepeat = false;

        $batch = 150;
        if ($total_lines > 1000) $batch = 500;
        if ($total_lines > 2000) $batch = 1000;
        if ($total_lines > 6000) $batch = 2000;
        if ($total_lines > 12000) $batch = 4000;
        if ($total_lines > 36000) $batch = 6000;
        if ($total_lines > 50000) $batch = 10000;
        if ($total_lines > 100000) $batch = 30000;
        if ($total_lines > 150000) $batch = 40000;
        if ($total_lines > 200000) $batch = 60000;

        if ($this->firstExtract == true) {
          $this->migration->log(__("Preparing batching technique for extraction...", 'backup-backup'), 'STEP');
          $this->migration->log(__('Files exported per batch: ', 'backup-backup') . $batch, 'INFO');
        }

        for ($i = $last_seek; $i < $total_lines; ++$i) {

          $file->seek($i);
          $line = trim($file->current());

          if ($line && strlen($line) > 0) {

            $files[] = $line;

          }

          $seek_begin++;
          $recent_seek = $i;
          if ($seek_begin > $batch) {

            $shouldRepeat = true;
            break;

          }

        }

        $isOk = $this->zip->extract_files($src, $files, $this->tmp, $this->migration, $this->firstExtract);

      }


      if (!$isOk) {

        // Verbose
        $this->migration->log(__('Failed to extract the files...', 'backup-backup'), 'WARN');
        $this->cleanup();

        return false;

      } else {

        if (!$this->isCLI) {

          $i = $recent_seek + 1;
          $milestone = intval((($i / $total_lines) * 100) / 4);
          $this->migration->progress($milestone);
          $this->migration->log(__('Extraction milestone: ', 'backup-backup') . $i . '/' . $total_lines . ' (' . number_format(($i / $total_lines) * 100, 2) . '%)', 'INFO');

        }

      }

      // Verbose
      if (!$this->isCLI && $shouldRepeat === true) {

        $this->recent_export_seek = $recent_seek;
        return 'repeat';

      } else {

        $this->migration->log(__('Files extracted...', 'backup-backup'), 'SUCCESS');
        return true;

      }

    }

    public function randomString($length = 64) {

      $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $str = "";

      for ($i = 0; $i < $length; ++$i) {

        $str .= $chars[mt_rand(0, strlen($chars) - 1)];

      }

      return $str;

    }

    public function makeWPConfigCopy() {

      $abs = untrailingslashit(ABSPATH);
      $this->migration->log(__('Saving wp-config file...', 'backup-backup'), 'STEP');
      copy($abs . '/wp-config.php', $abs . '/wp-config.' . $this->tmptime . '.php');
      $this->migration->log(__('File wp-config saved', 'backup-backup'), 'SUCCESS');

    }

    public function getCurrentManifest($first = false) {

      if ($first == true) {
        $this->migration->log(__('Getting backup manifest...', 'backup-backup'), 'STEP');
      }

      $manifest = json_decode(file_get_contents($this->tmp . '/bmi_backup_manifest.json'));

      if ($first == true) {
        $this->migration->log(__('Manifest loaded', 'backup-backup'), 'SUCCESS');
      }

      return $manifest;

    }

    public function restoreBackupFromFiles($manifest) {

      $this->same_domain = untrailingslashit($manifest->dbdomain) == untrailingslashit($this->siteurl) ? true : false;
      $this->migration->log(__('Restoring files (this process may take a while)...', 'backup-backup'), 'STEP');
      $contentDirectory = $this->WP_CONTENT_DIR;
      $pathtowp = DIRECTORY_SEPARATOR . 'wp-content';
      if (isset($manifest->config->WP_CONTENT_DIR) && isset($manifest->config->ABSPATH)) {
        $absi = $manifest->config->ABSPATH;
        $cotsi = $manifest->config->WP_CONTENT_DIR;
        if (strlen($absi) <= strlen($cotsi) && substr($cotsi, 0, strlen($absi)) == $absi) {
          $inside = true;
          $pathtowp = substr($cotsi, strlen($absi));
        } else {
          $inside = false;
          $pathtowp = $cotsi;
        }
      }

      $this->replaceAll($pathtowp);
      $this->migration->log(__('All files restored successfully.', 'backup-backup'), 'SUCCESS');

    }

    public function restoreDatabaseV1(&$manifest) {

      $this->migration->log(__('Older backup detected, using V1 engine to restore database...', 'backup-backup'), 'WARN');
      $this->migration->log(__('Database size: ' . BMP::humanSize(filesize($this->tmp . '/bmi_database_backup.sql')), 'backup-backup'), 'INFO');
      $old_domain = $manifest->dbdomain;
      $new_domain = $this->siteurl; // parse_url(home_url())['host'];

      $abs = BMP::fixSlashes($manifest->config->ABSPATH);
      $newabs = BMP::fixSlashes(ABSPATH);
      $file = $this->tmp . '/bmi_database_backup.sql';
      $this->db->importDatabase($file, $old_domain, $new_domain, $abs, $newabs, $manifest->config->table_prefix, $this->siteurl, $this->home);
      $this->migration->log(__('Database restored', 'backup-backup'), 'SUCCESS');

    }

    public function setDBProgress($xi, $init_start, $table_names_alter) {

      $this->db_xi = $xi;
      $this->ini_start = $init_start;
      $this->table_names_alter = $table_names_alter;

    }

    public function alter_tables(&$manifest) {

      $storage = $this->tmp . DIRECTORY_SEPARATOR . 'db_tables';

      $queriesAll = $manifest->total_queries;
      if (isset($this->conversionStats['total_queries'])) {
        $queriesAll = $this->conversionStats['total_queries'];
      }

      //                                             $manifest->total_queries # the other solution
      $importer = new BetterDatabaseImport($storage, $queriesAll, $manifest->config->ABSPATH, $manifest->dbdomain, $this->siteurl, $this->migration, $this->isCLI, $this->conversionStats);

      $importer->xi = $this->db_xi;
      $importer->init_start = $this->ini_start;
      $importer->table_names_alter = $this->table_names_alter;

      $importer->alter_names();
      $this->migration->log(__('Database restored', 'backup-backup'), 'SUCCESS');

    }

    public function restoreDatabaseV2(&$manifest) {

      $storage = $this->tmp . DIRECTORY_SEPARATOR . 'db_tables';

      if ($this->firstDB == true) {
        $this->migration->log(__('Successfully detected backup created with V2 engine, importing...', 'backup-backup'), 'INFO');
        $this->migration->log(__('Restoring database...', 'backup-backup'), 'STEP');
      }

      $queriesAll = $manifest->total_queries;
      if (isset($this->conversionStats['total_queries'])) {
        $queriesAll = $this->conversionStats['total_queries'];
      }
      $importer = new BetterDatabaseImport($storage, $queriesAll, $manifest->config->ABSPATH, $manifest->dbdomain, $this->siteurl, $this->migration, $this->isCLI, $this->conversionStats);

      if ($this->isCLI) {

        $importer->showFirstLogs();
        $importer->import();

      } else {

        if ($this->firstDB == true) {
          $importer->showFirstLogs();
        }

        $sqlFiles = $importer->get_sql_files($this->firstDB);

        if ($this->firstDB != true) {
          $importer->xi = $this->db_xi;
          $importer->init_start = $this->ini_start;
          $importer->table_names_alter = $this->table_names_alter;
        }

        if ($this->continueFile != false && $this->continueFile != '' && $this->continueSeek != false && $this->continueSeek != '') {

          $import = $importer->restore_by_file($this->continueFile, $this->continueSeek);
          $importer->queries_ended();
          $this->continueFile = $this->continueFile;
          $this->setDBProgress($importer->xi, $importer->init_start, $importer->table_names_alter);

        } else {

          if (sizeof($sqlFiles) > 0) {

            $import = $importer->restore_by_file($sqlFiles[0]);
            $importer->queries_ended();
            $this->continueFile = $sqlFiles[0];
            $this->setDBProgress($importer->xi, $importer->init_start, $importer->table_names_alter);

          } else {

            return true;

          }

        }

        if ($import !== true) {

          return ['status' => 'repeat', 'file' => $this->continueFile, 'seek' => $import];

        } else {

          return ['status' => 'new_file'];

        }

      }

      $this->migration->log(__('Database restored', 'backup-backup'), 'SUCCESS');

    }

    public function restoreDatabaseDynamic(&$manifest) {

      if ($this->firstDB == true) {
        $this->migration->log(__('Checking the database structure...', 'backup-backup'), 'STEP');
      }

      if (is_dir($this->tmp . DIRECTORY_SEPARATOR . 'db_tables')) {

        if (!$this->isCLI) {

          $import = $this->restoreDatabaseV2($manifest);
          return $import;

        } else {

          $this->restoreDatabaseV2($manifest);

        }

      } elseif (file_exists($this->tmp . '/bmi_database_backup.sql')) {

        $this->restoreDatabaseV1($manifest);

      } else {

        $this->migration->log(__('This backup does not contain database copy, omitting...', 'backup-backup'), 'INFO');
        return false;

      }

      return true;

    }

    public function replaceDbPrefixInWPConfig(&$manifest) {

      $abs = untrailingslashit(ABSPATH);
      $curr_prefix = $this->table_prefix;
      $new_prefix = $manifest->config->table_prefix;
      $this->migration->log(__('Restoring wp-config file...', 'backup-backup'), 'STEP');
      $file = file($abs . '/wp-config.' . $this->tmptime . '.php');
      rename($abs . '/wp-config.' . $this->tmptime . '.php', $abs . '/wp-config.php');
      $wpconfig = file_get_contents($abs . '/wp-config.php');
      if (strpos($wpconfig, '"' . $curr_prefix . '";') !== false) {
        $wpconfig = str_replace('"' . $curr_prefix . '";', '"' . $new_prefix . '";', $wpconfig);
      } elseif (strpos($wpconfig, "'" . $curr_prefix . "';") !== false) {
        $wpconfig = str_replace("'" . $curr_prefix . "';", "'" . $new_prefix . "';", $wpconfig);
      }

      file_put_contents($abs . '/wp-config.php', $wpconfig);
      $this->migration->log(__('WP-Config restored', 'backup-backup'), 'SUCCESS');

    }

    public function restoreOriginalWPConfig($remove = true) {

      $abs = untrailingslashit(ABSPATH);
      $tmp_file_f = $abs . '/wp-config.' . $this->tmptime . '.php';
      if (file_exists($tmp_file_f)) {
        copy($tmp_file_f, $abs . '/wp-config.php');
        if ($remove === true) @unlink($tmp_file_f);
      }

      wp_load_alloptions(true);

    }

    public function makeNewLoginSession(&$manifest) {

      wp_load_alloptions(true);

      $this->migration->log(__('Making new login session', 'backup-backup'), 'STEP');

      if ($manifest->cron === true || $manifest->cron === 'true' || $manifest->uid === 0 || $manifest->uid === '0') {
        $manifest->uid = 1;
      }

      if (is_numeric($manifest->uid)) {
        $existant = (bool) get_users(['include' => $manifest->uid, 'fields' => 'ID']);
        if ($existant) {
          $user = get_user_by('id', $manifest->uid);
        } else {
          $existant = (bool) get_users(['include' => 1, 'fields' => 'ID']);
          if ($existant) {
            $user = get_user_by('id', 1);
          }
        }
      }

      if (isset($user) && is_object($user) && property_exists($user, 'ID')) {
        clean_user_cache(get_current_user_id());
        clean_user_cache($user->ID);
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID, $user->user_login);
        wp_set_auth_cookie($user->ID, 1, is_ssl());
        do_action('wp_login', $user->user_login, $user);
        update_user_caches($user);
      }

      $this->migration->log(__('User should be logged in', 'backup-backup'), 'SUCCESS');

    }

    public function setOrUpdateXhria() {

      if ($this->code && is_string($this->code) && strlen($this->code) > 0) update_option('z__bmi_xhria', $this->code);
      else delete_option('z__bmi_xhria');

    }

    public function clearElementorCache() {

      $file = trailingslashit(wp_upload_dir()['basedir']) . 'elementor';
      if (file_exists($file) && is_dir($file)) {
        $this->migration->log(__('Clearing elementor template cache...', 'backup-backup'), 'STEP');
        $path = $file . '/*';
        foreach (glob($path) as $file_path) if (!is_dir($file_path)) @unlink($file_path);
        $this->migration->log(__('Elementor cache cleared!', 'backup-backup'), 'SUCCESS');
      }

    }

    public function finalCleanUP() {

      $this->migration->log(__('Cleaning temporary files...', 'backup-backup'), 'STEP');
      update_option('tastewp_auto_activated', true);
      $this->cleanup();
      $this->migration->log(__('Temporary files cleaned', 'backup-backup'), 'SUCCESS');

    }

    public function handleError($e) {

      // On this tragedy at least remove tmp files
      $this->migration->log(__('Something bad happened...', 'backup-backup'), 'ERROR');
      $this->migration->log($e->getMessage(), 'ERROR');
      $this->migration->log($e->getLine() . ' @ ' . $e->getFile(), 'ERROR');
      $this->cleanup();

    }

    public function makeTMPDirectory() {

      // Make temp dir
      $this->migration->log(__('Making temporary directory', 'backup-backup'), 'INFO');
      if (!(is_dir($this->tmp) || file_exists($this->tmp))) {
        mkdir($this->tmp, 0755, true);
      }

      // Deny read of this folder
      copy(BMI_INCLUDES . '/htaccess/.htaccess', $this->tmp . '/.htaccess');
      touch($this->tmp . '/index.html');
      touch($this->tmp . '/index.php');

    }

    private function makeRestoreSecret() {

      $this->migration->log(__('Making new secret key for current restore process.', 'backup-backup'), 'STEP');
      $secret = $this->randomString();
      file_put_contents(BMI_INCLUDES . '/htaccess/.restore_secret', $secret);
      $this->migration->log(__('Secret key generated, it will be returned to you (ping).', 'backup-backup'), 'SUCCESS');

      return $secret;

    }

    public function listBackupContents() {

      $manager = new ZipManager();

      $save = $this->scanFile;
      $amount = $manager->getZipContentList($this->src, $save);

      $this->migration->log(__('Scan found ', 'backup-backup') . $amount . __(' files inside the backup.', 'backup-backup'), 'INFO');

      return $amount;

    }

    public function extractTo($secret = null) {

      try {

        // Require Universal Zip Library
        require_once BMI_INCLUDES . '/zipper/src/zip.php';

        // Make restore secret
        if (!$this->isCLI && $this->batchStep == 0) {

          // Verbose
          Logger::log('Restoring site...');

          if ((gettype($secret) != 'string' || strlen($secret) != 64)) {

            $secret = $this->makeRestoreSecret();
            BMP::res(['status' => 'secret', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'step' => 0
            ]]);
            return;

          } else {

            // $this->migration->log(__('Secret key detected successfully (pong)!', 'backup-backup'), 'INFO');

          }

        }

        // STEP: 1
        if ($this->isCLI || $this->batchStep == 1) {

          if (!$this->isCLI) {

            $this->migration->log(__('Secret key detected successfully (pong)!', 'backup-backup'), 'INFO');

          }

          // Make temporary directory
          $this->makeTMPDirectory();

          // Time start
          $this->migration->log(__('Scanning archive...', 'backup-backup'), 'STEP');

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'step' => 1
            ]]);

            return;

          }

        }

        // STEP: 2
        if ($this->isCLI || $this->batchStep == 2) {

          // Get ZIP contents for batch unzipping
          $this->fileAmount = $this->listBackupContents();

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'step' => 2
            ]]);

            return;

          }

        }

        // STEP: 3
        if ($this->isCLI || $this->batchStep == 3) {

          // UnZIP the backup
          $unzipped = $this->makeUnZIP();
          if ($unzipped === false) {

            $this->handleError(__('File extraction process failed.', 'backup-backup'));
            return;

          }

          if (!$this->isCLI) {

            $shouldRepeat = false;
            if ($unzipped === 'repeat') {

              $shouldRepeat = true;

            } else {

              $shouldRepeat = false;

            }

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'recent_export_seek' => $this->recent_export_seek,
              'repeat_export' => $shouldRepeat,
              'firstExtract' => $this->firstExtract,
              'step' => 3
            ]]);

            return;

          }

        }

        // STEP: 4
        if ($this->isCLI || $this->batchStep == 4) {

          // WP Config backup
          $this->makeWPConfigCopy();

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'step' => 4
            ]]);

            return;

          }

        }

        // STEP: 5
        if ($this->isCLI || $this->batchStep == 5) {

          // Get manifest
          $manifest = $this->getCurrentManifest(true);

          // Restore files
          $this->restoreBackupFromFiles($manifest);

          // Restore WP-config if it's different site.
          if (untrailingslashit($manifest->dbdomain) != untrailingslashit($this->siteurl)) {

            // Restore WP Config if it's different domain
            $this->restoreOriginalWPConfig(false);

          }

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'step' => 5
            ]]);

            return;

          }

        }

        // STEP: 6
        if ($this->isCLI || $this->batchStep == 6) {

          // This literally does nothing.

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'step' => 6
            ]]);

            return;

          }

        }

        // STEP 7
        if ($this->isCLI || $this->batchStep == 7) {

          // Get manifest
          if (!isset($manifest)) {
            $manifest = $this->getCurrentManifest();
          }

          $wasDisabled = 0;
          if (!$this->splitting) {

            $this->migration->log(__('Splitting process is disabled in the settings, omitting.', 'backup-backup'), 'INFO');
            $wasDisabled = 1;

          } else {

            $newDataProcess = $this->processData;
            $dbFinishedConv = 'false';
            $db_tables = $this->tmp . DIRECTORY_SEPARATOR . 'db_tables';

            if (is_dir($db_tables)) {

              if (empty($this->processData)) {
                $this->migration->log(__('Converting database files into partial files.', 'backup-backup'), 'STEP');
                if (defined('BMI_DB_MAX_ROWS_PER_QUERY')) {
                  $this->migration->log(__('Max rows per query (this site): ', 'backup-backup') . BMI_DB_MAX_ROWS_PER_QUERY, 'INFO');
                }
              }

              $dbsort = new SmartDatabaseSort($db_tables, $this->migration, $this->isCLI);
              $process = $dbsort->sortUnsorted($this->processData);

              if (!is_null($process) && isset($process)) {
                $newDataProcess = $process;
              }

              if ($this->isCLI || (isset($process['convertionFinished']) && $process['convertionFinished'] == 'yes')) {
                $this->migration->log(__('Database convertion finished successfully.', 'backup-backup'), 'SUCCESS');

                $this->migration->log(__('Calculating new query size and counts.', 'backup-backup'), 'STEP');
                $stats = $dbsort->countAllFilesAndQueries();
                $this->migration->log(__('Calculaion completed, printing details.', 'backup-backup'), 'SUCCESS');

                $this->migration->log(__('Total queries to insert after conversion: ', 'backup-backup') . $stats['total_queries'], 'INFO');
                $this->migration->log(__('Partial files count after conversion: ', 'backup-backup') . sizeof($stats['all_files']), 'INFO');
                $this->migration->log(__('Total size of the database: ', 'backup-backup') . BMP::humanSize($stats['total_size']), 'INFO');
                $this->migration->log(__('Table count to be imported: ', 'backup-backup') . sizeof($stats['all_tables']), 'INFO');

                $this->conversionStats = $stats;

                $dbFinishedConv = 'true';
              }

            } else {

              if (file_exists($this->tmp . '/bmi_database_backup.sql')) {

                $this->migration->log(__('Ommiting database convert step as the database backup included was not made with V2 engine.', 'backup-backup'), 'WARN');
                $this->migration->log(__('The process may be less stable if the database is larger than usual.', 'backup-backup'), 'WARN');
                $dbFinishedConv = 'true';

              } else {

                $this->migration->log(__('Ommiting database convert step as there is no database backup included.', 'backup-backup'), 'INFO');
                $dbFinishedConv = 'true';

              }

            }

          }

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'dbConvertionFinished' => $dbFinishedConv,
              'processData' => $newDataProcess,
              'conversionStats' => $this->conversionStats,
              'step' => 7 + $wasDisabled
            ]]);

            return;

          }

        }

        // STEP: 8
        if ($this->isCLI || $this->batchStep == 8) {

          // Get manifest
          if (!isset($manifest)) {
            $manifest = $this->getCurrentManifest();
          }

          // Try to restore database
          if (!$this->isCLI) {

            $dbFinished = false;
            $database_exist = $this->restoreDatabaseDynamic($manifest);

            if ($database_exist === false || $database_exist === true) {

              $dbFinished = true;

            } else {

              if ($database_exist['status'] == 'new_file') {

                $this->continueFile = false;
                $this->continueSeek = false;

              } else {

                $this->continueFile = $database_exist['file'];
                $this->continueSeek = $database_exist['seek'];

              }

            }

          } else {

            $database_exist = $this->restoreDatabaseDynamic($manifest);

          }

          // Update TasteWP option
          update_option('tastewp_auto_activated', true);
          $this->databaseExist = $database_exist;

          if (!$this->isCLI) {

            BMP::res(['status' => 'restore_ongoing', 'tmp' => $this->tmptime, 'secret' => $secret, 'options' => [
              'code' => $this->code,
              'start' => $this->start,
              'amount' => $this->fileAmount,
              'databaseExist' => $database_exist === true ? 'true' : 'false',
              'continueFile' => $this->continueFile,
              'continueSeek' => $this->continueSeek,
              'dbFinished' => $dbFinished,
              'firstDB' => $this->firstDB,
              'db_xi' => $this->db_xi,
              'ini_start' => $this->ini_start,
              'table_names_alter' => $this->table_names_alter,
              'conversionStats' => $this->conversionStats,
              'step' => 8
            ]]);

            return;

          }

        }

        // STEP: 9
        if ($this->isCLI || $this->batchStep == 9) {

          // Rename database from temporary to destination
          // And do the rest
          // Step 9 runs only at the end of database import

          // Get manifest
          if (!isset($manifest)) {
            $manifest = $this->getCurrentManifest();
          }

          $database_exist = $this->databaseExist;

          // Restore WP Config ** It allows to recover session after restore no matter what
          if ($database_exist == true || $database_exist == 'true') {

            // Alter all tables
            $this->alter_tables($manifest);

            // Modify the WP Config and replace
            $this->replaceDbPrefixInWPConfig($manifest);

            // User is logged off at this point, try to log in
            $this->makeNewLoginSession($manifest);

          } else {

            // Restore WP Config without modifications
            $this->restoreOriginalWPConfig();

          }

          // Make sure the Xhria was not modified
          $this->setOrUpdateXhria();

          // Fix elementor templates
          $this->clearElementorCache();

          // Make final cleanup
          $this->finalCleanUP();

          // Touch autologin file
          $autologin_file = BMI_BACKUPS . '/.autologin';
          touch($autologin_file);

          // Final verbose
          $this->migration->log(__('Restore process took: ', 'backup-backup') . number_format(microtime(true) - $this->start, 2) . ' seconds.', 'INFO');
          Logger::log('Site restored...');

          // Return success
          return true;

        }

      } catch (\Exception $e) {

        // On this tragedy at least remove tmp files
        $this->handleError($e);
        return false;

      } catch (\Throwable $e) {

        // On this tragedy at least remove tmp files
        $this->handleError($e);
        return false;

      }

    }

  }
