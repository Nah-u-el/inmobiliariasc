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
                Agregar Propiedad
            </button>
            
            <button type="button" class="btn btn-success alta-cliente" data-bs-toggle="modal" data-bs-target="#modalContrato">Agregar Contrato</button>
          
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

$sql = "SELECT * FROM propiedades";

$result = mysqli_query($conn, $sql);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    // Iniciar la tabla HTML
    echo '<table id="clientesTable">
            <thead>
                <tr>
                    <th>Direccion</th>
                    <th>Ciudad</th>
                    <th>Barrio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';

    // Iterar sobre cada fila de resultados
    while ($fila = mysqli_fetch_assoc($result)) {
        // Mostrar cada fila en la tabla
        echo '<tr>
                <td>' . $fila['Direccion'] . '</td>
                <td>' . $fila['Ciudad'] . '</td>
                <td>' . $fila['Barrio'] . '</td>
                <td>
                    <a href="ver_contrato.php?id=' .$fila['PropiedadID'] . '">VER CONTRATO</a>
                    
                    <a href="ver_propiedad.php?id=' . $fila['PropiedadID'] . '">Ver Propiedad</a>
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
                <h5 class="modal-title" id="exampleModalLabel">Agregar Propiedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Propiedad</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                        <br>
                        <form id="clienteForm" action="agregar_propiedad.php" method="POST">
                            <div class="mb-3">
                                <input type="date" class="form-control" id="fecha" name="Fecha" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="barrio" name="Barrio" placeholder="Barrio" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="ciudad" name="Ciudad" placeholder="Ciudad" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="direccion" name="Direccion" placeholder="Dirección" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="nro" name="Nro" placeholder="Nro" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="dominio" name="Dominio" placeholder="Dominio" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="nro_partida" name="NroPartida" placeholder="Nro Partida" required>
                            </div>
                            <div class="mb-3">
                                <label for="estado">Estado de la propiedad</label>
                                <select name="Estado" id="estado">
                                <option value="alquilada">Alquilada</option>
                                <option value="en venta">En Venta</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                            
                        
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
    <!-- Fin Modal-->
    
    <!-- Modal Contrato -->
<div class="modal fade" id="modalContrato" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="guardar_contrato.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo Contrato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- Selección de propiedad -->
        <div class="mb-3">
          <label>Propiedad</label>
          <select name="PropiedadID" class="form-control" required>
            <?php
            include 'conexion.php';
            $propiedades = mysqli_query($conn, "SELECT * FROM propiedades");
            while ($row = mysqli_fetch_assoc($propiedades)) {
              echo "<option value='{$row['PropiedadID']}'>{$row['Direccion']} - {$row['Ciudad']}</option>";
            }
            ?>
          </select>
        </div>
        
        <div class="mb-3">
         <label>Cuota Mensual</label>
          <input type="number" step="0.01" name="CanonMensual" placeholder="Cuota Mensual" class="form-control mb-2" required>
        </div>
        <div class="mb-3">
        <label>Depósito</label>
        <input type="number" step="0.01" name="Deposito" placeholder="Deposito inicial" class="form-control mb-2" required>
        </div>

        <!-- Inquilino -->
        <h6>Inquilino</h6>
        <input type="text" name="InquilinoNombre" placeholder="Nombre" class="form-control mb-2" required>
        <input type="text" name="InquilinoDNI" placeholder="DNI" class="form-control mb-2" required>
        <input type="text" name="InquilinoTelefono" placeholder="Teléfono" class="form-control mb-2">
        <input type="email" name="InquilinoMail" placeholder="Email" class="form-control mb-2">

        <!-- Garantes -->
        <h6>Garante 1</h6>
        <input type="text" name="Garante1Nombre" placeholder="Nombre" class="form-control mb-2" required>
        <input type="text" name="Garante1DNI" placeholder="DNI" class="form-control mb-2" required>

        <h6>Garante 2</h6>
        <input type="text" name="Garante2Nombre" placeholder="Nombre" class="form-control mb-2" required>
        <input type="text" name="Garante2DNI" placeholder="DNI" class="form-control mb-2" required>

        <!-- Fechas del contrato -->
        <div class="row">
          <div class="col">
            <label>Fecha Inicio</label>
            <input type="date" name="FechaInicio" class="form-control" required>
          </div>
          <div class="col">
            <label>Fecha Fin</label>
            <input type="date" name="FechaFin" class="form-control" required>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar Contrato</button>
      </div>
    </form>
  </div>
</div>
    
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
