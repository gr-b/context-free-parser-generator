<?php

namespace ParserGenerator;

class Filesystem
{
    public function readDirectory($directoryPath)
    {
        @$directoryHandle = opendir($directoryPath);

        if (!is_resource($directoryHandle)) {
            return null;
        }

        $files = array();

        while (false !== ($childName = readdir($directoryHandle))) {
            if (($childName === '.') || ($childName === '..')) {
                continue;
            }

            $childPath = "{$directoryPath}/{$childName}";

            if (is_dir($childPath)) {
                $value = $this->readDirectory($childPath);
            } else {
                $value = $childName;
            }

            $files[] = $value;
        }

        closedir($directoryHandle);

        return $files;
    }

    public function delete($path)
    {
        if (is_dir($path)) {
            $children = $this->readDirectory($path);

            foreach ($children as $childName) {
                $childPath = "{$path}/{$childName}";

                $this->delete($childPath);
            }

            echo "rmdir({$path})\n";
        } else {
            echo "unlink({$path});\n";
        }
    }

    public static function read($path)
    {
        $contents = @file_get_contents($path);

        if (!is_string($contents)) {
            return null;
        }

        return $contents;
    }

    public static function write($path, $data)
    {
        $directory = dirname($path);

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        $lengthWritten = file_put_contents($path, $data);

        return $lengthWritten === strlen($data);
    }
}
