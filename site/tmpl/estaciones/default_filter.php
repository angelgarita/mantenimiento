<?php

/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */

defined('JPATH_BASE') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

$data = $displayData;

$data['options'] = !empty($data['options']) ? $data['options'] : array();

$filters       = false;
$filtered      = false;
$search_filter = false;

if (isset($data['view']->filterForm))
{
	$filters = $data['view']->filterForm->getGroup('filter');
}

if ($filters !== false)
{
	$filterFields = array_keys($filters);
	$filled       = false;

	foreach ($filterFields as $filterField)
	{
		$filterField = substr($filterField, 7);
		$filter      = $data['view']->getState('filter.' . $filterField);

		if (!empty($filter))
		{
			$filled = $filter;
		}

		if (!empty($filled))
		{
			$filtered = true;
			break;
		}
	}

	$search_filter = $filters['filter_search'];
	unset($filters['filter_search']);
}

$options = $data['options'];

$customOptions = array(
	'filtersHidden'       => isset($options['filtersHidden']) ? $options['filtersHidden'] : empty($data['view']->activeFilters) && !$filtered,
	'defaultLimit'        => isset($options['defaultLimit']) ? $options['defaultLimit'] : Factory::getApplication()->get('list_limit', 10),
	'searchFieldSelector' => '#filter_search',
	'orderFieldSelector'  => '#list_fullordering'
);

$data['options'] = array_unique(array_merge($customOptions, $data['options']));

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';

HTMLHelper::_('searchtools.form', $formSelector, $data['options']);
?>

<div class="js-stools clearfix">
	<div class="clearfix">
		<div class="js-stools-container-bar">
			<label for="filter_search" class="element-invisible"
				aria-invalid="false"><?php echo Text::_('COM_MANTENIMIENTO_SEARCH_FILTER_SUBMIT'); ?></label>
				<div class="input-group mb-3">
					<?php echo $search_filter->input; ?>
					<div class="input-group-append">
						<button class="btn btn-outline-success hasTooltip" title=""
					data-original-title="<?php echo Text::_('COM_MANTENIMIENTO_SEARCH_FILTER_SUBMIT'); ?>">
							<i class="icon-search"></i>
						</button>
						<?php if ($filters): ?>
						<button type="button" class="btn hasTooltip btn-outline-info dropdown-toggle js-stools-btn-filter" title=""
							data-original-title="<?php echo Text::_('JSEARCH_TOOLS_DESC'); ?>">
							<?php echo Text::_('JSEARCH_TOOLS'); ?>
						</button>
						<?php endif; ?>
						<button type="button" class="btn btn-outline-primary hasTooltip js-stools-btn-clear" title=""
							data-original-title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>"
							onclick="jQuery(this).closest('form').find('input').val('');">
							<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>
						</button>
					</div>
				</div>
		</div>
	</div>

	<div class="js-stools-container-filters hidden-phone clearfix" style="">

		<?php if ($filters) : ?>
			<?php foreach ($filters as $fieldName => $field) : ?>
				<?php if ($fieldName != 'filter_search') : ?>
					<div class="js-stools-field-filter">
						<?php echo $field->renderField(array('hiddenLabel' => true)); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>