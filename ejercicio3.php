<?php
// Conexiones
$mysqli = new mysqli("localhost", "root", "", "entrekids");

// Consultas SQL
$consulta = $mysqli->query("CREATE TEMPORARY TABLE IF NOT EXISTS historial AS SELECT transaccion.total AS total_sold, proveedor.id AS seller_id, proveedor.nombre AS seller_name, item.id AS idFind, (SELECT IF(EXISTS(SELECT paquete.id FROM paquete WHERE paquete.item_id = idFind), 'producto', 'experiencia')) AS tipo FROM transaccion, item, actividad_evento, actividad, proveedor, paquete, entrada WHERE transaccion.created BETWEEN '2022-08-01' AND '2022-08-31' AND transaccion.id = item.transaccion_id AND item.evento_id = actividad_evento.id AND actividad_evento.actividad_id = actividad.id AND actividad.proveedor_id = proveedor.id GROUP BY transaccion.total");
$consulta2 = $mysqli->query("SELECT SUM(total_sold) as total_sold, seller_id, seller_name, tipo FROM historial GROUP BY seller_id");

// Crear html con los datos de la consulta
$txt = "
    <table>
        <thead>
            <tr> total_sold |</tr>
            <tr> seller_id |</tr>
            <tr> seller_nombre |</tr>
            <tr> tipo</tr>
        </thead>
        <tbody>
";
foreach($consulta2 as $row) {
    $txt .= "<tr>";
    $txt .= "<td>"."|".$row["total_sold"]."</td>";
    $txt .= "<td>"."|".$row["seller_id"]."</td>";
    $txt .= "<td>"."|"."<a href='https://admin.entrekids.cl/proveedor/{$row["seller_id"]}'>".$row["seller_name"]."</a>"."</td>";
    $txt .= "<td>"."|".$row["tipo"]."</td>";
    $txt .= "</tr>";
}
// Cerramos etiquetas de la tabla html
$txt .= "</tbody>";
$txt .= "</table>";

// Guardamos contenido en el nuevo archivo
$archivo = fopen("full-stack-junior.html", "w") or die("error creando archivo!");
fwrite($archivo, $txt);
fclose($archivo);

?>