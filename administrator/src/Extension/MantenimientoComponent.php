<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Mantenimiento
 * @author     Andres Segovia <angarita@mundo-r.com>
 * @copyright  2022 Andres Segovia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Aemet\Component\Mantenimiento\Administrator\Service\Html\MANTENIMIENTO;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Association\AssociationServiceTrait;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Tag\TagServiceTrait;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;

/**
 * Component class for Mantenimiento
 *
 * @since  1.0.0
 */
class MantenimientoComponent extends MVCComponent implements RouterServiceInterface, BootableExtensionInterface, CategoryServiceInterface
{
	use AssociationServiceTrait;
	use RouterServiceTrait;
	use HTMLRegistryAwareTrait;
	use CategoryServiceTrait, TagServiceTrait {
		CategoryServiceTrait::getTableNameForSection insteadof TagServiceTrait;
		CategoryServiceTrait::getStateColumnForSection insteadof TagServiceTrait;
	}

	/** @inheritdoc  */
	public function boot(ContainerInterface $container)
	{
		$db = $container->get('DatabaseDriver');
		$this->getRegistry()->register('mantenimiento', new MANTENIMIENTO($db));
	}

	
/**
 * Returns the table for the count items functions for the given section.
	 *
	 * @param   string    The section
	 *
	 * * @return  string|null
	 *
	 * @since   4.0.0
	 */
	    protected function getTableNameForSection(string $section = null)            
	{
	}
}