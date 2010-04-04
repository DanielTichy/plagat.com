<?php
/* ***************************************************
 * *********** M A N A G E   C O M M E N T S *********
 * ***************************************************
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_CONFIRM_NOTIFY', 'Uyarıları göndermek ister misiniz?\n[İPTAL=uyarı yok]');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_SENT_TO', 'Uyarılar şu alıcılara gönderildi : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_NOTIFY_NOT_SENT', 'Uyarılar gönderilmedi');

JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_id', 'Id');
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_writer', 'Yazar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_userid', 'Kullanıcı ID'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_notify', 'Uyar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_url', 'Url'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_date', 'Tarih'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_comment', 'Yorum <br /><i>(link ve resimler kapalı)</i>'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_contentitem', 'İçerik Öğesi'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_published', 'Yayında (yazarı-uyar)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_delete', 'Sil (yazarı-uyar)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_ip', 'IP'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingyes', 'Evet Oyları'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_votingno', 'Hayır Oyları'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_viewcom_parentid', 'Üst Öğe ID'); 

/* ***************************************************
 * *************** S E T T I N G *********************
 * ***************************************************
 */
/*
 * common
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_NAME', 'Ad : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_SETTING_LINE_COMPONENT', 'Bileşen : ');
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_BASIC_SETTINGS', 'Temel Ayarlar'); 
/*
 * generalPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_GENERAL_PAGE', 'Genel'); 
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_CAPTION', 'Kaldırma tamamlama modu:'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_complete_uninstall_HELP', 'Kaldırdıktan sonra tabloları da sil!'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_CAPTION', 'Mambot içerik fonksiyonu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_mambot_func_HELP', '<u>Sadece uzmanlar için!</u> Eğer içerik html dosyasını <b>değiştirdiyseniz</b> joscomment mambot fonksiyonunu değiştirebilirsiniz.');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_CAPTION', 'Yorum sayfası dili:');
JOSC_define('_JOOMLACOMMENT_ADMIN_language_HELP', 'eğer otomatikse : mosConfigLanguage parametreleri kullanılacaktır');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_CAPTION', 'Yönetim dili:');
JOSC_define('_JOOMLACOMMENT_ADMIN_admin_language_HELP', 'eğer otomatikse : mosConfigLanguage parametreleri kullanılacaktır');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_CAPTION', 'Yerel karakter seti :<br />3.0.0\'dan daha eski bir sürümden yükselme yapıyorsanız, sağdaki tanımı dikkatlice okuyun !');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPnoiconv', 'Kullanılamayacak!! Php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv kütüphanesi</a><u/> mevcut değil.  <b>Eğer utf-8 and iso-8859-1 olmayan bir Joomla kurulumunuz varsa, lütfen yöneticinizle temasa geçin veya AJAX desteği parametresini iptal edin.</b>');
JOSC_define('_JOOMLACOMMENT_ADMIN_local_charset_HELPiconv', 'Php <a href="http://www.php.net/manual/fr/ref.iconv.php" target="_blank">iconv kütüphanesi</a> mevcut.'
	                    .  '<br /><b>Eğer utf-8\'den farklıysa Joomla kurulum karakter kodunuzu girin.<br />Iconv kütüphanesi tarafından desteklenip desteklenmediğini görmek için buraya <a href="http://www.gnu.org/software/libiconv/" target="_blank">tıklayın</a>! Desteklenmiyorsa, joomlacomment desteğine başvurun.</b> '
						.  '<br /><br /><b>Eğer bu 3.0.0\'sürümünden eski bir sürümden yükseltmeyse</b>, bu parametreyi kaydettiğnizde, <u>Manage Comments (Yorumları Yönet) sayfasına gidin ve Convert To LCharset (LCharset Çevir)</u> fonksiyonunu kullanın.'
        				.  '<br />Her zaman AJAX desteğini kullandıysanız tüm mesajları çevirin.'
        				.  '<br />Her zaman kullanmadıysanız, bazı yorumların (AJAx ile yüklenen) çevrilmesi, diğerlerinin ise çevrilmemesi gerekir!'
        				.  ' Bu durumda sadece gereken yorumları seçin (Bozuk karakterler içerenleri).'
        				);
/* SECTIONS_CATEGORIES */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_SECTIONS_CATEGORIES', 'Bölümler & Kategoriler'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_CAPTION', 'Dahil Et :');
JOSC_define('_JOOMLACOMMENT_ADMIN_include_sc_HELP', 'EVET seçilirse, bu durumda sadece seçili bölümler ve/veya kategoriler <u>dahil edilecektir</u>. HAYIR seçilirse, bu durumda sadece seçili bölümler ve/veya kategoriler <u>hariç edilecektir</u>');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_CAPTION', 'Hariç/Dahil bölümleri:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_sections_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_CAPTION', 'Hariç/Dahil kategorileri:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_categories_HELP', '');
/* CONTENT_ITEM */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CONTENT_ITEM', 'ID\'lerine göre İçerik Öğeleri'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_CAPTION', 'Hariç edilen öğe ID listesi:');
JOSC_define('_JOOMLACOMMENT_ADMIN_exclude_contentids_HELP', 'Bu alanı ID\'lerine göre öğeleri hariç etmek için kullanabilirsiniz. Öğe ID\'lerini virgül ile ayırarak yazın. Boşluk olmamasına dikkat edin.');
/* TECHNICAL */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TECHNICAL', 'Teknik parametreler (sadece joomlacomment desteği için)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_CAPTION', 'Hata ayıklama ile ilgili kullanıcı adı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_debug_username_HELP', 'Hata ayıklama uyarıları sadece bu kullanıcı için gösterilecektir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_CAPTION', 'XML Hata Uyarısı (xmlErrorAlert):');
JOSC_define('_JOOMLACOMMENT_ADMIN_xmlerroralert_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_CAPTION', 'Ajax hata ayıklama (ajaxdebug):');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajaxdebug_HELP', '');

/*
 * layoutPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_LAYOUT', 'Görünüm');
/* FRONTPAGE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_FRONTPAGE', 'Giriş yazısında "Devamını Oku" linki'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_CAPTION', '"Devamını Oku" linki göster:');
JOSC_define('_JOOMLACOMMENT_ADMIN_show_readon_HELP', 'İçerik öğelerinin (anasayfa, blog gibi) giriş yazısı gösterim modundayken yorum yazma linki parantez içinde mevcut yorum sayısıyla birlikte gösterilecektir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_CAPTION', 'Sadece menüde "Devamını Oku" seçiliyse:');
JOSC_define('_JOOMLACOMMENT_ADMIN_menu_readon_HELP', 'Sadece menü linkinde (Joomla admin Menu->...) "Devamını Oku" aktifse gösterilecektir. Önerilen: EVET.');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_CAPTION', 'Detay linki varsa gösterme:');
JOSC_define('_JOOMLACOMMENT_ADMIN_intro_only_HELP', 'İçerik öğesi detay linkine sahipse (Devamı veya Başlık) ve sayfa "Sadece Giriş Yazısı" ise gösterme.');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_CAPTION', 'Önizleme görünür:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_visible_HELP', 'Son yorumların önizlemesini göster ("Devamını Oku linki göster" aktifse)');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_CAPTION', 'Önizleme uzunluğu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_length_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_CAPTION', 'Önizleme satır sayısı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_preview_lines_HELP', ''); 
/* TEMPLATES */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_TEMPLATES', 'Şablonlar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_template_CAPTION', 'Standart şablon:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_HELP', 'Şablonlar yorumlara değişik görünümler verir.'
				   		. '<br />Gülen yüzleri aktif ettiyseniz, Bir satırda gösterilecek yüz sayısını şablona en uygun şekile seçin (aşağıya bakın).'
				   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_CAPTION', 'Standart şablonu şablon uyarlama klasörüne kopyala:');
JOSC_define('_JOOMLACOMMENT_ADMIN_copy_template_HELP', 'Seçilirse, ayarlar kaydedilirken seçilen standart şablon uyarlama klasörüne "my[standard template]" şeklinde kopyalanacaktır. Daha sonra değişiklikler yapabilirsiniz (aşağıdaki parametrelere bakın). Şablon daha önceden kopyalanmadıysa kopyalanacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_TEMPLATE_CUSTOM_LOCATION', 'Yer:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_CAPTION', 'Uyarlama şablonunuz:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_custom_HELP', 'Standart şablonu kopyalamak için uyarlama klasörüne kopyalayı seçin. Daha sonra HTML veya CSS\' düzenleyebileceksiniz (ileriki güncellemelerde üzerine yazılmayacaktır). Hiçbirşey seçilmezse, standart şablon kullanılacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_CAPTION', 'Mevcut uyarlama şablonu değiştir:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_modify_HELP', 'HTML ve CSS stillerini değiştirmek istiyorsanız EVET deyin. Seçimi kaydettikten sonra 2 yeni tab belirecektir.'
                   		. '<br />HAYIR demek ayarları basitleştirecektir (daha hızlı).</b>'
                   		);                   		
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_CAPTION', 'JavaScript kütüphanesini dahil et:');
JOSC_define('_JOOMLACOMMENT_ADMIN_template_library_HELP', 'Efektlerin olduğu şablonları kullanırken Javascript kütüphanelerini ekle (JQuery, Mootools...)'
       					. '<br />Eğer kütüphane zaten eklenmişse HAYIR deyin. Yoksa Javascript hataları ve problemleriyle karşılaşabilirsiniz.'
                   		);
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_CAPTION', 'Yazı giriş alanının sütün sayısı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_form_area_cols_HELP', 'Sayfanızın görünümüne göre sütün sayısını azaltıp arttırabilirsiniz.');
                   		
/* EMOTICONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_EMOTICONS', 'Gülen Yüzler'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_CAPTION', 'Gülen yüz (smilies) desteği:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_emoticons_HELP', 'Yorumlarda gülen yüzler kullanılsın mı?');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_CAPTION', 'Gülen yüz paketi:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_pack_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_CAPTION', 'Bir satırdaki gülen yüz sayısı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_emoticon_wcount_HELP', 'Herbir satırda gösterilecek gülen yüz sayısı. 0 limit olmadığı anlamına gelir.'
        				. '<br />Öneri: <i>emotop</i> şablonları seçerseniz (gülen yüzler üstte) 12 ve diğer şablonları seçerseniz (gülen yüzlerin solda olduğu) 2 veya 3 girin. Sayfanız için en iyisini bulmak için değişik değerleri deneyin!'
        				);
/*
 * postingPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_POSTING', 'Yorum Gönderimi');
/* BASIC_SETTINGS */
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_CAPTION', 'Ajax desteği (önerilen):');
JOSC_define('_JOOMLACOMMENT_ADMIN_ajax_HELP', 'Asynchronous JavaScript + XML');
/* STRUCTURE */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_STRUCTURE', 'Yapı'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_CAPTION', 'İçiçe yorumlara izin ver:');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_HELP', 'Bu seçenek sadece içeriğe değil aynı zamanda diğer yorumlara da cevap yazılmasını sağlar. İçiçe gösterilen yorumlar sağdan girintili olur.');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_CAPTION', 'SAdece moderatörler');
JOSC_define('_JOOMLACOMMENT_ADMIN_mlink_post_HELP', 'Sadece moderatörlere izin verilecektir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_CAPTION', 'Girinti (pixel cinsinden):');
JOSC_define('_JOOMLACOMMENT_ADMIN_tree_indent_HELP', 'İçiçe görünümde sağdan girinti miktarı.');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_CAPTION', 'Yorum sıralama (İçiçe yorumlar hariç):');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_FIRST', 'Yeni yorumlar önde');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_VALUE_LAST', 'Yeni yorumlar sonda');
JOSC_define('_JOOMLACOMMENT_ADMIN_sort_downward_HELP', 'Yorumların sıralaması : içiçe yorumlar aktif değilse kullanılır.<br />Yeni yorumlar öndeyse yorum formu üstte, diğer türlü altta olacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_CAPTION', 'Sayfa başına gösterilecek yorum sayısı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_display_num_HELP', 'Sayfa başına gösterilecek yorum sayısı');
/* POSTING */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_POSTING', 'Yorum Gönderimi'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_CAPTION', 'Web sayfası alanı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_enter_website_HELP', 'Kullanıcıların websitelerini girmelerine izin ver.');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_CAPTION', 'UBB kod desteği:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_UBBcode_HELP', 'UBB kodlarının kullanımına izin ver?');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_CAPTION', 'Resim desteği:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_pictures_HELP', 'Yorumlarda resim kullanımına izin ver?');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_CAPTION', 'En fazla resim genişliği:');
JOSC_define('_JOOMLACOMMENT_ADMIN_pictures_maxwidth_HELP', 'Pixel cinsinden maksimum genişlik');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_CAPTION', 'Oylamaya izin ver:');
JOSC_define('_JOOMLACOMMENT_ADMIN_voting_visible_HELP', 'Seçiliyse ve AJAX aktifse: Yorumlara oy vermeyi sağlayacak + ve - bulunan bir resim gösterir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_CAPTION', 'Adları kullan:');
JOSC_define('_JOOMLACOMMENT_ADMIN_use_name_HELP', 'Kullanıcı adı yerine ad kullan (sadece kayıtlı kullanıcılar için)');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_CAPTION', 'Profilleri aktif et:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_profiles_HELP', "Yorumlarda <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> profiline link verilmesine izin ver?");
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_CAPTION', 'Avatarları aktif et:');
JOSC_define('_JOOMLACOMMENT_ADMIN_support_avatars_HELP', "Yorumda <a href='http://www.joomlapolis.com/' target='_blank'>Community Builder</a> avatarlarını göster (sadece profiller aktifse).");
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_CAPTION', 'Tarih formatı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_date_format_HELP', 'PHP date() fonsiyonuna uygun tarih biçimi.');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_CAPTION', 'Arama butonunu iptal et:');
JOSC_define('_JOOMLACOMMENT_ADMIN_no_search_HELP', 'Arama butonunu gösterme.');
/* IP ADDRESS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_IP_ADDRESS', 'IP Adresi'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_CAPTION', 'Görünür:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_visible_HELP', 'Seçilirse kayıtlı olmayan kullanıcıların ve "kullanıcı tipleri"nin IP adreslerini gösterir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_CAPTION', 'Kullanıcı tipleri:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_usertypes_HELP', 'En az bir kullanıcı tipi seçilmellidir.'
							. '<br />"IP görünür" Evet ise: sadece seçili kullancı tipleri için gösterilecektir (tümünde gösterilmesi önerilir).'
							. '<br />"IP visible" Hayır ise: sadece seçili bağlı kullanıcı tipleri için gösterilecektir.'
							);
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_CAPTION', 'Kısmen Göster:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_partial_HELP', 'Seçilirse kayıtlı olmayanların IP adresinin son basamağı gösterilmeyecektir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_CAPTION', 'Başlık:');
JOSC_define('_JOOMLACOMMENT_ADMIN_IP_caption_HELP', 'IP adresi değerinden önce gösterilecek açıklama.');
/*
 * securityPage
 */
JOSC_define('_JOOMLACOMMENT_ADMIN_TAB_SECURITY', 'Güvenlik'); 
/* BASICS SETTINGS */  
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_CAPTION', 'Sadece kayıtlı kullanıcılar:');
JOSC_define('_JOOMLACOMMENT_ADMIN_only_registered_HELP', 'Sadece kayıtlı kullanıcılar yorum yazabilir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_CAPTION', 'Yorumları otomatik yayınla:');
JOSC_define('_JOOMLACOMMENT_ADMIN_autopublish_HELP', 'Eğer Hayır seçilirse yorumlar veritabanına kaydeilecek fakat sizin onayınızdan sonra yayınlanacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_CAPTION', 'Engelleme Listesi:');
JOSC_define('_JOOMLACOMMENT_ADMIN_ban_HELP', 'Engellenecek IP adreslerini aralarına virgül koyarak yazın.');
/* NOTIFICATIONS */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_NOTIFICATIONS', 'Uyarılar'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_CAPTION', 'Admini uyar (artık kullanılmıyor):');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_admin_HELP', 'artık kullanılmıyor - Moderatörleri uyar parametresini kullanın.');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_CAPTION', 'Admin\'in email adresi:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_email_HELP', 'Uyarılar hangi adrese yapılacak?');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_CAPTION', 'Moderatörleri uyar:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_moderator_HELP', 'Yeni yorum olduğunda moderatörleri uyar?');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_CAPTION', 'Moderatör grupları:');
JOSC_define('_JOOMLACOMMENT_ADMIN_moderator_HELP', 'Moderatörler yorumları silme ve düzenleme yetkisine sahip olacaklardır. Bu kullanıcılar için yorumlarda özel seçenekler olacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_CAPTION', 'Kullanıcı uyarma aktif:');
JOSC_define('_JOOMLACOMMENT_ADMIN_notify_users_HELP', 'Email ve uyarılar seçenekleri aktif ve bu alan da aktifse, yeni yorumlar olduğunda kullanıcılar uyarı alacaklardır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_CAPTION', 'Yorum beslemesi (RSS) aktif:');
JOSC_define('_JOOMLACOMMENT_ADMIN_rss_HELP', '');
/* OVERFLOW */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_OVERFLOW', 'Yorum Sınırları'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_CAPTION', 'En fazla uzunluk:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_text_HELP', 'İzin verilen en fazla yorum uzunluğu (-1: limitsiz.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_CAPTION', 'En fazla satır uzunluğu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_line_HELP', 'İzin verilen en fazla satır uzunluğu (-1: limitsiz.)');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_CAPTION', 'En fazla kelime uzunluğu:');
JOSC_define('_JOOMLACOMMENT_ADMIN_maxlength_word_HELP', 'İzin verilen en fazla kelime uzunluğu (-1: limitsiz.)');
/* ANTI-SPAM */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CAPTCHA', 'Anti-spam)'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_CAPTION', 'Captcha aktif (önerilen):');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_HELP', 'Kullacı rasgele oluşturulmuş karakterleri girmeye zorlanacaktır. Sayfaya yapılacak otomatik mesajları engeller.');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_CAPTION', 'Captcha kullanıcı tipleri:');
JOSC_define('_JOOMLACOMMENT_ADMIN_captcha_usertypes_HELP', 'Sadece seçilen kullanıcı tipleri için gösterilecekir.');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_CAPTION', 'Sadece kayıtlı kullanıcılar için Website URL göster:');
JOSC_define('_JOOMLACOMMENT_ADMIN_website_registered_HELP', 'Sadece kayıtlı kullanıcıların yorumlarında gönderenin website adresini göster.');
/* CENSORSHIP */
JOSC_define('_JOOMLACOMMENT_ADMIN_TITLE_CENSORSHIP', 'Sansürleme'); 
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_CAPTION', 'Aktif:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_enable_HELP', 'Sansür filtresini aktif et. Sansür filtresi sansürlü keliemeler listesini kullanacaktır.');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_CAPTION', 'Büyük/Küçük harf duyarlı:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_case_sensitive_HELP', '');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_CAPTION', 'Sansürlü kelimeler:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_words_HELP', false); /* colspan */
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_CAPTION', 'Kullanıcı tipleri:');
JOSC_define('_JOOMLACOMMENT_ADMIN_censorship_usertypes_HELP', 'Sadece seçili kullanıcı tiplerine uygulanacaktır.');
?>