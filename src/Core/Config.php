<?php

namespace easyPM\Core;

use easyPM\Exceptions\NotFoundException;

// require_once __DIR__ . '/../Exceptions/NotFoundException.php';

class Config {
    private $data;
    public function __construct() {
        try {
            $json = file_get_contents(
                __DIR__ . '/../../config/easypm.json'
            );
        } catch (NotFoundException $e) {
            echo 'Configure not found:' . $e->getMessage();
        }

        $this->data = json_decode($json, true);
        if (!(($json_err = json_last_error()) === JSON_ERROR_NONE))
        {
            var_dump($json);
            echo "<br/>Can't decode json file because of:<br/>";
            switch ($json_err) {
                case JSON_ERROR_DEPTH:
                    echo "到达了最大堆栈深度";
                    break;

                case JSON_ERROR_CTRL_CHAR:
                    echo "控制字符错误，可能是编码不对";
                    break;

                case JSON_ERROR_SYNTAX:
                    echo "语法错误";
                    break;

                case JSON_ERROR_UTF8:
                    echo "异常的 UTF-8 字符，也许是因为不正确的编码";
                    break;

                case JSON_ERROR_RECURSION:
                    echo "One or more recursive references in the value to be encoded";
                    break;

                case JSON_ERROR_INF_NAN:
                    echo "One or more NAN or INF values in the value to be encoded";
                    break;

                case JSON_ERROR_UNSUPPORTED_TYPE:
                    echo "指定的类型，值无法编码";
                    break;

                case JSON_ERROR_INVALID_PROPERTY_NAME:
                    echo "指定的属性名无法编码";
                    break;

                case JSON_ERROR_UTF16:
                    echo "畸形的 UTF-16 字符，可能因为字符编码不正确";
                    break;

                default:
                    echo "其他解码错误";
                    break;
            }
            exit();
        }
    }

    public function get($key) {
        if (!isset($this->data[$key])) {
            throw new NotFoundException("Key $key not in config.");
        }
        return $this->data[$key];
    }
}
