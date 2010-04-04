<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */


/**
 * Декоратор для добавления к изображению всплыющего окна Slimbox
 * 
 */
class plgContentMavikThumbnailsDecoratorSlimbox extends plgContentMavikThumbnailsDecorator
{
	/**
	 * Добавление кода в заголовок страницы 
	 */
	function addHeader()
	{
		// Подключить библиотеку slimbox
		$document = &JFactory::getDocument();
		JHTML::_('behavior.mootools');
		if ($this->plugin->linkScripts) {
			$document->addScript(JURI::base().'plugins/content/mavikthumbnails/slimbox/js/slimbox.js');
			$document->addStyleSheet(JURI::base().'plugins/content/mavikthumbnails/slimbox/css/slimbox.css');
		}
		
		if ($this->plugin->zoominCur || $this->plugin->zoominImg) {		
			// Подключить стили плагина к странице
			$document->addStyleSheet(JURI::base() . 'plugins/content/mavikthumbnails/style.php?base='.JURI::base());
		}
	}
	
	/**
	 * Декорирование тега изображения
	 * @return string Декорированый тег изображения
	 */
	function decorate() {
		$img =& $this->plugin->img;
		$title = $img->getAttribute('title');
		if (empty($title) && $img->getAttribute('alt')) {
			$title = $img->getAttribute('alt');
		}
		$title = htmlspecialchars($title); 
		
		$class = 'thumbnail';
		$style = '';
		$zoominImg = '';
		
		if ($this->plugin->zoominImg) {
			$style = $img->getAttribute('style');
			$img->setAttribute('style', '');
			$zoominImg = '<span class="zoomin-img"></span>';
			$class .= ' with-zoomin-img';			 			
		}
		
		if ($this->plugin->zoominCur) {
			$class .= ' zoomin-cur';
		}

		return '<a style="'. $style .'" class="' . $class . '" href="' . $this->plugin->originalSrc . '" rel="lightbox[' . @$this->plugin->article->id. ']" title="' . $title . '" target="_blank">' . $img->toString() . $zoominImg . '</a>';
	}	
	
}
?>