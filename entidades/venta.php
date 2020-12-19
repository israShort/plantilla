<?php

    class Venta {
        
        private $idVenta;
        private $fkCliente;
        private $cliente;
        private $fkProducto;
        private $producto;
        private $fecha;
        private $hora;
        private $cantidad;
        private $precioUnitario;
        private $total;
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
            $this->idVenta = isset($request["id"]) ? $request["id"] : "";
            $this->fkCliente = isset($request["lstCliente"]) ? $request["lstCliente"] : "";
            $this->fkProducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"]: "";
            $this->fecha = isset($request["txtFecha"])? $request["txtFecha"]: "";
            $this->hora = isset($request["txtHora"])? $request["txtHora"] : "";
            $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"] :"";
            $this->precioUnitario = isset($request["txtPrecioUnit"])? $request["txtPrecioUnit"] :"";
            $this->total = isset($request["txtTotal"])? $request["txtTotal"] :"";
        }

        public function insertar(){
            //Instancia la clase mysqli con el constructor parametrizado
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            //Arma la query
            $sql = "INSERT INTO ventas (
                        fk_idcliente,
                        fk_idproducto, 
                        fecha, 
                        cantidad,
                        preciounitario,
                        total
                    ) VALUES (
                        " . $this->fkCliente .",
                        " . $this->fkProducto .", 
                        '" . $this->fecha ." " . $this->hora . ":" . date("s") . "', 
                        " . $this->cantidad . ", 
                        " . $this->precioUnitario .", 
                        " . $this->total ."
                    );";
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "Error insertando una nueva venta.";
                $this->accion = false;
            }
            //Obtiene el id generado por la inserción
            $this->idVenta = $mysqli->insert_id;
            //Cierra la conexión
            $mysqli->close();
        }

        public function actualizar(){

            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

            $sql = "UPDATE ventas SET
                    fk_idcliente = ".$this->fkCliente.",
                    fk_idproducto = ".$this->fkProducto.",
                    fecha = '".$this->fecha." " . $this->hora . ":" . date("s") . "',
                    cantidad = ".$this->cantidad.",
                    preciounitario = ".$this->precioUnitario.",
                    total =  ".$this->total."
                    WHERE idventa = " . $this->idVenta;
              
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "Error actualizando la venta.";
                $this->accion = false;
            }
            $mysqli->close();
        }

        public function eliminar(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "DELETE FROM ventas WHERE idventa = " . $this->idVenta;
            //Ejecuta la query
            if (!$mysqli->query($sql)) {
                /*printf("Error en query: %s\n", $mysqli->error . " " . $sql);*/
                $this->msg = "No se ha podido eliminar la venta.";
                $this->accion = false;
            }
            $mysqli->close();
        }

        public function obtenerPorId(){
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT idventa, 
                            fecha, 
                            v.cantidad, 
                            preciounitario, 
                            total, 
                            fk_idcliente,
                            fk_idproducto,
                            c.nombre AS cliente,
                            p.nombre AS producto
                    FROM ventas AS v
                    INNER JOIN productos AS p ON v.fk_idproducto = p.idproducto
                    INNER JOIN clientes AS c ON v.fk_idcliente = c.idcliente
                    WHERE idventa = " . $this->idVenta;
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
    
            //Convierte el resultado en un array asociativo
            if($fila = $resultado->fetch_assoc()){
                $this->idVenta = $fila["idventa"];
                $this->fecha = $fila["fecha"];
                $this->cantidad = $fila["cantidad"];
                $this->precioUnitario = $fila["preciounitario"];
                $this->total = $fila["total"];
                $this->fkCliente = $fila["fk_idcliente"];
                $this->fkProducto = $fila["fk_idproducto"];
                $this->cliente = $fila["cliente"];
                $this->producto = $fila["producto"];
            }  
            $mysqli->close();
    
        }

        public function obtenerTodos(){
            $aVentas = null;
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT  idventa,
                            fecha, 
                            cantidad,
                            total, 
                            (SELECT nombre FROM clientes WHERE idcliente = fk_idcliente) AS cliente,
                            (SELECT nombre FROM productos WHERE idproducto = fk_idproducto) AS producto
                    FROM ventas;";
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
            $resultado = $mysqli->query($sql);
    
            if($resultado){
                while ($fila = $resultado->fetch_assoc()) {
                    $obj = new Venta();
                    $obj->idVenta = $fila["idventa"];
                    $obj->fecha = $fila["fecha"];
                    $obj->cantidad = $fila["cantidad"];
                    $obj->total = $fila["total"];
                    $obj->cliente = $fila["cliente"];
                    $obj->producto = $fila["producto"];
                    $aVentas[] = $obj;
    
                }
                return $aVentas;
            }
        }

        public function obtenerFacturacionMensual($mes) {
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT  SUM(total) AS factMes
                    FROM ventas
                    WHERE (SELECT EXTRACT(MONTH FROM fecha)) = $mes
                    AND (SELECT EXTRACT(YEAR FROM fecha)) = (SELECT EXTRACT(YEAR FROM NOW()));";
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
            $resultado = $mysqli->query($sql);

            if($fila = $resultado->fetch_assoc()){
                return $fila["factMes"];
            }
        }

        public function obtenerFacturacionAnual($anio) {
            $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
            $sql = "SELECT  SUM(total) AS factAnual
                    FROM ventas
                    WHERE (SELECT EXTRACT(YEAR FROM fecha)) = $anio;";
            if (!$resultado = $mysqli->query($sql)) {
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
            $resultado = $mysqli->query($sql);

            if($fila = $resultado->fetch_assoc()){
                return $fila["factAnual"];
            }
        }

    }

?>