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

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Router\Route;

class EstacionController extends BaseController
{
	public function edit()
	{
		$previousId = (int) $this->app->getUserState('com_mantenimiento.edit.estaciones.id');
		$editId     = $this->input->getInt('id', 0);

		$this->app->setUserState('com_mantenimiento.edit.estaciones.id', $editId);

		$model = $this->getModel('Estacion', 'Site');

		if ($editId)
		{
			$model->checkout($editId);
		}

		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estacionesform&layout=edit', false));
	}

	function publish()
	{
		$user = $this->app->getIdentity();

		if ($user->authorise('core.edit', 'com_mantenimiento') || $user->authorise('core.edit.state', 'com_mantenimiento'))
		{
			$model = $this->getModel('Estacion', 'Site');

			$id    = $this->input->getInt('id');
			$state = $this->input->getInt('state');

			$return = $model->publish($id, $state);

			if ($return === false)
			{
				$this->setMessage(Text::sprintf('Save failed: %s', $model->getError()), 'warning');
			}

			$this->app->setUserState('com_mantenimiento.edit.estaciones.id', null);
			$this->app->setUserState('com_mantenimiento.edit.estaciones.data', null);
			$this->setMessage(Text::_('COM_MANTENIMIENTO_ITEM_SAVED_SUCCESSFULLY'));
			$menu = Factory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estaciones', false));
			}
			else
			{
				$this->setRedirect(Route::_('index.php?Itemid='. $item->id, false));
			}
		}
		else
		{
			throw new \Exception(500);
		}
	}

	public function checkin()
	{
		$this->checkToken('GET');

		$id 	= $this->input->getInt('id', 0);
		$model 	= $this->getModel();
		$item 	= $model->getItem($id);

		$user = $this->app->getIdentity();

		if ($user->authorise('core.manage', 'com_mantenimiento') || $item->checked_out == $user->id) {

			$return = $model->checkin($id);

			if ($return === false)
			{
				$message = Text::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
				$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estaciones' . '&id=' . $id, false), $message, 'error');
				return false;
			}
			else
			{
				$message = Text::_('COM_MANTENIMIENTO_CHECKEDIN_SUCCESSFULLY');
				$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estaciones' . '&id=' . $id, false), $message);
				return true;
			}
		}
		else
		{
			throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}
	}

	public function remove()
	{
		// Checking if the user can remove object
		$user = $this->app->getIdentity();

		if ($user->authorise('core.delete', 'com_mantenimiento'))
		{
			$model = $this->getModel('Estacion', 'Site');

			$id = $this->input->getInt('id', 0);

			$return = $model->delete($id);

			if ($return === false)
			{
				$this->setMessage(Text::sprintf('Delete failed', $model->getError()), 'warning');
			}
			else
			{
				if ($return)
				{
					$model->checkin($return);
				}

				$this->app->setUserState('com_mantenimiento.edit.estaciones.id', null);
				$this->app->setUserState('com_mantenimiento.edit.estaciones.data', null);

				$this->app->enqueueMessage(Text::_('COM_MANTENIMIENTO_ITEM_DELETED_SUCCESSFULLY'), 'success');
				$this->app->redirect(Route::_('index.php?option=com_mantenimiento&view=estaciones', false));
			}

			$menu = Factory::getApplication()->getMenu();
			$item = $menu->getActive();
			$this->setRedirect(Route::_($item->link, false));
		}
		else
		{
			throw new \Exception(500);
		}
	}
}
