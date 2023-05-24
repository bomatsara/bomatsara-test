<?php

namespace BomatsaraTest\Meta;

class MetaPost
{
    private string $field_id;
    private string $field_type;
    private string $field_label;

    public function __construct(string $field_id, string $field_label, string $field_type = 'text')
    {
        $this->field_id = $field_id;
        $this->field_label = $field_label;
        $this->field_type = $field_type;

        add_action('add_meta_boxes', [$this, 'add_post_meta_boxes']);
        add_action('save_post', [$this, 'save_post_meta']);
    }

    public function add_post_meta_boxes(): void
    {
        add_meta_box($this->field_id, $this->field_label, [$this, 'render_post_meta_box'], 'post');
    }

    public function render_post_meta_box($post): void
    {
        $field_value = get_post_meta($post->ID, $this->field_id, true);

        echo '<label for="' . $this->field_id . '">' . $this->field_label . ': </label>';
        echo '<input type="' . $this->field_type . '" id="' . $this->field_id . '" name="' . $this->field_id . '" value="' . esc_attr($field_value) . '"/>';
    }

    public function save_post_meta($post_id): void
    {
        if (array_key_exists($this->field_id, $_POST)) {
            $field_value = sanitize_text_field($_POST[$this->field_id]);
            update_post_meta($post_id, $this->field_id, $field_value);
        }
    }
}