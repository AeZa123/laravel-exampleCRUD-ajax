<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    
    public function save(Request $request){

        // return 'test';
        // return response()->json(['code'=>0]);
        
        $validator = \Validator::make($request->all(),[
            'product_name' => 'required|string',
            'product_image' => 'required|image'
        ],
        [
            'product_name.required' => 'กรุณาใส่ชื่อสินค้า',
            // 'product_name.string' => '',
            'product_image.required' => 'กรุณาใส่รูปสินค้า',
            'product_image.image' => 'ไฟล์รูปไม่ถูกต้อง',
        ]);
       

        // return response()->json(['code'=>0, 'test'=>'ssssss']);
        if(!$validator->passes()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            $path = 'files/';
            $file = $request->file('product_image');
            $file_name = time().'_'.$file->getClientOriginalName();

            // $upload = $file->storeAs($path, $file_name);
            $upload = $file->storeAs($path, $file_name, 'public');

            if($upload){
                Product::insert([
                    'product_name'=>$request->product_name,
                    'product_image'=>$file_name,
                ]);
                return response()->json(['code'=>1,'msg'=>'New product has been saved successfully']);
            }
        }

    }


}
