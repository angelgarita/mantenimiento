<?php
\defined('_JEXEC') or die;
use \Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
//Valores configuracion mapa
$db1 = Factory::getContainer()->get('db');

//Valores insertar mapa

$query1 = $db1->getQuery(true);

// Seleccionando las columnas...
$query1->select(
    $db1->quoteName([
        'e.nombre',
        'e.latitud',
        'e.longitud',
        ])
    );

$query1->from(
    $db1->quoteName('#__estaciones', 'e')
);

$db1->setQuery($query1);
$results = $db1->loadRowList();

$xml = new DOMDocument( "1.0", "ISO-8859-15" );
$xml->formatOutput = true;
$xml_kml = $xml->createElement('kml');
$xml_kml->setAttribute("xmlns","http://www.opengis.net/kml/2.2");
$xml_kml->setAttribute('xmlns:gx', 'http://www.google.com/kml/ext/2.2');
$xml_kml->setAttribute('xmlns:kml', 'http://www.opengis.net/kml/2.2');
$xml_kml->setAttribute('xmlns:atom','http://www.w3.org/2005/Atom');
$xml_doc = $xml->createElement('Document');
$xml_namedoc = $xml->createElement('name', 'estaciones.kml');
$xml_doc->appendChild($xml_namedoc);
$xml_style = $xml->createElement('Style');
$xml_style->setAttribute('id','s_ylw-pushpin');
$xml_iconstyle = $xml->createElement('IconStyle');
$xml_scale = $xml->createElement('scale','1.1');
$xml_iconstyle->appendChild($xml_scale);
$xml_icon = $xml->createElement('Icon');
$xml_href = $xml->createElement('href','http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png');
$xml_icon->appendChild($xml_href);
$xml_iconstyle->appendChild($xml_icon);
$xml_style->appendChild($xml_iconstyle);
$xml_doc->appendChild($xml_style);

for ($i = 0; $i < sizeof($results); $i++){
    $xml_marker = $xml->createElement('Placemark');
    $xml_nombre = $xml->createElement('name',$results[$i][0]);
    $xml_marker->appendChild($xml_nombre);
    $xml_styleURL = $xml->createElement('styleUrl','#m_ylw-pushpin');
    $xml_marker->appendChild($xml_styleURL);
    $xml_point = $xml->createElement('Point');
    $coordenadas = $results[$i][2] . ',' . $results[$i][1];
    $xml_coordinates = $xml->createElement('coordinates',$coordenadas);
    $xml_point->appendChild($xml_coordinates);
    $xml_marker->appendChild($xml_point);

    $xml_doc->appendChild($xml_marker);
    $xml_kml->appendChild($xml_doc);
    $xml->appendChild($xml_kml);
}
$xml->save('images/estaciones.kml');

/*$salida = $xml->saveXML();
header_remove('X-Powered-By');

header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition: attachment; filename="estaciones.kml"');
var_dump(headers_list());*/
?>


<div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <strong class="d-inline-block mb-2 text-primary">KML</strong>
          <h3 class="mb-0">Google Earth</h3>
          <p class="card-text mb-auto">Descarga el fichero KML a partir desde los datos de la base de datos de estaciones de la delegación para utilizar con <a href="https://www.google.com/intl/es/earth/versions/" target="_blank"">Google Earth</a></p>
          <p></p>
          <a href="images/estaciones.kml" class="btn btn-primary">Descargar fichero KML</a>
        </div>
        <div class="col-auto d-none d-lg-block">

        <img src="<?= Uri::root() ?>media/com_mantenimiento/google_earth.png" class="float-start rounded" alt="Google Earth">

        </div>

<!--<img src="media/com_mantenimiento/google_earth.png" class="float-start rounded" alt="Google Earth">
<p>cosas a poner</p>
<center> <a href="images/estaciones.kml" class="btn btn-primary">Descargar fichero KML</a></center>-->


