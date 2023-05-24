<?php
namespace BomatsaraTest\Shortcode;

interface ShortcodeInterface
{
    public function register_shortcode();
    public function shortcode_callback($atts);
}