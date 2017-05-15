<?php

namespace Adagio\Rad\Traits;

trait GetDataDir
{
    /**
     * Return the best directory to hold Adagio RAD data (creates it if required)
     *
     * @return string
     */
    private function getDataDir()
    {
        if (!$home = getenv('HOME')) {
            $dataDir = sys_get_temp_dir().'/adagio/rad/data/';
        } else {
            $dataDir = rtrim(strtr($home, '\\', '/'), '/').'/.adagio/rad/data/';
        }

        if (!file_exists($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        return $dataDir;
    }
}
