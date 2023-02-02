<?php
namespace index;
require_once  __DIR__.'/vendor/autoload.php';

use Alerts\Alerts;
use Assest\Assest;
use Commerce\Commerce;
use Core\Router;
use Datainterface\Database;
use Datainterface\Tables;
use MiddlewareSecurity\Security;



@session_start();

Database::installer();

$security = new Security();
$user= $security->checkCurrentUser();

if($user === "U-Admin"){
    require_once 'Views/DefaultViews/nav.php';
}else{
    /*
     * Load your nav here
     */
}
//$data = ["name"=>"view-create","path"=>"create.view.php", "access"=>"private", "url"=>"creating-view", "description"=>"view creation page view"];
//Router::addView($data);
global $connection;
$connection = Database::database();
if(!Tables::tablesExists()){
    Tables::installTableRequired();
}
?>
<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php
        /**
         * Routing is happing from here Router::router();
         */
        Router::router(true);
        if(isset($_SESSION['message']['route']) && !empty($_SESSION['message']['route'])){
            echo Alerts::alert('danger', $_SESSION['message']['route']);
            $_SESSION['message']['route'] = "";
        }
        ?>
    </div>
    <?php
      Assest::loadJavaScript("js");
    ?>
</main>
<?php

if($user === "U-ADMIN"){
    require_once 'Views/DefaultViews/footer.php';
}else{
    /*
     * Load your footer here
     */
}
?>
