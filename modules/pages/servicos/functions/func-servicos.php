<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/functions/mysql_funcs.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/tools/tools.php');

date_default_timezone_set('EUROPE/LISBON');
$cmdEval = $_REQUEST['cmdEval'];

switch ($cmdEval) {
    case 'getServicos':
        getServicos();
        break;
    case 'getAllServicosByIdServico':
        getAllServicosByIdServico();
        break;
    case 'saveServico':
        saveServico();
        break;
    case 'deleteServico':
        deleteServico();
        break;
    case 'saveOrderServicos':
        saveOrderServicos();
        break;
    default:
        echo "error||[\"missing arguments\"]";
        exit;
        break;
}

function getServicos()
{
    $sqlCmd = " SELECT * from servicos
    order by display_order asc";
    $servicos = execQueryMySQL($sqlCmd);
    echo "true||" . json_encode($servicos);
}

function getAllServicosByIdServico()
{
    $sqlCmd = " SELECT * from servicos
    WHERE id = " . $_REQUEST['id_servico'];
    $servico = execQueryMySQL($sqlCmd);
    echo "true||" . json_encode($servico);
}

function saveServico()
{
    $id = $_REQUEST['id'];
    $title = $_REQUEST['title'];
    $sigla = $_REQUEST['sigla'];
    $short_description = $_REQUEST['short_description'];
    $about = $_REQUEST['about'];
    $why = $_REQUEST['why'];
    $sharing = $_REQUEST['sharing'];
    $slug = CleanToRef($title);

    if ($id == '') {
        if (isset($_FILES['filesServices'])) {
            $errors = [];
            $path = $_SERVER['DOCUMENT_ROOT']. '/uploads/servicos/'; //to BO online path
            $path1 = explode('backoffice', $_SERVER['DOCUMENT_ROOT'])[0] . 'httpdocs/uploads/servicos/'; //to website online path
            // $path1 = explode('backoffice.anacarolinapereira.pt', $_SERVER['DOCUMENT_ROOT'])[0] . 'uploads/servicos/'; //to website online path

            // $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/servicos/'; //to BO local path
            // $path1 = explode('/acp-bo',$_SERVER['DOCUMENT_ROOT'])[0] . '/acp/uploads/servicos/'; //to website local path
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];

            $all_files = count($_FILES['filesServices']['tmp_name']);

            for ($i = 0; $i < $all_files; $i++) {
                $file_name = $_FILES['filesServices']['name'][$i];
                $file_tmp = $_FILES['filesServices']['tmp_name'][$i];
                $file_type = $_FILES['filesServices']['type'][$i];
                $file_size = $_FILES['filesServices']['size'][$i];
                $file_ext = explode('.', $_FILES['filesServices']['name'][$i]);
                $ext = end($file_ext);

                $file = $path . $file_name;
                $file1 = $path1 . $file_name;

                if (!in_array($ext, $extensions)) {
                    $errors[] = 'Extensão de ficheiro não permitida: ' . $file_name . ' ' . $file_type;
                }

                if ($file_size > 2097152) {
                    $errors[] = 'O tamanho do ficheiro excede o limite de 2MB: ' . $file_name . ' ' . $file_type;
                }

                if ($file_size == 0) {
                    $errors[] = 'Não é possível ler o ficheiro: ' . $file_name . ' ' . $file_type;
                }

                if (empty($errors)) {

                    move_uploaded_file($file_tmp, $file);
                    copy($file, $file1);
                    if ($file) {
                        $lastRecordSql = 'SELECT MAX(display_order) 
                        FROM servicos';
                        $lastRecord = execQueryMySQL($lastRecordSql);

                        if ($lastRecord[0] == NULL) {
                            $sql_insert = "INSERT INTO servicos 
                                (title,
                                sigla,
                                img_src,
                                short_description,
                                about,
                                why,
                                sharing,
                                slug,
                                display_order) 
                                VALUES
                                ('" . $title . "',
                                '" . $sigla . "',
                                '" . $file . "',
                                '" . $short_description . "',
                                '" . $about . "',
                                '" . $sharing . "',
                                '" . $why . "',
                                '" . $slug . "',
                                '0');";
                        } else {
                            $sql_insert = "INSERT INTO servicos 
                                (title,
                                sigla,
                                img_src,
                                short_description,
                                about,
                                why,
                                sharing,
                                slug,
                                display_order) 
                                VALUES
                                ('" . $title . "',
                                '" . $sigla . "',
                                '" . $file . "',
                                '" . $short_description . "',
                                '" . $about . "',
                                '" . $sharing . "',
                                '" . $why . "',
                                '" . $slug . "',
                                " . $lastRecord[0]["MAX(display_order)"] . " + 1);";
                        }

                        include_once($_SERVER['DOCUMENT_ROOT'] . '/connection/class.connection.php');
                        $db = Database::getInstance();
                        $connection = $db->getConnection();
                        if ($result = $connection->query($sql_insert)) {
                            $db->commitAndClose();
                            echo "true||" . json_encode($result);
                        } else {
                            // $db->rollbackAndClose();
                            echo "false||Erro na inserção dos dados.";
                        }
                    }
                } else {
                    echo 'false||' . $errors[0];
                }
            }
        } else {
            $lastRecordSql = 'SELECT MAX(display_order) 
                FROM servicos';
            $lastRecord = execQueryMySQL($lastRecordSql);

            if ($lastRecord[0] == NULL) {
                $sql_insert = "INSERT INTO servicos 
                    (title,
                    sigla,
                    short_description,
                    about,
                    why,
                    sharing,
                    slug,
                    display_order) 
                    VALUES
                    ('" . $title . "',
                    '" . $sigla . "',
                    '" . $short_description . "',
                    '" . $about . "',
                    '" . $sharing . "',
                    '" . $why . "',
                    '" . $slug . "',
                    '0');";
            } else {
                $sql_insert = "INSERT INTO servicos 
                    (title,
                    sigla,
                    short_description,
                    about,
                    why,
                    sharing,
                    slug,
                    display_order) 
                    VALUES
                    ('" . $title . "',
                    '" . $sigla . "',
                    '" . $short_description . "',
                    '" . $about . "',
                    '" . $sharing . "',
                    '" . $why . "',
                    '" . $slug . "',
                    " . $lastRecord[0]["MAX(display_order)"] . " + 1);";
            }

            include_once($_SERVER['DOCUMENT_ROOT'] . '/connection/class.connection.php');
            $db = Database::getInstance();
            $connection = $db->getConnection();
            if ($result = $connection->query($sql_insert)) {
                $db->commitAndClose();
                echo "true||" . json_encode($result);
            } else {
                // $db->rollbackAndClose();
                echo "false||Erro na inserção dos dados.";
            }
        }
    } else {
        if (isset($_FILES['filesServices'])) {
            $errors = [];
            $path = $_SERVER['DOCUMENT_ROOT']. '/uploads/servicos/'; //to BO online path
            $path1 = explode('backoffice', $_SERVER['DOCUMENT_ROOT'])[0] . 'httpdocs/uploads/servicos/'; //to website online path
            // $path1 = explode('backoffice.anacarolinapereira.pt', $_SERVER['DOCUMENT_ROOT'])[0] . 'uploads/servicos'; //to website online path

            // $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/servicos/'; //to BO local path
            // $path1 = explode('/acp-bo',$_SERVER['DOCUMENT_ROOT'])[0] . '/acp/uploads/servicos/'; //to website local path
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];

            $all_files = count($_FILES['filesServices']['tmp_name']);

            for ($i = 0; $i < $all_files; $i++) {
                $file_name = $_FILES['filesServices']['name'][$i];
                $file_tmp = $_FILES['filesServices']['tmp_name'][$i];
                $file_type = $_FILES['filesServices']['type'][$i];
                $file_size = $_FILES['filesServices']['size'][$i];
                $file_ext = explode('.', $_FILES['filesServices']['name'][$i]);
                $ext = end($file_ext);

                $file = $path . $file_name;
                $file1 = $path1 . $file_name;

                if (!in_array($ext, $extensions)) {
                    $errors[] = 'Extensão de ficheiro não permitida: ' . $file_name . ' ' . $file_type;
                }

                if ($file_size > 2097152) {
                    $errors[] = 'O tamanho do ficheiro excede o limite de 2MB: ' . $file_name . ' ' . $file_type;
                }

                if ($file_size == 0) {
                    $errors[] = 'Não é possível ler o ficheiro: ' . $file_name . ' ' . $file_type;
                }

                if (empty($errors)) {

                    move_uploaded_file($file_tmp, $file);
                    copy($file, $file1);
                    if ($file) {
                        $sql_update = "UPDATE servicos
                        SET title = '" . $title . "',
                        sigla = '" . $sigla . "',
                        img_src = '" . $file . "',
                        short_description = '" . $short_description . "',
                        about = '" . $about . "',
                        why = '" . $why . "',
                        sharing = '" . $sharing . "',
                        slug = '" . $slug . "'
                        WHERE id = " . $id;

                        include_once($_SERVER['DOCUMENT_ROOT'] . '/connection/class.connection.php');
                        $db = Database::getInstance();
                        $connection = $db->getConnection();

                        if ($result = $connection->query($sql_update)) {
                            $db->commitAndClose();
                            echo "true||" . json_encode($result);
                        } else {
                            // $db->rollbackAndClose();
                            echo "false||Erro na inserção dos dados.";
                        }
                    }
                } else {
                    echo 'false||' . $errors[0];
                }
            }
        } else {
            $sql_update = "UPDATE servicos
            SET title = '" . $title . "',
            sigla = '" . $sigla . "',
            short_description = '" . $short_description . "',
            about = '" . $about . "',
            why = '" . $why . "',
            sharing = '" . $sharing . "',
            slug = '" . $slug . "'
            WHERE id = " . $id;

            include_once($_SERVER['DOCUMENT_ROOT'] . '/connection/class.connection.php');
            $db = Database::getInstance();
            $connection = $db->getConnection();

            if ($result = $connection->query($sql_update)) {
                $db->commitAndClose();
                echo "true||" . json_encode($result);
            } else {
                // $db->rollbackAndClose();
                echo "false||Erro na inserção dos dados.";
            }
        }
    }
}

function deleteServico()
{
    $sql_delete = "DELETE from servicos 
    where id='" . $_REQUEST["id"] . "'";
    execIUQueryMySQL($sql_delete);

    echo "true||Serviço apagado com sucesso.";
}

function saveOrderServicos()
{
    $i = 0;
    foreach ($_REQUEST['servico'] as $value) {
        $sql = "UPDATE servicos SET display_order = $i WHERE id = $value";
        execIUQueryMySQL($sql);
        $i++;
    }
}
