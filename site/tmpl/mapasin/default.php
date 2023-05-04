<?php
\defined('_JEXEC') or die;

//Valores configuracion mapa
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
//Valores configuracion mapa
//$db = JFactory::getDbo();

$db1 = Factory::getContainer()->get('db');
$query = $db1->getQuery(true);
$query->select(
    $db1->quoteName([
        'zoom',
        'longitud',
        'latitud',
        'alto',
        'ancho'
        ])
    );

   $query->from($db1->quoteName('#__mantenimiento_mapa'));
    $query->where($db1->quoteName('id') . ' =  1');
    $db1->setQuery($query);
    $registro = $db1->loadAssoc();
    //var_dump($registro);
    $zoom = $registro['zoom'];
    $longitud = $registro['longitud'];
    $latitud = $registro['latitud'];
    $ancho = $registro['ancho'];
    $alto = $registro['alto'];

//Valores insertar mapa

    $query1 = $db1->getQuery(true);

    // Seleccionando las columnas...
    $query1->select(
        $db1->quoteName([
            'e.nombre',
            'e.ind_sinoptico',
            'e.ind_climatologico',
            'e.variables',
            'e.tipo_estacion',
            'e.provincia',
            'e.latitud',
            'e.longitud',
            'e.altitud',
            'e.tipo_mant',
            ])
        );

        $query1->from(
            $db1->quoteName('#__estaciones', 'e')
        );

        $db1->setQuery($query1);
        $results = $db1->loadRowList();
        //var_dump($results);
?>
<strong>Mapa Estaciones</strong>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
crossorigin=""/>

<div id="mapid" style="width: <?= $ancho ?>px; height: <?= $alto ?>px;z-index:0"></div>

<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
crossorigin="">
</script>
<script>
var mymap = new L.Map('mapid').setView([<?= $latitud ?>,<?= $longitud ?>], <?= $zoom ?>);
L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
maxZoom: 18
}).addTo(mymap);
var bien = L.icon({
        iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/verde1.png',

        });
<?php

for ($i = 0; $i < sizeof($results); $i++) :
	$marker="marker".$i;
	$contenido="contenido".$i;
	$popup="popup".$i;
    ///new
    $closer="closer".$i;
?>

	var <?= $contenido ?>=`
		<div style=overflow: auto;>
			<div id=infoWindowContent>
				<div style=overflow-x:auto;overflow-y:auto;text-align:center;color:red;margin: 0px;padding: 0px;>
					<b><?= $results[$i][2] ?>  <?= $results[$i][0] ?></b>
				</div>


				<div style=overflow-x:auto;overflow-y:auto;color:black;margin:0px;padding:0;text-align:left>
					<table width=300px border=0 padding=0 margin=0 style=font-size:10px>
                        <tr>
                        <td valign=\"top\">
                        <br>Tipo de Estación : <b><?= $results[$i][4] ?></b>
                        <br>Variables medidas : <b> <?= $results[$i][3] ?></b>
                        <br>Loc/Prov : <b><?= $results[$i][0] ?> / <?= $results[$i][5] ?></b>
                        <br>Lat/Lon : <b><?= $results[$i][6] ?> / <?= $results[$i][7] ?></b>
                        <br>Altitud : <b><?= $results[$i][8] ?>m</b>
                        </td>
                        </tr>
					</table>
				</div>
			</div>
		</div>`;

<?php
	$color="bien";

?>

	<?= $popup ?> = new L.Popup({autoClose:true,closeOnClick: true});

	<?= $popup?>.setContent(<?= $contenido ?>);

	var <?= $marker ?> = L.marker([<?= $results[$i][6] ?>, <?= $results[$i][7] ?>],{icon:<?= $color ?>});

	<?= $marker ?>.addTo(mymap).bindPopup(<?= $popup ?>);

<?php
endfor;
?>
</script>

