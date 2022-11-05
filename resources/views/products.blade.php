<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Products</title>
    <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}">
</head>
<body>

    <div class="container">
        <div class="mt-5 text-center">
            <h1 class="display-3">Example CRUD Laravel and Ajax</h1>
        </div>
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Add new product</div>
                    <div class="card-body">
                        <form action="{{route('save.product')}}" method="POST" enctype="multipart/form-data" id="form">
                            @csrf
                            <div class="form-group >
                                <label for="">product name</label>
                                <input type="text" name="product_name" class="form-control" placeholder="Enter product name">
                                <span class="text-danger error-text product_name_error"></span>
                            </div>
                            <div class="form-group ">
                                <label for="">Product image</label>
                                <input type="file" name="product_image" class="form-control">
                                <span class="text-danger error-text product_image_error"></span>
                            </div>

                            <div class="img-holder text-center"></div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Save product</button>
                            </div>

                        </form> 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">All products</div>
                    <div class="card-body" id="AllProducts">

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('edit-product-modal')





    <script src="{{ asset('jquery.min.js') }}"></script>
    <script src="{{asset('bootstrap.min.js')}}"></script>
    <script>
        


        $(function(){

            //save product
            $('#form').on('submit', function(e){
                e.preventDefault();

                var form = this;
                $.ajax({
                   
                    url: $(form).attr('action'),
                    // url:'{{URL::to('/updateProduct')}}',
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function(){

                        $(form).find('span.error-text').text('');
                    },
                    success:function(data){
                        if(data.code == 0){
                            $.each(data.error, function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        }else{
                            $(form)[0].reset();
                            fetchAllProducts();
                        }
                    }

                })
                
                
            });
            //reset image 
            $('input[type="file"][name="product_image"]').val('');
            //preview image
            $('input[type="file"][name="product_image"]').on('change', function(){
                var img_path = $(this)[0].value;
                var img_holder = $('.img-holder');
                var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
                

                if(extension == 'jpeg' || extension == 'jpg' || extension == 'png') {
                    // alert(extension);
                    if(typeof(FileReader) != 'undefined') {
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>', {'src':e.target.result,'class':'img-fluid','style':'max-width:300px;margin-bottom:10px;'}).
                            appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0])


                    }else{
                        $(img_holder).html('This browser does not support FileReader');
                    }
                }else{
                    $(img_holder).empty();
                }
            });


            //fetch all Products
            fetchAllProducts();
            function fetchAllProducts() {
                $.get('{{route("fetch.products")}}',{}, function(data){
                    $('#AllProducts').html(data.result);
                },'json');
            }


            //edit
            $(document).on('click', '#editBtn', function(){
                var product_id = $(this).data('id');
                var url = '{{route("get.product.details")}}';
                $.get(url,{product_id:product_id}, function(data){
                    // alert(data.result.product_name);
                    var product_modal = $('.editProductModal')
                    $(product_modal).find('form').find('input[name="pid"]').val(data.result.id);
                    $(product_modal).find('form').find('input[name="product_name"]').val(data.result.product_name);
                    $(product_modal).find('form').find('.img-holder-update').html('<img src="/storage/files/'+data.result.product_image+'"class="img-fluid" style="max-botton:10px; max-width:300px;">');
                    $(product_modal).find('form').find('input[type="file"]').attr('data-value', '<img src="/storage/files/'+data.result.product_image+'"class="img-fluid style="max-width:100px;margin-bottom:10px;">');
                    $(product_modal).find('form').find('input[type="file"]').val('');
                    $(product_modal).find('form').find('span.error.text').text('');
                    $(product_modal).modal('show')
                },'json');
            });
            //preview edit image
            $('input[type="file"][name="product_image_update"]').on('change', function(){
                var img_path = $(this)[0].value;
                var img_holder = $('.img-holder-update');
                var currentImagePath = $(this).data('value'); 
                var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
                if(extension == 'jpg' || extension == 'jpeg' || extension == 'png'){
                    if(typeof(FileReader) != 'undefined'){
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>',{'src':e.target.result, 'class':'img-fluid', 'style':'max-width:300px;margin-bottom10px;'}).appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    }else{
                        $(img_holder).html('This is browser not support FileReader');
                    }
                }else{
                    $(img_holder).html(currentImagePath);
                }


            });

            //clear image
            $(document).on('click', '#clearInputFile', function(){
                var form = $(this).closest('form');
                $(form).find('input[type="file"]').val('');
                $(form).find('.img-holder-update').html($(form).find('input[type="file"]').data('value'));
            })

           

            //update product
            $('#update_form').on('submit', function(e){
                e.preventDefault();
                var form = this;
                $.ajax({
                   
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data){
                        if(data.code == 0){
                            
                            $.each(data.error, function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });

                        }else{
                            alert(data.msg);
                            fetchAllProducts();
                            $('.editProductModal').modal('hide');
                        }
                    }

                });
            });



            //delete product
            $(document).on('click', '#deleteBtn', function(){
                var product_id = $(this).data('id');
                var url = '{{route("delete.product")}}';


                if(confirm('ต้องการลบสินค้านี้หรือไม่ ?')){
                    $.ajax({
                        headers:{
                            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                        },
                        url:url,
                        method:'POST',
                        data:{product_id:product_id},
                        dataType:'json',
                        success:function(data){
                            if(data.code == 1 ){
                                fetchAllProducts();
                            }else{
                                alert(data.msg);
                            }
                        }
                    })
                }

            })



        })
    </script>
</body>
</html>