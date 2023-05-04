<?php
namespace Aemet\Component\Mantenimiento\Site\Table;

use Joomla\CMS\Table\Table;

\defined('_JEXEC') or die;

class ConsultamantenimientosTable extends Table
{
    public function __construct($db)
    {
        parent::__construct('#__mantenimientos','id',$db);
    }
}