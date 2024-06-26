<?php declare(strict_types=1);

namespace PostService\Service;

use Symfony\Component\Uid\Uuid;

interface PostServiceInterface
{

    public function createPost(Uuid $userId, Uuid $groupId, string $content): bool;

    public function canUserPostToGroup(Uuid $userId, Uuid $groupId): bool;

    public function getPostsFromGroup(Uuid $userId, Uuid $groupId): array;

    public function deletePost(Uuid $userId, Uuid $postId): bool;
}