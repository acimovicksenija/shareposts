<?php

class Pages extends Controller{
    public function __construct(){
       // $this->postModel = $this->model("Post");
    }

    public function index(){
        //$this->view("hello"); 
        //$posts = $this->postModel->getPosts();

        if(isLoggedIn()){
            redirect("posts");
        }

        $data = [
        "title" => "SharePosts",
        "description" => "Simple Ksenija's framework example"
        /*,
        "posts"=>$posts*/
        ];
        $this->view("pages/index" , $data);

       
    }

    public function about(/*$id*/){
        
            //echo "<br>".$id;
            $data = [
                "title" => "About Us",
                "description" => "Welcome to about"
              ];
            $this->view("pages/about",$data);
        
    }
}

?>