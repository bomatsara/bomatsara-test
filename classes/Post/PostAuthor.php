<?php

namespace BomatsaraTest\Post;

class PostAuthor
{
    public function get_admin_user_id(): ?int
    {
        $args = [
            'role' => 'Administrator',
            'orderby' => 'ID',
            'order' => 'ASC',
            'number' => 1,
        ];

        $users = get_users($args);

        if (!empty($users)) {
            return $users[0]->ID;
        }

        return null;
    }
}