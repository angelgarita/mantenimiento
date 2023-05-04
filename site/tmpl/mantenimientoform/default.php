<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2022 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Aemet\Component\Mantenimiento\Site\Helper\MantenimientoHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');

$lang = Factory::getLanguage();
$lang->load('com_mantenimiento', JPATH_SITE);

?>

<div class="mantenimiento-edit front-end-edit">

		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('Modificar mantenimiento', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('Añadir mantenimiento'); ?></h1>
		<?php endif; ?>

		<form id="form-mantenimiento"
			  action="<?php echo Route::_('index.php?option=com_mantenimiento&task=mantenimientoform.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'mantenimiento')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'mantenimiento', Text::_('MANTENIMIENTOS', true)); ?>
	<?php echo $this->form->renderField('ind_estacion'); ?>

	<?php echo $this->form->renderField('fecha'); ?>

	<?php echo $this->form->renderField('tecnicos'); ?>

	<?php echo $this->form->renderField('actuacion'); ?>

    <?php echo $this->form->renderField('adendas'); ?>

	<?php echo $this->form->renderField('comentarios'); ?>

	<?php echo $this->form->renderField('estado'); ?>

	<?php echo $this->form->renderField('ultimo'); ?>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<div class="control-group">
				<div class="controls">
                    <button type="submit" class="validate btn btn-primary">
                        <span class="fas fa-check" aria-hidden="true"></span>
                        <?php echo Text::_('JSUBMIT'); ?>
                    </button>

					<a class="btn btn-danger"
					   href="<?php echo Route::_('index.php?option=com_mantenimiento&task=mantenimientoform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
					   <span class="fas fa-times" aria-hidden="true"></span>
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_mantenimiento"/>
			<input type="hidden" name="task"
				   value="mantenimientoform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>

</div>
