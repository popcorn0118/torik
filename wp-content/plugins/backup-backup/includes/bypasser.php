<?php

  // Namespace
  namespace BMI\Plugin\Heart;

  // Usage
  use BMI\Plugin\BMI_Logger AS Logger;
  use BMI\Plugin\Progress\BMI_ZipProgress AS Output;

  // Exit on direct access
  if (!(defined('BMI_CURL_REQUEST') || defined('ABSPATH'))) exit;

  // Fixes for some cases
  require_once BMI_INCLUDES . '/compatibility.php';

  /**
   * Main class to handle heartbeat of the backup
   */
  class BMI_Backup_Heart {

    // Prepare the request details
    function __construct($curl = false, $config = false, $content = false, $backups = false, $abs = false, $dir = false, $url = false, $remote_settings = [], $it = 0) {

      $this->it = intval($it);
      $this->abs = $abs;
      $this->dir = $dir;
      $this->url = $url;
      $this->curl = $curl;
      $this->config = $config;
      $this->content = $content;
      $this->backups = $backups;

      $this->identy = $remote_settings['identy'];
      $this->manifest = $remote_settings['manifest'];
      $this->backupname = $remote_settings['backupname'];
      $this->safelimit = intval($remote_settings['safelimit']);
      $this->total_files = $remote_settings['total_files'];
      $this->rev = intval($remote_settings['rev']);
      $this->backupstart = $remote_settings['start'];
      $this->filessofar = intval($remote_settings['filessofar']);
      $this->identyfile = BMI_INCLUDES . '/htaccess' . '/.' . $this->identy;
      $this->browserSide = ($remote_settings['browser'] === true || $remote_settings['browser'] === 'true') ? true : false;

      $this->identyFolder = BMI_INCLUDES . '/htaccess/bg-' . $this->identy;
      $this->fileList = BMI_INCLUDES . '/htaccess/files_latest.list';
      $this->dbfile = BMI_INCLUDES . '/htaccess/bmi_database_backup.sql';
      $this->db_dir_v2 = BMI_INCLUDES . '/htaccess/db_tables';
      $this->db_v2_engine = false;

      $this->headersSet = false;
      $this->final_made = false;
      $this->final_batch = false;

      $this->lock_cli = BMI_BACKUPS . '/.backup_cli_lock';
      if ($this->it > 1) @touch($this->lock_cli);

    }

    // Human size from bytes
    public static function humanSize($bytes) {
      $label = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
      for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);

      return (round($bytes, 2) . " " . $label[$i]);
    }

    // Create new process
    public function send_beat($manual = false, &$logger = null) {

      try {

        $header = array(
          // 'Content-Type:Application/x-www-form-urlencoded',
          'Content-Accept:*/*',
          'Access-Control-Allow-Origin:*',
          'Content-ConfigDir:' . $this->config,
          'Content-Content:' . $this->content,
          'Content-Backups:' . $this->backups,
          'Content-Identy:' . $this->identy,
          'Content-Url:' . $this->url,
          'Content-Abs:' . $this->abs,
          'Content-Dir:' . $this->dir,
          'Content-Manifest:' . $this->manifest,
          'Content-Name:' . $this->backupname,
          'Content-Safelimit:' . $this->safelimit,
          'Content-Start:' . $this->backupstart,
          'Content-Filessofar:' . $this->filessofar,
          'Content-Total:' . $this->total_files,
          'Content-Rev:' . $this->rev,
          'Content-It:' . $this->it,
          'Content-Browser:' . $this->browserSide ? 'true' : 'false'
        );

        // if (!defined('CURL_HTTP_VERSION_2_0')) {
        //   define('CURL_HTTP_VERSION_2_0', CURL_HTTP_VERSION_1_0);
        // }

        // $ckfile = tempnam(BMI_INCLUDES . DIRECTORY_SEPARATOR . 'htaccess', "CURLCOOKIE");
        $c = curl_init();
             curl_setopt($c, CURLOPT_POST, 1);
             curl_setopt($c, CURLOPT_TIMEOUT, 10);
             // curl_setopt($c, CURLOPT_NOBODY, true);
             curl_setopt($c, CURLOPT_VERBOSE, false);
             curl_setopt($c, CURLOPT_HEADER, false);
             // curl_setopt($c, CURLOPT_COOKIEJAR, $ckfile);
             curl_setopt($c, CURLOPT_URL, $this->url);
             curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
             curl_setopt($c, CURLOPT_MAXREDIRS, 10);
             curl_setopt($c, CURLOPT_COOKIESESSION, true);
             // curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 1);
             curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
             curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
             curl_setopt($c, CURLOPT_HTTPHEADER, $header);
             curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'POST');
             curl_setopt($c, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
             // curl_setopt($c, CURLOPT_USERAGENT, 'BMI_HEART_TIMEOUT_BYPASS_' . $this->it);
             curl_setopt($c, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $r = curl_exec($c);

        if ($manual === true && $logger !== null) {
          if ($r === false) {
            if (intval(curl_errno($c)) !== 28) {
              Logger::error(print_r(curl_getinfo($c), true));
              Logger::error(curl_errno($c) . ': ' . curl_error($c));
              $logger->log('There was something wrong with the request:', 'WARN');
              $logger->log(curl_errno($c) . ': ' . curl_error($c), 'WARN');
            }
          } else {
            $logger->log('Request sent successfully, without error returned.', 'SUCCESS');
          }
        }

        curl_close($c);
        // if (file_exists($ckfile)) @unlink($ckfile);
        if (isset($this->output)) $this->output->end();

      } catch (Exception $e) {

        error_log($e->getMessage());
        if (isset($this->output)) $this->output->end();

      } catch (Throwable $e) {

        error_log($e->getMessage());
        if (isset($this->output)) $this->output->end();

      }

    }

    // Load backup logger
    public function load_logger() {

      require_once BMI_INCLUDES . '/logger.php';
      require_once BMI_INCLUDES . '/progress/logger-only.php';

      $this->output = new Output();
      $this->output->start();

    }

    // Remove common files
    public function remove_commons() {

      // Remove list if exists
      $identyfile = $this->identyfile;
      $logfile = BMI_INCLUDES . '/htaccess/bmi_logs_this_backup.log';
      $clidata = BMI_INCLUDES . '/htaccess/bmi_cli_data.json';
      if (file_exists($this->fileList)) @unlink($this->fileList);
      if (file_exists($this->dbfile)) @unlink($this->dbfile);
      if (file_exists($this->manifest)) @unlink($this->manifest);
      if (file_exists($logfile)) @unlink($logfile);
      if (file_exists($clidata)) @unlink($clidata);
      if (file_exists($identyfile)) @unlink($identyfile);
      if (file_exists($identyfile . '-running')) @unlink($identyfile . '-running');

      // Remove backup
      if (file_exists(BMI_BACKUPS . '/.running')) @unlink(BMI_BACKUPS . '/.running');
      if (file_exists(BMI_BACKUPS . '/.abort')) @unlink(BMI_BACKUPS . '/.abort');

      // Remove group folder
      if (file_exists($this->identyFolder)) {
        $files = glob($this->identyFolder . '/*');
        foreach ($files as $file) if (is_file($file)) unlink($file);
        @rmdir($this->identyFolder);
      }

      // Remove tmp database files
      if (file_exists($this->db_dir_v2) && is_dir($this->db_dir_v2)) {
        $files = glob($this->db_dir_v2 . '/*');
        foreach ($files as $file) if (is_file($file)) unlink($file);
        if (is_dir($this->db_dir_v2)) @rmdir($this->db_dir_v2);
      }

      // Remove cookie files
      if (file_exists($this->dir . '/tmp')) {
        $files = glob($this->dir . '/tmp/*');
        foreach ($files as $file) if (is_file($file)) unlink($file);
      }

      // Remove temporary files
      $files = glob(BMI_INCLUDES . '/htaccess/CURLCOOKIE*');
      foreach ($files as $file) if (is_file($file)) @unlink($file);

    }

    // Make success
    public function send_success() {

      // Set header for browser
      if ($this->browserSide && $this->headersSet === false) {

        // Content finished
        header('Content-Finished: true');
        header('Content-It: ' . ($this->it + 1));
        header('Content-Filessofar: ' . $this->filessofar);
        http_response_code(200);
        $this->headersSet = true;

      }

      // Display the success
      $this->output->log('Backup completed successfully!', 'SUCCESS');
      $this->output->log('#001', 'END-CODE');

      // Remove common files
      $this->remove_commons();

      // End logger
      if (isset($this->output)) @$this->output->end();

      // End the process
      exit;

    }

    // Make error
    public function send_error($reason = false, $abort = false) {

      // Set header for browser
      if ($this->browserSide && $this->headersSet === false) {

        // Content finished
        header('Content-Finished: false');
        header('Content-It: ' . ($this->it + 1));
        header('Content-Filessofar: ' . $this->filessofar);
        http_response_code(200);
        $this->headersSet = true;

      }

      // Log error
      $this->output->log('Something went wrong with background process... ' . '(part: ' . $this->it . ')', 'ERROR');
      if ($reason !== false) $this->output->log('Reason: ' . $reason, 'ERROR');
      $this->output->log('Removing backup files... ', 'ERROR');

      // Remove common files
      $this->remove_commons();

      // Remove backup
      if (file_exists(BMI_BACKUPS . '/' . $this->backupname)) @unlink(BMI_BACKUPS . '/' . $this->backupname);

      // Abort step
      $this->output->log('Aborting backup... ', 'STEP');
      if ($abort === false) $this->output->log('#002', 'END-CODE');
      else $this->output->log('#003', 'END-CODE');
      if (isset($this->output)) @$this->output->end();
      exit;

    }

    // Group files for batches
    public function make_file_groups() {

      $this->output->log('Making batches for each process...', 'STEP');
      $list_path = $this->fileList;

      $file = fopen($list_path, 'r');
      $this->output->log('Reading list file...', 'INFO');
      $first_line = explode('_', fgets($file));
      $files = intval($first_line[0]);
      $firstmax = intval($first_line[1]);

      if ($files > 0) {
        $batches = 100;
        if ($files <= 200) $batches = 100;
        if ($files > 200) $batches = 200;
        if ($files > 1600) $batches = 400;
        if ($files > 3200) $batches = 800;
        if ($files > 6400) $batches = 1600;
        if ($files > 12800) $batches = 3200;
        if ($files > 25600) $batches = 5000;
        if ($files > 30500) $batches = 10000;
        if ($files > 60500) $batches = 20000;
        if ($files > 90500) $batches = 40000;

        $this->output->log('Each batch will contain up to ' . $batches . ' files.', 'INFO');
        $this->output->log('Large files takes more time, you will be notified about those.', 'INFO');

        $folder = $this->identyFolder;
        mkdir($folder, 0755, true);

        $limitcrl = 96;
        if (BMI_CLI_REQUEST === true) {
          $limitcrl = 512;
          if ($files > 30000) $limitcrl = 1024;
        }

        $i = 0; $bigs = 0; $prev = 0; $currsize = 0;
        while (($line = fgets($file)) !== false) {

          $line = explode(',', $line);
          $last = sizeof($line) - 1;
          $size = intval($line[$last]);
          unset($line[$last]);
          $line = implode(',', $line);

          $i++;
          if ($firstmax != -1 && $i > $firstmax) $bigs++;
          $suffix = intval(ceil(abs($i / $batches))) + $bigs;

          if ($prev == $suffix) {
            $currsize += $size;
          } else {
            $currsize = $size;
            $prev = $suffix;
          }

          $skip = false;
          if ($currsize > ($limitcrl * (1024 * 1024))) $skip = true;

          $groupFile = $folder . '/' . $this->identy . '-' . $suffix . '.files';
          $group = fopen($groupFile, 'a');
                   fwrite($group, $line . ',' . $size . "\r\n");
                   fclose($group);

          if ($skip === true) $bigs++;
          unset($line);

        }

        fclose($file);
        sleep(2);
        if (file_exists($this->fileList)) @unlink($this->fileList);

      } else {

        $this->output->log('No file found to be backed up, omitting files.', 'INFO');

      }

      if (file_exists($this->fileList)) @unlink($this->fileList);
      $this->output->log('Batches completed...', 'SUCCESS');

    }

    // Final batch
    public function get_final_batch() {

      $db_root_dir = BMI_INCLUDES . '/htaccess' . '/';
      $logs = $db_root_dir . 'bmi_logs_this_backup.log';

      $log_file = fopen($logs, 'w');
                  fwrite($log_file, file_get_contents(BMI_BACKUPS . DIRECTORY_SEPARATOR . 'latest.log'));
                  fclose($log_file);

      $files = [$logs, $this->manifest];
      if (file_exists($this->dbfile)) {
        $files[] = $this->dbfile;
      } elseif (file_exists($this->db_dir_v2) && is_dir($this->db_dir_v2)) {
        $this->db_v2_engine = true;
        $db_files = scandir($this->db_dir_v2);
        foreach ($db_files as $i => $name) {
          if (!($name == '.' || $name == '..')) {
            $files[] = $this->db_dir_v2 . '/' . $name;
          }
        }
      }

      return $files;

    }

    // Final logs
    public function log_final_batch() {

      $this->output->log('Finalizing backup', 'STEP');
      $this->output->log('Closing files and archives', 'STEP');
      $this->output->log('Archiving of ' . $this->total_files . ' files took: ' . number_format(microtime(true) - floatval($this->backupstart), 2) . 's', 'INFO');
      $this->output->log('#001', 'END-CODE');

      if (!BMI_CLI_REQUEST) {
        if (!$this->browserSide) sleep(1);
      }

      if (file_exists(BMI_BACKUPS . '/.abort')) {
        $this->send_error('Backup aborted manually by user.', true);
        return;
      }

      $this->send_success();

    }

    // Load batch
    public function load_batch() {

      $allFiles = scandir($this->identyFolder);
      $files = array_slice($allFiles, 2);
      if (sizeof($files) > 0) {

        $largest = $files[0]; $prev_size = 0;
        for ($i = 0; $i < sizeof($files); ++$i) {
          $curr_size = filesize($this->identyFolder . '/' . $files[$i]);
          if ($curr_size > $prev_size) {
            $largest = $files[$i];
            $prev_size = $curr_size;
          }
        }
        $this->batches_left = sizeof($files);

        if (sizeof($files) == 1) {
          $this->final_batch = true;
        }

        return $this->identyFolder . '/' . $largest;

      } else {

        $this->log_final_batch();
        return false;

      }

    }

    // Cut Path for ZIP structure
    public function cutDir($file) {

      if (substr($file, -4) === '.sql') {

        if ($this->db_v2_engine == true) {

          return 'db_tables' . DIRECTORY_SEPARATOR . basename($file);

        } else {

          return basename($file);

        }

      } else {

        return basename($file);

      }

    }

    // Add files to ZIP – The Backup
    public function add_files($files = [], $file_list = false, $final = false) {

      try {

        if (class_exists('\ZipArchive') || class_exists('ZipArchive')) {

          // Initialize Zip
          if (!isset($this->_zip)) {
            $this->_zip = new \ZipArchive();
          }

          if ($this->_zip) {

            // Show what's in use
            if ($this->it === 1) {
              $this->output->log('Using ZipArchive module to create the Archive.', 'INFO');
            }

            // Open / create ZIP file
            $back = BMI_BACKUPS . DIRECTORY_SEPARATOR . $this->backupname;
            if (BMI_CLI_REQUEST) {
              if (!isset($this->zip_initialized)) {
                $this->_zip->open($back, \ZipArchive::CREATE);
              }
            } else {
              if (file_exists($back)) $this->_zip->open($back);
              else $this->_zip->open($back, \ZipArchive::CREATE);
            }

            // Final operation
            if ($final) {

              // Add files
              for ($i = 0; $i < sizeof($files); ++$i) {

                // Add the file
                $this->_zip->addFile($files[$i], $this->cutDir($files[$i]));
                $this->final_made = true;

              }

            } else {

              // Add files
              for ($i = 0; $i < sizeof($files); ++$i) {

                // Calculate Path in ZIP
                $path = 'wordpress' . DIRECTORY_SEPARATOR . substr($files[$i], strlen(ABSPATH));

                // Add the file
                $this->_zip->addFile($files[$i], $path);

              }

            }

            // Close archive and prepare next batch
            touch(BMI_BACKUPS . '/.running');
            if (!BMI_CLI_REQUEST || $final) {
              $result = $this->_zip->close();

              if ($result === true) {

                // Remove batch
                if ($file_list && file_exists($file_list)) {
                  @unlink($file_list);
                }

              } else {

                return false;

              }
            } else {

              // Remove batch
              if ($file_list && file_exists($file_list)) {
                @unlink($file_list);
              }

            }

          } else {
            $this->send_error('ZipArchive error, please contact support - your site may be special case.');
          }

        } else {

          // Check if PclZip exists
          if (!class_exists('PclZip')) {
            if (!defined('PCLZIP_TEMPORARY_DIR')) {
              $bmi_tmp_dir = BMI_ROOT_DIR . '/tmp';
              if (!file_exists($bmi_tmp_dir)) {
                @mkdir($bmi_tmp_dir, 0775, true);
              }

              define('PCLZIP_TEMPORARY_DIR', $bmi_tmp_dir . '/bmi-');
            }
          }

          // Require the LIB and check if it's compatible
          $alternative = dirname($this->dir) . '/backup-backup-pro/includes/pcl.php';
          if ($this->rev === 1 || !file_exists($alternative)) {
            require_once ABSPATH . 'wp-admin/includes/class-pclzip.php';
          } else {
            require_once $alternative;
            if ($this->it === 1) {
              $this->output->log('Using dedicated PclZIP for Pro', 'INFO');
            }
          }

          // Get/Create the Archive
          if (!isset($this->_lib)) {
            $this->_lib = new \PclZip(BMI_BACKUPS . DIRECTORY_SEPARATOR . $this->backupname);
          }

          if (!$this->_lib) {
            $this->send_error('PHP-ZIP: Permission Denied or zlib cannot be found');
            return;
          }

          if (sizeof($files) <= 0) {
            return false;
          }

          // Add files
          if ($final) {

            // Final configuration
            $back = $this->_lib->add($files, PCLZIP_OPT_REMOVE_PATH, BMI_INCLUDES . '/htaccess' . '/', PCLZIP_OPT_TEMP_FILE_THRESHOLD, $this->safelimit);
            $this->final_made = true;

          } else {

            // Additional path
            $add_path = 'wordpress' . DIRECTORY_SEPARATOR;

            // Casual configuration
            $back = $this->_lib->add($files, PCLZIP_OPT_REMOVE_PATH, ABSPATH, PCLZIP_OPT_ADD_PATH, $add_path, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $this->safelimit);

          }

          // Check if there was any error
          touch(BMI_BACKUPS . '/.running');
          if ($back == 0) {

            $this->send_error($this->_lib->errorInfo(true));
            return false;

          } else {

            if ($file_list && file_exists($file_list)) {
              @unlink($file_list);
            }

          }

        }

      } catch (\Exception $e) {

        $this->send_error($e->getMessage());
        return false;

      } catch (\Throwable $e) {

        $this->send_error($e->getMessage());
        return false;

      }

    }

    // ZIP one of the grouped files
    public function zip_batch() {

      $list_file = $this->load_batch();
      if ($list_file === false) return true;
      $files = explode("\r\n", file_get_contents($list_file));

      $total_size = 0;
      $parsed_files = [];

      for ($i = 0; $i < sizeof($files); ++$i) {
        if (strlen(trim($files[$i])) <= 1) {
          $this->total_files--;
          continue;
        }

        $files[$i] = explode(',', $files[$i]);
        $last = sizeof($files[$i]) - 1;
        $size = intval($files[$i][$last]);
        unset($files[$i][$last]);
        $files[$i] = implode(',', $files[$i]);

        $file = null;
        if ($files[$i][0] . $files[$i][1] . $files[$i][2] === '@1@') {
          $file = WP_CONTENT_DIR . '/' . substr($files[$i], 3);
        } else if ($files[$i][0] . $files[$i][1] . $files[$i][2] === '@2@') {
          $file = ABSPATH . '/' . substr($files[$i], 3);
        } else {
          $file = $files[$i];
        }

        if (!file_exists($file)) {
          $this->output->log('Removing this file from backup (it does not exist anymore): ' . $file, 'WARN');
          $this->total_files--;
          continue;
        }

        if (filesize($file) === 0) {
          $this->output->log('Removing this file from backup (file size is equal to 0 bytes): ' . $file, 'WARN');
          $this->total_files--;
          continue;
        }

        $parsed_files[] = $file;
        $total_size += $size;
        unset($file);
      }

      unset($files);
      if (sizeof($parsed_files) === 1) {
        $this->output->log('Adding: ' . sizeof($parsed_files) . ' file...' . ' [Size: ' . $this->humanSize($total_size) . ']', 'INFO');
        $this->output->log('Alone-file mode for: ' . $parsed_files[0] . ' file...', 'INFO');
      } else $this->output->log('Adding: ' . sizeof($parsed_files) . ' files...' . ' [Size: ' . $this->humanSize($total_size) . ']', 'INFO');

      if ((60 * (1024 * 1024)) < $total_size) $this->output->log('Current batch is quite large, it may take some time...', 'WARN');

      $this->add_files($parsed_files, $list_file);
      $this->filessofar += sizeof($parsed_files);

      $this->output->progress($this->filessofar . '/' . $this->total_files);
      $this->output->log('Milestone: ' . $this->filessofar . '/' . $this->total_files . ' [' . $this->batches_left . ' batches left]', 'SUCCESS');

      if ($this->final_batch === true) {
        $this->output->log('Adding final files to this batch...', 'STEP');
        $this->output->log('Adding manifest as addition...', 'INFO');

        $additionalFiles = $this->get_final_batch();
        $this->add_files($additionalFiles, false, true);
        $this->log_final_batch();
        return true;
      }

    }

    // Shutdown callback
    public function shutdown() {

      // Check if there was any error
      $err = error_get_last();
      if ($err != null) {
        Logger::error('Shuted down');
        Logger::error(print_r($err, true));
        $this->output->log('Background process had some issues, more details printed to global logs.', 'WARN');
      }

      // Remove lock
      if (file_exists($this->lock_cli)) {
        @unlink($this->lock_cli);
      }

      // Send next beat to handle next batch
      if (BMI_CLI_REQUEST) return;
      if (file_exists($this->identyfile)) {

        $this->it += 1;

        // Set header for browser
        if ($this->browserSide && $this->headersSet === false) {

          // Content finished
          header('Content-Finished: false');
          header('Content-It: ' . $this->it);
          header('Content-Filessofar: ' . $this->filessofar);
          http_response_code(200);
          $this->headersSet = true;

        } else {

          usleep(100);
          $this->send_beat();

        }

      }

    }

    // Handle received batch
    public function handle_batch() {

      // Check if aborted
      if (file_exists(BMI_BACKUPS . '/.abort')) {
        if (!isset($this->output)) $this->load_logger();
        $this->send_error('Backup aborted manually by user.', true);
        return;
      }

      // Handle cURL
      if ($this->curl == true) {

        // Check if it was triggered by verified user
        if (!file_exists($this->identyfile)) {
          return;
        }

        // Register shutdown
        register_shutdown_function([$this, 'shutdown']);

        // Load logger
        $this->load_logger();

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
          Logger::error('Bypasser error:');
          Logger::error($errno . ' - ' . $errstr);
          Logger::error($errfile . ' - ' . $errline);
        });

        // Notice parent script
        touch($this->identyfile . '-running');
        touch(BMI_BACKUPS . '/.running');

        // CLI case
        if (BMI_CLI_REQUEST) {

          // Log
          $this->output->log("PHP CLI initialized - process ran successfully", 'SUCCESS');
          $this->make_file_groups();

          // Make ZIP
          $this->output->log('Making archive...', 'STEP');
          while (!$this->final_made) {
            touch($this->identyfile . '-running');
            touch(BMI_BACKUPS . '/.running');
            $this->it++;
            $this->zip_batch();
          }

        } else {

          // Background
          if ($this->it === 0) {

            $this->output->log('Background process initialized', 'SUCCESS');
            $this->make_file_groups();
            $this->output->log('Making archive...', 'STEP');

          } else $this->zip_batch();

        }

      }

    }

  }
