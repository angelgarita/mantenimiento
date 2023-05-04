<?php
\defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
//Valores configuracion mapa
//$db = JFactory::getDbo();
$db = Factory::getContainer()->get('db');
//$query = $db->getQuery(true);
$query = $db->getquery(true)
->select(
    $db->quoteName([
        'zoom',
        'longitud',
        'latitud',
        'alto',
        'ancho'
        ])
    )

   ->from($db->quoteName('#__mantenimiento_mapa'))
   ->where($db->quoteName('id') . ' =  1');
    $db->setQuery($query);
    $registro = $db->loadAssoc();
    //var_dump($registro);
    $zoom = $registro['zoom'];
    $longitud = $registro['longitud'];
    $latitud = $registro['latitud'];
    $ancho = $registro['ancho'];
    $alto = $registro['alto'];

//Valores insertar mapa

    $query1 = $db->getquery(true)

    // Seleccionando las columnas...
    ->select(
        $db->quoteName([
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
            'm.actuacion',
            'm.comentarios',
            'm.fecha',
            'm.tecnicos',
            'm.estado',
            'm.ultimo'
            ])
        )

        ->from(
            $db->quoteName('#__estaciones', 'e')
        )
        ->join(
            'INNER',$db->quoteName(
                '#__mantenimientos', 'm') . ' ON ' .$db->quoteName('e.ind_climatologico') . ' = ' .$db->quoteName('m.ind_estacion')
            )
        ->where(
            $db->quoteName('m.ultimo') . ' LIKE ' . $db->quote(1)
        );
        $db->setQuery($query1);
        $results = $db->loadRowList();
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

var averia = L.icon({
iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/rojo1.png',
});
var observacion= L.icon({
iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/amarillo1.png',
});
var bien = L.icon({
iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/verde1.png',

});
var mantenimiento = L.icon({
iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/rAmarillo.png',
});
var mantenimientoPasado = L.icon({
iconUrl: '<?= Uri::root() ?>media/com_mantenimiento/rRojo.png',
});
<?php

for ($i = 0; $i < sizeof($results)-1; $i++) :
	$marker="marker".$i;
	$contenido="contenido".$i;
	$popup="popup".$i;
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
                        <td valign=\"top\">Último mantenimiento : <b> <?= $results[$i][12] ?></b>
                        <br>Tipo de Estación : <b><?= $results[$i][4] ?></b>
                        <br>Variables medidas : <b> <?= $results[$i][3] ?></b>
                        <br>Loc/Prov : <b><?= $results[$i][0] ?> / <?= $results[$i][5] ?></b>
                        <br>Lat/Lon : <b><?= $results[$i][6] ?> / <?= $results[$i][7] ?></b>
                        <br>Altitud : <b><?= $results[$i][8] ?>m</b>
                        <br>Actuación : <b><?= $results[$i][10] ?></b>
                        <br>Observaciones : <b><?= $results[$i][11] ?></b>
                        <br>Técnico/s : <b><?= $results[$i][13] ?></b>
                        </td>
                        </tr>
					</table>
				</div>
			</div>
		</div>`;

<?php
	if ($results[$i][14]=="R") {
	    $color="averia";
	} else if ($results[$i][14]=="A") {
	    $color="observacion";
	} else {
	    $color="bien";
	}

	$fecha1=strtotime(date("Y-m-d"));
	$fecha2=strtotime($results[$i][12]);
	$dif=($fecha1-$fecha2);
	$dif=$dif/60/60/24/7;
	$distancia1=1000;
	$distancia2=1000;

	if ($results[$i][9]==1){
		$distancia1=9;
		$distancia2=13;
	} elseif ($results[$i][9]==2){
		$distancia1=22;
		$distancia2=26;
	} elseif ($results[$i][9]==3){
		$distancia1=48;
		$distancia2=52;
	}

	if ($dif>$distancia1 && $dif<$distancia2){
		$color="mantenimiento";
	} elseif ($dif>=$distancia2){
		$color="mantenimientoPasado";
	}
?>

	<?= $popup ?> = new L.Popup({autoClose:true,closeOnClick: true});

	<?= $popup?>.setContent(<?= $contenido ?>);

	var <?= $marker ?> = L.marker([<?= $results[$i][6] ?>, <?= $results[$i][7] ?>],{icon:<?= $color ?>});

	<?= $marker ?>.addTo(mymap).bindPopup(<?= $popup ?>);

<?php
endfor;
?>
</script>

