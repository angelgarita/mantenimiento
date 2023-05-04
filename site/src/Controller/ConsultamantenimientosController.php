<?php
namespace Aemet\Component\Mantenimiento\Site\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

\defined('_JEXEC') or die;

class ConsultamantenimientosController extends FormController
{
    protected $view_list = 'consultamantenimientos'; //nombre de la vista
    protected $view_item = 'consultamantenimientos';
    public function dameData()
    {
        $input = $this->app->input;

        $data = $input->get('jform', [], 'array');

        $fecha=$data['fecha'];
        $est=$data['ind_estacion'];
        $dire="index.php?option=com_mantenimiento&view=mantenimientos&est=$est&fecha=$fecha";
        $URL=Route::_($dire,false);

        $this->setRedirect($URL);
    }

}