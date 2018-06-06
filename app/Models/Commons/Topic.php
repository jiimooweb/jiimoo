<?php

namespace App\Models\Commons;

use App\Models\Model;

class Topic extends Model
{

    public static function formatTopic($topic)
    {
        $products = [];
        $topic = $topic->toArray();
        $relateds = explode(',', $topic['related']);
        foreach($relateds as $id) {
            $products[] = Product::find($id);
        }
        $topic['products'] = $products;
        return $topic;
    }


}
