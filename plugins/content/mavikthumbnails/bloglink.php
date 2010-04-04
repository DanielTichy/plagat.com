<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */

/**
 * Декоратор для добавления к изображению ссылки на полный текст статьи
 * 
 */
class plgContentMavikThumbnailsDecoratorBlogLink extends plgContentMavikThumbnailsDecorator
{
	/**
	 * Декорирование тега изображения
	 * @param $img string Тег изображения 
	 * @return string Декорированый тег изображения
	 */
	function decorate() {
		$img =& $this->plugin->img;
		if(isset($this->plugin->article->readmore_link)){
			return '<a href="'. JRoute::_( $this->plugin->article->readmore_link ) .'">' . $img->toString() . '</a>';
		} else {
			return $img->toString();
		}
	}	
	
}
?>