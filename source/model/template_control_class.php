<?php
    class Route{
        // private $callback;

        // public function thisPage($p,$s){
        //     self::general_template_request($p,$s);
        // }

        // // Request Content From 
        // public function request_page_detail($page,$sub){
        //     include 'page/'.$this->search_file_page_content($page);
        //     return $page_detail;
        // }

        // public function search_file_page_content($pageRequest){
        //     return $this->page = $pageRequest.'.php';
        // }

        // // Send everything to General Template
        // private function general_template_request($page_request,$sub_page_request){
        //     $page_detail = $this->request_page_detail($page_request,$sub_page_request);
        //     include 'template/general.template.php';
        // }







        protected $routeDetail = array();
        // New Route system

        public function __construct(){
        }

        public function storePath($route,$path){
            $this->routeDetail[$route] = array(
                    'path' => $path
            );

            if(isset($_GET['p'])){
                if (array_key_exists($_GET['p'], $this->routeDetail)){
                    $this->get($this->routeDetail[$_GET['p']]['path']);
                }
            }
            else{
                $this->get($this->routeDetail['']['path']);
            }
        }

        public function get($path){
                self::requestTemplate($path);
                return;
        }

        public function request_page_detail($path){
            $detailFile = 'page/'.$path;
            $page_detail['name'] = '';
            $page_detail['subname'] = '';
            if (file_exists($detailFile)) {
                include_once 'page/'.$path;
            }
            return $page_detail;
        }

        private function requestTemplate($path){
            $page_detail = $this->request_page_detail($path);
            include_once 'views/template/general.template.php';
        }
        public function set_csrf(){
            if( ! isset($_SESSION["csrf"]) ){ 
              $_SESSION["csrf"] = bin2hex(random_bytes(50));
            }
            echo '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
        }
        public function is_csrf_valid(){
        if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){
            return false;
        }
        if( $_SESSION['csrf'] != $_POST['csrf']){
            return false;
        }
        return true;
        }
    }