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

    public function fetchProducts(){
        $products = Product::all();
        // $data = \View::make('all_products')->with('products', $products)->render();
        $data = view('all_products', ['products'=>$products])->render();
        return response()->json(['code'=>1,'result'=>$data]);
    }

    public function getProductDetails(Request $request){
        $product = Product::find($request->product_id);
        return response()->json(['code'=>1,'result'=>$product]);
    }


    public function updateProduct(Request $request) {
        $product_id = $request->pid;
        $product = Product::find($product_id);
        $path = 'files/';

        $validator = \Validator::make($request->all(),[
            'product_name' => 'required|string',
            'product_image_update' => 'image',
        ],[
            'product_name.required' => 'กรุณาใส่ชื่อสินค้า',
            'product_image_update.required' => 'กรุณาใส่รูปสินค้า',
            'product_image_update.image' => 'นามสกุลไฟล์ภาพไม่ถูกต้อง *ต้องเป็นนามสกุล png, jpg, jpeg เท่านั้น'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else{
            //update product
            if($request->hasFile('product_image_update')){
                $file_path = $path.$product->product_image;

                //delete old image
                if($product->product_image != null && \Storage::disk('public')->exists($file_path)){
                    \Storage::disk('public')->delete($file_path);
                }

                //update new image
                $file = $request->file('product_image_update');
                $file_name = time().'_'.$file->getClientOriginalName();
                $upload = $file->storeAs($path, $file_name, 'public');

                if($upload){
                    $product->update([
                        'product_name' => $request->product_name,
                        'product_image' => $file_name
                    ]);
                    return response()->json(["code"=>1, "msg"=>"อัปเดตสินค้าสำเร็จ"]);
                }


            }else{
                $product->update([
                    'product_name' => $request->product_name,
                ]);

                return response()->json(['code'=>1, 'msg'=>'อัปเดตสินค้าสำเร็จ']);
            }

        }

    }



    public function deleteProduct(Request $request) {
        $product = Product::find($request->product_id);
        $path = 'files/';
        $image_path = $path.$product->product_image;

        if($product->product_image != null && \Storage::disk('public')->exists($image_path)){
            \Storage::disk('public')->delete($image_path);
        }
        $query = $product->delete();
        if($query){
            return response()->json(["code"=>1, "msg"=>"ลบสินค้าสำเร็จ!"]);
        }else{
            return response()->json(["code"=>0, "msg"=>"ไม่สามารลบสินค้าได้"]);
        }

    }


}
