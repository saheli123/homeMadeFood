<?php
use Illuminate\Support\Str;
if (!function_exists('get_unique_dish_slug')) {
    function get_unique_dish_slug($slug){
        $slug=Str::slug($slug);
        if ($count = App\FoodItem::where('slug', 'like', "$slug%")->count())
        $slug = Str::finish($slug, "-$count");
        return $slug;
     }
}
