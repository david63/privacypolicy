<?php
/**
 *
 * @package Move Disapproved Posts
 * @copyright (c) 2020 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'CLICK_DONATE' 				=> 'Click here to donate',

	'DONATE' 					=> 'Donate',
	'DONATE_EXTENSIONS' 		=> 'Donate to my extensions',
	'DONATE_EXTENSIONS_EXPLAIN'	=> 'This extension, as with all of my extensions, is totally free of charge. If you have benefited from using it then please consider making a donation by clicking the PayPal donation button, or use the “Scan, Pay, Go” QR image, opposite - I would appreciate it.<br><br>I promise that there will be no spam nor requests for further donations, although they would always be welcome.',

	'NEW_VERSION' 				=> 'New Version - %s',
	'NEW_VERSION_EXPLAIN' 		=> 'Version %1$s of this extension is now available for download.<br>%2$s',
	'NEW_VERSION_LINK' 			=> 'Download here',
	'NO_JS' 					=> 'You appear to have javascript disabled.<br>Please <a href="https://enablejavascript.co/">enable</a> it in your browser to be able to take advantage of all the features on this page.',
	'NO_VERSION_EXPLAIN' 		=> 'Version update information is not available.',

	'PAYPAL_BUTTON' 			=> 'Donate with PayPal button',
	'PAYPAL_TITLE' 				=> 'PayPal - The safer, easier way to pay online!',
	'PHP_NOT_VALID' 			=> 'Your version of PHP is not compatible with this extension.',
	'PHPBB_NOT_VALID' 			=> 'Your version of phpBB is not compatible with this extension.',

	'SAVE'						=> 'Save',
	'SAVE_CHANGES'				=> 'Save changes',

	'VERSION' 					=> 'Version',
]);
