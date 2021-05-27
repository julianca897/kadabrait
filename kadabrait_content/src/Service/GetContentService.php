<?php

namespace Drupal\kadabrait_content\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Class GetContentService.
 */
class GetContentService implements GetContentInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private AccountInterface $current_user;

  /**
   * Constructs a new GetContentService object.
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    AccountInterface $current_user) {

    $this->entityTypeManager = $entityTypeManager;
    $this->current_user = $current_user;
  }

  /**
   * Function for return content created by current user.
   *
   * @param int $amount
   *   Content amount.
   *
   * @return array
   *   Array with content.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getContentUser(int $amount): array {
    $entity_handler = $this->entityTypeManager->getStorage('node');
    $query = $entity_handler->getQuery();
    $data = $query->condition('uid', $this->current_user->id())->range(0, $amount)
      ->sort('created', 'desc')->execute();
    return $entity_handler->loadMultiple($data);
  }

}
