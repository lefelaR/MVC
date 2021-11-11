<?php



class Server {

    private $default_ControllerName = 'Index';
    private $default_ActionName = 'Index';
    public $ProfileHandler;
    public $ActionClass;
    public $LoginPage = null;
    public $NotFoundPage= null; 
    public $API;
    public $RedirectRoutes;


    // function listen($siteroot, $layout){
    //     if (!isset($this->ProfileHandler)){
    //         $this->ProfileHandler = new ProfileHandler();
    //     }

    //     $profile = $this->initProfile();

    //     $this->executeContext($siteroot, $layout);  
        
    //     global $context;

    //     // if (!isset($profile)){
    //     //     if (isset($this->LoginPage)){
    //     //         if ($context->realurl != $this->LoginPage){
    //     //             header("Location: " . $context->siteroot . '/' .  $this->LoginPage); 
    //     //             die();
    //     //         }
    //     //     }
    //     // }
    // }

    function initProfile(){
        global $seahorse;
        $seahorse->profile=null;
        $authcookiename = $this->ProfileHandler->getAuthCookieName();
        $auth = '';
        if (isset($_COOKIE[$authcookiename])){
            $auth = $_COOKIE[$authcookiename];
            if ($auth==false){
                die();
            }
        }
        $profile = $this->ProfileHandler->initProfile($auth);
        if (isset($profile) && !empty($profile)){
            
            unset($seahorse->profile);
            $seahorse->profile = (object)$profile;
            return $seahorse->profile;
        }   
        
    }

    function encryptData($data){
        
        $iv = "1234567812345678";
        $pass = '#LOstr34m';
        $method = 'aes-128-cbc';
        return openssl_encrypt ($data, $method, $pass, true, $iv);
    }

    function decryptData($data){
       
        $iv = "1234567812345678";
        $pass = '#LOstr34m';
        $method = 'aes-128-cbc';
        return openssl_decrypt ($data, $method, $pass, true, $iv);
    }

    function executeContext($siteroot, $layout){
        global $context;

        $context = $this->initEnvironment($siteroot, $layout);

        $this->checkRedirects($context);

        $controller = new Controller($context);
        $controller->handleRoute();
        
    }

    function initEnvironment($siteroot, $layout)
    { 
        
        $context = new Context();
        $context->siteroot = $siteroot;
        $context->layout = $layout;
        
        $context->seahorsepath = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
        $context->realurl = str_replace('..', '.', $_SERVER['REDIRECT_real_url']);
       

        $this->initRoute($context);

        return $context;
    }

    function initRoute($context){

        $x = explode("/", $context->realurl);
        $x = $this->calculateRoute($x,$context);
        $context->path = implode("/", $x);
       
        $context->controllerpath = str_replace($context->controller,'', $context->path);
        $context->controllerpath = preg_replace('/\/\//i', '',  $context->controllerpath);

        if (!endsWith($context->controllerpath,'/')) {
            $context->controllerpath .= '/';
        }
        else if (endsWith($context->controllerpath,'//')){
            $context->controllerpath = substr($context->controllerpath, 0,strlen($context->controllerpath)-1);
        }

        if (strlen($context->controllerpath)==1){
            if ($context->controllerpath == '/'){
                $context->controllerpath = '';
            }
        }

        $this->formatRouteVariables($context);
     
    }

    function calculateRoute($x,$context){

        $x = $this->route($x,$context);

        if (empty($context->controller))
        {
            $context->controller = $this->default_ControllerName;
            $context->path = $this->default_ControllerName;
        }

        return $x;
    }

    function route($x,$context){
        if (sizeof($x) == 1) 
        {
            if (endsWith($context->realurl, '/')) 
            {
                $context->controller = $x[0];
                $context->action = $this->default_ActionName;
            } else 
            {
                $context->controller = $this->default_ControllerName;
                $isNumber = $this->isNumber($x[0]);
                $context->action = $x[0] == '' || $isNumber  ? $this->default_ActionName : $x[0];
                $context->id = $x[0];
                $x[0] = $this->default_ControllerName;
            }
        }
        else{
            return $this->routeIf2($x,$context);
        }   
        return $x;
    }

    function routeIf2($x,$context)
    {
        if (sizeof($x) == 2) {
            $context->controller = $x[0];
            $isNumber = $this->isNumber($x[1]);
            $context->action = $x[1] == '' || $isNumber ? $this->default_ActionName : $x[1];
            if ($isNumber){
                $context->id = $x[1];
            }
            unset($x[sizeof($x) - 1]);
        }
        else{
            return $this->routeIf3($x,$context);
        }
        return $x;
    }
    
    function routeIf3($x,$context)
    {
        if (sizeof($x) > 2) 
        {
            $index=0;
            $isLastIndexNumber = $this->isNumber( $x[sizeof($x)-1]);
            $removeAnotherIndex = false;;
            if ($isLastIndexNumber==true) {
                $tempx = $x;
                unset($tempx[sizeof($tempx) - 1]);
                unset($tempx[sizeof($tempx) - 1]);
                unset($tempx[sizeof($tempx) - 1]);
                $path = implode("/", $tempx);
                 
                $controller = ucfirst($x[sizeof($x)-3]) . 'Controller.php';
                $file = 'app/controllers/' . $path . '/' . $controller;
                $index++;
                if (!file_exists($file)){
                    $index++;
                    $removeAnotherIndex = true;
                }
                
            }

            $context->controller = $x[sizeof($x) - (2+$index)];
            $isNumber = $this->isNumber( $x[sizeof($x)-(1+$index)]);
            $context->action = $x[sizeof($x) - (1+$index)] == '' || $isNumber ? $this->default_ActionName : $x[sizeof($x) - (1+$index)];
            if ($isLastIndexNumber){
                $context->id = $x[sizeof($x)-1];
                 unset($x[sizeof($x) - 1]);
            }
            if ($removeAnotherIndex){
                unset($x[sizeof($x) - 1]);
            }
            
            unset($x[sizeof($x) - 1]);

        }   
      
        return $x;
    }

    function isNumber($val){
        if (preg_match('/^[0-9]+$/', $val)) {
            return true;
        }
        return false;
    }

    function formatRouteVariables($context)
    {
        $context->path = strtolower($context->path);
        $context->controller = ucFirst(removePHPExtension($context->controller));
        $context->action = ucFirst(removePHPExtension($context->action));
        $context->controller = $this->getControllerName($context->controller);
    }

    function getControllerName($file)
    {
        $controller = removePHPExtension($file);
        $controller .= 'Controller';
        return $controller;
    }

    function registerProfileHandler($profileHandlerClass){
         
        $profileHandlerClass = new $profileHandlerClass();

        if ($profileHandlerClass instanceof IProfileHandler) {
            $this->ProfileHandler = $profileHandlerClass;
        } else {
            throw new Exception('Not a Profile Handler. Please ensure you pass an object that implements iProfileHandler');
        }
    }

    function registerAPI($key, $baseurl, $endpoints){
         
        if (!isset($this->API)){
            $content = Array('url' =>  $baseurl, 'endpoints' => $endpoints ); 
            $this->API = Array( $key => $content);
        }
    }

    function registerViewAction(string $actionClass){
        
        $actionClass = new $actionClass();
        if ($actionClass instanceof IViewAction) {
            $this->ActionClass = $actionClass;
        } else {
            throw new Exception('Not a valid Action Handler. Please ensure you pass an object that implements iProfileHandler');
        }
    }

    public function registerLoginPage($loginUrl){
         $this->LoginPage = $loginUrl;
    }

    public function registerNotFoundPage($notFoundUrl){
         $this->NotFoundPage = $notFoundUrl;
    }
    

    public function addRedirect($url, $toUrl){

         if (!isset($this->RedirectRoutes)) 
         {
             $this->RedirectRoutes  = array($url => $toUrl);
         }
         else
         {
             $this->RedirectRoutes[$url] = $toUrl;
         }
    }

    public function checkRedirects($context){

        if (isset($this->RedirectRoutes)){

            foreach($this->RedirectRoutes as $url => $toUrl){
                
                if (preg_match($url, $context->realurl)) {

                    if (startsWith($toUrl, 'http')){
                        ob_clean();
                        Header('Location: ' . $toUrl);
                        die();
                    }
                    else{
                        $context->realurl = $toUrl;
                        $this->initRoute($context);
                        return;
                    }
                }
            }   
        }
    }

    public function redirectToNotFoundPage(){
        global $seahorse;
        global $context;
        $toUrl = $seahorse->server->NotFoundPage;
        if (isset($toUrl)){
            ob_clean();
            Header('Location: ' . buildurl($toUrl));
            die();  
        }
    }
   

      public function redirectToLogin(){
        global $seahorse;
        global $context;
        $toUrl = $seahorse->server->LoginPage;
        if (isset($toUrl)){
            ob_clean();
            Header('Location: ' . buildurl($toUrl));
            die();  
        }
    }
}

?>