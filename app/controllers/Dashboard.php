<?php
class Dashboard extends Controller
{
 /**
   * 
   * The default controller method 
   * 
   * @return void
   * */ 
   public function index()
   {
    
     return $this->view('dashboard/index');
   }
}

