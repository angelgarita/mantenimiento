<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2022 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Site\Model;

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;
use \Joomla\CMS\MVC\Model\FormModel;
use \Joomla\CMS\Object\CMSObject;

class MantenimientoformModel extends FormModel
{
	private $item = null;

	protected function populateState()
	{
		$app = Factory::getApplication('com_mantenimiento');

		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_mantenimiento.edit.mantenimiento.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_mantenimiento.edit.mantenimiento.id', $id);
		}

		$this->setState('mantenimiento.id', $id);

		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
				$this->setState('mantenimiento.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	public function getItem($id = null)
	{
		if ($this->item === null)
		{
			$this->item = false;

			if (empty($id))
			{
				$id = $this->getState('mantenimiento.id');
			}

			$table = $this->getTable();
			$properties = $table->getProperties();
			$this->item = ArrayHelper::toObject($properties, CMSObject::class);

			if ($table !== false && $table->load($id) && !empty($table->id))
			{
				$id   = $table->id;

				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						return $this->item;
					}
				}

				$properties = $table->getProperties(1);
				$this->item = ArrayHelper::toObject($properties, CMSObject::class);

			}
		}

		return $this->item;
	}

	public function getTable($type = 'Mantenimiento', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();

		if (!in_array('alias', $properties))
		{
				return null;
		}

		$table->load(array('alias' => $alias));
		$id = $table->id;


			return $id;

	}

	public function checkin($id = null)
	{
		$id = (!empty($id)) ? $id : (int) $this->getState('mantenimiento.id');

		return true;

	}

	public function checkout($id = null)
	{
		$id = (!empty($id)) ? $id : (int) $this->getState('mantenimiento.id');

		return true;

	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_mantenimiento.mantenimiento', 'mantenimientoform', array(
						'control'   => 'jform',
						'load_data' => $loadData
				)
		);

		if (empty($form))
		{
				return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_mantenimiento.edit.mantenimiento.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if ($data)
		{

            $array = array();

            foreach ((array) $data->estado as $value)
            {
                if (!is_array($value))
                {
                    $array[] = $value;
                }
            }
            if(!empty($array)){

            $data->estado = $array;
            }

            return $data;
		}

		return array();
	}

	public function save($data)
	{
		$id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('mantenimiento.id');
		$state = (!empty($data['state'])) ? 1 : 0;

        if($data['id']==0){
            $db = $this->getDatabase();
            $query = $db->getQuery(true);

            $registros = array($db->quoteName('ultimo') . ' = 0');

            $condiciones = array($db->quoteName('ind_estacion') . ' = ' . $db->quote($data["ind_estacion"]));

            $query->update($db->quoteName('#__mantenimientos'))->set($registros)->where($condiciones);

            $db->setQuery($query);

            $db->execute();
        }

		$table = $this->getTable();

		if(!empty($id))
		{
			$table->load($id);
		}


	try{
			if ($table->save($data) === true)
			{
				return $table->id;
			}
			else
			{
				return false;
			}
		}catch(\Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			return false;
		}

	}

	public function delete($id)
	{
		$user = Factory::getApplication()->getIdentity();

		if (empty($id))
		{
			$id = (int) $this->getState('mantenimiento.id');
		}

		if ($id == 0 || $this->getItem($id) == null)
		{
				throw new \Exception(Text::_('COM_MANTENIMIENTO_ITEM_DOESNT_EXIST'), 404);
		}

		if ($user->authorise('core.delete', 'com_mantenimiento') !== true)
		{
				throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
		}

		$table = $this->getTable();

		if ($table->delete($id) !== true)
		{
				throw new \Exception(Text::_('JERROR_FAILED'), 501);
		}

		return $id;

	}

	public function getCanSave()
	{
		$table = $this->getTable();

		return $table !== false;
	}

}
