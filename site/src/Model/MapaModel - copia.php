<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2022 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
namespace Aemet\Component\Mantenimiento\Site\Model;

use Joomla\CMS\MVC\Model\BaseModel;

defined('_JEXEC') or die;

class MapaModel extends BaseModel
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }
}