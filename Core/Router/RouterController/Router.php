<?php

namespace Core;

use Alerts\Alerts;
use ConfigurationSetting\ConfigureSetting;
use MiddlewareSecurity\Security;
use Modules\SettingWeb;
use Sessions\SessionManager;

@session_start();
class Router
{
    /**
     * @var $requestUrl
     */
    private $requestUrl;

    private $paramsInUrl;

    private $registeredUrl;

    /**
     * @return mixed
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param mixed $requestUrl
     */
    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return mixed
     */
    public function getParamsInUrl()
    {
        return $this->paramsInUrl;
    }

    /**
     * @param mixed $paramsInUrl
     */
    public function setParamsInUrl($paramsInUrl)
    {
        $this->paramsInUrl = $paramsInUrl;
    }

    /**
     * @return mixed
     */
    public function getRegisteredUrl()
    {
        return $this->registeredUrl;
    }

    /**
     * @param mixed $registeredUrl
     */
    public function setRegisteredUrl($registeredUrl)
    {
        $this->registeredUrl = $registeredUrl;
    }

    public static function addView($viewData = []){

        try {
            if(empty($viewData)){
                return false;
            }

            //start making the view here
            $listUri = explode('/', $_SERVER['REQUEST_URI']);
            $baseRoot = $_SERVER['DOCUMENT_ROOT'];
            $middler = "";
            if(in_array('Views', $listUri)){
                $middler = "";
            }else{
                $middler = count($listUri) > 1 ? $listUri[count($listUri) - 2] : "";
            }

            $additionalPath = $middler.'/Core/Router/Register/';
            $completePath = empty($middler) ? $baseRoot.'/Views' : $baseRoot.'/'.$middler.'/Views';
            $completePath .= $viewData['path'][0] === '/' ? $viewData['path'] : "/".$viewData['path'];
            $relativePath = '/Views';
            $relativePath .= $viewData['path'][0] === '/' ? $viewData['path'] : "/".$viewData['path'];
            $storage = $baseRoot.'/'.$additionalPath.'registered_path_available.json';
            $storage = str_replace('//','/',$storage);

            $listAssoc = json_decode(is_file($storage) ? file_get_contents($storage) : json_encode(["status"=>"no-file"]),true);
            if(isset($listAssoc['status']) && $listAssoc['status'] === 'no-file'){
                return false;
            }

            $accessModifies = ['public', 'private'];

            if(!isset($viewData['access']) && in_array($viewData['access'], $accessModifies) === false){
                return "Please ensure access key is added and has public or private value";
            }

            $viewFormat =[
                "view_name"=>$viewData['name'],
                "view_url"=>$viewData['url'],
                "view_path_absolute"=>$completePath,
                "view_path_relative"=>$relativePath,
                "view_timestamp"=>time(),
                "view_description"=>$viewData['description'],
                "view_role_access"=>$viewData['access']
            ];

            foreach ($listAssoc as $item){
                if($item['view_url'] === $viewData['url']) {
                    return "Ensure url is unique and try again";
                }

                $listed = explode('/', $viewData['url']);
                $vlist = explode('/', $item['view_url']);
                if(in_array($vlist[0], $listed)){
                    return "Ensure that first from hostname in url is unique your clean url";
                }
            }

            array_push($listAssoc, $viewFormat);
            $content = json_encode($listAssoc);

            //clear up

            $content = Router::clearUrl($content);
            if(file_exists($completePath)){
                return "View file name already exist in view directory!";
            }

            if(file_put_contents($completePath, Router::boilerpulate($viewData['path']))){
                if(file_put_contents($storage, $content)){
                    return "View created";
                }else{
                    unlink($completePath);
                    return "Failed to create view";
                }
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public static function clearUrl($content){

        if(!empty($content)){
            $content = str_replace("\\", "", $content);
            $content = str_replace('\/'," ",$content);
            $content = str_replace('/', '/', $content);
            return $content;
        }
    }

    public static function boilerpulate($view){

        $list = explode('.', $view);
        $extension = strtolower(end($list));
        switch ($extension){
            case 'html':
                return "<section>{$list[0]}</section>";
            case 'php':
                return "<?php @session_start(); ?>";
            default:
                return "add your code here .... valid for {$extension}";
        }
    }

    public static function router($restricstionLevel = false){

        if(!empty(ConfigureSetting::getDatabaseConfig())) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $host = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
            $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

            parse_str($query, $list);
            $queryList = isset($list) ? $list : [];

            $path = $path[strlen($path) - 1] === '/' ? substr($path, 1, strlen($path) - 2) : substr($path, 1, strlen($path));
            if (SessionManager::getSession('site') === false) {
                $path = 'registration';
            }

            $storage = 'Core/Router/Register/registered_path_available.json';
            $foundView = [];
            if (file_exists($storage)) {
                $views = json_decode(file_get_contents($storage), true);
                $_SESSION['viewsstorage'] = $views;
                foreach ($views as $view) {
                    if (strtolower($path) === strtolower($view['view_url'])) {
                        $foundView = $view;
                        break;
                    }
                }
                $data = [
                    "host" => $host,
                    "path" => $path,
                    "query" => $query,
                    "params" => $queryList,
                    "view" => $foundView
                ];
                $_SESSION['public_data'] = $data;
                if (!empty($foundView)) {

                    if ($restricstionLevel === true) {
                        $security = new Security();
                        $access = $security->checkViewAccess();

                        if ($access === "V-NULL") {
                            $_SESSION['message']['route'] = "Page looking for is unreachable at moment";
                        } elseif ($access === "V-PRIVATE") {
                           self::accessAuthenticate($foundView, $security);
                        }elseif ($access === "V-MODERATOR"){
                            self::accessAuthenticate($foundView, $security);
                        }
                        else {
                            self::requiringFile($foundView);
                        }
                    } else {
                        self::requiringFile($foundView);
                    }
                } else {
                    if ($path === '/' || empty($path)) {
                        $security = new Security();
                        $user = $security->checkCurrentUser();

                        if($user == 'U-BLOCK'){
                            echo Alerts::alert('danger', "Sorry your account is blocked by authority if this is misunderstand please contact administrator");
                            exit;
                        }
                        $foundView = self::findHomePage();
                        if (!empty($foundView)) {
                            self::requiringFile($foundView);
                        } else {
                            $_SESSION['message']['route'] = "404 view not found with url ({$path} ) in system visit <a href='creating-view'>Create view</a> also you can view list of all your views registered visit <a href='my-views'>Views list</a>";
                        }
                    } else {
                        $result = self::findParamsCleanUrl($path, $views);
                        if($result !== false){
                           self::requiringFile($result);
                        }else{
                            $_SESSION['message']['route'] = "404 view not found with url ({$path} ) in system visit <a href='creating-view'>Create view</a> also you can view list of all your views registered visit <a href='my-views'>Views list</a>";
                        }
                    }
                }

            } else {
                echo "View not found";
                exit;
            }
        }else{
            $storage = 'Core/Router/Register/registered_path_available.json';
            $views = json_decode(file_get_contents($storage), true);
            $foundView=[];
            $_SESSION['viewsstorage'] = $views;
            foreach ($views as $view) {
                if (strtolower('installation') === strtolower($view['view_url'])) {
                    $foundView = $view;
                    break;
                }
            }

            if(!empty($foundView)){
                self::requiringFile($foundView);
            }
        }
    }

    public static function requiringFile($foundView = []){
        http_response_code(200);
        $list = explode('.', $foundView['view_path_absolute']);
        $contetType = Router::headerContentType(end($list));

        if(file_exists($foundView['view_path_absolute'])){
            require_once $foundView['view_path_absolute'];
        }else{
            $_SESSION['message']['route'] ="404 view not found with url ({$foundView['view_url']} ) in system visit <a href='creating-view'>Create view</a> also you can view list of all your views registered visit <a href='my-views'>Views list</a>";
        }

    }

    public static function headerContentType($extension){
        switch ($extension){
            case 'html':
                return 'text/html';
            case 'php':
                return 'txt/html';
            case 'json':
                return 'application/json';
            case 'xml':
                return 'application/xml';
            case 'js':
                return 'application/javascript';
            default:
                return 'plain/text';

        }
    }

    public static function findHomePage(){
          $setting = new SettingWeb();
          return $setting->getSettingConfig('home');

    }

    public static function updateView($view, $url){
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = $path[strlen($path)-1] === '/' ? substr($path, 1, strlen($path) - 2) : substr($path, 1, strlen($path));
        $storage = 'Core/Router/Register/registered_path_available.json';
        $foundView = [];
        if(file_exists($storage)) {
            $views = json_decode(file_get_contents($storage), true);
            $remaining = [];
            $oldview = [];
            foreach ($views as $view) {
                if (strtolower($url) !== strtolower($view['view_url'])) {
                    array_push($remaining, $view);
                }else{
                  $oldview = $view;
                }
            }


            foreach ($views as $item){
                if($item['view_url'] === $_POST['view-url']) {
                    return "Ensure url is unique and try again";
                }
            }

            $list = explode('/', $oldview['view_path_absolute']);
            $completePath = "";
            $relativePath = "";
            if(end($list) !== $_POST['path-address']){
                $sub = array_slice($list, 0 , count($list) - 1);
                $line = implode('/',$sub).'/'.$_POST['path-address'];
                if(rename($oldview['view_path_absolute'], $line)){
                    $completePath = $line;

                    $newlist = explode('/', $oldview['view_path_relative']);
                    $sub = array_slice($newlist,0,count($newlist) - 1);
                    $line = implode('/', $newlist).'/'.$_POST['path-address'];
                    $relativePath = $line;
                }else{
                    return false;
                }
            }else{
                $completePath = $oldview['view_path_absolute'];
                $relativePath = $oldview['view_path_relative'];
            }


            $viewFormat =[
                "view_name"=>htmlspecialchars(strip_tags($_POST['view-name'])),
                "view_url"=>htmlspecialchars(strip_tags($_POST['view-url'])),
                "view_path_absolute"=>$completePath,
                "view_path_relative"=>$relativePath,
                "view_timestamp"=>time(),
                "view_description"=>$_POST['description'],
                "view_role_access"=>$_POST['accessible']
            ];
            array_push($remaining, $viewFormat);

            $content = json_encode($remaining);

            //clear up

            $content = Router::clearUrl($content);

                if(file_put_contents($storage, $content)){
                    return "View updated";
                }else {
                    unlink($completePath);
                    return "Failed to update view";
                }
        }
    }

    public static function errorPages($code){
        $storage = $_SERVER['DOCUMENT_ROOT'].'/Core/Router/Register/registered_path_available.json';
        $views = json_decode(file_get_contents($storage), true);
        switch ($code){
            case 404:
                $foundViews = [];
                foreach ($views as $view){
                    if($view['view_url'] === '404'){
                        $foundViews = $view;
                        break;
                    }
                }
                self::requiringFile($foundViews);
                break;
            case 500:
                $foundViews = [];
                foreach ($views as $view){
                    if($view['view_url'] === '500'){
                        $foundViews = $view;
                        break;
                    }
                }
                self::requiringFile($foundViews);
            default:
                echo "here";
        }
    }

    public static function findParamsCleanUrl($url, $views){

        $data = [];
        $flag = false;
        $queryline = "";
        $foundView = [];
        foreach($views as $view){
            $list = explode('/', $view['view_url']);
            $urlList = explode('/', $url);

            $urlSize = count($urlList);
            if($urlSize === count($list)){
                $firstpart = $urlList[0];
                if(in_array($firstpart, $list)){
                    $list = array_slice($list, 1);
                    $prms = array_slice($urlList, 1);

                    $conter = 0;
                    foreach ($list as $li){
                        $element = str_replace('{',' ',$li);
                        $element = str_replace('}',' ', $element);
                        $element = trim($element);
                        $item = [$element=> $prms[$conter]];
                        $data = array_merge($data, $item);
                        $queryline .= $element.'='.$prms[$conter].'&';
                        $conter += 1;
                    }
                    $foundView = $view;
                    //$_SESSION['public_data'][]
                   $flag = true;
                }
            }

            if($flag === true){
                break;
            }
        }

        if($flag === true){
            $queryline = substr($queryline,0, strlen($queryline) - 1);
            $_SESSION['public_data']['query']=$queryline;
            $_SESSION['public_data']['params']=$data;
            $_SESSION['public_data']['view'] = $foundView;
            return $foundView;
        }else{
            return false;
        }
    }

    public static function accessAuthenticate($foundView, $securityClass){
        $user = $securityClass->checkCurrentUser();
        if ($user === "U-Admin") {
            $_SESSION['access']['role'] = 1;
            self::requiringFile($foundView);
        } elseif ($user === "U-BLOCK") {
            echo Alerts::alert('danger', "Sorry your account is blocked by authority if this is misunderstand please contact administrator");
            exit;
        } elseif ($user === "V-VERIFIED") {
            self::requiringFile($foundView);
        } 
        else {
            $_SESSION['message']['route'] = "Page is private your are not allowed here";
        }
    }
}