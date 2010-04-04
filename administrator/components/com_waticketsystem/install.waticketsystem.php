<?php
/**
 * @version $Id: install.waticketsystem.php 203 2009-12-12 06:21:50Z webamoeba $
 * @copyright Copyright (C) James Kennard
 * @license GNU/GPL
 * @package wats
 */

// Don't allow direct linking
defined('_JEXEC') or die('Restricted Access');

function changeIcon( $name, $icon ) {
	$database = JFactory::getDBO();
	$database->setQuery( "UPDATE #__components SET admin_menu_img=\"".$icon."\" WHERE " . $database->nameQuote('name') . "=\"".$name."\" AND " . $database->nameQuote('option') . "=\"com_waticketsystem\";");
	$database->query();
}

function com_install()
{
	$database = JFactory::getDBO();

	// new install
	$version = "0.0.0";
	// determine upgrade status
	$database->setQuery( "DESCRIBE #__wats_settings" );
	$vars = $database->loadObjectList();
	// if ( count($vars) != 0 )
	if ( $database->getErrorNum() == 0 )
	{
		// upgrade
		$database->setQuery( "SELECT value FROM #__wats_settings WHERE name=\"upgrade\"" );
		$vars = $database->loadObjectList();
		if ( count( $vars ) == 1 )
		{
			if ( $vars[0]->value == 1 )
			{
				$upgrade = true;
				$database->setQuery( "SELECT value FROM #__wats_settings WHERE name=\"versionmajor\"" );
				$vars = $database->loadObjectList();
				$version = $vars[0]->value;
				$database->setQuery( "SELECT value FROM #__wats_settings WHERE name=\"versionminor\"" );
				$vars = $database->loadObjectList();
				$version .= '.'.$vars[0]->value;
				$database->setQuery( "SELECT value FROM #__wats_settings WHERE name=\"versionpatch\"" );
				$vars = $database->loadObjectList();
				$version .= '.'.$vars[0]->value;
			}
		}
	}
	// end determine upgrade status
	
	switch ( $version ) {
		/**
		 * new install
		 */	
		case '0.0.0':
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_category') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_category') . " (
					  " . $database->nameQuote('catid') . " int(11) NOT NULL auto_increment,
					  " . $database->nameQuote(name) . " varchar(50) NOT NULL default '',
					  " . $database->nameQuote('description') . " varchar(255) default NULL,
					  " . $database->nameQuote('image') . " varchar(255) default NULL,
					  PRIMARY KEY  (" . $database->nameQuote('catid') . "),
					  UNIQUE KEY " . $database->nameQuote('name') . " (" . $database->nameQuote('name') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_category') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_category') . " (
					  " . $database->nameQuote('catid') . " int(11) NOT NULL auto_increment,
					  " . $database->nameQuote('name') . " varchar(50) NOT NULL default '',
					  " . $database->nameQuote('description') . " varchar(255) default NULL,
					  " . $database->nameQuote('image') . " varchar(255) default NULL,
					  PRIMARY KEY  (" . $database->nameQuote('catid') . "),
					  UNIQUE KEY " . $database->nameQuote('name') . " (" . $database->nameQuote('name') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_groups') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_groups') . " (
					  " . $database->nameQuote('grpid') . " int(10) unsigned NOT NULL auto_increment,
					  " . $database->nameQuote('name') . " varchar(50) NOT NULL default '',
					  " . $database->nameQuote('image') . " varchar(255) default NULL,
					  " . $database->nameQuote('userrites') . " varchar(4) NOT NULL default '----',
					  PRIMARY KEY  (" . $database->nameQuote('grpid') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_highlight') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_highlight') . " (
                      " . $database->nameQuote('watsid') . " int(11) NOT NULL default '0',
                      " . $database->nameQuote('ticketid') . " int(11) NOT NULL default '0',
                      " . $database->nameQuote('datetime') . " timestamp,
                      PRIMARY KEY  (" . $database->nameQuote('watsid') . "," . $database->nameQuote('ticketid') . ")
                    );");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_msg') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE " . $database->nameQuote('#__wats_msg') . " (
					  " . $database->nameQuote('msgid') . " int(11) NOT NULL auto_increment,
					  " . $database->nameQuote('ticketid') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('watsid') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('msg') . " text NOT NULL,
					  " . $database->nameQuote('datetime') . " timestamp,
					  PRIMARY KEY  (" . $database->nameQuote('msgid') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_permissions') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_permissions') . " (
					  " . $database->nameQuote('grpid') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('catid') . " int(11) default '0',
					  " . $database->nameQuote('type') . " varchar(8) NOT NULL default '',
					  KEY " . $database->nameQuote('grpid') . " (" . $database->nameQuote('grpid') . "," . $database->nameQuote('catid') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_settings') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_settings') . " (
					  " . $database->nameQuote('name') . " varchar(255) NOT NULL default '',
					  " . $database->nameQuote('value') . " varchar(255) default NULL,
					  PRIMARY KEY  (" . $database->nameQuote('name') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_ticket') . ";");
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_ticket') . " (
					  " . $database->nameQuote('watsid') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('ticketid') . " int(11) NOT NULL auto_increment,
					  " . $database->nameQuote('ticketname') . " varchar(25) NOT NULL default '',
					  " . $database->nameQuote('lifecycle') . " tinyint(1) NOT NULL default '0',
					  " . $database->nameQuote('datetime') . " timestamp,
					  " . $database->nameQuote('category') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('assign') . " int(11) default NULL,
					  PRIMARY KEY  (" . $database->nameQuote('ticketid') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_users') . ";");
			$database->query();
			$database->setQuery( "CREATE TABLE  " . $database->nameQuote('#__wats_users') . " (
					  " . $database->nameQuote('watsid') . " int(11) NOT NULL default '0',
					  " . $database->nameQuote('organisation') . " varchar(25) NOT NULL default '',
					  " . $database->nameQuote('agree') . " tinyint(1) NOT NULL default '0',
					  " . $database->nameQuote('grpid') . " int(11) NOT NULL default '0',
					  PRIMARY KEY  (" . $database->nameQuote('watsid') . ")
					);");
			$database->query();
			$database->setQuery( "DROP TABLE IF EXISTS " . $database->nameQuote('#__wats_agree') . ";");
			$database->query();			
			$database->setQuery( "INSERT INTO " . $database->nameQuote('#__wats_settings') . " (" . $database->nameQuote('name') . "," . $database->nameQuote('value') . ") VALUES ('iconset','mdn_'),
                                                                                               ('highlight','!'),
                                                                                               ('enhighlight','1'),
                                                                                               ('ticketsfront','5'),
                                                                                               ('ticketssub','15'),
                                                                                               ('sourceemail',''),
                                                                                               ('msgbox','bbcode'),
                                                                                               ('name','Webamoeba Ticket System'),
                                                                                               ('users','1'),
                                                                                               ('agree','0'),
                                                                                               ('agreei',''),
                                                                                               ('agreelw','You must agree to the following terms to use this system'),
                                                                                               ('agreen','agreement'),
                                                                                               ('agreela','If you have read the terms please continue'),
                                                                                               ('agreeb','continue'),
                                                                                               ('view','a'),
                                                                                               ('msgboxh','10'),
                                                                                               ('msgboxw','58'),
                                                                                               ('msgboxt','1'),
                                                                                               ('dorganisation','individual'),
                                                                                               ('copyright','WebAmoeba Ticket System for Mambo and Joomla'),
                                                                                               ('date','j-M-Y (h:i)'),
                                                                                               ('defaultmsg','type here...'),
                                                                                               ('dateshort','j-M-Y'),
                                                                                               ('assignname','Assigned Tickets'),
                                                                                               ('assigndescription','Tickets assigned to you to answer'),
                                                                                               ('assignimage',''),
                                                                                               ('css','disable'),
                                                                                               ('versionname','stable'),
                                                                                               ('upgrade','0'),
                                                                                               ('userdefault','1'),
                                                                                               ('usersimport','1'),
                                                                                               ('debug','0'),
                                                                                               ('debugmessage','Continue >>'),
                                                                                               ('versionmajor','0'),
                                                                                               ('versionminor','0'),
                                                                                               ('versionpatch','0');");
			$database->query();
			$database->setQuery( "INSERT INTO " . $database->nameQuote('#__wats_groups') . " (" . $database->nameQuote('grpid') . "," . $database->nameQuote('name') . "," . $database->nameQuote('image') . "," . $database->nameQuote('userrites') . ") VALUES (1,'user','components/com_waticketsystem/images/mdn_userSmall.jpg','----'),(2,'advisor','components/com_waticketsystem/images/mdn_userSmallGreen.jpg','V---'),(3,'administrator','components/com_waticketsystem/images/mdn_userSmallRed.jpg','VMED');");
			$database->query();
			$database->setQuery( "INSERT INTO " . $database->nameQuote('#__wats_permissions') . " (" . $database->nameQuote('grpid') . "," . $database->nameQuote('catid') . "," . $database->nameQuote('type') . ") VALUES (1,1,'vmrcd---'),(2,1,'VmRCDPAO'),(3,1,'VmRCDPAO');");
			$database->query();
			$database->setQuery( "INSERT INTO " . $database->nameQuote('#__wats_category') . " (" . $database->nameQuote('catid') . "," . $database->nameQuote('name') . "," . $database->nameQuote('description') . "," . $database->nameQuote('image') . ") VALUES (1,'Default Category','If there are no other suitable categories submit your tickets here ;)',NULL);");			
			$database->query();
		/**
		 * patch from 2.0.0 to 2.0.1
		 */	
		case '2.0.0':
			$database->setQuery( "INSERT INTO " . $database->nameQuote('#__wats_settings') . " (" . $database->nameQuote('name') . "," . $database->nameQuote('value') . ") VALUES ('   ','0'),('debugmessage','Continue >>');");
			$database->query();
		/**
          * patch from 2.0.1 - 2.0.8 to 3.0.0
		 */	
        case '2.0.1':
		case '2.0.2':
		case '2.0.3':
		case '2.0.4':
		case '2.0.5':
		case '2.0.6':
		case '2.0.7':
		case '2.0.8':
            $database->setQuery( "UPDATE " . $database->nameQuote('#__wats_settings') . " SET " . $database->nameQuote('value') . "='release candidate' WHERE " . $database->nameQuote('name') . "='versionnane';" );
			$database->query();
            $database->setQuery( "UPDATE " . $database->nameQuote('#__wats_settings') . " SET " . $database->nameQuote('value') . "='WebAmoeba Ticket System for Joomla!' WHERE " . $database->nameQuote('name') . "='copyright';" );
			$database->query();
            
            $database->setQuery("DELETE FROM " . $database->nameQuote('#__wats_settings') . " WHERE " . $database->nameQuote('name') . " = 'newpostmsg' OR
                                                                      " . $database->nameQuote('name') . " = 'newpostmsg1' OR 
                                                                      " . $database->nameQuote('name') . " = 'newpostmsg2' OR 
                                                                      " . $database->nameQuote('name') . " = 'newpostmsg3' OR 
                                                                      " . $database->nameQuote('name') . " = 'notifyusers' OR
                                                                      " . $database->nameQuote('name') . " = 'notifyemail'");
            $database->query();	
        
		/**
         * patch from 3.0.0 - 3.0.3 to 3.0.4
		 */
        case '3.0.0':
		case '3.0.1':
		case '3.0.2':
		case '3.0.3':
            // increase size of ticket name
            $database->setQuery( "ALTER TABLE " . $database->nameQuote('#__wats_ticket') . " MODIFY COLUMN " . $database->nameQuote('ticketname') . " VARCHAR(255) NOT NULL;" );
			$database->query();            
            // convert timestamp fields to datetime fields
            $database->setQuery('ALTER TABLE ' . $database->nameQuote('#__wats_highlight') . ' MODIFY COLUMN ' . $database->nameQuote('datetime') . ' DATETIME NOT NULL;');
            $database->query();
            $database->setQuery('ALTER TABLE ' . $database->nameQuote('#__wats_ticket') . ' MODIFY COLUMN ' . $database->nameQuote('datetime') . ' DATETIME NOT NULL;');
            $database->query();
            $database->setQuery('ALTER TABLE ' . $database->nameQuote('#__wats_msg') . ' MODIFY COLUMN ' . $database->nameQuote('datetime') . ' DATETIME NOT NULL;');
            $database->query();
            $database->setQuery("UPDATE " . $database->nameQuote('#__wats_settings') . " SET " . $database->nameQuote('value') . " = '%d-%m-%Y (%H:%M)' WHERE " . $database->nameQuote('name') . " = 'date'");
            $database->query();
            $database->setQuery("UPDATE " . $database->nameQuote('#__wats_settings') . " SET " . $database->nameQuote('value') . " = '%d-%m-%Y' WHERE " . $database->nameQuote('name') . " = 'dateshort'");
            $database->query();
        /**
         * patch from 3.0.4 to 3.0.5
		 */
		case '3.0.4':
            // add email field to categories
            $database->setQuery( "ALTER TABLE " . $database->nameQuote('#__wats_category') . " ADD COLUMN " . $database->nameQuote('emails') . " VARCHAR(255) NOT NULL;" );
			$database->query();            
	}
    
    // set the version
    $database->setQuery('UPDATE ' . $database->nameQuote('#__wats_settings') . 
                        ' SET ' . $database->nameQuote('value') . '=' . intval(3) .
                        ' WHERE ' . $database->nameQuote('name') . '=' . $database->Quote('versionmajor'));
    $database->query();
    $database->setQuery('UPDATE ' . $database->nameQuote('#__wats_settings') . 
                        ' SET ' . $database->nameQuote('value') . '=' . intval(0) .
                        ' WHERE ' . $database->nameQuote('name') . '=' . $database->Quote('versionminor'));
    $database->query();
    $database->setQuery('UPDATE ' . $database->nameQuote('#__wats_settings') . 
                        ' SET ' . $database->nameQuote('value') . '=' . intval(5) .
                        ' WHERE ' . $database->nameQuote('name') . '=' . $database->Quote('versionpatch'));
    $database->query();
    $database->setQuery('UPDATE ' . $database->nameQuote('#__wats_settings') . 
                        ' SET ' . $database->nameQuote('value') . '=' . $database->Quote('stable') .
                        ' WHERE ' . $database->nameQuote('name') . '=' . $database->Quote('versionname'));
    $database->query();

	changeIcon("WATicketSystem", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("About", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("Ticket Viewer", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("User Manager", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("Configure", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("CSS", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("Rights Manager", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("Category Manager", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	changeIcon("Database Maintenance", "../components/com_waticketsystem/images/mdn_ticket1616.gif");
	echo "<table class=\"adminlist\">
				<thead>
					<tr>
						<th>
							<div style=\"text-align: center;\">
								WebAmoeba Ticket System<br />
								3.0.5 stable
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td nowrap=\"true\" align=\"center\">
							<div style=\"text-align: center;\">
								<p><strong>Developers</strong><br />
								<a href=\"mailto:james@webamoeba.co.uk\">James Kennard</a></p>
								<p><strong>Web</strong><br />
								<a href=\"http://www.webamoeba.co.uk\" target=\"_blank\">www.webamoeba.co.uk</a></p>
								<p><strong>Libraries</strong><br />
								BBCode - Leif K-Brooks</p>
								<p><strong>Translations</strong><br />
                                en-GB - English - 	 James Kennard<br />
                                pt-BR -	Brazillian -	Mauro Machado<br />
                                cs-CZ -	Czech -	Luk·ö NÏmec<br />
                                fa-IR -	Farsi -	AmirReza Tehrani<br />
                                fr-FR -	French -	Johan Aubry<br />
                                da-DK -	Dansih - Soren Oxholm<br />
                                de-DE -	German -	Chr.G‰rtner<br />
                                el-GR -	Greek -	George Yiftoyiannis<br />
                                it-IT -	Italian -	Leonardo Lombardi<br />
                                nb-NO -	Norwegian - Erol Haagenrud<br />
                                nl-NL -	Netherlands 	 <br />
                                pt-PT -	Portuguese -	Jorge Rosado<br />
                                ro-RO -	Romainian - Tudor Jitianu<br />
                                ru-RU -	Russian -	Vasily Korotkov<br />
                                sr-RS -	Serbian -	Ivica Petrovic<br />
                                sl-SI -	Slovenian -	Matjaz Krmelj<br />
                                sk-SK -	Slovak -	Daniel K·Ëer<br />
                                es-ES -	Spanish -	Urano Gonzalez & Ventura Ventolera<br />
                                sv-SE -	Swedish -	Thomas Westman<br />
                                tr-TR -	Turkish
								<p><strong>Beta Testers</strong><br />
								72dpi<br />
								ateul<br />
								backupnow<br />
								claudio<br />
								DanielMD<br />
								elmar<br />
								gaertner65<br />
								gdude66<br />
								jrpi<br />
								laurie_lewis<br />
								lexel<br />
								peternie<br />
								ravenswood<br />
								Skye<br />
								tvinhas<br />
								urano</p>
							</div>
						</td>
					</tr>
				</tbody>
			</table>";
}

?>