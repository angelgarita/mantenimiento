<?php
namespace Aemet\Component\Mantenimiento\Site\View\Mapasin;
\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
	public function display($tpl = null)
	{

        //$this->items = $this->get('Items');
		return parent::display($tpl);
	}
}