<?php
namespace App\Http\Actions\PageHandler;
use App\Models\Category;
class CategoryHandler{
    public function handle(array $params){
        if (!empty($params['handler']) && method_exists($this, $params['handler']))
            if(!empty($params['data']))
                return $this->{$params['handler']}($params['data']);
            else
                return $this->{$params['handler']}();
        else
            return ['status'=>'error','massage'=>'invalid requst'];
    }
    private function all_categories(){
        return ['status' => 'success','data'=>Category::all()];
    }
}