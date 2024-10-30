<?php
require_once dirname(__FILE__).'/Version.php';
require_once dirname(__FILE__).'/admin/settings.php';
require_once dirname(__FILE__).'/disable_pingback.php';

require_once dirname(__FILE__).'/LoginParameter.php';
require_once dirname(__FILE__).'/barb_libs.php';

use barbsecurity\Version as Version;
use barbsecurity\LoginParameter as LoginParameter;

define('BARB_SECURITY_AUTHORITYSECURE', 'manage_options');    //User level required in order to change the settings.
define('BARB_SECURITY_SAVE_TRANSIENT', Version::$name."_SAVE");

$version = Version::getVersion();
$barb_security_options = get_option(Version::$name, array());

function barb_security_plugins_loaded() {
    $result = load_plugin_textdomain(Version::$name, false, Version::$name.'/languages');
}
add_action( 'plugins_loaded', 'barb_security_plugins_loaded' );



/**
 * check login
 */
function barb_security_login_init(){
    global $barb_security_options;
    BarbTool::bp_log("barb_security_login_init");

    if(isset($barb_security_options['parameter_enable']) && $barb_security_options['parameter_enable'] == true) {
        // リファラが空の場合はGETにパラメータがあることをチェックする
        if(!isset($_SERVER['HTTP_REFERER'])){
            // check get parameter case referer is empty
            if(!LoginParameter::checkGetParam()){
                BarbTool::bp_log("1 case referer is empty");
                exit_404();
            }
        }else if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'/wp-login.php') !== false){
            BarbTool::bp_log("2 case referer is wp-login.php");
            /**
             * リファラがwp-login.phpの場合はリファラかリクエストにパラメータがあることを確認する
             */
            //$actions = array('postpass', 'lostpassword', 'retrievepassword', 'resetpass', 'rp');
            //if(isset($_GET['action']) && in_array($_GET['action'], $actions, true)){
            //    return;
            //}

            if(!LoginParameter::checkRefererParam() || !LoginParameter::checkGetParam()){
                exit_404();
            }
        }else if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'/wp-admin/') !== false){
            // do nothing case referer is wp-admin
            BarbTool::bp_log("3 case referer is wp-admin");
            return true;
        }else if(isset($_SERVER['HTTP_REFERER'])){
            // それ以外のリファラでGETにパラメータがあることをチェックする
            if(!LoginParameter::checkGetParam()){
                exit_404();
            }
        }else{
            exit_404();
        }

    }
    return ;
}
add_action( 'login_init', 'barb_security_login_init', 1 );


function barb_security_secure_auth_redirect (){
    global $barb_security_options;
    if(isset($barb_security_options['parameter_enable']) && $barb_security_options['parameter_enable'] == true){
        if(strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false && !is_user_logged_in()){
            // wp-adminからの未ログインリダイレクトの場合は404で終了する
            exit_404();
        }
    }

}
add_action( 'secure_auth_redirect', 'barb_security_secure_auth_redirect' );
/*************************************
 * ADMIN LOGIN PAGE URL PARAMETER
 *************************************/

$barb_security_options = get_option(Version::$name, array());
/* If enable ADMIN LOGIN PAGE URL PARAMETER, initialize activate it.  */
if(isset($barb_security_options['parameter_enable']) && $barb_security_options['parameter_enable'] == true){
    LoginParameter::activeteLoginParameter();
}


/*************** OTHER ***************/

/**
 * エラーコード403で終了する
 */
function exit_403(){
    echo '<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1>'.__('Failed to login.', Version::$name).'</body></html>';
    status_header( 403 );
    exit();
}

/**
 * エラーコード403で終了する
 */
function exit_404(){
    echo '<html><head><title>404</title></head><body><h1>Not Found</h1>'."The requested URL {$_SERVER['REQUEST_URI']} was not found on this server".'</body></html>';
    status_header( 404 );
    exit();
}
