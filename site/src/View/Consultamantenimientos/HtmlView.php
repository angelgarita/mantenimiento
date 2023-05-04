<?php
namespace Aemet\Component\Mantenimiento\Site\View\Consultamantenimientos;

use Joomla\CMS\MVC\View\HtmlView as JView;

\defined('_JEXEC') or die;


class HtmlView extends JView
{
    public $form;
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        parent::display($tpl);
    }
}
