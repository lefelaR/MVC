<?php

class Authentication extends Controller
{
/**
   * 
   * The default controller method 
   * 
   * @return void
   * */ 
    public function register()
    {
        echo "Authentication/register";
    }


    public function login()
    {
        return $this->view('authentication/login');
    }


    public function postlogin($data)
    {
        $user = new User();
        $profile = user::Login($data);

        return $this->redirect();
    }
   

    public function forgotpassword()
    {

        
    }

    public function resetpassword()
    {

        
    }
    public function index()
    {

        return $this->view('authentication/index',null);
    }

}