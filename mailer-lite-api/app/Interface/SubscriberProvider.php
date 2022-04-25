<?php
namespace App\Interface;

interface SubscriberProvider
{
    public function add_subscriber($email, $name);
    public function add_subscriber_field($email, $title, $type);
    public function get_subscribers_field($email, $page_index,$page_size);
    public function validate_auth_token($token);
}