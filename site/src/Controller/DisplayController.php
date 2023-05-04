<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Site\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;


class DisplayController extends \Joomla\CMS\MVC\Controller\BaseController
{
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);
	}

	public function display($cachable = false, $urlparams = false)
	{

		$view = $this->input->getCmd('view', '//XXX_DEFAULT_VIEW_XXX');
		$view = $view == "featured" ? '//XXX_DEFAULT_VIEW_XXX' : $view;
		$this->input->set('view', $view);


		parent::display($cachable, $urlparams);
		return $this;
	}
}
