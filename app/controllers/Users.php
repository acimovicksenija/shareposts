<?php

class Users extends Controller{

    public function __construct(){
        $this->userModel = $this->model("User");
    }


    public function index(){

    }

    public function register(){
        if($_SERVER["REQUEST_METHOD"]=="POST"){

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [

                "name" => trim($_POST["name"]),
                "email" => trim($_POST["email"]),
                "password" => trim($_POST["password"]),
                "confirm_password"=> trim($_POST["confirm_password"]),

                "name_err" => "",
                "email_err" => "",
                "password_err" => "",
                "confirm_password_err" => ""

            ];

            if(empty($data["email"])){
                $data["email_err"] = "Please enter email";
            }else{
                if($this->userModel->findUserByEmail($data["email"])){
                    $data["email_err"] = "Email is already taken";
                }
            }

            if(empty($data["name"])){
                $data["name_err"] = "Please enter your name";
            }

            if(empty($data["password"])){
                $data["password_err"] = "Please enter password";
            }else if(strlen($data["password"])<6){
                $data["password_err"] = "Password must be at least 6 characters";
            }

            if(empty($data["confirm_password"])){
                $data["confirm_password_err"] = "Please enter password";
            }else {
                if($data["confirm_password"]!=$data["password"]){
                $data["confirm_password_err"] = "Passwords don't match";
                }
            }

            if(empty($data["name_err"]) && empty($data["email_err"]) && empty($data["password_err"]) && empty($data["confirm_password_err"]) ){
               // die("success");
                
               $data["password"] = password_hash($data["password"] , PASSWORD_DEFAULT);

               if($this->userModel->register($data)){
                flash("register_success" , "you are registered");
                redirect("users/login");
               }else{
                   die("something went wrong");
               }

            }else{
                $this->view("users/register" , $data);
            }

        }else{
            $data = [
                "name" => "",
                "email" => "",
                "password" => "",
                "confirm_password"=> "",

                "name_err" => "",
                "email_err" => "",
                "password_err" => "",
                "confirm_password_err" => ""

            ];

            $this->view("users/register"  , $data);


        }
    }

    public function login(){
        if($_SERVER["REQUEST_METHOD"]=="POST"){

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
      
                "email" => trim($_POST["email"]),
                "password" => trim($_POST["password"]),
               
                "email_err" => "",
                "password_err" => "",

            ];

            
            if(empty($data["email"])){
                $data["email_err"] = "Please enter email";
            }

            
            if(empty($data["password"])){
                $data["password_err"] = "Please enter password";
            }


            if($this->userModel->findUserByEmail($data["email"])){

            }else{
                $data["email_err"] = "No user found";
            }



            if( empty($data["email_err"]) && empty($data["password_err"]) ){
                //die("success");
                $loggedInUser= $this->userModel->login($data["email"] , $data["password"]);
                if($loggedInUser){
                    //die("success");
                    $this->createUserSession($loggedInUser);

                }else{
                    $data["password_err"] = "Password incorrect";
                    $this->view("users/login", $data);
                }


            }else{
                $this->view("users/login" , $data);
            }


        }else{
            $data = [
               
                "email" => "",
                "password" => "",

                "email_err" => "",
                "password_err" => "",
              

            ];

            $this->view("users/login"  , $data);


        }
    }

    public function createUserSession($user){
        $_SESSION["user_id"] = $user->id;
        $_SESSION["user_email"] = $user->email;
        $_SESSION["user_name"] = $user->name;
        redirect("posts/index");
    }


    public function logout(){
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_email"]);
        unset($_SESSION["user_name"]);
        session_destroy();
        redirect("pages/index");
        
    }



}

?>