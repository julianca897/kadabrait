<?php

namespace Drupal\kadabrait_content\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\kadabrait_content\Service\GetContentService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'UserContentBlock' block.
 *
 * @Block(
 *  id = "user_content_block",
 *  admin_label = @Translation("User content block"),
 * )
 */
class UserContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Drupal\kadabrait_content\Service\GetContentService definition.
   *
   * @var \Drupal\kadabrait_content\Service\GetContentService
   */
  protected $getContentUser;

  /**
   * UserContentBlock constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\kadabrait_content\Service\GetContentService $get_content
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    GetContentService $get_content
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->getContentUser = $get_content;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('kadabrait_content_service.default')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $content = $this->getContentUser->getContentUser(3);
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
    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $results,
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
  }

}
