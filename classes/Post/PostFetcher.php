<?php

namespace BomatsaraTest\Post;

use BomatsaraTest\API\APIPost;

class PostFetcher
{
    private Post $post;
    private PostCategory $post_category;
    private PostThumbnail $post_thumbnail;
    private APIPost $api;
    private PostAuthor $post_author;

    public function __construct(
        APIPost       $api,
        Post          $post,
        PostCategory  $postCategory,
        PostThumbnail $post_thumbnail,
        PostAuthor $post_author
    ) {
        $this->api = $api;
        $this->post = $post;
        $this->post_category = $postCategory;
        $this->post_thumbnail = $post_thumbnail;
        $this->post_author = $post_author;
    }

    public function fetch_posts(): void
    {
        $data = $this->api->get_data();

        if ($data !== null) {
            foreach ($data as $post_data) {
                $post = [];

                if (!isset($post_data['title']) || empty($post_data['title'])) {
                    continue;
                }

                if (!$this->post->is_exists(sanitize_text_field($post_data['title']))) {
                    $post['post_status'] = 'publish';
                    $post['post_title'] = sanitize_text_field($post_data['title']);

                    $post['post_content'] = (isset($post_data['content']) && !empty($post_data['content']))
                        ? $post_data['content']
                        : '';

                    $post['post_category'] = (isset($post_data['category']) && !empty($post_data['category']))
                        ? [$this->post_category->get_category_by_name($post_data['category'])]
                        : [$this->post_category->get_category_by_name('Uncategorized')];

                    $post['post_date'] = \BomatsaraTest\Utilities\Helpers::random_date();
                    $post['post_author'] = $this->post_author->get_admin_user_id();

                    $post_id = $this->post->create($post);

                    if (isset($post_data['image']) && !empty($post_data['image']) && $post_id) {
                        $this->post_thumbnail->set_post_thumbnail($post_id, $post_data['image']);
                        update_post_meta($post_id, 'rating', $post_data['rating'] ?? '');

                        if (isset($post_data['site_link'])) {
                            update_post_meta($post_id, 'site_link', $post_data['site_link']);
                        }
                    }
                }
            }
        }
    }
}