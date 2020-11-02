<?php
    
    include_once("config.php");
    include_once("mensajes.php");
    include_once("entidades\cliente.php");

    $cliente = new Cliente();
    $cliente->cargarFormulario($_REQUEST);

    if ($_POST) {
        if (isset($_GET["id"]) && ($_GET["id"] >= 0)) {
            if (isset($_POST["btnGuardar"])) {
                $cliente->actualizar();
                $cliente->accion ? $msg = "El cliente se ha actualizado correctamente." : $msg = $cliente->msg;
            } else if (isset($_POST["btnBorrar"])) {
                $cliente->eliminar();
                $cliente->accion ? $msg = "El cliente se ha eliminado correctamente." : $msg = $cliente->msg;
                /*header("Location: cliente-formulario.php");*/
                $_GET["id"] = null;
            }
        } else {
            $cliente->insertar();
            $cliente->accion ? $msg = "Cliente cargado con éxito." : $msg = $cliente->msg;
        }
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

  <title>Edición de cliente</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
<form action="" method="POST">

    <!-- Page Wrapper -->
    <div id="wrapper">

            <?php include_once("menu.php"); ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Cliente</h1>
                </div>

                <div class="row">
                    <div class="col-12">
                        <a href="clientes-listado.php" class="btn btn-primary m-2">Listado</a>
                        <a href="cliente-formulario.php" class="btn btn-primary m-2">Nuevo</a>
                        <button type="submit" class="btn btn-success m-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                        <button type="submit" class="btn btn-danger m-2" id="btnBorrar" name="btnBorrar">Borrar</button>
                    </div>
                    <h6 class="alert <?php echo $cliente->accion ? "alert-success" : "alert-danger"; ?> ml-3 mt-3 <?php echo isset($msg) ? "" : "d-none"; ?>" role="alert"><?php echo $msg; ?></h6>
                </div>

                <?php

                    if (isset($_GET["id"])) {
                        $cliente->idCliente = $_GET["id"];
                        $cliente->obtenerPorId();
                    }

                ?>

                <div class="row mt-3">
                    <div class="col-6 form-group">
                        <label for="txtNombre">Nombre:</label>
                        <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php  echo isset($_GET["id"]) ? $cliente->nombre : ""; ?>">
                    </div>

                    <div class="col-6 form-group">
                        <label for="txtCuit">CUIT:</label>
                        <input type="text" required class="form-control" name="txtCuit" id="txtCuit" value="<?php  echo isset($_GET["id"]) ? $cliente->cuit : ""; ?>">
                    </div>

                    <div class="col-6 form-group">
                        <label for="txtFechaNac">Fecha de nacimiento:</label>
                        <input type="date" required class="form-control" name="txtFechaNac" id="txtFechaNac" value="<?php  echo isset($_GET["id"]) ? $cliente->fecha_nac : ""; ?>">
                    </div>

                    <div class="col-6 form-group">
                        <label for="txtTelefono">Teléfono:</label>
                        <input type="text" required class="form-control" name="txtTelefono" id="txtTelefono" value="<?php  echo isset($_GET["id"]) ? $cliente->telefono : ""; ?>">
                    </div>

                    <div class="col-6 form-group">
                        <label for="txtCorreo">Correo:</label>
                        <input type="mail" required class="form-control" name="txtCorreo" id="txtCorreo" value="<?php  echo isset($_GET["id"]) ? $cliente->correo : ""; ?>">
                    </div>
                </div>
            
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
  </form>
</body>

</html>
