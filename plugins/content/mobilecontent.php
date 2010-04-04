<?php
/**
 * mobilecontent.php
 *
 * PHP version 5
 *
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Content
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

$mainframe->registerEvent('onPrepareContent', 'plgMobileContent');

/**
 * @category   Plugins
 * @package    Wafl
 * @subpackage Plugin.Content
 * @author     Heiko Desruelle <heiko.desruelle@ugent.be>
 * @author     Stijn De Vos <stdevos.devos@ugent.be>
 * @author     Klaas Lauwers <klaas.lauwers@ugent.be>
 * @author     Robin Leblon <robin.leblon@ugent.be>
 * @author     Mattias Poppe <mattias.poppe@ugent.be>
 * @author     Daan Van Britsom <daan.vanbritsom@ugent.be>
 * @author     Rob Vanden Meersche <rob.vandenmeersch@ugent.be>
 * @author     Kristof Vandermeeren <kristof.vandermeeren@ugent.be>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    Release: @package_version@
 * @link       http://www.wafl.ugent.be
 * 
 */

/**
 * Main function
 * 
 * @param String &$article The article
 * @param array  &$params  The parameters to set
 * @param int    $page     The page
 * 
 * @return unknown_type
 */
function plgMobileContent(&$article, &$params, $page=0)
{
    $view=JRequest::getCmd('view');

    if ($_SESSION['mobile'] && $view == 'frontpage') {
        
        $plugin			=& JPluginHelper::getPlugin('content', 'mobilecontent');
        $pluginParams	= new JParameter($plugin->params);
        
        $readmore = $pluginParams->get('readmore', 0);
        if ($readmore) {
            $mobilebreak = strpos($article->text, "<hr id=\"system-readmore-mobile\" />");

            if (!($mobilebreak === false)) {
                $article->text = substr($article->text, 0, $mobilebreak);
                $article->readmore = 1;
            }

            $mobilebreak = strpos($article->fulltext, "<hr id=\"system-readmore-mobile\" />");

            if (!($mobilebreak === false)) {
                $article->text = $article->text.substr($article->fulltext, 0, $mobilebreak);
                $article->readmore = 1;
            }
        
            // TODO: Checken van instelling 'auto-readmore' en in dat geval altijd
            // enkel de eerste x zinnen/paragrafen weergeven als sessie mobiel is
        } else {
            $regex = '#<hr([^>]*?)id=(\"|\')system-readmore-mobile(\"|\')([^>]*?)\/*>#iU';
            $article->text = preg_replace($regex, '', $article->text);
        }
        
        $showtitle = $pluginParams->get('showtitle', 1);
        $params->set('show_title', $showtitle);
        
        $showauthor = $pluginParams->get('showauthor', 1);
        $params->set('show_author', $showauthor);

        $showcreatedate = $pluginParams->get('showcreatedate', 1);
        $params->set('show_create_date', $showcreatedate);
        
        $showmodifydate = $pluginParams->get('showmodifydate', 1);
        $params->set('show_modify_date', $showmodifydate);
        
        $showmailicon = $pluginParams->get('showmailicon', 1);
        $params->set('show_email_icon', $showmailicon);
        
        $showpdficon = $pluginParams->get('showpdficon', 1);
        $params->set('show_pdf_icon', $showpdficon);
        
        $showprinticon = $pluginParams->get('showprinticon', 1);
        $params->set('show_print_icon', $showprinticon);
    } else {
        $regex = '#<hr([^>]*?)id=(\"|\')system-readmore-mobile(\"|\')([^>]*?)\/*>#iU';
        $article->text = preg_replace($regex, '', $article->text);
    }
    return true;
}

?>
