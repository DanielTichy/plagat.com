<?php
/**
 * index.php
 *
 * PHP version 5
 *
 * @category   Templates
 * @package    Wafl
 * @subpackage Template
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link       http://www.wafl.ugent.be
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * File for all our extra php code
 */
require_once 'includes' .DS. 'template_config.php';
require_once  JPATH_BASE .DS. 'plugins' .DS. 'system' .DS. 'wafl' .DS. 'services' .DS. 'cssComposer.php';

$url = clone(JURI::getInstance());

//$composer = new CssComposer();

?>
<?php echo '<?xml version="1.0" encoding="utf-8"?' .'>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<head>
	<jdoc:include type="head" />
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/default.css" rel="stylesheet" />
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" />
</head>

<body id="joomla">


    <div id="waflpage">
		<a id="top" name="top"></a>

		<div id="modules">
			<?php
				$modules = getMobileModules();
				if (count($modules) > 0) {
					foreach ($modules as $module) {
						echo '<div class="' . $module->name . '">';
						echo renderModule($module);
						echo '</div>';
					}
				}
			?>
        </div>

		<!-- Back to top link -->
		<div id="backtotoplink">
			<a href="<?php $url->setFragment('top'); echo $url->toString();?>" class="to-additional">Back to Top</a>
		</div>

    </div><!-- end page -->

    <!-- leaving this as default, could come in hand -->
    <?php if ($this->countModules('debug')) : ?>
        <div id="debug">
            <jdoc:include type="modules" name="debug" />
        </div>
    <?php endif; ?>

</body>
</html>
