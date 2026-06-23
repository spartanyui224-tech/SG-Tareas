<?php
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$titulo_val = isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : '';
$descripcion_val = isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : '';
$fecha_val = isset($_POST['fecha']) ? $_POST['fecha'] : '2026-06-11';
$prioridad_val = isset($_POST['prioridad']) ? $_POST['prioridad'] : 'alta';

$esta_completado = ($accion === 'completado') ? true : false;

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Tarea</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
       {
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body{
            background:#e9e9ee;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            min-height:100vh;
            padding-top: 20px;
        }

        .container{
            width:430px;
            background:#f5f5f5;
            min-height:90vh;
            border-radius:40px; 
            box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* HEADER */
        .header{
            background:#6738e5;
            color:white;
            height:90px;
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            border-bottom-left-radius:25px;
            border-bottom-right-radius:25px;
        }

        .header h1{
            font-size:2rem;
            font-weight:400;
        }

        .back-btn{
            position:absolute;
            left:25px;
            background:none;
            border:none;
            color:white;
            font-size:2rem;
            cursor:pointer;
        }

        .form-container{
            padding:40px 35px;
            flex-grow: 1;
        }

        .title-section{
            display:flex;
            align-items:center;
            gap:15px;
            margin-bottom:40px;
        }

        .title-section label{
            font-size:1.8rem;
            font-weight:500;
            color: #333;
        }

        .title-section input{
            flex:1;
            border:none;
            border-bottom:2px solid #999;
            background:transparent;
            font-size:1.3rem;
            outline:none;
            padding: 5px;
        }

        /* DESCRIPCION */
        .description-section{
            margin-bottom:40px;
        }

        .description-section label{
            display:block;
            font-size:1.4rem;
            font-weight:500;
            color:#333;
            margin-bottom:15px;
        }

        .description-section textarea{
            width:100%;
            height:130px;
            border:2px solid #d0d0d0;
            border-radius:15px;
            resize:none;
            padding:15px;
            font-size:1rem;
            line-height: 40px; 
            background:
                repeating-linear-gradient(
                    white,
                    white 39px,
                    #9bb0d0 40px,
                    white 41px
                );
            outline: none;
        }

        /* CAMPOS */
        .field-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:25px;
        }

        .field-row label{
            font-size:1.3rem;
            font-weight:500;
            color:#333;
            width:140px;
        }

        .field-row input,
        .field-row select{
            width:210px;
            height:45px;
            border:2px solid #d8d8d8;
            border-radius:18px;
            padding:0 15px;
            font-size:1rem;
            color:#667;
            background:white;
            outline:none;
        }

        select[disabled] {
            opacity: 1;
            color: #667;
            background-color: #fff;
        }

        .status-completed-row {
            display: none; /* Controlado dinámicamente más abajo */
            align-items: center;
            justify-content: space-between;
            margin-top: 25px;
            padding: 10px 15px;
            background: #e8f5e9;
            border-radius: 15px;
            border: 1px solid #c8e6c9;
        }

        .status-completed-row span {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2e7d32;
        }

        .status-completed-row i {
            font-size: 1.4rem;
            color: #2e7d32;
        }

        /* BOTONES DE ACCIÓN INFERIORES */
        .action-buttons {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 20px 10px 40px 10px;
            background: #f5f5f5;
            border-top: 1px solid #e0e0e0;
        }

        .btn-action {
            background: none;
            border: none;
            color: #757575;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            transition: color 0.2s;
        }

        .btn-action i {
            font-size: 1.5rem;
        }

        .btn-action.edit:hover { color: #2196F3; }
        .btn-action.delete:hover { color: #F44336; }
        .btn-action.complete:hover { color: #4CAF50; }

        /* MODAL / VENTANA EMERGENTE DE ELIMINAR */
        .modal-overlay {
            display: none; 
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            width: 320px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .modal-box h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: #333;
        }

        .modal-close-x {
            position: absolute;
            top: 15px; right: 15px;
            background: none; border: none;
            font-size: 1.2rem; cursor: pointer; color: #888;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-around;
            gap: 15px;
        }

        .btn-modal {
            padding: 10px 30px;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-modal.yes { background: #F44336; color: white; }
        .btn-modal.no { background: #e0e0e0; color: #333; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <button class="back-btn" onclick="window.history.back();">&lt;</button>
        <h1>Detalles</h1>
    </div>

    <form id="form-tarea" action="" method="POST" class="form-container">
        
        <div class="title-section">
            <label for="titulo">Titulo</label>
            <input type="text" id="titulo" name="titulo" placeholder=\'" _ "\' value="' . $titulo_val . '" readonly>
        </div>

        <div class="description-section">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" readonly>' . $descripcion_val . '</textarea>
        </div>

        <div class="field-row">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="' . $fecha_val . '" readonly>
        </div>

        <div class="field-row">
            <label for="prioridad">Prioridad:</label>
            <select id="prioridad" disabled>
                <option value="alta" ' . ($prioridad_val == 'alta' ? 'selected' : '') . '>Alta</option>
                <option value="media" ' . ($prioridad_val == 'media' ? 'selected' : '') . '>Media</option>
                <option value="baja" ' . ($prioridad_val == 'baja' ? 'selected' : '') . '>Baja</option>
            </select>
            <input type="hidden" id="prioridad-oculta" name="prioridad" value="' . $prioridad_val . '">
        </div>

        <div id="status-completado" class="status-completed-row" style="display: ' . ($esta_completado ? 'flex' : 'none') . ';">
            <span>Completada</span>
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="action-buttons">
            <button type="button" id="btn-editar" class="btn-action edit" onclick="activarEdicion()">
                <i class="fa-solid fa-pen"></i>
                <span>Editar</span>
            </button>
            
            <button type="button" class="btn-action delete" onclick="mostrarModalEliminar()">
                <i class="fa-solid fa-trash"></i>
                <span>Eliminar</span>
            </button>
            
            <button type="submit" name="accion" value="completado" class="btn-action complete">
                <i class="fa-solid fa-circle-check"></i>
                <span>Acompletado</span>
            </button>
        </div>
        
        <input type="hidden" id="input-accion-oculto" name="accion" value="">
    </form>
</div>

<div id="modal-eliminar" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close-x" onclick="cerrarModalEliminar()">&times;</button>
        <h3>¿Eliminar?</h3>
        <div class="modal-buttons">
            <button class="btn-modal yes" onclick="confirmarEliminacion()">Sí</button>
            <button class="btn-modal no" onclick="cerrarModalEliminar()">No</button>
        </div>
    </div>
</div>

<script>
    // Lógica interactiva de Edición
    function activarEdicion() {
        document.getElementById(\'titulo\').removeAttribute(\'readonly\');
        document.getElementById(\'descripcion\').removeAttribute(\'readonly\');
        document.getElementById(\'fecha\').removeAttribute(\'readonly\');
        document.getElementById(\'prioridad\').removeAttribute(\'disabled\');
        
        const btnEditar = document.getElementById(\'btn-editar\');
        btnEditar.innerHTML = \'<i class="fa-solid fa-floppy-disk"></i><span>Guardar</span>\';
        btnEditar.setAttribute(\'onclick\', \'guardarCambios()\');
        btnEditar.style.color = \'#4CAF50\';
    }

    function guardarCambios() {
        document.getElementById(\'prioridad-oculta\').value = document.getElementById(\'prioridad\').value;
        document.getElementById(\'input-accion-oculto\').value = \'editar\';
        document.getElementById(\'form-tarea\').submit();
    }

    function mostrarModalEliminar() {
        document.getElementById(\'modal-eliminar\').style.display = \'flex\';
    }

    function cerrarModalEliminar() {
        document.getElementById(\'modal-eliminar\').style.display = \'none\';
    }

    function confirmarEliminacion() {
        document.getElementById(\'prioridad-oculta\').value = document.getElementById(\'prioridad\').value;
        document.getElementById(\'input-accion-oculto\').value = \'eliminar\';
        document.getElementById(\'form-tarea\').submit();
    }
</script>

</body>
</html>';
?>
