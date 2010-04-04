<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */

/**
 * Декоратор для добавления к изображению всплыющего окна Highslide
 * 
 */
class plgContentMavikThumbnailsDecoratorHighslide extends plgContentMavikThumbnailsDecorator
{
	/**
	 * Добавление кода в заголовок страницы 
	 */
	function addHeader()
	{
		// Подключить библиотеку slimbox
		$document = &JFactory::getDocument();
		if ($this->plugin->linkScripts) {
			$document->addScript(JURI::base().'plugins/content/mavikthumbnails/highslide/highslide-with-gallery.packed.js');
			$document->addStyleSheet(JURI::base().'plugins/content/mavikthumbnails/highslide/highslide.css');
			$document->addScriptDeclaration('
				hs.graphicsDir = "'.JURI::base().'plugins/content/mavikthumbnails/highslide/graphics/"
				hs.align = "center";
				hs.transitions = ["expand", "crossfade"];
				hs.outlineType = "rounded-white";
				hs.fadeInOut = true;
				hs.showCredits = false;
				//hs.dimmingOpacity = 0.75;

				// Add the controlbar
				hs.addSlideshow({
					slideshowGroup: "'.@$this->plugin->article->id.'",
					interval: 5000,
					repeat: false,
					useControls: true,
					fixedControls: "fit",
					overlayOptions: {
						opacity: .75,
						position: "bottom center",
						hideOnMouseOut: true
					}
				});
			');
			$document->addCustomTag('
				<!--[if lte IE 6]>
					<link href="<?php echo $this->baseurl ?>/plugins/content/mavikthumbnails/highslide/highslide-ie6.css" rel="stylesheet" type="text/css" />
				<![endif]-->
			');
		}
	
		if ($this->plugin->zoominCur || $this->plugin->zoominImg) {		
			// Подключить стили плагина к странице
			$document->addStyleSheet(JURI::base() . 'plugins/content/mavikthumbnails/style.php?base='.JURI::base());
		}

	}
	
	/**
	 * Декорирование тега изображения
	 * @param $img string Тег изображения 
	 * @return string Декорированый тег изображения
	 */
	function decorate() {
		$img =& $this->plugin->img;
		
		$title = $img->getAttribute('title');
		if (empty($title) && $img->getAttribute('alt')) {
			$title = $img->getAttribute('alt');
		}
		$title = htmlentities($title, ENT_QUOTES, 'UTF-8');
		
		$class = 'thumbnail highslide';
		$style = '';
		$zoominImg = '';
		
		if ($this->plugin->zoominImg) {
			$style = $img->getAttribute('style');
			$img->setAttribute('style', '');
			$zoominImg = '<span class="zoomin-img"></span>';
			$class .= ' with-zoomin-img';
			$align = $img->getAttribute('align');
			if($align == 'left' || $align == 'right') { $style .= '; float:'.$align.';'; }   		 			
		}
		
		if ($this->plugin->zoominCur) {
			$class .= ' zoomin-cur';
		}
		
		return '<a class="'.$class.'" style="'.$style.'" href="' . $this->plugin->originalSrc . '" onclick=\'return hs.expand(this, { captionText: "'.$title.'", slideshowGroup: "'.@$this->plugin->article->id.'" })\'>' . $img->toString() . $zoominImg . '</a>';
	}	
	
}
?>