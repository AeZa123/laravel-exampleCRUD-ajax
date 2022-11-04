<div class="modal fade editProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('update.product')}}" method="post" enctype="multipart/form-data" id="update_form">
                    @csrf
                    <input type="hidden" name="pid">
                    <div class="form-group">
                        <label for="">Product name</label>
                        <input type="text" class="form-control" name="product_name" placeholder="Enter product name">
                        <span class="text-danger error-text product_name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Product image <button id="clearInputFile" type="button" class="btn btn-danger btn-sm">Clear</button></label>
                        <input type="file" name="product_image_update" class="form-control" data-value="">
                        <span class="text-danger error-text product_image_update_error"></span>
                    </div>
                    <div class="text-center img-holder-update"></div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">Save Change</button>
                        <button type="button" class="btn btn-danger">cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>