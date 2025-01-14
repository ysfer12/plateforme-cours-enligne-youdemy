<?php
namespace App\Controllers;

use App\Models\TagsModel;
use App\Classes\Tags;

class TagsController {
    private $tagsModel;

    public function __construct() {
        $this->tagsModel = new TagsModel();
    }

    public function getTags() {
        return $this->tagsModel->getTags();
    }

    public function getTagById($id) {
        return $this->tagsModel->getTagById($id);
    }

    public function addTag($nom) {
        return $this->tagsModel->addTag($nom);
    }

    public function addTags($tagNames) {
        // Support adding multiple tags
        $addedTags = [];
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                $addedTags[] = $this->addTag($tagName);
            }
        }
        return $addedTags;
    }

    public function updateTag($id, $nom) {
        return $this->tagsModel->updateTag($id, $nom);
    }

    public function deleteTag($id) {
        return $this->tagsModel->deleteTag($id);
    }
}