<?php

namespace Onix;

class Runner
{
    private $allowed_methods = array(
        'run' => 'runAllTests',
        'list' => 'listTests'
    );

    private $config;

    private $test_dir;

    public function __construct()
    {
        $this->config = Config::getInstance()->get('global');
        $this->test_dir = ROOT_DIR.'/'.$this->config['test_dir'];
    }

    public function start($argv)
    {
        //$this->parseOptions(&$argv);
        if (isset($argv[1]) && array_key_exists($argv[1], $this->allowed_methods)) {
            if (method_exists($this, $this->allowed_methods[$argv[1]])) {
                call_user_func_array(
                    array($this, $this->allowed_methods[$argv[1]]),
                    array(isset($argv[2]) ? array_splice($argv, 2) : array())
                );
            } else {
                $this->usage();
                throw new Exception('Function allowed, but not present');
            }
        } else {
            $this->usage();
        }
    }

    private function parseOptions($argv)
    {

    }

    private function usage($msg = '')
    {
        echo "Usage: say something nice\n";
    }

    public function runAllTests($params)
    {
        /*foreach (glob(TEST_DIR.'/*.php') as $test_file) {
            if (is_readable($test_file)) {
                echo '>> '.Utility::getTestNameFromFile($test_file);
                $test_output = '';
                try {
                    ob_start();
                    include($test_file);
                    $test_output = ob_get_clean();
                    echo " (PASS)\n";
                } catch (TestFailedException $tfe) {
                    $test_output = ob_get_clean();
                    echo " (FAIL)\n";
                } catch (\Exception $e) {
                    echo $e->getMessage();
                    // Something bad happened
                }
                print_r($test_output);
            }
        }
        Logger::getInstance()->output();*/
        if ($handle = opendir($this->test_dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                } elseif (is_dir($this->test_dir.'/'.$entry)) {
                    echo "[[".$entry."]]\n";
                    Config::getInstance()->setContext($entry);
                    $this->parseDirectory($this->test_dir.'/'.$entry);
                }
            }
        }
    }

    public function runTest($context, $test_name)
    {
        Config::getInstance()->setContext($context);
        return $this->execute($this->test_dir.'/'.$test_name);
    }

    private function execute($test_file)
    {
        if (!is_readable($test_file)) {
            $return = array('Status' => 'N', 'ErrMsg' => 'Invalid test specified');
        } else {
            ob_start();
            try {
                include($test_file);
            } catch (TestFailedException $tfe) {
                // No need to do anything with this, the Logger will have recorded the error
            } catch (\Exception $e) {
                // Something bad happened
            }
            $test_output = ob_get_clean();
            $return = array('Status' => 'Y', 'Results' => Logger::getInstance()->getCurrentResults());
        }

        return $return;
    }

    public function listTests($params = array())
    {
        $tests = array();
        $tests = $this->parseDirectory($this->test_dir, false);

        return array('Status' => 'Y', 'Results' => $tests);
    }

    private function parseDirectory($directory, $run_tests = true)
    {
        $listing = array();

        if ($handle = opendir($directory)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                } elseif (is_dir($directory.'/'.$entry)) {
                    $listing[$entry] = $this->parseDirectory($directory.'/'.$entry, $run_tests);
                } elseif (preg_match('/([a-z0-9_\-]+)\.php$/i', $entry, $matches)) {
                    $listing[] = $matches[1];
                    if ($run_tests && is_readable($directory.'/'.$entry)) {
                        echo " >> ".Utility::getTestNameFromFile($directory.'/'.$entry).'... ';
                        $test_results = $this->execute($directory.'/'.$entry);
                        echo (!isset($test_results['result']) || $test_results['result'] == 'fail' ? 'PASS' : 'FAIL')."\n";
                    }
                }
            }
            closedir($handle);
        }

        return $listing;
    }
}
