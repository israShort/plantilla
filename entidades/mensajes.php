<?php

    abstract class Mensajes {
        private $mensaje;
        private $accion;

        public function setAccion($accion) {
            $this->accion = $accion;
        }

        public function getAccion() {
            return $this->accion;
        }

        public function setMensaje($mensaje) {
            $this->mensaje = $mensaje;
        }

        public function getMensaje() {
            return $this->mensaje;
        }
    }

?>