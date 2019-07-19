<?php
namespace mj\libraries;

use mj\config;
use mj\libraries\application as App;

class controller{

    private $path;
    private $method;
    private $actions;
    private $redirect;
    private $task;
    private $redirect_status;
    
    public function __construct(){

        $input = App::use('input');

        $this->path = $input->url()->find('cpath', '');
        $routers = empty($this->path) ? config::$routers : config::$routers[$this->path];
        
        $task = $input->url()->find('task', 'cmd');
        if(empty($task)) $task = config::$notFoundAction;

        if( !isset($routers[$task]) ) $task = config::$notFoundAction;

        $method = App::use('env')->getRequestMethod();

        $this->actions = isset( $routers[$task][$method] ) ? $routers[$task][$method] : $routers[$task];
            
        $this->task = $task;
        $this->method = $method;
        
    }

    public function process(){

        $okie = true; 
        foreach( $this->actions as $action){
            if( !$okie ) continue;
            $okie = $this->execute( $action );
        }

        if( !empty($this->redirect) ){

            if( headers_sent()){
                echo '<script>document.location.href="'.$this->redirect.'"</script>';
            }else{
                $status = empty( $this->redirect_status ) ? 302 : (int) $this->redirect_status;
                header( "Location: $this->redirect", true, $status );
            }
            exit(0);
        }

        return $okie;
    }

    public function execute( $action ){

        $path = empty($this->path ) ? '' : $this->path.'\\';
        $class = 'mj\\actions\\'.$path. $action;
        $act = new $class( $this->task ); 

        $act->processLogic();
        $act->prepareContent();

        $this->redirect = $act->getRedirect();
        return $act->processResult();

    }
}