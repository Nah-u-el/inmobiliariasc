<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Inmobiliaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
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
                <li><a href="clientes.php"><i class="fas fa-users"></i> Clientes</a></li>
                <li><a href="propietarios.php"><i class="fas fa-user-tie"></i> Propietarios</a></li>
                <li><a href="propiedades.php"><i class="fas fa-home"></i> Propiedades</a></li>
                <li><a href="contabilidad.php" class="active"><i class="fas fa-file-invoice-dollar"></i> Contabilidad</a></li>

            </ul>
        </nav>
    </header>
    <main>
        <div class="main">
            <button type="button" class="btn btn-success alta-cliente" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Nuevo Saldo
            </button>
            <button type="button" class="btn btn-success alta-cliente" onclick="mostrarCajaMensual()">
                Ver Caja Mensual
            </button>
            <br><br>
        </div>
        <?php 
include 'conexion.php';

// Consulta SQL para obtener los datos de la tabla `caja`
$stmt = $conn->prepare("SELECT * FROM caja WHERE DATE(Fecha) = CURDATE()");
$stmt->execute();
$result = $stmt->get_result();

// Consulta para calcular el total recaudado en el día
$stmtTotal = $conn->prepare("SELECT SUM(RecibidoEnviado) AS total_recaudado FROM caja WHERE DATE(Fecha) = CURDATE()");
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalRecaudado = $totalRow['total_recaudado'] ?? 0; // Si no hay datos, el total es 0

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    // Iniciar la tabla HTML
    echo '<table id="clientesTable">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Recibido/Enviado</th>
                        <th>Forma de pago</th>
                        <th>Cliente/Inmueble</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    // Iterar sobre cada fila de resultados
    while ($fila = mysqli_fetch_assoc($result)) {
        // Determine if RecibidoEnviado is positive or negative for the dropdown
        $tipoMovimiento = ($fila['RecibidoEnviado'] >= 0) ? 'recibido' : 'enviado';
        
        echo '<tr>
                        <td>' . $fila['Fecha'] . '</td>
                        <td>' . $fila['Concepto'] . '</td>
                        <td>' . $fila['RecibidoEnviado'] . '</td>
                        <td>' . $fila['FormaPago'] . '</td>
                        <td>' . $fila['ClienteInmueble'] . '</td>
                        <td>' . $fila['Observaciones'] . '</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" 
                                onclick="editarCaja(' . htmlspecialchars(json_encode($fila)) . ')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>';
    }

    // Cerrar la tabla HTML
    echo '</tbody>
    <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total Recaudado:</strong></td>
                        <td><strong>' . '$' . number_format($totalRecaudado, 2) . '</strong></td>
                        <td colspan="4"></td>
                    </tr>
            </tfoot>
                </table>';
} else {
    // Si no hay resultados, mostrar un mensaje
    
    echo '
    <table>
            <tr>
            <th>
    Hoy no se ingresaron nuevos datos 
            </th>
            </tr>
        </table>
            ';
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
    </main>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Caja</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                            <br>
                            <form id="addCajaForm" action="agregar_caja.php" method="POST">
                                <div class="mb-3">
                                    <label for="add_concepto">Concepto</label>
                                    <input type="text" class="form-control" id="add_concepto" name="Concepto" placeholder="Concepto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add_tipo_movimiento">Tipo de Movimiento</label>
                                    <select class="form-control" id="add_tipo_movimiento" name="TipoMovimiento" required>
                                        <option value="recibido">Recibido</option>
                                        <option value="enviado">Enviado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="add_monto">Monto</label>
                                    <input type="number" class="form-control" id="add_monto" name="Monto" placeholder="Monto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add_forma_pago">Forma de Pago</label>
                                    <input type="text" class="form-control" id="add_forma_pago" name="FormaPago" placeholder="Forma de Pago" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add_cliente_inmueble">Cliente/Inmueble</label>
                                    <input type="text" class="form-control" id="add_cliente_inmueble" name="ClienteInmueble" placeholder="Buscar cliente o inmueble" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add_observaciones">Observaciones</label>
                                    <input type="text" class="form-control" id="add_observaciones" name="Observaciones" placeholder="Observaciones" required>
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
        </div>
    </div>

    <div class="modal fade" id="editCajaModal" tabindex="-1" aria-labelledby="editCajaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCajaModalLabel">Editar Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="editCajaForm" action="editar_caja.php" method="POST">
                        <input type="hidden" id="edit_id_caja" name="id_caja">
                        <div class="mb-3">
                            <label for="edit_fecha">Fecha</label>
                            <input type="text" class="form-control" id="edit_fecha" name="Fecha" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="edit_concepto">Concepto</label>
                            <input type="text" class="form-control" id="edit_concepto" name="Concepto" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tipo_movimiento">Tipo de Movimiento</label>
                            <select class="form-control" id="edit_tipo_movimiento" name="TipoMovimiento" required>
                                <option value="recibido">Recibido</option>
                                <option value="enviado">Enviado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_monto">Monto</label>
                            <input type="number" class="form-control" id="edit_monto" name="Monto" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_forma_pago">Forma de Pago</label>
                            <input type="text" class="form-control" id="edit_forma_pago" name="FormaPago" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cliente_inmueble">Cliente/Inmueble</label>
                            <input type="text" class="form-control" id="edit_cliente_inmueble" name="ClienteInmueble" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_observaciones">Observaciones</label>
                            <input type="text" class="form-control" id="edit_observaciones" name="Observaciones" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Adjust monto based on tipo_movimiento for "Nuevo Saldo" form
    document.getElementById('add_tipo_movimiento').addEventListener('change', function() {
        adjustAddMonto();
    });

    document.getElementById('add_monto').addEventListener('input', function() {
        adjustAddMonto();
    });

    function adjustAddMonto() {
        const tipoMovimiento = document.getElementById('add_tipo_movimiento').value;
        const montoInput = document.getElementById('add_monto');
        let monto = parseFloat(montoInput.value) || 0;

        if (tipoMovimiento === 'enviado') {
            montoInput.value = -Math.abs(monto);
        } else {
            montoInput.value = Math.abs(monto);
        }
    }

    // Adjust monto based on tipo_movimiento for "Editar Saldo" form
    document.getElementById('edit_tipo_movimiento').addEventListener('change', function() {
        adjustEditMonto();
    });

    document.getElementById('edit_monto').addEventListener('input', function() {
        adjustEditMonto();
    });

    function adjustEditMonto() {
        const tipoMovimiento = document.getElementById('edit_tipo_movimiento').value;
        const montoInput = document.getElementById('edit_monto');
        let monto = parseFloat(montoInput.value) || 0;

        if (tipoMovimiento === 'enviado') {
            montoInput.value = -Math.abs(monto);
        } else {
            montoInput.value = Math.abs(monto);
        }
    }

    // Function to open the edit modal and populate data
    function editarCaja(cajaData) {
        document.getElementById('edit_id_caja').value = cajaData.ID;
        document.getElementById('edit_fecha').value = cajaData.Fecha;
        document.getElementById('edit_concepto').value = cajaData.Concepto;
        document.getElementById('edit_monto').value = cajaData.RecibidoEnviado;
        document.getElementById('edit_forma_pago').value = cajaData.FormaPago;
        document.getElementById('edit_cliente_inmueble').value = cajaData.ClienteInmueble;
        document.getElementById('edit_observaciones').value = cajaData.Observaciones;

        // Set the correct selected option for TipoMovimiento
        if (cajaData.RecibidoEnviado < 0) {
            document.getElementById('edit_tipo_movimiento').value = 'enviado';
        } else {
            document.getElementById('edit_tipo_movimiento').value = 'recibido';
        }
        
        // Ensure the monto is displayed correctly based on the selected type
        adjustEditMonto();

        var editModal = new bootstrap.Modal(document.getElementById('editCajaModal'));
        editModal.show();
    }

    function mostrarCajaMensual() {
        window.location.href = 'caja_mensual.php';
    }

    // Handle form submission for adding new entry (original functionality)
    document.getElementById('addCajaForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        fetch("agregar_caja.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.includes("Registro agregado correctamente")) {
                alert("Saldo agregado correctamente");
                document.getElementById("addCajaForm").reset();
                $('#exampleModal').modal('hide');
                location.reload();
            } else {
                alert("Error al agregar saldo. Verifica los datos.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error en la solicitud. Inténtalo de nuevo.");
        });
    });

    // Handle form submission for editing an entry
    document.getElementById('editCajaForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        fetch("editar_caja.php", { // This will be your new PHP file
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.includes("Registro actualizado correctamente")) {
                alert("Saldo actualizado correctamente");
                $('#editCajaModal').modal('hide');
                location.reload(); // Reload the page to see updated data
            } else {
                alert("Error al actualizar saldo. Verifica los datos.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error en la solicitud. Inténtalo de nuevo.");
        });
    });

    </script>
    
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>

</body>
</html>