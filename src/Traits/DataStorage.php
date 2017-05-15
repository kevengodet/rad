<?php

namespace Adagio\Rad\Traits;

trait DataStorage
{

    /**
     * Read data from the PHP file.
     *
     * @param string $filePath
     * @param mixed $default Default value to returned if $filePath does not exist.
     *
     * @return mixed
     */
    private function readData($filePath, $default = null)
    {
        if (!file_exists($filePath)) {
            return $default;
        }

        return include $filePath;
    }

    /**
     *
     * @param mixed $data
     * @param string $filePath
     */
    private function writeData($data, $filePath)
    {
        file_put_contents($filePath, '<?php return '.var_export($data, true).';', LOCK_EX);
    }
}
