<?php

/**
 * @file
 * Implementation of hook_form_system_theme_settings_alter().
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function simpleblog_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
  // Define the configuration object for the theme.
  $config = \Drupal::config('simpleblog.settings');

  // Create the theme settings form.
  $form['simpleblog_settings'] = [
    '#type' => 'fieldset',
    '#title' => t('SimpleBlog Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  ];

  // Grid view option
  $form['simpleblog_settings']['grid_view'] = [
    '#type' => 'checkbox',
    '#title' => t('Show grid view in the front page'),
    '#default_value' => $config->get('grid_view'),
    '#description' => t("Check this option to show grid view in the home page."),
  ];

  // Banner settings
  $form['simpleblog_settings']['banner'] = [
    '#type' => 'fieldset',
    '#title' => t('Banner'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];

  $form['simpleblog_settings']['banner']['show'] = [
    '#type' => 'checkbox',
    '#title' => t('Show banner in the home page'),
    '#default_value' => $config->get('show'),
    '#description' => t("Check this option to show banner in the home page. Uncheck to hide."),
  ];

  $form['simpleblog_settings']['banner']['image'] = [
    '#type' => 'managed_file',
    '#title' => t('Image'),
    '#default_value' => $config->get('image'),
    '#upload_location' => 'public://',
  ];

  // Copyright text
  $form['simpleblog_settings']['copy'] = [
    '#type' => 'fieldset',
    '#title' => t('Copyright'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];

  $form['simpleblog_settings']['copy']['copyright'] = [
    '#type' => 'textfield',
    '#title' => t('Copyright Text'),
    '#default_value' => $config->get('copyright'),
  ];

  // Social links settings
  $form['simpleblog_settings']['social'] = [
    '#type' => 'fieldset',
    '#title' => t('Social Links'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  ];

  $social_links = ['drupal', 'facebook', 'instagram', 'twitter', 'googleplus'];
  foreach ($social_links as $social) {
    $form['simpleblog_settings']['social'][$social] = [
      '#type' => 'textfield',
      '#title' => t(ucwords($social) . ' Link'),
      '#default_value' => $config->get($social),
    ];
  }

  // Add a submit handler.
  $form['#submit'][] = 'simpleblog_settings_form_submit';

  // Ensure that theme settings file is included (if needed).
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $theme_file = \Drupal::service('extension.list.theme')->getPath($theme) . '/theme-settings.php';
  $build_info = $form_state->getBuildInfo();
  if (!in_array($theme_file, $build_info['files'])) {
    $build_info['files'][] = $theme_file;
  }
  $form_state->setBuildInfo($build_info);
}

/**
 * Submit handler for simpleblog theme settings form.
 */
function simpleblog_settings_form_submit(&$form, FormStateInterface $form_state) {
  $account = \Drupal::currentUser();
  $values = $form_state->getValues();

  // Handle the image upload and make it permanent.
  if (!empty($values['image'])) {
    $file = File::load($values['image'][0]);
    if ($file) {
      $file->setPermanent();
      $file->save();
      \Drupal::service('file.usage')->add($file, 'user', 'user', $account->id());
    }
  }

  // Save the theme settings to the config.
  \Drupal::configFactory()->getEditable('simpleblog.settings')
    ->set('grid_view', $values['grid_view'])
    ->set('show', $values['show'])
    ->set('image', $values['image'][0] ?? NULL)
    ->set('copyright', $values['copyright'])
    ->set('drupal', $values['drupal'])
    ->set('facebook', $values['facebook'])
    ->set('instagram', $values['instagram'])
    ->set('twitter', $values['twitter'])
    ->set('googleplus', $values['googleplus'])
    ->save();
}
