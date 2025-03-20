<?php

session_start();
if (isset($_SESSION['mensaje'])) {
    echo "<script>alert('" . $_SESSION['mensaje'] . "');</script>";
    unset($_SESSION['mensaje']); // Elimina el mensaje después de mostrarlo
}


// ver_clientes.php

include 'conexion.php';

// Obtener el ID del cliente desde la URL
if (isset($_GET['id'])) {
    $clienteID = $_GET['id'];

    // Consulta SQL para obtener los detalles del cliente
    $sql = "SELECT * FROM clientes WHERE ClienteID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $clienteID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontró el cliente
    if (mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result);
    } else {
        echo "Cliente no encontrado.";
        exit;
    }

    // Cerrar la consulta preparada
    mysqli_stmt_close($stmt);
} else {
    echo "ID de cliente no proporcionado.";
    exit;
}


$sql_garantes = "SELECT g.* FROM garantes g WHERE g.ClienteID = ?";
$stmt_garantes = mysqli_prepare($conn, $sql_garantes);
mysqli_stmt_bind_param($stmt_garantes, "i", $clienteID);
mysqli_stmt_execute($stmt_garantes);
$result_garantes = mysqli_stmt_get_result($stmt_garantes);

// Verificar si hay garantes
$garantes = [];
if (mysqli_num_rows($result_garantes) > 0) {
    while ($garante = mysqli_fetch_assoc($result_garantes)) {
        $garantes[] = $garante;
    }
}

// Cerrar la consulta preparada de garantes
mysqli_stmt_close($stmt_garantes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Inmobiliaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
</head>
<body>

    <header>
        <div class="header-content">
            <div class="dropdown">
                <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/>
                    </svg>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">📆 Pagos del Mes</a></li>
                </ul>
            </button>
            </div>

            <img src="logo2.png" alt="Logo Inmobiliaria" class="logo">

            <div>
                <div class="dropdown">
                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        👤
                        🔔
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">🔑 Cambiar Clave</a></li>
                    <li><a class="dropdown-item" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/>
                        </svg> Cerrar sesion</a></li>
                </ul>
                    </button>
            </div>
        </div>
    </div>
        <nav>
            <ul>
                <li><a href="clientes.php">👥 Clientes</a></li>
                <li><a href="propietarios.php">👤 Propietarios</a></li>
                <li><a href="propiedades.php">🏢 Propiedades</a></li>
                <li><a href="contabilidad.php">💲 Contabilidad</a></li>

            </ul>
        </nav>
    </header>
    <main>
        <div class="main">
            
            <a href="clientes.php">Volver</a>
            
            <button type="button" class="btn btn-success alta-cliente" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Editar Cliente/Garante
            </button>
           
        </div>
         
         <br>
       
        <table>
            <thead>
                <tr>
                    <th>Fecha Ingreso</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>DNI</th>
                    <th>Direccion Personal</th>
                    <th>Telefono</th>
                    <th>Mail</th>
                  
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $cliente['Fecha']; ?></td>
                    <td><?php echo $cliente['Nombre']; ?></td>
                    <td><?php echo $cliente['Apellido']; ?></td>
                    <td><?php echo $cliente['Direccion']; ?></td>
                    <td><?php echo $cliente['DNI']; ?></td>
                    <td><?php echo $cliente['DireccionPersonal']; ?></td>
                    <td><?php echo $cliente['Telefono']; ?></td>
                    <td><?php echo $cliente['Mail']; ?></td>
                    <td>
                    <a href="desactivar_cliente.php?id=<?php echo $cliente['ClienteID']; ?>"
                    onclick="return confirm('¿Seguro que quieres Borrar este cliente?');">Borrar</a><br>
                    <a href="">Generar Factura</a></td>
                </tr>
               
            </tbody>
        </table>
        
        <h1>Garantes</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Fecha Ingreso</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Dirección</th>
                    <th>DNI</th>
                    <th>Direccion Personal</th>
                    <th>Telefono</th>
                    <th>Mail</th>
                    
                    
                </tr>
            </thead>
            <tbody>
            
            <?php if (!empty($garantes)): ?>
                    
                    <?php foreach ($garantes as $garante): ?>
                    
                <tr>
                            
                    <td><?php echo $garante['Fecha']; ?></td>
                    <td><?php echo $garante['Nombre']; ?></td>
                    <td><?php echo $garante['Apellido']; ?></td>
                    <td><?php echo $garante['Direccion']; ?></td>
                    <td><?php echo $garante['DNI']; ?></td>
                    <td><?php echo $garante['DireccionPersonal']; ?></td>
                    <td><?php echo $garante['Telefono']; ?></td>
                    <td><?php echo $garante['Mail']; ?></td>
                            
                        <?php endforeach; ?>
                    
                <?php else: ?>
                    No hay garantes asociados.
                <?php endif; ?>
            
                    
                   
                </tr>
               
            </tbody>
        </table>
        
    </main>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Cliente y Garantes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Cliente</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="garante1-tab" data-bs-toggle="tab" data-bs-target="#garante1" type="button" role="tab" aria-controls="garante1" aria-selected="false">Garante 1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="garante2-tab" data-bs-toggle="tab" data-bs-target="#garante2" type="button" role="tab" aria-controls="garante2" aria-selected="false">Garante 2</button>
                    </li>
                </ul>

                <form id="clienteForm" action="actualizar_cliente.php" method="POST">
                    <div class="tab-content" id="myTabContent">
                        <!-- Formulario del Cliente -->
                        <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                            <br>
                            <input type="hidden" name="ClienteID" value="<?php echo $cliente['ClienteID']; ?>">
                            <div class="mb-3">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="Nombre" value="<?php echo $cliente['Nombre']; ?>" required>
                            </div>
                            <div class="mb-3">
                            <label for="">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="Apellido" value="<?php echo $cliente['Apellido']; ?>" placeholder="Apellido" required>
                            </div>
                            <div class="mb-3">
                            <label for="">Direccion</label>
                                <input type="text" class="form-control" id="direccion" name="Direccion" value="<?php echo $cliente['Direccion']; ?>" placeholder="Dirección" required>
                            </div>
                            <div class="mb-3">
                            <label for="">DNI</label>
                                <input type="text" class="form-control" id="dni" name="DNI" value="<?php echo $cliente['DNI']; ?>" placeholder="DNI" required minlength="8" maxlength="8" pattern="\d{8}" title="Debe ingresar exactamente 8 números">
                            </div>
                            <div class="mb-3">
                            <label for="">Direccion Personal</label>
                                <input type="text" class="form-control" id="direccion_personal" name="DireccionPersonal" value="<?php echo $cliente['DireccionPersonal']; ?>" placeholder="Dirección Personal" required>
                            </div>
                            <div class="mb-3">
                            <label for="">Telefono</label>
                                <input type="tel" class="form-control" id="telefono" name="Telefono" value="<?php echo $cliente['Telefono']; ?>" placeholder="Teléfono" required>
                            </div>
                            <div class="mb-3">
                            <label for="">Correo Electronico</label>
                                <input type="email" class="form-control" id="mail" name="Mail" value="<?php echo $cliente['Mail']; ?>" placeholder="Correo Electrónico" required>
                            </div>
                        </div>

                        <!-- Formulario del Garante 1 -->
                        <div class="tab-pane fade" id="garante1" role="tabpanel" aria-labelledby="garante1-tab">
                            <br>
                            <?php if (isset($garantes[0])): ?>
                                <input type="hidden" name="GaranteID1" value="<?php echo $garantes[0]['GaranteID']; ?>">
                                <div class="mb-3">
                                <label for="">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_garante1" name="NombreGarante1" value="<?php echo $garantes[0]['Nombre']; ?>" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Apellido</label>
                                    <input type="text" class="form-control" id="apellido_garante1" name="ApellidoGarante1" value="<?php echo $garantes[0]['Apellido']; ?>" placeholder="Apellido" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Direccion</label>
                                    <input type="text" class="form-control" id="direccion_garante1" name="DireccionGarante1" value="<?php echo $garantes[0]['Direccion']; ?>" placeholder="Dirección" required>
                                </div>
                                <div class="mb-3">
                                <label for="">DNI</label>
                                    <input type="text" class="form-control" id="dni_garante1" name="DNIGarante1" value="<?php echo $garantes[0]['DNI']; ?>" placeholder="DNI" required minlength="8" maxlength="8" pattern="\d{8}" title="Debe ingresar exactamente 8 números">
                                </div>
                                <div class="mb-3">
                                <label for="">Direccion Personal</label>
                                    <input type="text" class="form-control" id="direccion_personal_garante1" name="DireccionPersonalGarante1" value="<?php echo $garantes[0]['DireccionPersonal']; ?>" placeholder="Dirección Personal" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Telefono</label>
                                    <input type="tel" class="form-control" id="telefono_garante1" name="TelefonoGarante1" value="<?php echo $garantes[0]['Telefono']; ?>" placeholder="Teléfono" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Correo Electronico</label>
                                    <input type="email" class="form-control" id="mail_garante1" name="MailGarante1" value="<?php echo $garantes[0]['Mail']; ?>" placeholder="Correo Electrónico" required>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Formulario del Garante 2 -->
                        <div class="tab-pane fade" id="garante2" role="tabpanel" aria-labelledby="garante2-tab">
                            <br>
                            <?php if (isset($garantes[1])): ?>
                                <input type="hidden" name="GaranteID2" value="<?php echo $garantes[1]['GaranteID']; ?>">
                                <div class="mb-3">
                                <label for="">Nombre</label>
                                    <input type="text" class="form-control" id="nombre_garante2" name="NombreGarante2" value="<?php echo $garantes[1]['Nombre']; ?>" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Apellido</label>
                                    <input type="text" class="form-control" id="apellido_garante2" name="ApellidoGarante2" value="<?php echo $garantes[1]['Apellido']; ?>" placeholder="Apellido" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Direccion</label>
                                    <input type="text" class="form-control" id="direccion_garante2" name="DireccionGarante2" value="<?php echo $garantes[1]['Direccion']; ?>" placeholder="Dirección" required>
                                </div>
                                <div class="mb-3">
                                <label for="">DNI</label>
                                    <input type="text" class="form-control" id="dni_garante2" name="DNIGarante2" value="<?php echo $garantes[1]['DNI']; ?>" placeholder="DNI" required minlength="8" maxlength="8" pattern="\d{8}" title="Debe ingresar exactamente 8 números">
                                </div>
                                <div class="mb-3">
                                <label for="">Direccion Personal</label>
                                    <input type="text" class="form-control" id="direccion_personal_garante2" name="DireccionPersonalGarante2" value="<?php echo $garantes[1]['DireccionPersonal']; ?>" placeholder="Dirección Personal" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Telefono</label>
                                    <input type="tel" class="form-control" id="telefono_garante2" name="TelefonoGarante2" value="<?php echo $garantes[1]['Telefono']; ?>" placeholder="Teléfono" required>
                                </div>
                                <div class="mb-3">
                                <label for="">Correo Electronico</label>
                                    <input type="email" class="form-control" id="mail_garante2" name="MailGarante2" value="<?php echo $garantes[1]['Mail']; ?>" placeholder="Correo Electrónico" required>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        // Activar la pestaña del Cliente al abrir el modal
        document.getElementById('exampleModal').addEventListener('show.bs.modal', function () {
            const clienteTab = new bootstrap.Tab(document.getElementById('cliente-tab'));
            clienteTab.show();
        });
    
        // Validar el formulario del Cliente antes de habilitar las otras pestañas
        document.addEventListener('DOMContentLoaded', function () {
            const clienteForm = document.querySelector('#cliente form');
            const garante1Tab = document.getElementById('garante1-tab');
            const garante2Tab = document.getElementById('garante2-tab');
    
            clienteForm.addEventListener('input', function () {
                if (clienteForm.checkValidity()) {
                    garante1Tab.classList.remove('disabled');
                    garante2Tab.classList.remove('disabled');
                } else {
                    garante1Tab.classList.add('disabled');
                    garante2Tab.classList.add('disabled');
                }
            });
        });
    
       
    </script>
    <!-- Fin Modal-->>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
</body>
</html>
