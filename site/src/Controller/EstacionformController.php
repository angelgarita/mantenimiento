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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class EstacionformController extends FormController
{
	public function edit($key = NULL, $urlVar = NULL)
	{
		$previousId = (int) $this->app->getUserState('com_mantenimiento.edit.estaciones.id');
		$editId     = $this->input->getInt('id', 0);

		$this->app->setUserState('com_mantenimiento.edit.estaciones.id', $editId);

		$model = $this->getModel('Estacionform', 'Site');

		if ($editId)
		{
			$model->checkout($editId);
		}

		if ($previousId)
		{
			$model->checkin($previousId);
		}

		$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estacionform&layout=edit', false));
	}

	public function save($key = NULL, $urlVar = NULL)
	{

		$this->checkToken();

		$model = $this->getModel('Estacionform', 'Site');

		$data = $this->input->get('jform', array(), 'array');

		$form = $model->getForm();

		if (!$form)
		{
			throw new \Exception($model->getError(), 500);
		}

		$objData = (object) $data;
		$this->app->triggerEvent(
			'onContentNormaliseRequestData',
			array($this->option . '.' . $this->context, $objData, $form)
		);
		$data = (array) $objData;

		$data = $model->validate($form, $data);

		if ($data === false)
		{
			$errors = $model->getErrors();

			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$this->app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$this->app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$jform = $this->input->get('jform', array(), 'ARRAY');

			$this->app->setUserState('com_mantenimiento.edit.estaciones.data', $jform);

			$id = (int) $this->app->getUserState('com_mantenimiento.edit.estaciones.id');
			$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estacionform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		$return = $model->save($data);

		if ($return === false)
		{
			$this->app->setUserState('com_mantenimiento.edit.estaciones.data', $data);

			$id = (int) $this->app->getUserState('com_mantenimiento.edit.estaciones.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_mantenimiento&view=estacionform&layout=edit&id=' . $id, false));
			$this->redirect();
		}

		if ($return)
		{
			$model->checkin($return);
		}

		$this->app->setUserState('com_mantenimiento.edit.estaciones.id', null);

		if (!empty($return))
		{
			$this->setMessage(Text::_('COM_MANTENIMIENTO_ITEM_SAVED_SUCCESSFULLY'));
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_mantenimiento&view=estaciones' : $item->link);
		$this->setRedirect(Route::_($url, false));

		$this->app->setUserState('com_mantenimiento.edit.estaciones.data', null);

		$this->postSaveHook($model, $data);

	}

	public function cancel($key = NULL)
	{
		$editId = (int) $this->app->getUserState('com_mantenimiento.edit.estaciones.id');

		$model = $this->getModel('Estacionform', 'Site');

		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_mantenimiento&view=estaciones' : $item->link);
		$this->setRedirect(Route::_($url, false));
	}

	public function remove()
	{
		$model = $this->getModel('Estacionform', 'Site');
		$pk    = $this->input->getInt('id');

		try
		{
			$return = $model->checkin($return);

			$this->app->setUserState('com_mantenimiento.edit.estaciones.id', null);

			$menu = $this->app->getMenu();
			$item = $menu->getActive();
			$url = (empty($item->link) ? 'index.php?option=com_mantenimiento&view=estaciones' : $item->link);

			if($return)
			{
				$model->delete($pk);
				$this->setMessage(Text::_('COM_MANTENIMIENTO_ITEM_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$this->setMessage(Text::_('COM_MANTENIMIENTO_ITEM_DELETED_UNSUCCESSFULLY'), 'warning');
			}


			$this->setRedirect(Route::_($url, false));

			$this->app->setUserState('com_mantenimiento.edit.estacion.data', null);
		}
		catch (\Exception $e)
		{
			$errorType = ($e->getCode() == '404') ? 'error' : 'warning';
			$this->setMessage($e->getMessage(), $errorType);
			$this->setRedirect('index.php?option=com_mantenimiento&view=estaciones');
		}
	}


    protected function postSaveHook(BaseDatabaseModel $model, $validData = array())
    {
    }
}
