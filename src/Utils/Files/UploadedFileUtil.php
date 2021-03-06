<?php

namespace Fastwf\Api\Utils\Files;

use Fastwf\Api\Model\UploadedFile;

/**
 * Wrapper for uploaded file sent by POST request.
 * 
 * This wrapper is a utility class that allows to acess to file information and help to handle them.
 */
class UploadedFileUtil {

    /**
     * Extract from the files array parameter the file info mapped to UploadedFile instance.
     * 
     * The next form upload produce the next $_FILES super global array :
     * 
     * ```html
     * <form enctype="multipart/form-data">
     *   <input type="file" name="main">
     *   <input type="file" name="secondary[index]">
     *   <input type="file" name="secondary[appendix]">
     *   <input type="file" name="others[]">
     *   <input type="file" name="others[]">
     *   <input type="submit">
     * </form>
     * ```
     * 
     * ```php
     * [
     *   "main" => [
     *     "name" => "main.png",
     *     "type" => "image/png",
     *     "tmp_name" => "/tmp/phpWW5yhj",
     *     "error" => 0,
     *     "size" => 3652
     *   ],
     *   "secondary" => [
     *     "name" => [
     *       "index" => "index.svg",
     *       "appendix" => "appendix.jpg"
     *     ],
     *     "type" => [
     *       "index" => "image/svg+xml",
     *       "appendix" => "image/jpeg"
     *     ],
     *     "tmp_name" => [
     *       "index" => "/tmp/phpHwoHYj",
     *       "appendix" => "/tmp/phpvIMFHj"
     *     ],
     *     "error" => [
     *       "index" => 0,
     *       "appendix" => 0
     *     ],
     *     "size" => [
     *       "index" => 4724,
     *       "appendix" => 283926
     *     ]
     *   ],
     *   "others" => [
     *     "name" => [
     *       0 => "otherA.jpg",
     *       1 => "otherB.jpg"
     *     ],
     *     "type" => [
     *       0 => "image/jpeg",
     *       1 => "image/jpeg"
     *     ],
     *     "tmp_name" => [
     *       0 => "/tmp/php6I9Xil",
     *       1 => "/tmp/phppftaJj"
     *     ],
     *     "error" => [
     *       0 => 0,
     *       1 => 0
     *     ],
     *     "size" => [
     *       0 => 283926,
     *       1 => 283926
     *     ]
     *   ]
     * ]
     * ```
     *
     * This function transform the incoming $_FILES to :
     * ```txt
     * Array
     * (
     *     [main] => Fastwf\Api\Model\UploadedFile Object
     *         (
     *             [clientFilename:Fastwf\Api\Model\UploadedFile:private] => main.png
     *             [clientMediaType:Fastwf\Api\Model\UploadedFile:private] => image/png
     *             [size:Fastwf\Api\Model\UploadedFile:private] => 3652
     *             [path:Fastwf\Api\Model\UploadedFile:private] => /tmp/phpWW5yhj
     *             [error:Fastwf\Api\Model\UploadedFile:private] => 0
     *         )
     *     [secondary] => Array
     *         (
     *             [index] => Fastwf\Api\Model\UploadedFile Object
     *                 (
     *                     [clientFilename:Fastwf\Api\Model\UploadedFile:private] => index.svg
     *                     [clientMediaType:Fastwf\Api\Model\UploadedFile:private] => image/svg+xml
     *                     [size:Fastwf\Api\Model\UploadedFile:private] => 4724
     *                     [path:Fastwf\Api\Model\UploadedFile:private] => /tmp/phpHwoHYj
     *                     [error:Fastwf\Api\Model\UploadedFile:private] => 0
     *                 )
     *             [appendix] => Fastwf\Api\Model\UploadedFile Object
     *                 (
     *                     [clientFilename:Fastwf\Api\Model\UploadedFile:private] => appendix.jpg
     *                     [clientMediaType:Fastwf\Api\Model\UploadedFile:private] => image/jpeg
     *                     [size:Fastwf\Api\Model\UploadedFile:private] => 283926
     *                     [path:Fastwf\Api\Model\UploadedFile:private] => /tmp/phpvIMFHj
     *                     [error:Fastwf\Api\Model\UploadedFile:private] => 0
     *                 )
     *         )
     *     [others] => Array
     *         (
     *             [0] => Fastwf\Api\Model\UploadedFile Object
     *                 (
     *                     [clientFilename:Fastwf\Api\Model\UploadedFile:private] => otherA.jpg
     *                     [clientMediaType:Fastwf\Api\Model\UploadedFile:private] => image/jpeg
     *                     [size:Fastwf\Api\Model\UploadedFile:private] => 283926
     *                     [path:Fastwf\Api\Model\UploadedFile:private] => /tmp/php6I9Xil
     *                     [error:Fastwf\Api\Model\UploadedFile:private] => 0
     *                 )
     *             [1] => Fastwf\Api\Model\UploadedFile Object
     *                 (
     *                     [clientFilename:Fastwf\Api\Model\UploadedFile:private] => otherB.jpg
     *                     [clientMediaType:Fastwf\Api\Model\UploadedFile:private] => image/jpeg
     *                     [size:Fastwf\Api\Model\UploadedFile:private] => 283926
     *                     [path:Fastwf\Api\Model\UploadedFile:private] => /tmp/phppftaJj
     *                     [error:Fastwf\Api\Model\UploadedFile:private] => 0
     *                 )
     *         )
     * )
     * ```
     * 
     * @param array $files the array extracted from POST request
     * @return array the array containing uploaded file info
     */
    public static function fromSuperGlobal($files) {
        $array = [];

        foreach ($files as $name => $file) {
            if (\is_array($file["name"])) {
                $array[$name] = self::extractFiles($file);
            } else {
                $array[$name] = new UploadedFile($file);
            }
        }

        return $array;
    }

    /**
     * Extract file info from the multi file array an return an array of UploadedFile instance.
     *
     * @param array $multiFile the array of field containing dispersed file info
     * @return array the array with field => UploadedFile
     */
    private static function extractFiles($multiFile) {
        $files = [];

        foreach ($multiFile as $key => $info) {
            foreach ($info as $fileName => $value) {
                self::getSafe($files, $fileName)[$key] = $value;
            }
        }

        return \array_map(function ($file) { return new UploadedFile($file); }, $files);
    }

    /**
     * Return the array associated to the key from the array in parameter.
     * 
     * When the key not exists, the sub array is added to the array in parameter and the sub array is returned.
     *
     * @param array $array the array where the value associated to key parameter must be extracted.
     * @param string $key the key to find in the array
     * @return array the array value associated to key in array parameter
     */
    private static function &getSafe(&$array, $key) {
        if (!\array_key_exists($key, $array)) {
            $array[$key] = [];
        }

        return $array[$key];
    }

}
