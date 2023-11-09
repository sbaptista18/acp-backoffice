<!DOCTYPE html>
<html lang="en">

<?php
include '../../includes/header.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    include '../../components/sidebar.php'
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
        include '../../includes/topbar.php'
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4" style="flex-direction: column; align-items: flex-start !important;">
            <h1 class="h1 mb-0 text-gray-800">Formações</h1>
          </div>

          <div class="row">
            <div class="col-lg-12 mb-4">
              <!-- Formações -->
              <div id="formacoes" class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-lg-12">
                      <label for="formacoes-intro-title">Título:</label>
                      <input id="formacoes-intro-title" class="form-control" type="text">
                      
                      <span class="formacoes-intro-title-erro erro"></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-12">
                      <label for="formacoes-intro-text">Texto:</label>
                        <div id="formacoes-intro-text"></div>
                        <span class="characters formacoes-intro-text">caracteres: <span>400</span></span>
                        
                        <span class="formacoes-intro-text-erro erro"></span>
                    </div>
                  </div>

                  <div class="row" style="justify-content: flex-end;">
                    <div class="col-lg-12">
                      <a id="saveFormacaoIntro" class="btn btn-success btn-icon-split float-right">
                        <span class="icon text-white-50">
                          <i class="fas fa-check"></i>
                        </span>
                        <span class="text" style="color: #fff;">Guardar</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      include '../../includes/footer.php'
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <?php
  include '../../components/scroll-to-top-btn.php'
  ?>

  <!-- Logout Modal-->
  <?php
  include '../../components/logout-modal.php'
  ?>

  <?php
  include '../../includes/js-includes.php'
  ?>
  <script src="js/formacoes.js"></script>

</body>

</html>