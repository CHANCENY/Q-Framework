<?php
namespace Manipulator;
@session_start();

if(file_exists('../../Core/Globals/Globals.php')){
    require_once  '../../Core/Globals/Globals.php';
}
if(file_exists('../../Core/Modules/CountriesModular.php')){
    require_once '../../Core/Modules/CountriesModular.php';
}

use GlobalsFunctions\Globals;
use Modules\CountriesModular;

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

parse_str($url, $action);

switch ($action['action']){
    case 'pagetitle':
        $title = Globals::titleView();
        echo $title;
        exit;
    case 'states':
        echo json_encode(CountriesModular::getStateByCountry($action['country']));
        exit;
    default:
        echo "not found";
}
?>