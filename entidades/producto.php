<?php

    include_once("entidades\mensajes.php");

    class Producto extends Mensajes {

        private $idProducto;
        private $nombre;
        private $fkTipoProducto;
        private $tipoProducto;
        private $cantidad;
        private $precio;
        private $descripcion;
        private $imagen;

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
            $this->fkTipoProducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"]: "";
            $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: "";
            $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"] : "";
            $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] :"";
            $this->imagen = isset($request["imagenProducto"])? $request["imagenProducto"] :"";
        }

        public function insertar(){
            //Instancia la clase mysqli con el constructor parametrizado
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            //Arma la query
            $sql = "INSERT INTO productos (
                        nombre,
                        cantidad, 
                        precio, 
                        descripcion,
                        imagen,
                        fk_idtipoproducto
                    ) VALUES (
                        '" . $this->nombre ."',
                        " . $this->cantidad .", 
                        " . $this->precio .", 
                        '" . $this->descripcion ."', 
                        '" . $this->imagen ."', 
                        " . $this->fkTipoProducto ."
                    );";
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->setMensaje("Error insertando un nuevo producto.");
                $this->setAccion(false);
            }
            //Obtiene el id generado por la inserción
            $this->idProducto = $mysqli->insert_id;
            //Cierra la conexión
            $mysqli->close();
        }

        public function actualizar(){

            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            
            /*comprobar que sea un valor numérico*/
            if (!(strpos($this->precio, ",") === false)) {
                $this->precio = str_replace(".", "", $this->precio);
                $this->precio = str_replace(",", ".", $this->precio);
            }

            $sql = "UPDATE productos SET
                    nombre = '".$this->nombre."',
                    fk_idtipoproducto = ".$this->fkTipoProducto.",
                    cantidad = ".$this->cantidad.",
                    precio = ".$this->precio.",
                    imagen = '".$this->imagen."',
                    descripcion =  '".$this->descripcion."'
                    WHERE idproducto = " . $this->idProducto.";";
              
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->setMensaje("Error actualizando al cliente.");
                $this->setAccion(false);
            }
            $mysqli->close();
        }

        public function eliminar(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "DELETE FROM productos WHERE idproducto = " . $this->idProducto;
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->setMensaje("No se ha podido eliminar el producto. Compruebe que el mismo no tenga ventas asociadas.");
                $this->setAccion(false);
            }
            $mysqli->close();
        }

        public function obtenerPorId(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT p.idproducto, 
                            p.nombre, 
                            p.cantidad, 
                            p.precio, 
                            p.descripcion, 
                            p.imagen,
                            p.fk_idtipoproducto,
                            tp.nombre AS tipoProducto
                    FROM productos AS p
                    INNER JOIN tipoproductos AS tp ON p.fk_idtipoproducto = tp.idtipoproducto
                    WHERE idproducto = " . $this->idProducto;
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
    
            //Convierte el resultado en un array asociativo
            if($fila = $resultado->fetch_assoc()){
                $this->idProducto = $fila["idproducto"];
                $this->nombre = $fila["nombre"];
                $this->fkTipoProducto = $fila["fk_idtipoproducto"];
                $this->tipoProducto = $fila["tipoProducto"];
                $this->cantidad = $fila["cantidad"];
                $this->precio = $fila["precio"];
                $this->descripcion = $fila["descripcion"];
                $this->imagen = $fila["imagen"];
            }
            $mysqli->close();
    
        }

        public function obtenerTodos(){
            $aProductos = null;
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT  idproducto,
                            nombre, 
                            cantidad, 
                            precio, 
                            imagen
                    FROM productos;";
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
            $resultado = $mysqli->query($sql);
    
            if($resultado){
                while ($fila = $resultado->fetch_assoc()) {
                    $obj = new Producto();
                    $obj->idProducto = $fila["idproducto"];
                    $obj->nombre = $fila["nombre"];
                    $obj->cantidad = $fila["cantidad"];
                    $obj->precio = $fila["precio"];
                    $obj->imagen = $fila["imagen"];
                    $aProductos[] = $obj;
    
                }
                return $aProductos;
            }
        }

    }

?>