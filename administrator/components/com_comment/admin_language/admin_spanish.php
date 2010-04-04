<?php
/* ***************************************************
 * *********** M A N A G E   C O M M E N T S *********
 * ***************************************************
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_CONFIRM_NOTIFY', 'Would you also send notifications ?\n[CANCEL=no notifications]');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_SENT_TO', 'Notifications sent to : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT', 'Notifications not sent');

JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_id', 'Id');
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_writer', 'Autor'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_userid', 'ID de usuario'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_notify', 'Notificar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_url', 'Url'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_date', 'Fecha'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_comment', 'Comentario<br /><i>(Enlaces e imagenes estan desactivadas)</i>'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_contentitem', 'Item de contenido'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_published', 'Despublicar (notificar)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_delete', 'Eliminar (notificar)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_ip', 'IP'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingyes', 'Votacion Si'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingno', 'Votacion No'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_parentid', 'Parent Id'); 


/* ***************************************************
 * *************** S E T T I N G *********************
 * ***************************************************
 */
/*
 * common
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_NAME', 'Nombre : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_COMPONENT', 'Componente :');
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS', 'Configuracion basica'); 
/*
 * generalPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_GENERAL_PAGE', 'General'); 
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_CAPTION', 'Desinstalar modo completo:'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_HELP', 'Eliminar tambien tablas al desinstalar! No activa, excepto si no uso! Joomlacomment mas.'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_CAPTION', 'Mambot contenido de la funcion:');
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_HELP', '<u>Para los expertos solo !</u> Puede cambiar aqui la joscomment mambot funcion, si tiene <b>hackeado</b> el contenido html (por ejemplo: para mostrar la de solo lectura Funcion primera).');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_CAPTION', 'Lenguaje parte Publica:');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_HELP', 'en caso de auto: se utiliza el mosConfigLanguage parametros');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_CAPTION', 'Lenguaje parte Publica en 2do Idioma:');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_HELP', 'En caso de auto: se utiliza el mosConfigLanguage parametros');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_CAPTION', 'Local de caracteres :<br />si va a realizar la actualizacion de edad liberacion thant 3.0.0, lea cuidadosamente la descripcion de la derecha !');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPnoiconv', 'No se utilizara! ! Php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv biblioteca</a><u/> No esta disponible. <b>Si utiliza un NO utf-8 y un NO iso-8859-1 Joomla instalacion, por favor, pongase en contacto con su administrador o desactivar el parametro ajax apoyo.</b>');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPiconv', 'Php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv biblioteca</a> esta disponible en su servidor.'
	                    .  '<br /><b>La entrada de caracteres de su instalacion Joomla si es diferente de utf-8. <br />Clic <a href="http://www.gnu.org/software/libiconv/" target="_blank">AQUI</a> para comprobar si cuenta con el apoyo de la biblioteca inconv! Otra persona, en contacto con la joomlacomment apoyo.</b> '
						.  '<br /><br /><b>Si es una actualizacion de una version mayor que 3.0.0</b>, una vez que ha guardado este parametro, <u> ir a Administrar el uso y Comentarios Para Convertir LCharset</u> funcion para convertir los correspondientes comentarios.'
        				.  '<br />Si siempre han utilizado el ajax modo, convertir todos los comentarios.'
        				.  '<br />Si ha cambiado, algunos comentarios (creado con ajax) que se han convertido, algunos otros (creado sin ajax) no han de ser convertidos!'
        				.  ' En este caso, seleccionar solo los comentarios en cuestion (los que tienen caracteres extraños!).'
        				);
/* SECTIONS_CATEGORIES */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_SECTIONS_CATEGORIES', 'Secciones y Categorias'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_CAPTION', 'Incluir :');
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_HELP', 'Si la respuesta es afirmativa, se <u>incluyen</u> solo algunos sectores y / o categorias seleccionadas. En caso negativo, esto <u>excluir</u> secciones seleccionadas y/o de las categorias');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_CAPTION', 'Excluir/ Incluir secciones:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_CAPTION', 'Excluir/ Incluir categorias:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_HELP', '');
/* CONTENT_ITEM */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CONTENT_ITEM', 'Elementos de contenido por id'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_CAPTION', 'Excluidos elemento de contenido lista:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_HELP', 'Usted puede utilizar este campo para excluir por id elemento de contenido. Formato: lista de ID separados por contenido, sin espacio.');
/* TECHNICAL */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TECHNICAL', 'Los parametros tecnicos (por joomlacomment apoyo solamente)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_CAPTION', 'Nombre de usuario preocupado por depuracion:');
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_HELP', 'Las alertas de depuracion, se dislplaid solo para esta cuenta de usuario.');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_CAPTION', 'xmlErrorAlert:');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_CAPTION', 'ajaxdebug:');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_HELP', '');

/*
 * layoutPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_LAYOUT', 'Diseno');
/* FRONTPAGE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_FRONTPAGE', '"Lea sobre" vinculo cuando intro texto'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_CAPTION', 'Mostrar "Leer El":');
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_HELP', 'En caso de la intro modo de visualizacion de los elementos de contenido (primera pagina, los blogs ...), escribir mostrara un enlace con el comentario contar de los actuales comentarios de este elemento de contenido');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_CAPTION', 'Solo en el caso de "Leer El" establecido en el Menu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_HELP', 'Mostrar solo si "Leer El" parametro de la llamada menu enlace (en Joomla Menu admin->...) se fija. Si se recomienda.');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_CAPTION', 'No mostrar detalle si existe enlace:');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_HELP', 'No mostrar al elemento de contenido ha vinculo al detalle (Readon o Titulo) y la pagina es "solo intro"');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_CAPTION', 'Vista previa visible:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_HELP', 'Mostrar previsualizacion de los ultimos comentarios (si "Mostrar Lea On" es permitir)');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_CAPTION', 'Vista previa Longitud:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_CAPTION', 'Vista previa Numero de lineas:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_HELP', ''); 
/* TEMPLATES */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TEMPLATES', 'Plantillas'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_template_CAPTION', 'Norma plantilla:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_HELP', 'Las plantillas son los diferentes aspectos de sus comentarios.'
				   		. '<br />Si ha habilitado emoticonos, adaptar el numero de emoticonos por linea (vease mas adelante) de acuerdo a su plantilla eleccion.'
				   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_CAPTION', 'Copiar estandar actual plantilla a plantilla personalizada directorio:');
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_HELP', 'Si se establece, al ajuste de ahorro, la plantilla estandar seleccionado se copiaran en el directorio de la costumbre como un nuevo "mi [plantilla estandar]", que puede entonces modificar (ver parametros a continuacion). Se copian solo Si no existen ya.');
JOSC_define('_JOOMLACOMMENT_ADMIN_TEMPLATE_CUSTOM_LOCATION', 'Localizacion:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_CAPTION', 'Su plantilla personalizada:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_HELP', 'Uso de la copia a la copia estandar de los parametros de plantilla. A continuacion, se le permite la modificacion de HTML o CSS (no sera sobreescrito durante la proxima actualizacion). Si no se selecciona, la plantilla estandar sera utilizado.');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_CAPTION', 'Modificar personalizada actual plantilla:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_HELP', 'Si si desea modificar el codigo HTML y CSS estilo personalizado de su actual plantilla. 2 nuevas pestañas aparecera despues de guardar la seleccion.'
                   		. '<br />Conjunto NO va a simplificar el guardar de la configuracion (mas rapido).</b>'
                   		);                   		
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_CAPTION', 'javascript Incluir biblioteca:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_HELP', 'javascript biblioteca Incluir al utilizar plantillas con efectos  (JQuery, Mootools...)'
       					. '<br />Conjunto NO si a la biblioteca ya esta incluido. Otra persona, usted tendra javascript errores y problemas.'
                   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_CAPTION', 'numero de columnas del area de entrada:');
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_HELP', 'Usted puede utilizar este parametro para aumentar o disminuir la anchura de la zona de entrada de texto de acuerdo a su sitio web pies.');
                   		
/* EMOTICONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_EMOTICONS', 'Caritas'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_CAPTION', 'de iconos gestuales (emoticones) de apoyo:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_HELP', 'Permitir el uso de caritas en los comentarios ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_CAPTION', 'pack de iconos gestuales:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_CAPTION', 'Numero de caritas por linea:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_HELP', 'Numero de caritas para mostrar en cada linea. El valor 0 significa que no hay limite.'
        				. '<br />Proposicion: uso 12 en caso de <i>emotop</i> plantillas (caritas estan en la parte superior) y 2 o 3 si es posible para otros (a la izquierda de caritas plantillas). Tratar de ver que es lo mejor para su sitio web !'
        				);
/*
 * postingPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_POSTING', 'Publicar');
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_CAPTION', 'Ajax apoyo (recomendado):');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_HELP', 'asincrona JavaScript + XML');
/* STRUCTURE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_STRUCTURE', 'Estructura'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_CAPTION', 'Permitir comentarios anidados:');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_HELP', 'Esto permitira a los usuarios insertar despues en respuesta a cualquier puesto de elemento de contenido (no solo el ultimo), con un guion pantalla.');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_CAPTION', 'Solo los moderadores');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_HELP', 'Solo los moderadores se permitira');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_CAPTION', 'sangria (pixels):');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_HELP', 'Este guion se utiliza para mensajes de los hilos de vista.');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_CAPTION', 'Comentarios de clasificacion (si no comentarios anidados):');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_FIRST', 'Nuevas entradas primero');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_LAST', 'Nuevas entradas ultimo');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_HELP', 'Pedidos de comentarios: anidados utilizarse unicamente si no es activo.<br /> Si Nuevas entradas en primer lugar, el formulario sera en la parte superior, otra cosa sera en la parte inferior');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_CAPTION', 'Numero de comentarios de la pagina:');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_HELP', 'Numero de comentarios que se mostrara por pagina');
/* POSTING */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_POSTING', 'Publicar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_CAPTION', 'Entrada sitio web:');
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_HELP', 'Permitir a los usuarios a la entrada de su sitio web enlace.');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_CAPTION', 'codigo UBB apoyo:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_HELP', 'Permitir el uso de codigos UBB ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_CAPTION', 'Imagen de apoyo:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_HELP', 'Permitir el uso de imagenes en los comentarios ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_CAPTION', 'Imagen anchura maxima:');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_HELP', 'Ancho maximo de pixel');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_CAPTION', 'Habilitar votacion:');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_HELP', 'Si se establece y se fija el modo de ajax: Mostrara reactiva imagen, que permita el voto + o - para cualquier comentario.');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_CAPTION', 'Use nombres :');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_HELP', 'Use nombres en lugar de los nombres de usuario (que se refiere unicamente a los usuarios registrados)');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_CAPTION', 'Habilitar perfiles:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_HELP', "Permitir el uso de <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> - Perfiles en los comentarios ?");
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_CAPTION', 'Habilitar avatares:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_HELP', "Permitir el uso de <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> - Avatares en los comentarios? (Solo si esta habilitado perfiles)");
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_CAPTION', 'Fecha de formato:');
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_HELP', 'La sintaxis usada es identica a la fecha de PHP() Funcion.');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_CAPTION', 'Desactivar el boton de busqueda?:');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_HELP', 'Desactiva el boton de busqueda.');
/* IP ADDRESS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_IP_ADDRESS', 'direccion IP'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_CAPTION', 'Visible:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_HELP', 'Si se establece, se mostrara la direccion IP de los escritores o de los no registrados "de usuario" de los usuarios registrados.');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_CAPTION', 'Tipos de usuario (Usertypes) :');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_HELP', 'Al menos un usuario debe seleccionar.'
							. '<br />"IP visible "se pone en Si: solo se mostraran a los comentarios de algunos Tipos de usuario (Usertypes) (se recomienda seleccionar todas).'
							. '<br />"IP visible "se pone en No: solo se mostraran para determinados conectado Tipos de usuario (Usertypes)'
							);
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_CAPTION', 'parcial:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_HELP', 'Si se establece, no mostrara el ultimo digito de la direccion IP de los usuarios no registrados');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_CAPTION', 'Leyenda:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_HELP', 'Descripcion de la propiedad intelectual antes de mostrar el valor.');
/*
 * securityPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_SECURITY', 'Seguridad'); 
/* BASICS SETTINGS */  
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_CAPTION', 'Solo registrados:');
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_HELP', 'Solo los usuarios registrados pueden escribir comentarios.');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_CAPTION', 'Autopublish comentarios:');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_HELP', 'Si lo establece a "no" entonces los comentarios se añadiran a la base de datos y te esperamos para revisar y publicarlas antes de mostrar.');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_CAPTION', 'Lista de prohibicion (Banlist):');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_HELP', 'Para especificar diferentes direcciones IP separadas por comas.');
/* NOTIFICATIONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_NOTIFICATIONS', 'Notificacion'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_CAPTION', 'Notificar al administrador (no utilizar nunca mas ):');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_HELP', 'no utilizar nunca mas - por favor, utilice el parametro Notify moderador');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_CAPTION', 'Admi\'s email:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_HELP', 'Correo notificacion que la direccion de correo electronico?');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_CAPTION', 'Notificar a los moderadores:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_HELP', 'Notificar a los moderadores cuando nuevo comentario ?');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_CAPTION', 'Moderador grupos:');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_HELP', 'Los moderadores podran modificar o borrar en cualquier linea de comentarios. Un menu especial aparecera en cada comentario de los usuarios.');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_CAPTION', 'Permite a los usuarios la notificacion :');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_HELP', 'El correo electronico y la notificacion sobre el terreno sera notificado disponible, y si se fija, los usuarios recibiran informacion de la nueva entrada.');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_CAPTION', 'Habilitar comentarios de alimentacion (RSS):');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_HELP', '');
/* OVERFLOW */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_OVERFLOW', 'Overflow (Mas de flujo)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_CAPTION', 'Post max longitud:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_HELP', 'caracteres maximo permitido en los puestos (-1 para no max.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_CAPTION', 'Linea de longitud maxima:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_HELP', 'caracteres maximo permitido en una linea (-1 para no max.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_CAPTION', 'Word max longitud:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_HELP', 'caracteres maximo permitido en palabras (-1 para no max.)');
/* ANTI-SPAM */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CAPTCHA', 'Anti-spam)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_CAPTION', 'Captcha Activado (recomendado):');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_HELP', 'fuerza al usuario a introducir una cadena aleatoria muestran en una imagen especialmente preparada. Automatizado de inhabilitar Esto presenta a su sitio.');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_CAPTION', 'Tipos de usuario (Usertypes) Captcha:');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_HELP', 'Solo los Tipos de usuario (Usertypes) seleccionados tendran que usar el Captcha.');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_CAPTION', 'URL del sitio web solo para el registro de:');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_HELP', 'Nuevo Para comentarios: Mostrara los escritores de la URL del sitio web, unicamente cuando el usuario esta registrado');
/* CENSORSHIP */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CENSORSHIP', 'Censura'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_CAPTION', 'Activar:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_HELP', 'activa o no censurar filtro. El censor utilizara las normas escritas en la lista de palabras censuradas para ocultar o modificar las palabras');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_CAPTION', 'mayusculas y minusculas:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_CAPTION', 'Censurado palabras:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_HELP', false); /* colspan */
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_CAPTION', 'Tipos de usuario (Usertypes):');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_HELP', 'Se aplicara solo a los seleccionados Tipos de usuario.');
?>