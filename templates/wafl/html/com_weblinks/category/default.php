<?php // @version $Id: default.php 8796 2007-09-09 15:46:34Z jinx $
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1 class="componentheading">
	<?php echo $this->category->title; ?>
</h1>
<?php endif; ?>


<div class="weblinks">

	<?php if ( $this->category->image || $this->category->description) : ?>
	<div class="contentdescription">

		<?php if ($this->category->image) :
		  echo "<div id='category_image'>";
		  echo $this->category->image;
		  echo "</div>";
		endif; ?>

	    <div id='category_description'>
	       <?php echo $this->category->description; ?>
        </div>

		<?php if ($this->category->image) : ?>
		  <div class="wrap_image">&nbsp;</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<?php echo $this->loadTemplate('items'); ?>

</div>
