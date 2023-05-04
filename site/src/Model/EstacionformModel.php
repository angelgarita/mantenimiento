<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
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


class EstacionformModel extends FormModel
{
	private $item = null;

	protected function populateState()
	{
		$app = Factory::getApplication('com_mantenimiento');

		if (Factory::getApplication()->input->get('layout') == 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_mantenimiento.edit.estaciones.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_mantenimiento.edit.estaciones.id', $id);
		}

		$this->setState('estaciones.id', $id);

		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
				$this->setState('estaciones.id', $params_array['item_id']);
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
				$id = $this->getState('estaciones.id');
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

	public function getTable($type = 'Estacion', $prefix = 'Administrator', $config = array())
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
		return true;

	}

	public function checkout($id = null)
	{

		return true;

	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_mantenimiento.estacion', 'estacionform', array(
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
		$data = Factory::getApplication()->getUserState('com_mantenimiento.edit.estaciones.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if ($data)
		{


			return $data;
		}

		return array();
	}

	public function save($data)
	{
		$id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('estaciones.id');

        $estacion = $data['ind_climatologico'];
        $hoy = date("Y-m-d");
        if($data['id']==0){
            $db = $this->getDatabase();
            $query = $db->getQuery(true);
            //Pongo a 0 el campo ultimo de los anteriores
            $columnas = array('ind_estacion','fecha','tecnicos','actuacion','comentarios','estado','ultimo');

            $valores = array($db->quote($estacion),$db->quote($hoy), $db->quote('Inicio'),$db->quote('Inicio'),$db->quote('Inicio'),$db->quote('V'),'1');

            $query
                ->insert($db->quoteName('#__mantenimientos'))
                ->columns($db->quoteName($columnas))
                ->values(implode(',', $valores));

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
		if (empty($id))
		{
			$id = (int) $this->getState('estacion.id');
		}

		if ($id == 0 || $this->getItem($id) == null)
		{
				throw new \Exception(Text::_('COM_MANTENIMIENTO_ITEM_DOESNT_EXIST'), 404);
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
