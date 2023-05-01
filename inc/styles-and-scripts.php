<?php

function set_enqueue_style()
{
    wp_enqueue_style('main', get_template_directory_uri() . '/assest/css/global.css');
}

wp_enqueue_scripts('set_enqueue_style');
