<?php

    include_once("config.php");
    include_once("entidades\\venta.php");
    include_once("entidades\producto.php");
    include_once("entidades\cliente.php");

    $venta = new Venta();
    $venta->cargarFormulario($_REQUEST);

    if ($_POST) {
        if (isset($_GET["id"]) && ($_GET["id"] >= 0)) {
            $venta->idVenta = $_GET["id"]; //sería el new
            if (isset($_POST["btnGuardar"])) {
                $ventaOld = new Venta();
                $ventaOld->idVenta = $_GET["id"];
                $ventaOld->obtenerPorId();
                
                $producto = new Producto();
                $producto->idProducto = $venta->fkProducto;
                $producto->obtenerPorId();

                if ($producto->cantidad - ($venta->cantidad - $ventaOld->cantidad) >= 0) {
                    $venta->actualizar();
                    if ($venta->accion) {
                        $producto->cantidad -= ($venta->cantidad - $ventaOld->cantidad);
                        $producto->actualizar();
                        $msg = "La venta se ha actualizado correctamente.";
                    } else {
                        $msg = $venta->msg;
                    }
                } else {
                    print_r("Error");
                }
            } else if (isset($_POST["btnBorrar"])) {
                $producto = new Producto();
                $producto->idProducto = $venta->fkProducto;
                $producto->obtenerPorId();
                $producto->cantidad += $venta->cantidad;
                $producto->actualizar();
                $venta->eliminar();
                $venta->accion ? $msg = "La venta se ha eliminado correctamente." : $msg = $venta->msg;
                $_GET["id"] = null;
            }
        } else {
            $producto = new Producto();
            $producto->idProducto = $venta->fkProducto;
            if ($producto->idProducto != "") {
                $producto->obtenerPorId();
                
                if (($producto->cantidad >= $venta->cantidad) && ($venta->cantidad > 0)) {
                    $venta->insertar();
                    if ($venta->accion) {
                        $producto->cantidad -= $venta->cantidad;
                        $producto->actualizar();
                        $msg = "La venta se insertó de manera exitosa.";
                    } else {
                        $msg = $venta->msg;
                    }
                } else {
                    print_r("Error");
                }
            }
        }
    }

    if (isset($_GET["do"]) && ($_GET["do"] == "precioProducto")) {
        $articulo = new Producto();
        $articulo->idProducto = $_GET["id"];
        $articulo->obtenerPorId();
        echo json_encode($articulo->precio);exit;
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Edición de venta</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="js/bootstrap-select.min.js"></script>

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

            <?php include_once("menu.php"); ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Venta</h1>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="ventas-listado.php" class="btn btn-primary m-2">Listado</a>
                        <a href="venta-formulario.php" class="btn btn-primary m-2">Nuevo</a>
                        <button type="submit" class="btn btn-success m-2" id="btnGuardar" name="btnGuardar" onclick="guardar();">Guardar</button>
                        <button type="submit" class="btn btn-danger m-2" id="btnBorrar" name="btnBorrar">Borrar</button>
                    </div>
                    <h6 class="alert <?php echo $venta->accion ? "alert-success" : "alert-danger"; ?> ml-3 mt-3 <?php echo isset($msg) ? "" : "d-none"; ?>" role="alert"><?php echo $msg; ?></h6>
                </div>

                <?php

                    if (isset($_GET["id"])) {
                        $venta->idVenta = $_GET["id"];
                        $venta->obtenerPorId();
                    }

                ?>

                <form id="formulario" action="" method="POST">
                    <div class="row mt-3">
                        <div class="col-6 form-group">
                            <label for="txtFecha">Fecha:</label>
                            <input type="date" required class="form-control" name="txtFecha" id="txtFecha" value="<?php echo isset($_GET["id"]) ? date("yy-m-d", strtotime($venta->fecha)) : date("yy-m-d"); ?>">
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtHora">Hora:</label>
                            <input type="time" required class="form-control" name="txtHora" id="txtHora" value="<?php echo isset($_GET["id"]) ? date("H:i",strtotime($venta->fecha)) : date("H:i"); ?>">
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtCliente">Cliente:</label>
                            <select name="lstCliente" id="lstCliente" class="form-control" required>
                                <?php
                                    $c = new Cliente();
                                    $aClientes = $c->obtenerTodos();
                                ?>
                                <option value="<?php echo isset($_GET["id"]) ? $venta->fkCliente : ""; ?>" <?php echo isset($_GET["id"]) ? "selected" : "selected disabled" ; ?>><?php echo isset($_GET["id"]) ? $venta->cliente : "Seleccionar"; ?></option>
                                <?php
                                    foreach ($aClientes as $cliente) {
                                        if (!($venta->cliente === $cliente->nombre)) {
                                            echo "<option value='$cliente->idCliente'>$cliente->nombre</option>\n";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtProducto">Producto:</label>
                            <select onchange="traerDatosProducto();" name="lstTipoProducto" id="lstTipoProducto" class="form-control selectpicker" data-live-search="true" required>
                                <?php
                                    $prod = new Producto();
                                    $aProductos = $prod->obtenerTodos();
                                ?>
                                <option value="<?php echo isset($_GET["id"]) ? $venta->fkProducto : ""; ?>" <?php echo isset($_GET["id"]) ? "selected" : "selected disabled" ; ?>><?php echo isset($_GET["id"]) ? $venta->producto : "Seleccionar"; ?></option>
                                <?php
                                    foreach ($aProductos as $producto) {
                                        if (!($venta->producto === $producto->nombre)) {
                                            echo "<option value='$producto->idProducto'>$producto->nombre</option>\n";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtPrecioUnit">Precio unitario:</label>
                            <input type="text" required class="form-control" name="txtPrecioUnit" id="txtPrecioUnit" disabled value="<?php echo isset($_GET["id"]) ? number_format($venta->precioUnitario, 2, ",", ".") : "0"; ?>">
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtCantidad">Cantidad:</label>
                            <input onchange="calcularPrecioTotal();" type="number" min="0" required class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo isset($_GET["id"]) ? $venta->cantidad : "0"; ?>">
                        </div>

                        <div class="col-6 form-group">
                            <label for="txtTotal">Total:</label>
                            <input type="text" required class="form-control" name="txtTotal" id="txtTotal" disabled value="<?php echo isset($_GET["id"]) ? number_format($venta->total, 2, ",", ".") : "0"; ?>">
                        </div>
                    </div>
                </form>
            </div>

            

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2020</span>
            </div>
            </div>
        </footer>
        <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
</body>

</html>

<script>                             

    function guardar() {                                
        $("#formulario").submit();
    }

    function traerDatosProducto() {
        idProducto = $('#lstTipoProducto').val();
        $.ajax({
            type: "GET",
            url: "venta-formulario.php?do=precioProducto",
            data: {
                id: idProducto
            },
            async: true,
            dataType: "json",
            success: function(data) {
                $('#txtPrecioUnit').val(data);
                calcularPrecioTotal();
            }
        });
    }

    function calcularPrecioTotal() {
        $('#txtTotal').val($('#txtPrecioUnit').val() * $('#txtCantidad').val());
    }

</script>