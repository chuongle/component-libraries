<?php

/**
 * @file
 * Contains Drupal\component_libraries\Plugin\Block\BlockWithCustomTemplate.
 */

namespace Drupal\component_libraries\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'BlockWithCustomTemplate' block.
 *
 * @Block(
 *  id = "block_with_custom_template",
 *  admin_label = @Translation("Block with custom template"),
 * )
 */
class BlockWithCustomTemplate extends BlockBase {

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();
	  $form['title'] = array(
	    '#type' => 'textfield',
	    '#title' => $this->t('Title'),
	    '#default_value' => isset($config['title']) ? $config['title'] : '',
	  );
	  $form['body'] = array(
	    '#type' => 'text_format',
      '#title' => t('Body'),
      '#default_value' => isset($config['body']['value']) ? $config['body']['value'] : '',
      '#format' => 'basic_html',
	  );
	  $form['template'] = array(
	  	'#type' => 'textarea',
	  	'#title' => t('Custom Template'),
	  	'#default_value' => isset($config['template']) ? $config['template'] : '',
	  );

	  return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('title', $form_state->getValue('title'));
		$this->setConfigurationValue('body', $form_state->getValue('body'));
		$this->setConfigurationValue('template', $form_state->getValue('template'));
		$this->setConfigurationValue('classes', $form_state->getValue('classes'));
	}

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$config = $this->getConfiguration();
  	$config['template'] = str_replace('%title', $config['title'], $config['template']);
  	$config['template'] = str_replace('%body', $config['body']['value'], $config['template']);
		return [
		  '#theme' => 'block_with_custom_template',
		  '#title' => $config['title'],
		  '#body'  => $config['body']['value'],
		  '#template' => $config['template'],
		];
  }

}
