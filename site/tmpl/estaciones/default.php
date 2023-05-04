<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_mantenimiento.list');
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_mantenimiento') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'estacionform.xml');
$canEdit    = $user->authorise('core.edit', 'com_mantenimiento') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'estacionform.xml');
$canCheckin = $user->authorise('core.manage', 'com_mantenimiento');
$canChange  = $user->authorise('core.edit.state', 'com_mantenimiento');
$canDelete  = $user->authorise('core.delete', 'com_mantenimiento');
//$canCreate  = true;
//$canEdit    = true;
//$canDelete  = true;

?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if(!empty($this->filterForm)) { echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); } ?>
	<div class="table-responsive">
		<table class="table table-striped" id="estacionList">
			<thead>
			<tr>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_NOMBRE', 'a.nombre', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_IND_CLIMATOLOGICO', 'a.ind_climatologico', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_TIPO_ESTACION', 'a.tipo_estacion', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_VARIABLES', 'a.variables', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_TIPO_MANT', 'a.tipo_mant', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'COM_MANTENIMIENTO_ESTACIONES_PROVINCIA', 'a.provincia', $listDirn, $listOrder); ?>
					</th>
                    <?php if ($canEdit || $canDelete): ?>

					<th class="center">
						<?php echo Text::_('Editar/Borrar'); ?>
					</th>
                    <?php endif; ?>

			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
					<div class="pagination">
						<?php echo $this->pagination->getPagesLinks(); ?>
					</div>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) : ?>

				<tr class="row<?php echo $i % 2; ?>">
				    <td>
					<a href="<?php echo Route::_('index.php?option=com_mantenimiento&view=mantenimientos&est=' . $item->ind_climatologico, false, 2); ?>" class="btn btn-mini" type="button" title="Ver mantenimientos"><?php echo $item->nombre; ?></a>
					</td>

					<td>
						<a href="<?php echo Route::_('index.php?option=com_mantenimiento&view=estacion&id='.(int) $item->id); ?>" class="btn btn-mini" type="button" title="Ver todos los datos">
						<?php echo $item->ind_climatologico; ?></a>
					</td>
					<td>
						<?php echo $item->tipo_estacion; ?>
					</td>
					<td>
						<?php echo $item->variables; ?>
					</td>
					<td>
						<?php
                          if($item->tipo_mant ==1){
                            echo "Mensual";
                          }elseif($item->tipo_mant ==2){
                            echo "Trimestral";
                          }elseif($item->tipo_mant ==3){
                            echo "Semestral";
                          }
                         ?>
					</td>
					<td>
						<?php echo $item->provincia; ?>
					</td>
                    <?php if ($canEdit || $canDelete): ?>
                    <td class="center">
                        <a href="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacionform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
                        <a href="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacionform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
                    </td>
                    <?php endif; ?>
				</tr>
			<?php endforeach; ?>

			</tbody>
		</table>
	</div>
    <?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacionform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_MANTENIMIENTO_ADD_ITEM'); ?></a>
        <?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value=""/>
	<input type="hidden" name="filter_order_Dir" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php

		$wa->addInlineScript("
			jQuery(document).ready(function () {
				jQuery('.delete-button').click(deleteItem);
			});

			function deleteItem() {

				if (!confirm(\"" . Text::_('¿Seguro que desea borrar la estación?') . "\")) {
					return false;
				}
			}
		", [], [], ["jquery"]);

?>