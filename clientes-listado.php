<?php

    include_once("config.php");
    include_once("entidades\cliente.php");

    $cliente = new Cliente();
    $aClientes = $cliente->obtenerTodos();

?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Listado de clientes</title>

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
                    <h1 class="h3 mb-0 text-gray-800">Listado de clientes</h1>
                </div>

                <div class="row">
                    <a href="cliente-formulario.php" class="btn btn-primary m-2">Nuevo</a>
                </div>

                <div class="row mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>CUIT</th>
                                <th>Nombre</th>
                                <th>Fecha nac.</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Domicilios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($aClientes)) {
                                    foreach ($aClientes as $cliente) {
                                        echo "<tr>";
                                        echo "<td>$cliente->cuit</td>";
                                        echo "<td>$cliente->nombre</td>";
                                        echo "<td>".date_format(date_create($cliente->fecha_nac), "d-m-Y")."</td>";
                                        echo "<td>$cliente->telefono</td>";
                                        echo "<td>$cliente->correo</td>";
                                        echo "<td></td>";
                                        echo "<td class=text-center><a href='cliente-formulario.php?id=" .$cliente->idCliente. "'><i class='fas fa-search'></i></a></td>";
                                        echo "<tr>";
                                    }
                                }
                            ?>
                        </tdoby>
                    </table>
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
