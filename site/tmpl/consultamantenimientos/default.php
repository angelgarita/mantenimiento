<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
?>

<strong>Consultar histórico mantenimientos</strong>

<form
method="POST"
name="consultaMantenimiento"
id="adminForm"
action="<?php echo Route::_('index.php?option=com_mantenimiento&layout=mantenimientos&task=consultamantenimientos.dameData');?> "
>

<?php echo $this->form->renderFieldset('cm'); ?>

<?php echo HTMLHelper::_('form.token'); ?>
<br/>
<button class="btn btn-primary" type="submit">Consultar Mantenimiento</button>
</form>



