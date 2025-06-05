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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" defer></script>
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
            <button type="button" class="btn btn-success alta-cliente" onclick="volverCaja()">
                Volver
            </button>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Seleccionar Mes y Año
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="mesesDropdown">
                    <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                </ul>
            </div>
            <br><br>
        </div>
        <?php
        include 'conexion.php'; // Incluye la conexión a la base de datos

        // Obtener el mes y año desde la URL
        $mes = $_GET['mes'] ?? date('m'); // Mes actual si no se especifica
        $año = $_GET['año'] ?? date('Y'); // Año actual si no se especifica

        // Consulta SQL para obtener los datos mensuales
        $stmt = $conn->prepare("SELECT * FROM caja WHERE MONTH(Fecha) = ? AND YEAR(Fecha) = ?");
        $stmt->bind_param("ii", $mes, $año);
        $stmt->execute();
        $result = $stmt->get_result();

        // Consulta para calcular el total mensual
        $stmtTotal = $conn->prepare("SELECT SUM(RecibidoEnviado) AS total_mensual FROM caja WHERE MONTH(Fecha) = ? AND YEAR(Fecha) = ?");
        $stmtTotal->bind_param("ii", $mes, $año);
        $stmtTotal->execute();
        $totalResult = $stmtTotal->get_result();
        $totalRow = $totalResult->fetch_assoc();
        $totalMensual = $totalRow['total_mensual'] ?? 0; // Si no hay datos, el total es 0

        // Verificar si hay resultados
        if (mysqli_num_rows($result) > 0) {
            // Iniciar la tabla HTML
            setlocale(LC_TIME, 'es_ES.UTF-8', 'spanish'); // Configurar el idioma a español
            echo '<h3>Detalles de ' . strftime("%B %Y", mktime(0, 0, 0, $mes, 1, $año)) . '</h3>';
            echo '<table id="cliente_id">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Recibido/Enviado</th>
                            <th>Forma de pago</th>
                            <th>Cliente/Inmueble</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            // Iterar sobre cada fila de resultados
            while ($fila = mysqli_fetch_assoc($result)) {
                // Mostrar cada fila en la tabla
                echo '<tr>
                        <td>' . $fila['Fecha'] . '</td>
                        <td>' . $fila['Concepto'] . '</td>
                        <td>' . $fila['RecibidoEnviado'] . '</td>
                        <td>' . $fila['FormaPago'] . '</td>
                        <td>' . $fila['ClienteInmueble'] . '</td>
                        <td>' . $fila['Observaciones'] . '</td>
                      </tr>';
            }

            // Cerrar la tabla HTML y mostrar el total mensual
            echo '</tbody>
                  <tfoot>
                      <tr>
                          <td colspan="2"><strong>Total Mensual:</strong></td>
                          <td><strong>' . '$' . number_format($totalMensual, 2) . '</strong></td>
                          <td colspan="3"></td>
                      </tr>
                  </tfoot>
                </table>';
        } else {
            // Si no hay resultados, mostrar un mensaje
            echo '<p>No hay datos para el mes seleccionado.</p>';
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conn);
        ?>
    </main>
    <script>
        function volverCaja() {
            window.location.href = 'contabilidad.php';
        }
    </script>

    <script>
       document.addEventListener("DOMContentLoaded", function() {
    fetch('obtener_meses_con_datos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data); // <-- Agrega esto para ver qué datos llegan

            const dropdown = document.getElementById('mesesDropdown');
            dropdown.innerHTML = '';

            if (data.length === 0) {
                dropdown.innerHTML = '<li><a class="dropdown-item" href="#">No hay datos disponibles</a></li>';
                return;
            }

            data.forEach(item => {
                const año = item.año;
                const mes = item.mes;
                const nombreMes = new Date(año, mes - 1).toLocaleString('es-ES', { month: 'long' });
                const totalMensual = item.total_mensual;

                const li = document.createElement('li');
                const a = document.createElement('a');
                a.classList.add('dropdown-item');
                a.href = `caja_mensual.php?mes=${mes}&año=${año}`;
                a.textContent = `${nombreMes} ${año} (Total: $${parseFloat(totalMensual).toFixed(2)})`;

                li.appendChild(a);
                dropdown.appendChild(li);
            });
        })
        .catch(error => {
            console.error("Error al cargar los meses:", error);
            alert("Error al cargar los meses y años.");
        });
});

    </script>

    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
</body>
</html>