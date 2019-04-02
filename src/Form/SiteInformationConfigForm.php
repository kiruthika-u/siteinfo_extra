<?php

namespace Drupal\siteinfo_extra\Form;

// Classes referenced in this class:
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\system\Form\SiteInformationForm;

/**
 * Extending basic site information form.
 */

 class SiteInformationConfigForm extends SiteInformationForm{

	public function buildForm(array $form, FormStateInterface $form_state)
	{
		$config = $this->config('system.site');
		$form = parent::buildForm($form, $form_state);
		$form['site_information']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $config->get('siteapikey')?$config->get('siteapikey'):'',
			'#description' => t('This is the key to be used in API request'),
			'#attributes' => array('placeholder' => t('No API Key yet')),
		];
		$form['actions']['submit']['#value'] = 'Update Configuration';
		return $form;
	}

	/** 
   * {@inheritdoc}
   */
	public function submitForm(array &$form, FormStateInterface $form_state)
  {    
		$updateInfo = $this->config('system.site')
		->set('siteapikey', $form_state->getValue('siteapikey'))
		->save();
		parent::submitForm($form, $form_state);
		if($updateInfo && $form_state->getValue('siteapikey')!='')
		drupal_set_message(t('API Key %key has been updated.',array('%key'=>$form_state->getValue('siteapikey'))), 'status');		
  }
 }
