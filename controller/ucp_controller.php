<?php
/**
*
* @package Privacy Policy Extension
* @copyright (c) 2018 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\privacypolicy\controller;

use phpbb\config\config;
use phpbb\user;
use phpbb\request\request;
use phpbb\language\language;
use phpbb\template\template;
use david63\privacypolicy\core\privacypolicy;
use david63\privacypolicy\core\functions;

/**
* UCP controller
*/
class ucp_controller
{
	/** @var config */
	protected $config;

	/** @var user */
	protected $user;

	/** @var request */
	protected $request;

	/** @var language */
	protected $language;

	/** @var template */
	protected $template;

	/** @var privacypolicy */
	protected $privacypolicy;

	/** @var functions */
	protected $functions;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for ucp controller
	*
	* @param config				$config			Config object
	* @param user				$user			User object
	* @param request			$request		Request object
	* @param language			$language		Language object
	* @param template          	$template		Template object
	* @param privacypolicy		privacypolicy	Methods for the extension
	* @param functions			$functions		Functions for the extension
	*
	* @return \david63\privacypolicy\controller\ucp_controller
	* @access public
	*/
	public function __construct(config $config, user $user, request $request, language $language, template $template, privacypolicy $privacypolicy, functions $functions)
	{
		$this->config			= $config;
		$this->user				= $user;
		$this->request			= $request;
		$this->language			= $language;
		$this->template			= $template;
		$this->privacypolicy	= $privacypolicy;
		$this->functions		= $functions;
	}

	/**
	* Display the privacy data for a user
	*
	* @return null
	* @access public
	*/
	public function privacy_output()
	{
		// Add the language files
		$this->language->add_lang('ucp_privacypolicy', $this->functions->get_ext_namespace());
		$this->language->add_lang('common_privacypolicy', $this->functions->get_ext_namespace());

		// Create a form key for preventing CSRF attacks
		$form_key = 'privacy_policy_data';
		add_form_key($form_key);

		$error = '';

		// Is the form being submitted?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($form_key))
			{
				$error = $this->user->lang('FORM_INVALID');
			}
			else
			{
				$this->privacypolicy->create_csv($this->user->data['username'], $this->user->data['user_id']);
			}
		}

		$this->template->assign_vars(array(
			'ERROR'				=> ($error) ? true : false,
			'ERROR_MESSAGE'		=> $error,

			'NAMESPACE'			=> $this->functions->get_ext_namespace('twig'),

			'S_FORM_ENCTYPE'	=> 'enctype="multipart/form-data"',
			'S_UCP_ACTION'		=> $this->u_action,

			'U_REMOVE_ME'		=> $this->config['privacy_policy_remove'],
		));

		$this->privacypolicy->display_privacy_data($this->user->data['user_id']);
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		return $this->u_action = $u_action;
	}
}
