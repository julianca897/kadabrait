<?php

namespace Drupal\kadabrait_content\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UserContentController.
 */
class UserContentController extends ControllerBase {

  /**
   * Drupal\kadabrait_content\Service\GetContentInterface definition.
   *
   * @var \Drupal\kadabrait_content\Service\GetContentInterface
   */
  protected $getUserContent;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->getUserContent = $container->get('kadabrait_content_service.default');
    return $instance;
  }

  /**
   *
   */
  public function pageUserContent() {
    $content = $this->getUserContent->getContentUser(10);
    $results = [];
    foreach ($content as $item) {
      $results[] = [
        'nid' => $item->id(),
        'title' => $item->get('title')->value,
      ];
    }

    $header = [
      'title' => t('Nid'),
      'content' => t('Title'),
    ];

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $results,
      '#empty' => t('No data'),
    ];

    return [
      '#type' => 'markup',
      '#markup' => render($build),
    ];
  }

}
