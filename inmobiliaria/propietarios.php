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
            <button type="button" class="btn btn-success alta-cliente" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Nuevo Propietario
            </button>
          
          <input type="text" id="searchInput" placeholder="Buscar" class="search">
          <!-- VER LOS CLIENTES DESACTIVADOS
            <button type="button" class="btn btn-success alta-cliente" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <a href="clientes.php?mostrar=inactivos">Ver Clientes Inactivos</a>
            </button>
-->  
        </div>
        <?php 
include 'conexion.php';

// Consulta SQL para obtener los datos de la tabla `clientes`

if (isset($_GET['mostrar']) && $_GET['mostrar'] == 'inactivos') {
    $sql = "SELECT * FROM clientes WHERE estado = 'inactivo'";
} else {
    $sql = "SELECT * FROM clientes WHERE estado = 'activo'";
}
$result = mysqli_query($conn, $sql);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    // Iniciar la tabla HTML
    echo '<table id="clientesTable">
            <thead>
                <tr>
                    <th>Nombre y Apellido</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';

    // Iterar sobre cada fila de resultados
    while ($fila = mysqli_fetch_assoc($result)) {
        // Mostrar cada fila en la tabla
        echo '<tr>
                <td>' . $fila['Nombre'] . ' ' . $fila['Apellido'] . '</td>
                <td>' . $fila['Direccion'] . '</td>
                <td>
                <button type="button" class="btn btn-success alta-cliente" data-bs-target="#exampleModal" title="VER PROPIEDADES">
                    <a href="propietarios_ver_propiedades.php?id='. htmlspecialchars($fila['ClienteID'], ENT_QUOTES, 'UTF-8') . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20 7.093l-3-3v-2.093h3v5.093zm4 5.907h-3v10h-18v-10h-3l12-12 12 12zm-10 2h-4v6h4v-6z"/></svg></a>
                </button>
                
                <button type="button" class="btn btn-success alta-cliente" data-bs-target="#exampleModal" title="VER INQUILINOS">    
                    <a href="ver_inquilinos.php?id=' . htmlspecialchars($fila['ClienteID'], ENT_QUOTES, 'UTF-8') . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10.118 16.064c2.293-.529 4.428-.993 3.394-2.945-3.146-5.942-.834-9.119 2.488-9.119 3.388 0 5.644 3.299 2.488 9.119-1.065 1.964 1.149 2.427 3.394 2.945 1.986.459 2.118 1.43 2.118 3.111l-.003.825h-15.994c0-2.196-.176-3.407 2.115-3.936zm-10.116 3.936h6.001c-.028-6.542 2.995-3.697 2.995-8.901 0-2.009-1.311-3.099-2.998-3.099-2.492 0-4.226 2.383-1.866 6.839.775 1.464-.825 1.812-2.545 2.209-1.49.344-1.589 1.072-1.589 2.333l.002.619z"/></svg></a>
                </button>
                </td>
                
              </tr>';
    }

    // Cerrar la tabla HTML
    echo '</tbody>
        </table>';
} else {
    // Si no hay resultados, mostrar un mensaje
    echo 'No se encontraron clientes.';
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cliente y Garantes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Cliente</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                        <br>
                        <form id="clienteForm" action="agregar_clientes.php" method="POST">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="fecha" name="Fecha" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nombre" name="Nombre" placeholder="Nombre" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="apellido" name="Apellido" placeholder="Apellido" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="direccion" name="Direccion" placeholder="Dirección" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="dni" name="DNI" placeholder="DNI" required minlength="8" maxlength="8" pattern="\d{8}" title="Debe ingresar exactamente 8 números">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="direccion_personal" name="DireccionPersonal" placeholder="Dirección Personal" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="telefono" name="Telefono" placeholder="Teléfono" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="mail" name="Mail" placeholder="Correo Electrónico" required>
                            </div>
                            
                        
                    </div>
                    
                    <!-- Formulario garante 1 -->
                    
                    
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Garante 1</button>
                    </li>
                </ul>
                
                <br>    
                <div class="mb-3">
                 <input type="date" class="form-control" name="garante1_fecha" placeholder="Fecha Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_nombre" placeholder="Nombre Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_apellido" placeholder="Apellido Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_direccion" placeholder="Direccion Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_dni" placeholder="DNI Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_direccion_personal" placeholder="Direccion Personal Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante1_telefono" placeholder="Telefono Garante ">
                </div>
                <div class="mb-3">
                  <input type="text" class="form-control" name="garante1_mail" placeholder="Mail Garante ">
                </div>
                    
                     <!-- Agregar garante2 -->
                    
                    
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Garante 2</button>
                    </li>
                </ul>
                
                <br>
                
                <div class="mb-3">
                 <input type="date" class="form-control" name="garante2_fecha" placeholder="Fecha Garante">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_nombre" placeholder="Nombre Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_apellido" placeholder="Apellido Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_direccion" placeholder="Direccion Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_dni" placeholder="DNI Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_direccion_personal" placeholder="Direccion Personal Garante ">
                </div>
                <div class="mb-3">
                 <input type="text" class="form-control" name="garante2_telefono" placeholder="Telefono Garante ">
                </div>
                <div class="mb-3">
                  <input type="text" class="form-control" name="garante2_mail" placeholder="Mail Garante ">
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
