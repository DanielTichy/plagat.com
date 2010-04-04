<?php
/**
 * @package Joomla
 * @subpackage mavikThumbnails
 * @copyright 2008 Vitaliy Marenkov
 * @author Vitaliy Marenkov <admin@mavik.com.ua>
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */

defined( '_JEXEC' ) or die();

jimport( 'joomla.event.plugin' );
//jimport('joomla.filesystem.file');
require_once 'mavikthumbnails/imgtag.class.php';

/**
 * Плагин заменяет изображения иконками со ссылкой на полную версию.
 */
class plgContentMavikThumbnails extends JPlugin
{

	/**
	 * Объект - тег изображения
	 * @var plgContentMavikThumbnailsImgTag
	 */
	var $img;
	
	/**
	 * Имя оригинального изображения
	 * @var string
	 */
	var $origImgName;
	
	/**
	 * Оригинальный адрес изобажения
	 * @var string
	 */
	var $originalSrc;
	
	/**
	 * Размеры оригинального изображения
	 * @var array
	 */
	var $origImgSize;
	
	/**
	 * Каталог с иконками
	 * @var string
	 */
	var $thumbPath;
	
	/**
	 * Тип всплывающего окна
	 * @var string
	 */
	var $popupType;
	
	/**
	 * Подключать ли ява-скрипты
	 *
	 * @var boolean
	 */
	var $linkScripts;
	
	/**
	 * Отображать изображение увеличительного стекла на картинке
	 * @var boolean
	 */
	var $zoominImg;
	
	/**
	 * Менять ли курсор при наведении на картинку на увеличительное стекло
	 * @var boolean
	 */
	var $zoominCur;
	
	/**
	 * В блогах изображения является ссылкой на полный текст
	 * @var boolean
	 */
	var $blogLink;
	
	/**
	 * Ссылка на статью
	 * @var object
	 */
	var $article;
	
	/**
	 * Ссылка на параметры статьи
	 * @var object
	 */
	var $articleParams;
	
	/**
	 * Декоратор тега изображения (зависит от popupType)
	 * @var plgContentMavikThumbnailsDecorator
	 */
	var $decorator;

	/**
	 * Добевлены уже декларации в head
	 * @var boolean
	 */
	var $has_header;
	
	/**
	* Конструктор
	* @param object $subject Обрабатываемый объект
	* @param object $params  Объект содержащий параметры плагина
	*/
	function plgContentMavikThumbnails( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		
		// Заплатка для компонентов использующих старый механизм работы с плагинами 
		if (!is_object($params)) {
			$this->plugin = &JPluginHelper::getPlugin('content', 'mavikthumbnails');
			$this->params = new JParameter($this->plugin->params);
		}
		
		// Определить параметры плагина
		$this->thumbPath =	$this->params->def('thumbputh', 'plugins/content/mavikthumbnails/thumbnails');
		$this->popupType =	$this->params->def('popuptype', 'slimbox');
		$this->linkScripts = $this->params->def('link_scripts', 1);
		$this->blogLink = $this->params->def('blog_link', 0);
		$this->zoominImg = $this->params->def('zoomin_img', 0);
		$this->zoominCur = $this->params->def('zoomin_cur', 0);
		
		// Подключить необходимый класс декоратора		
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		$layout = JRequest::getVar('layout');
		if ( $this->blogLink && $option=='com_content' && ($layout=='blog' || $view=='frontpage' )) {
			$this->popupType = 'bloglink';
		} elseif ($this->popupType == 'none') {
			$this->popupType = '';
		}
		$file = JPATH_PLUGINS . '/content/mavikthumbnails/' . $this->popupType . '.php';
		if (file_exists($file)) require_once $file; 
		$type = 'plgContentMavikThumbnailsDecorator' . $this->popupType;
		$this->decorator = new $type($this);
		$this->img = new plgContentMavikThumbnailsImgTag();
   
	}
	
	/**
	* Метод вывызываемый при просмотре
	* @param 	object		Объект статьи
	* @param 	object		Параметры статьи
	* @param 	int			Номер страницы
	*/
	function onBeforeDisplayContent( &$article, &$params, $limitstart )
	{
		$this->article =& $article;
		$this->articleParams =& $params;
		// Найти в тексте изображения и заменить на иконки
		$regex = '#<img\s.*?>#';
		$article->text = preg_replace_callback($regex, array($this, "imageReplacer"), $article->text);
		return '';
	}

/**
 * Преобразует img-тег в html-код иконки 
 * @param array $matches
 * @return string
 */
	function imageReplacer(&$matches)
	{
		// Создать объект тега изображения
		$newImgStr = $imgStr = $matches[0];
		$this->img->parse($imgStr);
		
/*
		// Если изображение удаленное - проверить наличие локальной копии, при отсутствии создать
		//TODO Не работает с картинками, которые формируются скриптами
		//TODO Включить возможность копирования в настройки
		$juri =& JFactory::getURI();
		$src = $this->img->getAttribute('src');
		if (!$juri->isInternal($src)) {
			$fileName = str_replace(array('/','\\',':',';',' ','&','?', '='), '-', $src);
			$localFile = $this->thumbPath . DS . 'remote' . DS . $fileName; 
			if (!file_exists($localFile)) {
				copy($src, $localFile);
			}
			$this->img->setAttribute('src', $this->thumbPath . '/remote/' . $fileName);
		}
*/		
		
		// Проверить необходимость замены - нужна ли иконка?
		// Прежде чем обращатья к функциям GD, проверяются атрибуты тега.
		if ( $this->img->getHeight() || $this->img->getWidth() )
		{
			$this->origImgName = $this->img->getAttribute('src');
			$this->origImgName = $this->urlToFile($this->origImgName);
				
			$this->origImgSize = @getimagesize($this->origImgName);
			$origImgW = $this->origImgSize[0];
			$origImgH = $this->origImgSize[1];
			
			if (( $this->img->getWidth() && $this->img->getWidth() < $origImgW ) || ( $this->img->getHeight() && $this->img->getHeight() < $origImgH ))
			{
				// Заменить изображение на иконку
				$newImgStr = $this->createThumb();
				$this->img->isThumb = true;
			}
		}
		if ($this->img->isThumb) { 
			if (!$this->has_header) $this->decorator->addHeader();
			$this->has_header = true;
			$result = $this->decorator->decorate();
		}
		else { $result = $this->img->toString(); }
		return $result; 
	}
	
	/**
	 * Создает иконку, если она еще не существует.
	 * @return string html-код иконки
	 */
	function createThumb()
	{
		// Доопределить размеры, если необходимо
		if ($this->img->getWidth()==0) $this->img->setWidth(intval($this->img->getHeight() * $this->origImgSize[0] / $this->origImgSize[1])); 
		if ($this->img->getHeight()==0) $this->img->setHeight(intval($this->img->getWidth() * $this->origImgSize[1] / $this->origImgSize[0]));
		// Сформировать путь к иконке
		$thumbName = str_replace(array('/','\\',':',' ','&','?', '='), '-', $this->origImgName); 
		$thumbName = $this->img->getWidth() . 'x' . $this->img->getHeight() . '-' . $thumbName;
		$thumbPath = JPATH_BASE . DS . $this->thumbPath . DS . $thumbName; 
		// Если иконки не существует - создать
		if (!file_exists($thumbPath))
		{
			// Определить тип оригинального изображения
			$mime = $this->origImgSize['mime'];
			// В зависимости от этого создать объект изобразения
			switch ($mime)
			{
				case 'image/jpeg':
					$orig = imagecreatefromjpeg($this->origImgName);
					break;
				case 'image/png':
					$orig = imagecreatefrompng($this->origImgName);
					break;
				case 'image/gif':
					$orig = imagecreatefromgif($this->origImgName);
					break;
				default:
					// Если тип не поддерживается - вернуть тег без изменений
					return $this->img->toString();
			}
			// Создать объект иконки
			$thumb = imagecreatetruecolor($this->img->getWidth(), $this->img->getHeight());
		    // Обработать прозрачность
    		$transparent_index = imagecolortransparent($orig);
    		if ($transparent_index >= 0)
    		{
				// без альфа-канала
    			$t_c = imagecolorsforindex($orig, $transparent_index);
        		$transparent_index = imagecolorallocate($orig, $t_c['red'], $t_c['green'], $t_c['blue']);
        		imagecolortransparent($thumb, $transparent_index);
    		} else {
				// с альфа-каналом
				imagealphablending ( $thumb, false );
				imagesavealpha ( $thumb, true );
				$transparent = imagecolorallocatealpha ( $thumb, 255, 255, 255, 127 );
				imagefilledrectangle ( $thumb, 0, 0, $this->img->getWidth(), $this->img->getHeight(), $transparent );
    		}
			// Создать превью
    		imagecopyresampled($thumb, $orig, 0, 0, 0, 0, $this->img->getWidth(), $this->img->getHeight(), $this->origImgSize[0],$this->origImgSize[1]);
			// Записать иконку в файл
			switch ($mime)
			{
				case 'image/jpeg':
					imagejpeg($thumb, $thumbPath, 80);
					break;
				case 'image/png':
					imagepng($thumb, $thumbPath);
					break;
				case 'image/gif':
					imagegif($thumb, $thumbPath);
			}
			imagedestroy($orig);
			imagedestroy($thumb);
		}
		$this->originalSrc = $this->img->getAttribute('src');
		$this->img->setAttribute('src', $this->thumbPath . '/' . $thumbName);
	}
	
	/**
	 * Преобразует url-путь в путь к файлу
	 * если хост в url совпадает с url сайта,
	 * иначе оставляет без изменений
	 *
	 * @param string $url
	 */
	function urlToFile($url)
	{
		$siteUri = JFactory::getURI();
		$imgUri = JURI::getInstance($url);
		
		$siteHost = $siteUri->getHost();
		$imgHost = $imgUri->getHost();
		// игнорировать www при сверке хостов 
		$siteHost = preg_replace('/^www\./', '', $siteHost);
		$imgHost = preg_replace('/^www\./', '', $imgHost);
		if (empty($imgHost) || $imgHost == $siteHost) {
			$imgPath = $imgUri->getPath(); 
			// если путь к изображению абсолютный от корня домена (начинается со слеша),
			// преобразовать его в относительный от базового адреса сайта
			if ($imgPath[0] == '/')	{
				$siteBase = $siteUri->base();
				$dirSite = substr($siteBase, strpos($siteBase, $siteHost) + strlen($siteHost));
				$url = substr($imgPath, strlen($dirSite));
			}
			$url = urldecode(str_replace('/', DS, $url));
		}
		return $url;
	}
}

/**
 * Декорирование тега изображения: всплывающие окна и т.п.
 *
 */
class plgContentMavikThumbnailsDecorator
{
	/**
	 * Ссылка на объект плагина
	 * @var plgContentMavikThumbnails 
	 */
	var $plugin;
	
	/**
	 * Конструктор
	 * @param $plugin
	 * @return unknown_type
	 */
	function plgContentMavikThumbnailsDecorator(&$plugin)
	{
		$this->plugin = $plugin;
	}
	
	/**
	 * Добавление кода в заголовок страницы 
	 */
	function addHeader() {}
	
	/**
	 * Декорирование тега изображения
	 * @return string Декорированый тег изображения
	 */
	function decorate() {
		$img =& $this->plugin->img;
		return $img->toString();
	}
}
