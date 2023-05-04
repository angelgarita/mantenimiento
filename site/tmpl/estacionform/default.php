<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Mantenimiento
 * @author     Andres Segovia <angarita@mundo-r.com>
 * @copyright  2022 Andres Segovia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Aemet\Component\Mantenimiento\Site\Helper\MantenimientoHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_mantenimiento', JPATH_SITE);

$user    = Factory::getApplication()->getIdentity();
$canEdit = MantenimientoHelper::canUserEdit($this->item, $user);


?>

<div class="estacion-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
		<?php throw new \Exception(Text::_('COM_MANTENIMIENTO_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_MANTENIMIENTO_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_MANTENIMIENTO_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-estacion"
			  action="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacionform.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'estacion')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'estacion', Text::_('COM_MANTENIMIENTO_TAB_ESTACION', true)); ?>
	<?php echo $this->form->renderField('nombre'); ?>

	<?php echo $this->form->renderField('ind_sinoptico'); ?>

	<?php echo $this->form->renderField('ind_climatologico'); ?>

	<?php echo $this->form->renderField('tipo_estacion'); ?>

	<?php echo $this->form->renderField('variables'); ?>

	<?php echo $this->form->renderField('tipo_mant'); ?>

	<?php echo $this->form->renderField('provincia'); ?>

	<?php echo $this->form->renderField('latitud'); ?>

	<?php echo $this->form->renderField('longitud'); ?>

	<?php echo $this->form->renderField('altitud'); ?>

	<?php echo $this->form->renderField('geografica'); ?>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<span class="fas fa-check" aria-hidden="true"></span>
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn btn-danger"
					   href="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacionform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
					   <span class="fas fa-times" aria-hidden="true"></span>
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_mantenimiento"/>
			<input type="hidden" name="task"
				   value="estacionform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
