<?php

namespace Onix;

class Config
{
    private static $instance;

    private $config_dir;

    private $config_data;

    private function __construct($config_dir = null)
    {
        if (!$config_dir) {
            $config_dir = ROOT_DIR.'/etc';
        }

        if (!is_readable($config_dir)) {
            throw new \Exception('Unable to read from config directory: '.$config_dir);
        }

        $this->config_dir = $config_dir;

        $this->loadConfigs();
    }

    public static function getInstance($refresh = false)
    {
        if ($refresh || !self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function loadConfigs()
    {
        foreach (glob($this->config_dir.'/*.json') as $config_file) {
            $raw_contents = file_get_contents($config_file);
            $data = json_decode($raw_contents, true);
            if (!is_array($data) || empty($data)) {
                throw new \Exception('Unable to load config file: '.$config_file);
            }
            $this->config_data[preg_replace('/\.json$/', '', basename($config_file))] = $data;
        }
    }

    public function get($name)
    {
        return isset($this->config_data[$name]) ? $this->config_data[$name] : null;
    }
}
