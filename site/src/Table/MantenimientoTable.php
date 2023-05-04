<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2022 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Administrator\Table;
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Table\Table as Table;
use \Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use \Joomla\Database\DatabaseDriver;
use \Joomla\Registry\Registry;



class MantenimientoTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;

	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_mantenimiento.mantenimientoform';
		parent::__construct('#__mantenimientos', 'id', $db);
		$this->setColumnAlias('published', 'state');

	}


	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	public function bind($array, $ignore = '')
	{
		$date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
		$user = Factory::getApplication()->getIdentity();


		if (isset($array['estado']))
		{
			if (is_array($array['estado']))
			{
				$array['estado'] = implode(',',$array['estado']);
			}
			elseif (strpos($array['estado'], ',') != false)
			{
				$array['estado'] = explode(',',$array['estado']);
			}
			elseif (strlen($array['estado']) == 0)
			{
				$array['estado'] = '';
			}
		}
		else
		{
			$array['estado'] = '';
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!$user->authorise('core.admin', 'com_mantenimiento.mantenimiento.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_mantenimiento/access.xml',
				"/access/section[@name='mantenimiento']/"
			);
			$default_actions = Access::getAssetRules('com_mantenimiento.mantenimiento.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				if (key_exists($action->name, $default_actions))
				{
					$array_jaccess[$action->name] = $default_actions[$action->name];
				}
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}


		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = true)
	{
		return parent::store($updateNulls);
	}

	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	public function check()
	{
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}



		return parent::check();
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return $this->typeAlias . '.' . (int) $this->$k;
	}

	protected function _getAssetParentId($table = null, $id = null)
	{
		$assetParent = Table::getInstance('Asset');

		$assetParentId = $assetParent->getRootId();

		$assetParent->loadByName('com_mantenimiento');

		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

    public function delete($pk = null)
    {
        $this->load($pk);
        $result = parent::delete($pk);

        return $result;
    }
}
