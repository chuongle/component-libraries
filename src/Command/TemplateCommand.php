<?php

/**
 * @file
 * Contains Drupal\component_libraries\Command\TemplateCommand.
 */

namespace Drupal\component_libraries\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Drupal\AppConsole\Command\ContainerAwareCommand;

/**
 * Class TemplateCommand.
 *
 * @package Drupal\component_libraries
 */
class TemplateCommand extends ContainerAwareCommand {
  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('generate:template')
      ->setDescription("Generate Template")
      ->addArgument('template_name', InputArgument::REQUIRED, "What is the name of the template?");
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $helper = $this->getHelper('question');

    // get template name
    $template_name = $input->getArgument('template_name');

    // create templates folder if not exist
    if (!file_exists('modules/custom/' . $this->getModule() . '/templates')) {
      mkdir('modules/custom/' . $this->getModule() . '/templates', 0777, true);
    }

    if(file_exists('modules/custom/' . $this->getModule() . '/templates/'.$template_name.".html.twig")) {
      $question = new ConfirmationQuestion("File existed. Do you want to override? (yes/no) ", false);
      $answer = $helper->ask($input, $output, $question);
      if($answer) {
        $template_directory = 'modules/custom/' . $this->getModule() . '/templates/';
        
        $output->writeln($text);

        $templateFile = fopen($template_directory.$template_name.".html.twig", "w");
        fwrite($templateFile, "{# Drop your template into this file and replace static content with dynamic content #}");
        fclose($templateFile);
      }else {
        return;
      }
    }
  }

}
