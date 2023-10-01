<?php

    class ControladorUsuarios{

        /*========================================
        INGRESO USUARIO
        ==========================================*/

        static public function ctrIngresoUsuario(){

            if(isset($_POST["ingUsuario"])){

                if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])){

                    $encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                    $tabla = "usuarios";
                    
                    $item = "usuario";
                    $valor = $_POST["ingUsuario"];

                    $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

                    if ($respuesta["usuario"] == $_POST["ingUsuario"] && $respuesta["password"] == $encriptar){
                                                                                                        
                            $_SESSION["iniciarSesion"] = "ok";
                            $_SESSION["id"] = $respuesta["id"];
                            $_SESSION["nombre"] = $respuesta["nombre"];
                            $_SESSION["usuario"] = $respuesta["usuario"];
                            $_SESSION["foto"] = $respuesta["foto"];
                            $_SESSION["perfil"] = $respuesta["perfil"];

                            echo '<script>
                            
                                window.location = "inicio";
                            
                            </script>';


                        }else{

                            echo '<script>
                        
                            Swal.fire({
    
                                icon: "error",
                                title: "¡Usuario o contraseña incorrectos!",
                                text: "",
                                showConfirmButton: true,
                                confirmButtonText: "OK",
                                closeOnConfirm: false
    
                            });
                            
                                  </script>';
                            //echo '<br><div class="alert alert-danger" id="alert">Usuario o contraseña incorrectos</div>';

                        }



                }

            }


        }

        /*========================================
        REISTRO DE USUARIO
        ==========================================*/

        static public function ctrCrearUsuario(){


            if(isset($_POST["nuevoUsuario"])){

                if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) && 
                   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"]) &&
                   preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoPassword"])){

                    /*=======================================================
                    VALIDAR IMAGEN
                    =======================================================*/

                    $ruta = "";

                    if(isset($_FILES["nuevaFoto"]["tmp_name"])){

                        list($ancho, $alto) = getimagesize($_FILES["nuevaFoto"]["tmp_name"]);

                        $nuevoAncho = 500;
                        $nuevoAlto = 500;

                        /*==================================================
                        CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
                        ==================================================*/

                        $directorio = "vistas/img/usuarios/".$_POST["nuevoUsuario"];

                        mkdir($directorio, 0755);

                        /*==================================================
                        DEACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
                        ==================================================*/

                        if($_FILES["nuevaFoto"]["type"] == "image/jpeg"){
 
                        /*==================================================
                        Guardar imagen en el directorio
                        ==================================================*/
                        
                        $aleatorio = mt_rand(100,999);

                        $ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".jpg";


                        $oringen = imagecreatefromjpeg($_FILES["nuevaFoto"]["tmp_name"]);

                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                        imagecopyresized($destino, $oringen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

                        imagejpeg($destino, $ruta);

                        }

                        if($_FILES["nuevaFoto"]["type"] == "image/png"){
 
                            /*==================================================
                            Guardar imagen en el directorio
                            ==================================================*/
                            
                            $aleatorio = mt_rand(100,999);
    
                            $ruta = "vistas/img/usuarios/".$_POST["nuevoUsuario"]."/".$aleatorio.".png";
    
    
                            $oringen = imagecreatefrompng($_FILES["nuevaFoto"]["tmp_name"]);
    
                            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
    
                            imagecopyresized($destino, $oringen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
    
                            imagepng($destino, $ruta);
    
                            }
    

                    }

                    $tabla = "usuarios";

                    $encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                        $datos = array("nombre" => $_POST["nuevoNombre"],
                                       "usuario" => $_POST["nuevoUsuario"],
                                       "password" => $encriptar,
                                       "perfil" => $_POST["nuevoPerfil"],
                                       "foto" => $ruta);

                        $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla,$datos);

                        if($respuesta == "ok"){

                            echo '<script>
                        
                            Swal.fire({
    
                                icon: "success",
                                title: "¡El usuario ha sido guardado correctamente!",
                                text: "",
                                showConfirmButton: true,
                                confirmButtonText: "OK",
                                closeOnConfirm: false
    
                            }).then((result)=>{
    
                                if(resultado.value){
    
                                    window.location = "usuarios";
    
                                }
    
                            });
                            
                                  </script>';

                        }

                   }else{

                        echo '<script>
                        
                        Swal.fire({

                            icon: "error",
                            title: "¡El usuario no puede ir vacío o llevar caracteres especiales!",
                            text: "",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar",
                            closeOnConfirm: false

                        }).then((result)=>{

                            if(resultado.value){

                                window.location = "usuarios";

                            }

                        });
                        
                              </script>';

                   }

            }

        }

        /*=====================================
        MOSTRAR USUARIO
        =====================================*/

        static public function ctrMostrarUsuarios($item, $valor){

            $tabla = "usuarios";

            $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

            return $respuesta;

        }

    }