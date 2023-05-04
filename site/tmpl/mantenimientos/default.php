<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2022 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */


use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_mantenimiento') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'mantenimientoform.xml');
$canEdit    = $user->authorise('core.edit', 'com_mantenimiento') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'mantenimientoform.xml');
$canCheckin = $user->authorise('core.manage', 'com_mantenimiento');
$canChange  = $user->authorise('core.edit.state', 'com_mantenimiento');
$canDelete  = $user->authorise('core.delete', 'com_mantenimiento');
//$canCreate  = "true";
//$canEdit    = "true";
//$canDelete  = "true";

$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_mantenimiento.list');

//$solouna=FALSE;
$solouna='';
$primer='';
$segundo='';

$cuantos=count($this->items);
if($cuantos > 0){
    $primer=$this->items[0]->nombre;
    if($cuantos > 1){$segundo=$this->items[1]->nombre;}

    if( $primer == $segundo || $cuantos == 1){$solouna=$this->items[0]->nombre; }
}else{
    $solouna='No existen mantenimientos que cumplan las condiciones';
}
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if(!empty($this->filterForm)) { echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); } ?>
	<div class="table-responsive">
		<table class="table table-striped" id="mantenimientoList">
			<thead>
            <tr class="">
                    <h3><?= $solouna ?></h3>
            </tr>
			<tr>
                <?php if( $primer != $segundo  && $cuantos >1):?>
                    <th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'NOMBRE', 'e.nombre', $listDirn, $listOrder); ?>
					</th>
                    <th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'INDICATIVO', 'a.ind_estacion', $listDirn, $listOrder); ?>
					</th>
                <?php endif;?>
					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'FECHA', 'a.fecha', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
						<?php echo HTMLHelper::_('grid.sort',  'TECNICOS', 'a.tecnicos', $listDirn, $listOrder); ?>
					</th>

					<th class=''>
                        ACTUACION
					</th>

					<th class=''>
                        COMENTARIOS
					</th>

						<?php if ($canEdit || $canDelete): ?>
					<th class="center">
						<?php echo Text::_('MODIFICAR'); ?>
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
                <?php if( $primer != $segundo  && $cuantos >1):?>
                    <td>
						<?php //echo $item->nombre;  ?>
                        <a href="<?php echo Route::_('index.php?option=com_mantenimiento&view=mantenimientos&est=' . $item->ind_estacion, false, 2); ?>" class="btn btn-mini" type="button"><?php echo $item->nombre; ?></a>
					</td>
					<td>
						<?php echo $item->ind_estacion; ?>
					</td>
                    <?php endif;?>
					<td>
						<?php echo $item->fecha; ?>
					</td>
					<td>
						<?php echo $item->tecnicos; ?>
					</td>
					<td>
						<?php echo $item->actuacion; ?>
					</td>
					<td>
						<?php echo $item->comentarios; ?>
					</td>
					<?php if ($canEdit): ?>
						<td class="center">
							<a href="<?php echo Route::_('index.php?option=com_mantenimiento&task=mantenimientoform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						</td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_mantenimiento&task=mantenimientoform.edit&id=0', false, 0); ?>"
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
	if($canDelete) {
		$wa->addInlineScript("
			jQuery(document).ready(function () {
				jQuery('.delete-button').click(deleteItem);
			});

			function deleteItem() {

				if (!confirm(\"" . Text::_('COM_MANTENIMIENTO_DELETE_MESSAGE') . "\")) {
					return false;
				}
			}
		", [], [], ["jquery"]);
	}
?>