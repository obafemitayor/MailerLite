<?php
namespace App\Implementation;

use Illuminate\Support\Facades\DB;
use App\Models\Subscriber;
use App\Models\Field;
use App\Interface\SubscriberProvider;

class AppSubscriberProvider implements SubscriberProvider
{
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
        $subscriber = Subscriber::where('email', '=', $email);
        if($subscriber->count() == 0)
        {
            $subscriber = new Subscriber;
            $subscriber->email = $email;
            $subscriber->name = $name;
            $subscriber->state  = "active";
            $subscriber->save();
        }
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
        $subscriber = Subscriber::where('email', '=', $email);
        if($subscriber->count() == 0)
        {
            $response['responseCode'] = 404;
            $response['responseMessage'] = "No Record Found For Subscriber, Please Register as a Subscriber First";
            return $response;
        }
        if($subscriber->get()[0]->state == "active")
        {
            $field = new Field;
            $field->title = $title;
            $field->type  = $type;
            $field->subscriber_id  = $subscriber->get()[0]->id;
            $field->save();
            $response['responseCode'] = 200;
            $response['responseMessage'] = "You Have Subscribed To This Field Successfully";
        }
        else{
            $response['responseCode'] = 401;
            $response['responseMessage'] = "Inactive Subscriber";
        }
        return $response;
    }
    public function get_subscribers_field($email, $page_index,$page_size)
    {
        $response = array();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['responseCode'] = 400;
            $response['responseMessage'] = "Invalid email format";
            return $response;
        }
        $subscriber = Subscriber::where('email', '=', $email);
        if($subscriber->count() == 0)
        {
            $response['responseCode'] = 404;
            $response['responseMessage'] = "No Record Found For Subscriber, Please Register as a Subscriber First";
            return $response;
        }
        if($subscriber->get()[0]->state != "active")
        {
            $response['responseCode'] = 401;
            $response['responseMessage'] = "Inactive Subscriber";
            return $response;
        }
        $fields = Subscriber::find($subscriber->get()[0]->id)->fields()->orderBy('created_at')->get();
        $total_count = count($fields);
        $all_fields = array();
        foreach ($fields as $field) {
            array_push($all_fields,$field);
        }
        $paginated_response = array();
        $paginated_fields = array_slice($all_fields,$page_index,$page_size);
        $paginated_response['records'] = $paginated_fields;
        $paginated_response['total_count'] = $total_count;
        $response['responseCode'] = 200;
        $response['responseMessage'] = $paginated_response;
        return $response;
    }
    public function validate_auth_token($token)
    {
        $hash_algo = hash_init('md5');
        hash_update($hash_algo, 'Secret_Key');
        $secret_key =  hash_final($hash_algo);
        return hash_equals($secret_key, $token);
    }
}