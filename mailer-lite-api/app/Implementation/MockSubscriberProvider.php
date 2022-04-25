<?php
namespace App\Implementation;

use GuzzleHttp\Promise;
use App\Interface\SubscriberProvider;

class MockSubscriberProvider implements SubscriberProvider
{
    private $subscribers = array();
    private $fields = array();
    private $counter = 1;
    private $field_counter = 1;

    public function __construct()
    {
        $newSubscriber = array();
        $newSubscriber['id'] = $this->counter;
        $newSubscriber['email'] = "obafemitayorinactive@gmail.com";
        $newSubscriber['name'] = "obafemi tayo";
        $newSubscriber['state'] = "inactive";
        array_push($this->subscribers,$newSubscriber);
        $this->counter = $this->counter + 1;

        $newSubscriber['id'] = $this->counter;
        $newSubscriber['email'] = "obafemitayor@gmail.com";
        $newSubscriber['name'] = "obafemi tayo";
        $newSubscriber['state'] = "active";
        array_push($this->subscribers,$newSubscriber);
        $this->counter = $this->counter + 1;

        $newField = array();
        $newField['id'] = $this->field_counter;
        $newField['title'] = "obafemi tayo";
        $newField['type'] = "type";
        $newField['subscriber_id'] = 2;
        array_push($this->fields,$newField);
        $this->field_counter = $this->field_counter + 1;

        $newField = array();
        $newField['id'] = $this->field_counter;
        $newField['title'] = "obafemi tayo II";
        $newField['type'] = "type";
        $newField['subscriber_id'] = 2;
        array_push($this->fields,$newField);
        $this->field_counter = $this->field_counter + 1;
    }

    public function add_subscriber($email, $name)
    {
        $response = array();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Invalid email format";
            return $response;
        }
        if (empty($name)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Name Is Required";
            return $response;
        }
        $newSubscriber = array();
        $newSubscriber['id'] = $this->counter;
        $newSubscriber['email'] = $email;
        $newSubscriber['name'] = $name;
        $newSubscriber['state'] = "active";
        array_push($this->subscribers,$newSubscriber);
        $this->counter = $this->counter + 1;
        $response['responseCode'] = 200;
        $response['responseMessage'] = "You Have Subscribed Successfully";
        return $response;
    }

    public function add_subscriber_field($email, $title, $type)
    {
        $response = array();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Invalid email format";
            return $response;
        }
        if (empty($title)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Title Is Required";
            return $response;
        }
        if (empty($type)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Type Is Required";
            return $response;
        }
        $subscriber_exist = false;
        $subscriber_id = 0;
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber['email'] == $email) {
                $subscriber_id = $subscriber['id'];
                $subscriber_exist = true;
                break;
            }
        }
        if(!$subscriber_exist)
        {
            $response['responseCode'] = 404;
            $response['responseMessage'] = "No Record Found For Subscriber, Please Register as a Subscriber First";
            return $response;
        }
        $newField = array();
        $newField['id'] = $this->field_counter;
        $newField['title'] = $title;
        $newField['type'] = $type;
        $newField['subscriber_id'] = $subscriber_id;
        array_push($this->fields,$newField);
        $this->field_counter = $this->field_counter + 1;
        $response['responseCode'] = 200;
        $response['responseMessage'] = "You Have Subscribed Successfully";
        return $response;
    }

    public function get_subscribers_field($email, $page_index,$page_size)
    {
        $results = array();
        $response = array();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Invalid email format";
            return $response;
        }
        $subscriber_exist = false;
        $subscriber_id = 0;
        $subscriber_state = 0;
        $subscriber_exist = false;
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber['email'] == $email) {
                $subscriber_id = $subscriber['id']; 
                $subscriber_state = $subscriber['state']; 
                $subscriber_exist = true;
                break;
            }
        }
        if(!$subscriber_exist)
        {
            $response['responseCode'] = 404;
            $response['responseMessage'] = "No Record Found For Subscriber, Please Register as a Subscriber First";
            return $response;
        }
        if($subscriber_state != "active")
        {
            $response['responseCode'] = 401;
            $response['responseMessage'] = "Inactive Subscriber";
            return $response;
        }
        foreach ($this->fields as $field) {
            if ($field['subscriber_id'] == $subscriber_id) {
                array_push($results,$field);
            }
        }
        $paginated_fields = array_slice($results,$page_index,$page_size);
        $response['responseCode'] = 200;
        $response['responseMessage'] = $paginated_fields;
        return $response;
    }

    public function validate_auth_token($token)
    {
        return true;
    }
}