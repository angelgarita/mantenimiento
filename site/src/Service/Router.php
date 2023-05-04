<?php

/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

namespace Aemet\Component\Mantenimiento\Site\Service;

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\CMS\Menu\AbstractMenu;

/**
 * Class MantenimientoRouter
 *
 */
class Router extends RouterView
{
	private $noIDs;
	/**
	 * The category factory
	 *
	 * @var    CategoryFactoryInterface
	 *
	 * @since  1.0.0
	 */
	private $categoryFactory;

	/**
	 * The category cache
	 *
	 * @var    array
	 *
	 * @since  1.0.0
	 */
	private $categoryCache = [];

	public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
	{
		$params = Factory::getApplication()->getParams('com_mantenimiento');
		$this->noIDs = (bool) $params->get('sef_ids');
		$this->categoryFactory = $categoryFactory;


			$estaciones = new RouterViewConfiguration('estaciones');
			$this->registerView($estaciones);
            $consultamantenimientos = new RouterViewConfiguration('consultamantenimientos');
			$this->registerView($consultamantenimientos);
            $mantenimientos = new RouterViewConfiguration('mantenimientos');
			$this->registerView($mantenimientos);
			$estacion = new RouterViewConfiguration('estacion');
			$estacion->setKey('id')->setParent($estacion);
			$this->registerView($estacion);
			$estacionesform = new RouterViewConfiguration('estacionesform');
			$estacionesform->setKey('id');
			$this->registerView($estacionesform);
            $mapa = new RouterViewConfiguration('mapa');
            $mapa->setKey('id');
            $this->registerView($mapa);
            $mapasin = new RouterViewConfiguration('mapasin');
            $mapasin->setKey('id');
            $this->registerView($mapasin);
            $kml = new RouterViewConfiguration('kml');
            $kml->setKey('id');
            $this->registerView($kml);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}



		/**
		 * Method to get the segment(s) for an estaciones
		 *
		 * @param   string  $id     ID of the estaciones to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getEstacionesSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an estacionesform
			 *
			 * @param   string  $id     ID of the estacionesform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getEstacionesformSegment($id, $query)
			{
				return $this->getEstacionesSegment($id, $query);
			}


		/**
		 * Method to get the segment(s) for an estaciones
		 *
		 * @param   string  $segment  Segment of the estaciones to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getEstacionesId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an estacionesform
			 *
			 * @param   string  $segment  Segment of the estacionesform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getEstacionesformId($segment, $query)
			{
				return $this->getEstacionesId($segment, $query);
			}

	/**
	 * Method to get categories from cache
	 *
	 * @param   array  $options   The options for retrieving categories
	 *
	 * @return  CategoryInterface  The object containing categories
	 *
	 * @since   1.0.0
	 */
	private function getCategories(array $options = []): CategoryInterface
	{
		$key = serialize($options);

		if (!isset($this->categoryCache[$key]))
		{
			$this->categoryCache[$key] = $this->categoryFactory->createCategory($options);
		}

		return $this->categoryCache[$key];
	}
}
