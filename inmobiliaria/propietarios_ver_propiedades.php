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
               <li><a href="clientes.php" class="active"><i class="fas fa-users"></i> Clientes</a></li>
                <li><a href="propietarios.php"><i class="fas fa-user-tie"></i> Propietarios</a></li>
                <li><a href="propiedades.php"><i class="fas fa-home"></i> Propiedades</a></li>
                <li><a href="contabilidad.php"><i class="fas fa-file-invoice-dollar"></i> Contabilidad</a></li>

            </ul>
        </nav>
    </header>
    <main>
        
        
    <?php
include 'conexion.php';

try {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $clienteID = (int)$_GET['id'];

        // Verificar si el ClienteID existe
        $sql_check = "SELECT ClienteID FROM clientes WHERE ClienteID = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $clienteID);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            echo '<p>No se encontró un cliente con el ID proporcionado.</p>';
            exit();
        }
        $stmt_check->close();

        // Obtener datos del propietario
        $sql_propietario = "SELECT Nombre, Apellido, DNI, Telefono 
                            FROM clientes 
                            WHERE ClienteID = (SELECT ClienteID FROM clientes WHERE ClienteID = ? LIMIT 1)";
        $stmt_propietario = $conn->prepare($sql_propietario);
        $stmt_propietario->bind_param("i", $clienteID);
        $stmt_propietario->execute();
        $result_propietario = $stmt_propietario->get_result();

        if ($result_propietario->num_rows > 0) {
            $fila_propietario = $result_propietario->fetch_assoc();
            echo '<h1>Datos del Propietario</h1>';
            echo '<table id="clientesTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>' . htmlspecialchars($fila_propietario['Nombre']) . '</td>
                            <td>' . htmlspecialchars($fila_propietario['Apellido']) . '</td>
                            <td>' . htmlspecialchars($fila_propietario['DNI']) . '</td>
                            <td>' . htmlspecialchars($fila_propietario['Telefono']) . '</td>
                        </tr>
                    </tbody>
                  </table>';
        } else {
            echo '<p>No se encontraron datos del propietario.</p>';
        }
        $stmt_propietario->close();

        // Obtener propiedades del cliente
        $sql_propiedades = "SELECT PropiedadID, Direccion, Ciudad, Barrio, Nro, Dominio, NroPartida, Estado 
                            FROM propiedades 
                            WHERE ClienteID = ?";
        $stmt_propiedades = $conn->prepare($sql_propiedades);
        $stmt_propiedades->bind_param("i", $clienteID);
        $stmt_propiedades->execute();
        $result_propiedades = $stmt_propiedades->get_result();

        if ($result_propiedades->num_rows > 0) {
            echo '<h1>Propiedades del Cliente</h1>';
            echo '<table cid="clientesTable">
                    <thead>
                        <tr>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Barrio</th>
                            <th>Nro</th>
                            <th>Dominio</th>
                            <th>Nro Partida</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($fila_propiedad = $result_propiedades->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($fila_propiedad['Direccion']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['Ciudad']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['Barrio']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['Nro']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['Dominio']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['NroPartida']) . '</td>
                        <td>' . htmlspecialchars($fila_propiedad['Estado']) . '</td>
                        <td>
                            <button type="button" class="btn btn-warning editar-propiedad" 
                data-bs-toggle="modal" 
                data-bs-target="#exampleModal" 
                data-id="' . $fila_propiedad['PropiedadID'] . '"
                data-direccion="' . htmlspecialchars($fila_propiedad['Direccion']) . '"
                data-ciudad="' . htmlspecialchars($fila_propiedad['Ciudad']) . '"
                data-barrio="' . htmlspecialchars($fila_propiedad['Barrio']) . '"
                data-nro="' . htmlspecialchars($fila_propiedad['Nro']) . '"
                data-dominio="' . htmlspecialchars($fila_propiedad['Dominio']) . '"
                data-nropartida="' . htmlspecialchars($fila_propiedad['NroPartida']) . '"
                data-estado="' . htmlspecialchars($fila_propiedad['Estado']) . '">
            Editar
        </button>
                        </td>
                      </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>No se encontraron propiedades para este cliente.</p>';
        }
        $stmt_propiedades->close();
    } else {
        echo '<p>Error: El ID del cliente no es válido o no se proporcionó.</p>';
    }
} catch (Exception $e) {
    echo '<p>Ocurrió un error: ' . htmlspecialchars($e->getMessage()) . '</p>';
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
    </main>
    
    <!-- Buscador -->
    
    <script>
        // Función para filtrar la tabla
        function filtrarTabla() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('clientesTable');
            const tr = table.getElementsByTagName('tr');

            // Recorrer todas las filas de la tabla y ocultar las que no coinciden con la búsqueda
            for (let i = 1; i < tr.length; i++) { // Empezar desde 1 para omitir el encabezado
                const tdNombre = tr[i].getElementsByTagName('td')[0];
                const tdDireccion = tr[i].getElementsByTagName('td')[1];
                if (tdNombre || tdDireccion) {
                    const txtValueNombre = tdNombre.textContent || tdNombre.innerText;
                    const txtValueDireccion = tdDireccion.textContent || tdDireccion.innerText;
                    if (txtValueNombre.toUpperCase().indexOf(filter) > -1 || txtValueDireccion.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        // Escuchar el evento input en el campo de búsqueda
        document.getElementById('searchInput').addEventListener('input', filtrarTabla);
    </script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
</body>
</html>

    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Propiedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="propiedadForm" action="actualizar_propiedad.php" method="POST">
                    <input type="hidden" id="propiedadID" name="propiedadID">
                    <div class="mb-3">
                        <label for="barrio">Barrio</label>
                        <input type="text" class="form-control" id="barrio" name="barrio" placeholder="Barrio" required>
                    </div>
                    <div class="mb-3">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                    </div>
                    <div class="mb-3">
                        <label for="nro">Nro</label>
                        <input type="text" class="form-control" id="nro" name="nro" placeholder="Nro">
                    </div>
                    <div class="mb-3">
                        <label for="dominio">Dominio</label>
                        <input type="text" class="form-control" id="dominio" name="dominio" placeholder="Dominio" required>
                    </div>
                    <div class="mb-3">
                        <label for="nroPartida">Nro Partida</label>
                        <input type="text" class="form-control" id="nroPartida" name="nroPartida" placeholder="Nro Partida" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="alquilada">Alquilada</option>
                            <option value="en venta">En Venta</option>
                        </select>
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
                    
                    <script>document.addEventListener('DOMContentLoaded', function () {
    // Capturar el evento de clic en los botones "Editar"
    document.querySelectorAll('.editar-propiedad').forEach(function (button) {
        button.addEventListener('click', function () {
            // Obtener los datos del botón
            const propiedadID = button.getAttribute('data-id');
            const direccion = button.getAttribute('data-direccion');
            const ciudad = button.getAttribute('data-ciudad');
            const barrio = button.getAttribute('data-barrio');
            const nro = button.getAttribute('data-nro');
            const dominio = button.getAttribute('data-dominio');
            const nroPartida = button.getAttribute('data-nropartida');
            const estado = button.getAttribute('data-estado');

            // Cargar los datos en el modal
            document.getElementById('propiedadID').value = propiedadID;
            document.getElementById('direccion').value = direccion;
            document.getElementById('ciudad').value = ciudad;
            document.getElementById('barrio').value = barrio;
            document.getElementById('nro').value = nro;
            document.getElementById('dominio').value = dominio;
            document.getElementById('nroPartida').value = nroPartida;
            document.getElementById('estado').value = estado;

            // Cambiar el título del modal
            document.getElementById('exampleModalLabel').textContent = 'Editar Propiedad';
        });
    });

    // Limpiar el modal cuando se cierre
    document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('propiedadForm').reset(); // Limpiar el formulario
        document.getElementById('exampleModalLabel').textContent = 'Actualizar Propiedad'; // Restaurar el título
    });
});
                    </script>
    <!-- Fin Modal-->
    
    <!-- Buscador -->
    
    <script>
        // Función para filtrar la tabla
        function filtrarTabla() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('clientesTable');
            const tr = table.getElementsByTagName('tr');

            // Recorrer todas las filas de la tabla y ocultar las que no coinciden con la búsqueda
            for (let i = 1; i < tr.length; i++) { // Empezar desde 1 para omitir el encabezado
                const tdNombre = tr[i].getElementsByTagName('td')[0];
                const tdDireccion = tr[i].getElementsByTagName('td')[1];
                if (tdNombre || tdDireccion) {
                    const txtValueNombre = tdNombre.textContent || tdNombre.innerText;
                    const txtValueDireccion = tdDireccion.textContent || tdDireccion.innerText;
                    if (txtValueNombre.toUpperCase().indexOf(filter) > -1 || txtValueDireccion.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        // Escuchar el evento input en el campo de búsqueda
        document.getElementById('searchInput').addEventListener('input', filtrarTabla);
    </script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
</body>
</html>
