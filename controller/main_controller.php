<?php
/**
*
* @package Privacy Policy Extension
* @copyright (c) 2018 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\privacypolicy\controller;

use phpbb\exception\http_exception;
use phpbb\user;
use phpbb\request\request;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\config\config;
use phpbb\language\language;
use david63\privacypolicy\core\privacypolicy_lang;
use david63\privacypolicy\core\privacypolicy;
use david63\privacypolicy\core\functions;

class main_controller
{
	/** @var user */
	protected $user;

	/** @var request */
	protected $request;

	/** @var helper */
	protected $helper;

	/** @var driver_interface */
	protected $db;

	/** @var template */
	protected $template;

	/** @var config */
	protected $config;

	/** @var language */
	protected $language;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $phpEx;

	/** @var privacypolicy_lang */
	protected $privacypolicy_lang;

	/** @var privacypolicy */
	protected $privacypolicy;

	/** @var functions */
	protected $functions;

	/** @var array phpBB tables */
	protected $tables;

	/**
	* Constructor
	*
	* @param user					$user				User object
	* @param request				$request			Request object
	* @param helper					$helper				Helper object
	* @param driver_interface		$db					The db connection
	* @param template				$template			Template object
	* @param config					$config				Config object
	* @param language				$language			Language object
	* @param string					$phpbb_root_path	phpBB root path
	* @param string					$php_ext            phpBB extension
	* @param privacypolicy_lang		privacypolicy_lang  Methods for the extension
	* @param privacypolicy			privacypolicy		Methods for the extension
	* @param functions				$functions			Functions for the extension
	* @param array					$tables				phpBB db tables
	*
	* @return \david63\privacypolicy\controller\acp_managemain
	*/
	public function __construct(user $user, request $request, helper $helper, driver_interface $db, template $template, config $config, language $language, $root_path, $php_ext, privacypolicy_lang $privacypolicy_lang, privacypolicy $privacypolicy, functions $functions, $tables)
	{
		$this->user					= $user;
		$this->request				= $request;
		$this->helper				= $helper;
		$this->db					= $db;
		$this->template				= $template;
		$this->config				= $config;
		$this->language	   			= $language;
		$this->root_path			= $root_path;
		$this->php_ext				= $php_ext;
		$this->privacypolicy_lang 	= $privacypolicy_lang;
		$this->privacypolicy		= $privacypolicy;
		$this->functions			= $functions;
		$this->tables				= $tables;
	}

	/**
	* Process the acceptance/denial
	*
	* @return null
	* @access public
	*/
	public function acceptance()
	{
		// Create a form key for preventing CSRF attacks
		$form_key = 'privacypolicy_accept';
		add_form_key($form_key);

		// Is the form being submitted?
		if ($this->request->is_set_post('accept') || $this->request->is_set_post('decline'))
		{
			// Is the submitted form is valid?
			if (!check_form_key($form_key))
			{
				throw new http_exception(400, 'FORM_INVALID');
			}

			// The user has accepted the policy so we add it to the db
			if ($this->request->is_set_post('accept'))
			{
				// Set selected groups to 1
				$sql = 'UPDATE ' . $this->tables['users'] . '
					SET user_accept_date = ' . time() . '
					WHERE user_id = ' . (int) $this->user->data['user_id'];

				$this->db->sql_query($sql);

				// Update Auto Groups
				$this->privacypolicy->update_auto_groups($this->user->data['user_id']);

				redirect(append_sid("{$this->root_path}index.$this->php_ext"));
			}

			// The user has declined the policy so we log them out
			if ($this->request->is_set_post('decline'))
			{
				redirect(append_sid("{$this->root_path}ucp.$this->php_ext", 'mode=logout', true, $this->user->session_id));
			}
		}

		$privacy_text 	= $this->privacypolicy_lang->get_text('privacy_policy', $this->user->data['user_lang']);
		$privacy_accept	= $this->privacypolicy_lang->get_text('privacy_policy_accept', $this->user->data['user_lang']);

		$this->template->assign_vars(array(
			'ACCEPT_MESSAGE'	=> generate_text_for_display($privacy_text['privacy_lang_text'], $privacy_text['privacy_text_bbcode_uid'], $privacy_text['privacy_text_bbcode_bitfield'], $privacy_accept['privacy_text_bbcode_options']) . generate_text_for_display($privacy_accept['privacy_lang_text'], $privacy_accept['privacy_text_bbcode_uid'], $privacy_accept['privacy_text_bbcode_bitfield'], $privacy_accept['privacy_text_bbcode_options']),

			'U_ACTION'			=> $this->helper->route('david63_privacypolicy_acceptance'),
		));

		return $this->helper->render('policy_accept.html', $this->language->lang('POLICY_ACCEPT'));
	}

	/**
	* Controller for route /privacypolicy/{name}
	*
	* @param string		$name
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function policyoutput($name)
	{
		$cookie_message	= $output_name = '';

		switch ($name)
		{
			case 'policy':
				if ($this->config['cookie_policy_enable'])
				{
					$cookie_text 	= $this->privacypolicy_lang->get_text('cookie_policy', $this->user->data['user_lang']);
					$cookie_message	= generate_text_for_display($cookie_text['privacy_lang_text'], $cookie_text['privacy_text_bbcode_uid'], $cookie_text['privacy_text_bbcode_bitfield'], $cookie_text['privacy_text_bbcode_options']);
				}

				if ($this->config['privacy_policy_enable'])
				{
					$cookie_text 	= $this->privacypolicy_lang->get_text('privacy_policy', $this->user->data['user_lang']);
					$cookie_message .= generate_text_for_display($cookie_text['privacy_lang_text'], $cookie_text['privacy_text_bbcode_uid'], $cookie_text['privacy_text_bbcode_bitfield'], $cookie_text['privacy_text_bbcode_options']);
				}

				$this->template->assign_var('COOKIE_MESSAGE', $cookie_message);
				$output_name = $this->language->lang('COOKIE_POLICY');
			break;

			case 'access':
				$this->template->assign_var('COOKIE_MESSAGE', $this->language->lang('COOKIE_REQUIRE_ACCESS', $this->config['sitename']));
				$output_name = $this->language->lang('COOKIE_ACCESS');
			break;
		}

		$this->template->assign_vars(array(
			'COOKIE_PAGE_BG_COLOUR'		=> $this->config['cookie_page_bg_colour'],
			'COOKIE_PAGE_CORNERS'		=> $this->config['cookie_page_corners'],
			'COOKIE_PAGE_RADIUS'		=> $this->config['cookie_page_radius'],
			'COOKIE_PAGE_TXT_COLOUR'	=> $this->config['cookie_page_txt_colour'],

			'NAMESPACE'					=> $this->functions->get_ext_namespace('twig'),

			'S_COOKIE_CUSTOM_PAGE'		=> $this->config['cookie_custom_page'],
		));

		return $this->helper->render('cookie_body.html', $output_name);
	}
}
