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
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

class MantenimientosModel extends ListModel
{

	public function __construct($config = array())
	{

        if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
                'nombre', 'e.nombre',
				'ind_estacion', 'a.ind_estacion',
				'fecha', 'a.fecha',
				'tecnicos', 'a.tecnicos',
				'actuacion', 'a.actuacion',
				'comentarios', 'a.comentarios',
				'estado', 'a.estado',
				'ultimo', 'a.ultimo',
			);
		}

		parent::__construct($config);
	}


	protected function populateState($ordering = null, $direction = null)
	{

		//parent::populateState("a.id", "ASC");
		parent::populateState("a.fecha", "DESC");

		$app = Factory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$value = $app->getUserState($this->context . '.list.limit', $app->get('list_limit', 25));
		$list['limit'] = $value;

		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		//$ordering  = $this->getUserStateFromRequest($this->context .'.filter_order', 'filter_order', "a.id");
		$ordering  = $this->getUserStateFromRequest($this->context .'.filter_order', 'filter_order', "a.fecha");
		$direction = strtoupper($this->getUserStateFromRequest($this->context .'.filter_order_Dir', 'filter_order_Dir', "DESC"));

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
                $db->quoteName([
                    'e.nombre',
                    'a.id',
                    'a.ind_estacion',
                    'a.fecha',
                    'a.tecnicos',
                    'a.actuacion',
                    'a.comentarios',
                    'a.estado',
                    'a.ultimo'
                    ])
            );

            if(isset($_GET['est'])){
                $estacion=$_GET['est'];
                if(isset($_GET['fecha']) && $_GET['fecha'] !='' ){
                    $desde=$_GET['fecha'];
                    $extrae=explode("-",$desde);
                    $inicio=$extrae[2]."-".$extrae[1]."-".$extrae[0];
                }else{
                    $inicio='2015-01-01';
                }
                $query->from('`#__mantenimientos` AS a');
                $query->join(
                    'INNER',$db->quoteName(
                        '#__estaciones', 'e') . ' ON ' .$db->quoteName('e.ind_climatologico') . ' = ' .$db->quoteName('a.ind_estacion')
                    );
                    $query->where($db->quoteName('e.ind_climatologico') . ' = ' . $db->quote($estacion) . ' AND ' . $db->quoteName('a.fecha') . ' >= ' . $db->quote($inicio));

            }else{
                $query->from('`#__mantenimientos` AS a');
                $query->join(
                    'INNER',$db->quoteName(
                        '#__estaciones', 'e') . ' ON ' .$db->quoteName('e.ind_climatologico') . ' = ' .$db->quoteName('a.ind_estacion')
                    );
                    $query->where($db->quoteName('a.ultimo') . ' = 1' );
            }

			// Filtros
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
					$query->where('( a.ind_estacion LIKE ' . $search . '  OR  a.tecnicos LIKE ' . $search . '  OR  a.actuacion LIKE ' . $search . '  OR  a.comentarios LIKE ' . $search . '   OR  e.nombre LIKE ' . $search . ')');
				}
			}
			// Ordenacion
			$orderCol  = $this->state->get('list.ordering', "a.fecha");
			$orderDirn = $this->state->get('list.direction', "DESC");

			if ($orderCol && $orderDirn)
			{
				$query->order($db->escape($orderCol . ' ' . $orderDirn));
			}

			return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{

				if (!empty($item->estado))
					{
						$item->estado = Text::_('COM_MANTENIMIENTO_MANTENIMIENTOS_ESTADO_OPTION_' . strtoupper($item->estado));
					}
		}

		return $items;
	}

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
