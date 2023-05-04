<?php
namespace Aemet\Component\Mantenimiento\Site\Model;

use Joomla\CMS\MVC\Model\FormModel;

\defined('_JEXEC') or die;

class ConsultamantenimientosModel extends FormModel
{
    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_mantenimiento.consultamantenimientos',
            'consultamantenimientos',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
            );
        if(empty($form))
        {
            throw new \Exception('No se ha podido cargar el formulario');
        }
        return $form;
    }
}