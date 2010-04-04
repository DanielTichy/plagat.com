<?php
// DWP Customization include setup

class DwpConfig {
	// Nastaveni delky titlu v ja_newsflash/tmpl/block_item.php
	var $JaNewsBlockItemTitleLength = 28;
	
	// Nastaveni v JaBulletin s kolika prvnich znaku se pokusi ziskat url obrazku, jinak to vraci posledni obrazek s clanku.
	var $JaBulletingSearchImageFromFirstChars = 250;
	
	// Nastaveni zda se obrazky vykresluji jako aktvni odkazy na detail polozky
	var $GenerateImageAsLink = true;	
	var $GenerateHpImageAsLink = true;
	
	var $detail_show_intro = true;
}

?>