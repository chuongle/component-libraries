<?php

/**
 * @file
 * Contains Drupal\component_libraries\Plugin\Block\BandComponent.
 */

namespace Drupal\component_libraries\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'BandComponent' block.
 *
 * @Block(
 *  id = "band_component",
 *  admin_label = @Translation("Band with title, body and background image"),
 * )
 */
class BandComponent extends BlockBase {

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
	  $form['background_image'] = array(
	    '#type' => 'managed_file',
	    '#title' => t('Browse to upload'),
	    '#default_value' => isset($config['background_image']) ? $config['background_image'] : '',
	    '#upload_location' => 'public://background-image',
	  );
	  $form['classes'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Classes'),
	    'description'	=>	'These classes will be added to the wrapper of the block',
	    '#default_value' => isset($config['classes']) ? $config['classes'] : '',
	  );

	  return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->setConfigurationValue('title', $form_state->getValue('title'));
		$this->setConfigurationValue('body', $form_state->getValue('body'));
		$this->setConfigurationValue('background_image', $form_state->getValue('background_image'));
		$this->setConfigurationValue('classes', $form_state->getValue('classes'));

		$config = $this->getConfiguration();
		// https://api.drupal.org/api/examples/image_example
		// The new file's status is set to 0 or temporary and in order to ensure
		// that the file is not removed after 6 hours we need to change it's status
		// to 1. Save the ID of the uploaded image for later use.
    $background_image = file_load($config['background_image'][0]);
    $background_image->status = FILE_STATUS_PERMANENT;
    $background_image->save();
	}

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$config = $this->getConfiguration();
		return [
		  '#theme' => 'band_component',
		  '#title' => $config['title'],
		  '#body'  => $config['body']['value'],
		  '#background_image'  =>  !empty($config['background_image']) ? file_create_url(file_load($config['background_image'][0])->getFileUri()) : '',
		  '#classes'  => $config['classes'],
		];
  }

}
