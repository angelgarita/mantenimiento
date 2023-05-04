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
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_mantenimiento');

if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_mantenimiento'))
{
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">


		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_NOMBRE'); ?></th>
			<td><?php echo $this->item->nombre; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_IND_SINOPTICO'); ?></th>
			<td><?php echo $this->item->ind_sinoptico; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_IND_CLIMATOLOGICO'); ?></th>
			<td><?php echo $this->item->ind_climatologico; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_TIPO_ESTACION'); ?></th>
			<td><?php echo $this->item->tipo_estacion; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_VARIABLES'); ?></th>
			<?php
				$options      = explode(',',$this->item->variables);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					if (!empty($option))
					{
					$options_text[] = Text::_('COM_MANTENIMIENTO_MANTENIMIENTOS_VARIABLES_OPTION_' . strtoupper($option));
					}
				}

				$this->item->variables = !empty($options_text) ? implode('<br >', $options_text) : $this->item->variables;
			?>
			<td><?php echo $this->item->variables; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_TIPO_MANT'); ?></th>
			<td>
            <?php
                if($this->item->tipo_mant ==1){
                    echo "Mensual";
                  }elseif($this->item->tipo_mant ==2){
                    echo "Trimestral";
                  }elseif($this->item->tipo_mant ==3){
                    echo "Semestral";
                  }

             ?>
             </td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_PROVINCIA'); ?></th>
			<td><?php echo $this->item->provincia; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_LATITUD'); ?></th>
			<td><?php echo $this->item->latitud; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_LONGITUD'); ?></th>
			<td><?php echo $this->item->longitud; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_ALTITUD'); ?></th>
			<td><?php echo $this->item->altitud; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_MANTENIMIENTO_FORM_LBL_ESTACION_GEOGRAFICA'); ?></th>
			<td><?php echo $this->item->geografica; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit): ?>

	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_mantenimiento&task=estacion.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_MANTENIMIENTO_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (Factory::getApplication()->getIdentity()->authorise('core.delete','com_mantenimiento.estacion.'.$this->item->id)) : ?>

	<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
		<?php echo Text::_("COM_MANTENIMIENTO_DELETE_ITEM"); ?>
	</a>

	<?php echo HTMLHelper::_(
                                    'bootstrap.renderModal',
                                    'deleteModal',
                                    array(
                                        'title'  => Text::_('COM_MANTENIMIENTO_DELETE_ITEM'),
                                        'height' => '50%',
                                        'width'  => '20%',

                                        'modalWidth'  => '50',
                                        'bodyHeight'  => '100',
                                        'footer' => '<button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button><a href="' . Route::_('index.php?option=com_mantenimiento&task=estacion.remove&id=' . $this->item->id, false, 2) .'" class="btn btn-danger">' . Text::_('COM_MANTENIMIENTO_DELETE_ITEM') .'</a>'
                                    ),
                                    Text::sprintf('COM_MANTENIMIENTO_DELETE_CONFIRM', $this->item->id)
                                ); ?>

<?php endif; ?>