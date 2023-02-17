<?php
namespace index;
require_once  __DIR__.'/vendor/autoload.php';

use Core\Router;
use Commerce\Commerce;
use Datainterface\Tables;
use Datainterface\Database;
use GlobalsFunctions\Globals;
use ApiHandler\ApiHandlerClass;
use MiddlewareSecurity\Security;
use ConfigurationSetting\ConfigureSetting;

// enable these two line to show error on web page
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


@session_start();

try{
    $content = ApiHandlerClass::findHeaderValue('Content-Type');
    if($content === "application/json"){
        $url = Globals::uri();
        $parseurl = parse_url($url, PHP_URL_PATH);
        $parseurl = substr($parseurl, 1 , strlen($parseurl));
        $view = Globals::findViewByUrl($parseurl);
        if(!empty($view)){
            Router::requiringFile($view);
        }
        exit;
    }
}catch(\Exception $e){
 Router::errorPages(500);
}
//CustomInstallation::writeComposerFile('h','h');

try{
    Database::installer();
}catch (\Exception $e){
    Router::errorPages(500);
}

try{
    $security = new Security();
    $user= $security->checkCurrentUser();

    if($user === "U-Admin"){
        require_once 'Views/DefaultViews/nav.php';
    }else{
        /*
         * Your nav will load from here if exist in Views directory
         */
        if(file_exists('Views/nav.view.php')){
            require_once 'Views/nav.view.php';
        }else{
            //default nav will load here with menus that are not admin based
            require_once 'Views/DefaultViews/nav.php';
        }

        global $connection;

        $connection = Database::database();

        if(!empty(ConfigureSetting::getDatabaseConfig())){
            if(!Tables::tablesExists()){
                Tables::installTableRequired();
            }
        }
    }
}catch (\Exception $e){
    Router::errorPages(500);
}
?>
<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php
        /**
         * Routing is happing from here Router::router();
         */
        try{
            
            Router::router(true);
            if(isset($_SESSION['message']['route']) && !empty($_SESSION['message']['route'])){
                Router::errorPages(404);
                $_SESSION['message']['route'] = "";
            }
        }catch (\Exception $e){
            Router::errorPages(500);
        }

        ?>
    </div>
    <script src="Js/main.js"></script>
    <script src="Js/addressing.js"></script>
</main>
<?php

if($user === "U-ADMIN"){
    require_once 'Views/DefaultViews/footer.php';
}else{
    /*
    * Your nav will load from here if exist in Views directory
    */
    if(file_exists('Views/footer.view.php')){
        require_once 'Views/footer.view.php';
    }else{
        //default nav will load here with menus that are not admin based
        require_once 'Views/DefaultViews/footer.php';
    }
}
?>
