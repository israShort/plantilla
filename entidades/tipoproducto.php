<?php

    class TipoProducto {

        private $idTipoProducto;
        private $nombre;
        private $accion;
        private $msg;

        public function __construct() {
            $this->accion = true;
        }

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }
        
        public function cargarFormulario($request) {
            $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        }

        public function insertar(){
            //Instancia la clase mysqli con el constructor parametrizado
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            //Arma la query
            $sql = "INSERT INTO tipoproductos (
                        nombre
                    ) VALUES (
                        '" . $this->nombre ."'
                    );";
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "Error insertando al tipo producto '$this->nombre'.";
                $this->accion = false;
            }
            //Obtiene el id generado por la inserción
            $this->idTipoProducto = $mysqli->insert_id;
            //Cierra la conexión
            $mysqli->close();
        }

        public function actualizar(){

            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "UPDATE tipoproductos SET
                    nombre = '".$this->nombre."'
                    WHERE idtipoproducto = " . $this->idTipoProducto;
              
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "Error actualizando el tipo producto '$this->nombre'.";
                $this->accion = false;
            }
            $mysqli->close();
        }

        public function eliminar(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "DELETE FROM tipoproductos WHERE idtipoproducto = " . $this->idTipoProducto;
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "No se ha podido eliminar el tipo producto '$this->nombre'. Compruebe que el mismo no tenga productos asociados.";
                $this->accion = false;
            }
            $mysqli->close();
        }
    
        public function obtenerPorId(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT idtipoproducto, 
                            nombre
                    FROM tipoproductos 
                    WHERE idtipoproducto = " . $this->idTipoProducto;
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
    
            //Convierte el resultado en un array asociativo
            if($fila = $resultado->fetch_assoc()){
                $this->idTipoProducto = $fila["idtipoproducto"];
                $this->nombre = $fila["nombre"];
            }  
            $mysqli->close();
    
        }

        public function obtenerTodos() {
            $aTipoProductos = null;
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT
                        A.idtipoproducto,
                        A.nombre
                        FROM
                            tipoproductos A";

            $resultado = $mysqli->query($sql);

            if($resultado){
                while ($fila = $resultado->fetch_assoc()) {
                    $obj = new TipoProducto();
                    $obj->idTipoProducto = $fila["idtipoproducto"];
                    $obj->nombre = $fila["nombre"];
                    $aTipoProductos[] = $obj;
                }
                return $aTipoProductos;
            }
        }

    }

?>