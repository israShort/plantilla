<?php

    include_once("entidades\mensajes.php");

    class Cliente extends Mensajes {

        private $idCliente;
        private $nombre;
        private $cuit;
        private $telefono;
        private $correo;
        private $fecha_nac;

        public function __construct() {
            $this->setAccion(true);
        }

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }
        
        public function cargarFormulario($request) {
            $this->idCliente = isset($request["id"]) ? $request["id"] : "";
            $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
            $this->cuit = isset($request["txtCuit"])? $request["txtCuit"]: "";
            $this->telefono = isset($request["txtTelefono"])? $request["txtTelefono"]: "";
            $this->correo = isset($request["txtCorreo"])? $request["txtCorreo"] : "";
            $this->fecha_nac = isset($request["txtFechaNac"])? $request["txtFechaNac"] :"";
        }

        public function insertar(){
            //Instancia la clase mysqli con el constructor parametrizado
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            //Arma la query
            $sql = "INSERT INTO clientes (
                        nombre, 
                        cuit, 
                        telefono, 
                        correo, 
                        fecha_nac
                    ) VALUES (
                        '" . $this->nombre ."', 
                        '" . $this->cuit ."', 
                        '" . $this->telefono ."', 
                        '" . $this->correo ."', 
                        '" . $this->fecha_nac ."'
                    );";
                    
            if (!$mysqli->query($sql)) {
                $this->setMensaje("Error insertando al cliente '$this->nombre'.");
                $this->setAccion(false);
            }
            
            $this->idcliente = $mysqli->insert_id;
            
            $mysqli->close();
        }

        public function actualizar(){

            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "UPDATE clientes SET
                    nombre = '".$this->nombre."',
                    cuit = '".$this->cuit."',
                    telefono = '".$this->telefono."',
                    correo = '".$this->correo."',
                    fecha_nac =  '".$this->fecha_nac."'
                    WHERE idcliente = " . $this->idCliente;
              
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->setMensaje("Error actualizando al cliente '$this->nombre'.");
                $this->setAccion(false);
            }
            $mysqli->close();
        }

        public function eliminar(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "DELETE FROM clientes WHERE idcliente = " . $this->idCliente;
            
            if (!$mysqli->query($sql)) {
                $this->setMensaje("Error eliminando al cliente '$this->nombre'. Compruebe que el mismo no tenga ventas asociadas.");
                $this->setAccion(false);
            }

            $mysqli->close();
        }
    
        public function obtenerPorId(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT idcliente, 
                            nombre, 
                            cuit, 
                            telefono, 
                            correo, 
                            fecha_nac 
                    FROM clientes 
                    WHERE idcliente = " . $this->idCliente;
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
    
            //Convierte el resultado en un array asociativo
            if($fila = $resultado->fetch_assoc()){
                $this->idCliente = $fila["idcliente"];
                $this->nombre = $fila["nombre"];
                $this->cuit = $fila["cuit"];
                $this->telefono = $fila["telefono"];
                $this->correo = $fila["correo"];
                $this->fecha_nac = $fila["fecha_nac"];
            }  
            $mysqli->close();
    
        }

        public function obtenerTodos() {
            $aClientes = null;
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT
                        A.idcliente,
                        A.cuit,
                        A.nombre,
                        A.telefono,
                        A.correo,
                        A.fecha_nac
                        FROM
                            clientes A";

            $resultado = $mysqli->query($sql);

            if($resultado){
                while ($fila = $resultado->fetch_assoc()) {
                    $obj = new Cliente();
                    $obj->idCliente = $fila["idcliente"];
                    $obj->cuit = $fila["cuit"];
                    $obj->nombre = $fila["nombre"];
                    $obj->telefono = $fila["telefono"];
                    $obj->correo = $fila["correo"];
                    $obj->fecha_nac = $fila["fecha_nac"];
                    $aClientes[] = $obj;
                }
                return $aClientes;
            }
        }

    }

?>