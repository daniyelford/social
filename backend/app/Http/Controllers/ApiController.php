<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ApiController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        if(empty($data)) return response()->json(['status' => 'error', 'message' => 'عملیات مشخص نشده است'], 400);
        if (!empty($data['action'])){
            $actionClass = $this->resolveActionClass($data['action']);
            if (!class_exists($actionClass)) {
                return response()->json(['status' => 'error', 'message' => 'عملیات ناشناخته'], 400);
            }
            $actionInstance = new $actionClass();
            $result = $actionInstance->handle($data);
            return response()->json($result);
        }
        return response()->json($data);
    }
    protected function resolveActionClass(string $action)
    {
        $parts = explode('/', $action);
        $parts = array_map(fn($part) => Str::studly($part), $parts);
        $classPath = implode('\\', $parts);
        return "App\\Http\\Actions\\{$classPath}";
    }
}
