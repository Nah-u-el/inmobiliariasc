<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $caja_id = $_POST['CajaID'];
    $fecha = $_POST['Fecha'];
    $concepto = $_POST['Concepto'];
    $monto = $_POST['Monto'];
    $formaPago = $_POST['FormaPago'];
    $clienteInmueble = $_POST['ClienteInmueble'];
    $observaciones = $_POST['Observaciones'];

    $sql = "UPDATE caja SET 
                Fecha = ?, 
                Concepto = ?, 
                RecibidoEnviado = ?, 
                FormaPago = ?, 
                ClienteInmueble = ?, 
                Observaciones = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssi", $fecha, $concepto, $monto, $formaPago, $clienteInmueble, $observaciones, $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Movimiento actualizado correctamente');
                window.location.href = 'contabilidad.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al actualizar');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
