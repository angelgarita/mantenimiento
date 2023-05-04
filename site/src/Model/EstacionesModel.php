<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Site\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class EstacionesModel extends ListModel
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'nombre', 'a.nombre',
				'ind_sinoptico', 'a.ind_sinoptico',
				'ind_climatologico', 'a.ind_climatologico',
				'tipo_estacion', 'a.tipo_estacion',
				'variables', 'a.variables',
				'tipo_mant', 'a.tipo_mant',
				'provincia', 'a.provincia',
				'latitud', 'a.latitud',
				'longitud', 'a.longitud',
				'altitud', 'a.altitud',
				'geografica', 'a.geografica',
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.id', 'ASC');

		$app = Factory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$value = $app->getUserState($this->context . '.list.limit', $app->get('list_limit', 25));
		$list['limit'] = $value;

		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		$ordering  = $this->getUserStateFromRequest($this->context .'.filter_order', 'filter_order', 'a.id');
		$direction = strtoupper($this->getUserStateFromRequest($this->context .'.filter_order_Dir', 'filter_order_Dir', 'ASC'));

		if(!empty($ordering) || !empty($direction))
		{
			$list['fullordering'] = $ordering . ' ' . $direction;
		}

		$app->setUserState($this->context . '.list', $list);



		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		if (!empty($context))
		{
			$parts = FieldsHelper::extract($context);

			if ($parts)
			{
				$this->setState('filter.component', $parts[0]);
				$this->setState('filter.section', $parts[1]);
			}
		}
	}

	protected function getListQuery()
	{
			//$db    = $this->getDbo();
            $db    = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->select(
						$this->getState(
								'list.select', 'DISTINCT a.*'
						)
				);

			$query->from('`#__estaciones` AS a');
			//$query->order($db->quotename('a.nombre') .' DESC');

			$search = $this->getState('filter.search');

			if (!empty($search))
			{
				if (stripos($search, 'id:') === 0)
				{
					$query->where('a.id = ' . (int) substr($search, 3));
				}
				else
				{
					$search = $db->Quote('%' . $db->escape($search, true) . '%');
					$query->where('( a.nombre LIKE ' . $search . '  OR  a.ind_sinoptico LIKE ' . $search . '  OR  a.ind_climatologico LIKE ' . $search . '  OR  a.tipo_estacion LIKE ' . $search . '  OR  a.tipo_mant LIKE ' . $search . '  OR  a.provincia LIKE ' . $search . ' )');
				}
			}

			$orderCol  = $this->state->get('list.ordering', 'a.id');
			$orderDirn = $this->state->get('list.direction', 'ASC');

			if ($orderCol && $orderDirn)
			{
				$query->order($db->escape($orderCol . ' ' . $orderDirn));
				//$query->order($db->quotename($orderCol) . ' ' . $orderDirn);
			}
			//echo $query;
			return $query;
	}

    public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{

				// Get the title of every option selected.
				$options      = explode(',',$item->variables);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					if (!empty($option))
					{
					$options_text[] = Text::_('COM_MANTENIMIENTO_MANTENIMIENTOS_VARIABLES_OPTION_' . strtoupper($option));
					}
				}

				$item->variables = !empty($options_text) ? implode('<br >', $options_text) : $item->variables;
		}
		return $items;
	}
	/*public function getItems()
	{
		$items = parent::getItems();


		return $items;
	}*/

	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_MANTENIMIENTO_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
