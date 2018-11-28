<?php

namespace App\Core\Helpers\Zip;

/**
 * Class ZipFileDecompress
 */
class ZipFileDecompress
{

    /**
     * Extract files.
     *
     * @param string $content
     * @param callable|null $filter
     * @return array
     */
    public function decompress($content, callable $filter = null)
    {
        $temp = tempnam(sys_get_temp_dir(),time() . '.zip');
        file_put_contents($temp, $content);
        $zip = new \ZipArchive();
        $output = [];
        if ($zip->open($temp) === true && $zip->numFiles > 0) {
            $output = iterator_to_array($this->getFiles($zip, $filter));
        }
        $zip->close();
        unlink($temp);

        return $output;
    }

    private function getFiles(\ZipArchive $zip, $filter)
    {
        $total = $zip->numFiles;
        for ($i = 0; $i < $total; $i++) {
            $name = $zip->getNameIndex($i);
            if (false === $name) {
                continue;
            }

            if (!$filter || $filter($name)) {
                yield [
                    'filename' => $name,
                    'content'  => $zip->getFromIndex($i)
                ];
            }
        }
    }

    public function extractResponse($zipContent)
    {
        $xml = $this->getXmlResponse($zipContent);

        return $xml;
    }

    private function getXmlResponse($content)
    {
        $filter = function ($filename) {
            return strtolower($this->getFileExtension($filename)) === 'xml';
        };
        $files = $this->decompress($content, $filter);

        return count($files) === 0 ? '' : $files[0]['content'];
    }

    private function getFileExtension($filename)
    {
        $lastDotPos = strrpos($filename, '.');
        if (!$lastDotPos) {
            return '';
        }

        return substr($filename, $lastDotPos + 1);
    }
}