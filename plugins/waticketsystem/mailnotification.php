<?php
/**
 * @version $Id: mailnotification.php 201 2009-11-29 08:37:36Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL, see LICENSE.php
 * @package wats-plugins
 * @subpackage mailnotification
 */

// no direct access
defined("_JEXEC") or die("Restricted access");

jimport("joomla.plugin.plugin");

/**
 * Path to the waticketsystem plugins
 */
define("WPATH_PLUGINS", JPATH_PLUGINS . DS . "waticketsystem");

/**
 * Plugin class that enables email notification of changes to tickets
 */
class plgWaticketsystemMailnotification extends JPlugin {
    
	function plgWaticketsystemMailnotification(&$subject, $config) {
		parent::__construct($subject, $config);
        $this->loadLanguage();
	}

    /**
     * Fire this when the ticket has just been created
     *
     * @param watsTicket $ticket
     */
    function onTicketNew(&$ticket) {
        $this->_sendNotification($ticket, "new");
	}
    
    /**
     * Fire this when a new reply to the ticket has just arrived. Note that this
     * is also used when a ticket is reopened.
     *
     * @param watsTicket $ticket
     */
    function onTicketReply(&$ticket) {
		$this->_sendNotification($ticket, "reply");
	}

    /**
     * Fire this when a ticket is reopened
     *
     * @param watsTicket $ticket
     */
    function onTicketReopen(&$ticket) {
		$this->_sendNotification($ticket, "reopen");
	}

    /**
     * Fire this when a ticket is assigned to a user
     *
     * @param watsTicket $ticket
     */
    function onTicketAssign(&$ticket) {
		$this->_sendNotification($ticket, "assign");
	}
    
    function _sendNotification(&$ticket, $type) {
        // check if we are supposed to notifying anyone
        if ($this->params->get($type . "-enabled", 0) != 1) {
            return;
        }
        
        // build the View object
        require_once(WPATH_PLUGINS . DS . "mailnotification" . DS . "view.php");
        $view = new WEmailView();
        $view->assignRef("ticket", $ticket);
        $view->assignRef("lastMessage", $ticket->_msgList[count($ticket->_msgList) - 1]);
        $ticketOwner =& JFactory::getUser($ticket->watsId);
        $view->assignRef("ticketOwner", $ticketOwner);
        $lastMessageOwner =& JFactory::getUser();
        $view->assignRef("lastMessageOwner", $lastMessageOwner);
        if (@$ticket->assignId) {
            $ticketAsignee =& JFactory::getUser($ticket->assignId);
            $view->assignRef("assignee", $ticketAsignee);
        }
        
        // determine the templates to use
        $tmplHTML = $this->params->get($type . "-tmpl-html", $type);
        $tmplTEXT = $this->params->get($type . "-tmpl-text", $type);
        
        // get the mailer object and email addresses of those users who we want to notify
        $mailer = JFactory::getMailer();
        $users =& $this->_getRelatedUsers($ticket);
        $users = array_merge($users, $this->_getNotificationUsers($ticket));
        if (@$ticketAsignee) {
            $ticketAsignee->watsid = $ticketAsignee->id;
            $users[] =& $ticketAsignee;
        }
        
        // we only want unique users, ignore duplicates!
        $uniqueUsers = array();
        for ($i = 0; $i < count($users); $i++) {
            $isUnique = true;
            for ($n = 0; $n < count($uniqueUsers); $n++) {
                if ($users[$i]->email == $uniqueUsers[$n]->email) {
                    $isUnique = false;
                }
            }
            if ($isUnique) {
                $uniqueUsers[] =& $users[$i];
            }
        }
        
        // loop through users
        for ($i = 0; $i < count($uniqueUsers); $i++) {
            $recipient =& $uniqueUsers[$i];
        
            // build the email
            $mailer->setSubject(JText::sprintf($this->params->get($type."-subject", "SUBJECT $type %s"), $ticket->name));
            $view->assignRef("recipient", $recipient);
            if ($this->params->get("email-allow-html", 1) == 1) {
                // HTML body with alternative
                $mailer->Body    = $view->render($tmplHTML, "html");
                $mailer->AltBody = $view->render($tmplTEXT, "text");
                $mailer->IsHTML(true);
            } else {
                // plain text body only
                $mailer->Body = $view->render($tmplTEXT, "text");
                $mailer->IsHTML(false);
            }

            if (!is_string($mailer->Body)) {
                // uh oh it's gone a bit wrong... let's quit now!
                return;
            }
            
            // set recipient
            $mailer->ClearAllRecipients();
            $mailer->addRecipient($recipient->email);
            
            // send email
            $result = $mailer->Send();
            if ($result !== true) {
                // uh oh, sending of email failed!
                JError::raiseWarning("500", JText::sprintf("FAILED TO SEND NOTIFICATION TO %s", $user->username));
            }
        }
    }
    
    function &_getRelatedUsers(&$ticket) {
        // get the DBO
        $database =& JFactory::getDBO();
        
        // build and execute query to get users who are related to the ticket
        $query = "SELECT DISTINCT u.id, m.watsid, u.email, u.username, u.name FROM #__wats_msg AS m LEFT  JOIN #__users AS u ON m.watsid=u.id WHERE m.ticketid = " . intval($ticket->ticketId) . " AND u.block = 0";
        $database->setQuery($query);
        $users = $database->loadObjectList();
        
        // return the users
        return $users;
    }
    
    function &_getNotificationUsers(&$ticket) {
        // get the notification email addresses
        $rawNotifyEmails = $this->params->get("email-others", "");
        $rawNotifyEmails .= ', '.$this->_getCategoryNotificationEmails($ticket->category);
        $notifyEmails = array();
        var_dump($rawNotifyEmails);
        if (!preg_match_all("~([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})~i", $rawNotifyEmails, $notifyEmails)) {
            $array = array();
            return $array;
        }
        $notifyEmails = $notifyEmails[0];
        
        // get the DBO
        $database =& JFactory::getDBO();
        
        // make email addresses SQL safe
        // doesn't really need this because of the pattern, but it's an implementation unaware mechanism for quoting values
        $safeNotifyEmails = array();
        for ($i = 0; $i < count($notifyEmails); $i ++) {
            $safeNotifyEmails[] = $database->Quote($notifyEmails[$i]);
        }
        
        // Find matching users
        $query = "SELECT DISTINCT u.id, u.email, u.username, u.name FROM #__users AS u WHERE u.email = ";
        $query .= implode(" OR u.email = ", $safeNotifyEmails);
        $database->setQuery($query);
        $users = $database->loadObjectList();
        
        // check we got everyone!
        for ($i = 0; $i < count($notifyEmails); $i ++) {
            // flag if we known the email address
            $isKnown = false;
            
            // look for the email
            for ($n = 0; $n < count($users); $n ++) {
                if ($users[$n]->email == $notifyEmails[$i]) {
                    $isKnown = true;
                    break;
                }
            }
            
            // uh oh, not known! better create a special generic unknown user...
            if (!$isKnown) {
                $unknownUser = new stdClass();
                $unknownUser->id       = 0;
                $unknownUser->watsid   = 0;
                $unknownUser->email    = $notifyEmails[$i];
                $unknownUser->name     = JText::_("UNKNOWN USER");
                $unknownUser->username = JText::_("UNKNOWN USER");
                $users[] = $unknownUser;
            }
        }
        
        // return the users
        return $users;
    }
    
    function _getCategoryNotificationEmails($catId) {
        // prep cache
        static $emails;
        if (!$emails) {
            $emails = array();
        }
        $catId = (int)$catId;
        
        // check if we need to load the emails from the category
        if (!array_key_exists($catId, $emails)) {
            // get the DBO
            $database =& JFactory::getDBO();
            $database->setQuery("SELECT c.emails FROM #__wats_category AS c WHERE c.catid = $catId");
            $emails[$catId] = $database->loadResult();
        }
        
        return $emails[$catId];
    }
}