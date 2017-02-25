<form action="{path_media}cms/recadrage/save.html" method="POST" id="form_recadrage">
    <input type="hidden" name="base_img" id="base_img">
    <input type="hidden" name="type" id="type" value="{type}">
    <input type="hidden" name="media_id" id="media_id" value="{media_id}">
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <div class="form-group">
                <label class="button button-bleu btn-block pointer" for="inputImage" title="Upload image file">
                    <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="Import image with Blob URLs">
                        Importer une image
                    </span>
                    <i class="fa fa-upload"></i>
                </label>
            </div>
        </div>
    </div>

    <div class="row mt15">
        <div class="col-xs-12">
            <div id="img-container">
                <img id="image" src=""/>
            </div>
        </div>
    </div>
</form>

<style>
    #image
    {
        max-width: 100%;
    }

</style>

<script src="{path_media}assets/js/plugins/crop_img/crop_img.min.js"></script>
<link rel="stylesheet" href="{path_media}assets/css/crop_img.min.css">
<script>
    $(document).ready(function(){

        var $image = $('#image');

        var options = {
//            aspectRatio: 3/2,
            crop: function(e) {
                // Output the result data for cropping image.
                console.log(e.x);
                console.log(e.y);
                console.log(e.width);
                console.log(e.height);
                console.log(e.rotate);
                console.log(e.scaleX);
                console.log(e.scaleY);
            }
        };

        $image.cropper(options);


        //A la soumission du formulaire
        $('#form_recadrage').on("submit",function(e){
            var result = $image.cropper('getCroppedCanvas');
            $('#base_img').val(result.toDataURL('image/png'));
        });


        //Chargement du cropper
        // Import image
        var $inputImage = $('#inputImage');
        var URL = window.URL || window.webkitURL;
        var uploadedImageURL;


        $inputImage.on("change",function () {
            var files = this.files;
            var file;
            if (!$image.data('cropper')) {
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    if (uploadedImageURL) {
                        URL.revokeObjectURL(uploadedImageURL);
                    }

                    uploadedImageURL = URL.createObjectURL(file);
                    $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                    $inputImage.val('');
                    $('.img-container').show();
                } else {
                    window.alert('Please choose an image file.');
                }
            }
        });
    })
</script>