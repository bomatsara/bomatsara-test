<?php

namespace BomatsaraTest\Post;

class PostCategory
{
    public function get_category_by_name(string $category_name): ?int
    {
        $category = get_term_by('name', $category_name, 'category');

        if ($category) {
            return $category->term_id;
        }

        return $this->create_category($category_name);
    }

    public function create_category(string $category_name): bool|int
    {
        $result = wp_insert_term($category_name, 'category');

        if (is_wp_error($result)) {
            return false;
        }

        return $result['term_id'];
    }
}