<?php

if(!class_exists(BarbTool)){
    class BarbTool {
        /**
         * ログファイル出力する
         * @param $message
         */
        static function bp_log($message) {
            if (defined('BARB_DEBUG') && BARB_DEBUG) {
                $trace = debug_backtrace();
                $time = date('Y-m-d H:i:s');
                error_log("$time $message {$trace[0]['file']} {$trace[0]['line']}\n", 3, ABSPATH.date('Y-m-d').'.log');
            }
        }

        /**
         * SQL用のエスケープ
         * @param string $str エスケープする対象の文字列
         */
        static function doSqlEscape(&$str)
        {
            $str = str_replace("\\", "\\\\", $str);
            $str = str_replace(";", "\;", $str);
            $str = str_replace("'", "\'", $str);
            $str = str_replace('"', '\'', $str);
            $str = str_replace('%', '\%', $str);
            $str = str_replace("`", "\`", $str);
        }
    }

}
