<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
  <header>
  </header>
  <main>
    <div class="container-fluid mt-5 shadow-2">
        <div class="row d-flex justify-content-center align-content-center p-2">
            {{-- I want a big center div in bootstrap with  feilds promts and div that will show the images  --}}
            <div class="col-md-6 bg-light mt-2 shadow shadow-2" style="border-style: dashed;">
                <div class="bg-white shadow shadow-1 rounded-2 d-flex justify-content-center mt-4 mb-2" >
                    <img src="https://stablediffusionapi.com//storage/themes/October2022/Kh4QdnAqdueh6PeJpCQD.png" height="40" width="40"  class="img-fluid rounded rounded-2">
                    <span><h3>Image to Image - Stable Diffusion API Demo</h3></span>
                    
                </div>
                <form id="prompt-generate" enctype="multipart/form-data" >
                    @csrf
                    <label for="prompt" class="form-label">Image Link:</label>
                    <div class="mb-3">
                        
                        <div class="input-group ">
                            
                            {{-- need an input to with a grouped button saying submit link --}}
                            <input type="file"  class="form-control" placeholder="Link to your file!!" aria-label="Recipient's username" aria-describedby="button-addon2" name="link" id="link">
                            <button class="btn btn-outline-secondary" id="submit-file" id="button-addon2">Upload Link</button>
                        </div>
                        <small id="helpId" class="form-text text-muted">Upload your link here</small>
                        <div class="col-md-12 mt-2">
                            <h5 id="img-upload" hidden>Uploaded Image:</h5>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6">
                                    <img src="" id="uploaded-image" class="img-fluid rounded rounded-2">
                                </div>
                            </div>
                        </div>
                      <label for="prompt" class="form-label">Prompt:</label>
                      <input type="text" 
                        class="form-control" name="prompt" placeholder="Enter Your Prompt Here!!" id="prompt" aria-describedby="helpId" placeholder="">
                        <small id="helpIdPrompt" class="form-text text-muted">Enter your prompt here</small>
                        </div>
                    <button  id="generate-image" class="btn btn-primary" type="submit">Generate Image</button>
                    <p class="text-danger" id="error"></p>
                </form>
                <div class="col-md-12 mt-2">
                    <h5 id="img-text" hidden>Generated Image:</h5>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6">
                            <img src="" id="generated-image" class="img-fluid rounded rounded-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.5.0/axios.min.js" integrity="sha512-aoTNnqZcT8B4AmeCFmiSnDlc4Nj/KPaZyB5G7JnOnUEkdNpCZs1LCankiYi01sLTyWy+m2P+W4XM+BuQ3Q4/Dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  <script>
    $(document).ready(function () {
        url = "{{ route('generate-prompt') }}";
        var link;

        
        
        $('#submit-file').click(function (e) { 
            e.preventDefault();
            
            var token = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData();
            formData.append('file', $('#link')[0].files[0]);
            console.log(formData);
            $.ajax({
                type : "POST",
                url : "{{ route('upload-file') }}",
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data : formData,
                success : function(data){
                    console.log(data);
                    $('#img-upload').removeAttr('hidden');
                    $('#uploaded-image').attr('src', data['url']);
                    link = data['url'];
                },
                error : function(data){
                    console.log(data);
                }
            });
            
        });
        $('#prompt-generate').validate({
            rules: {
                prompt: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                prompt: {
                    required: "Please enter a prompt",
                    minlength: "Your prompt must be at least 10 characters long"
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                $("#helpIdPrompt").html("");
                $("#helpIdPrompt").html(error);
                
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
                $("#helpIdPrompt").html("");
                $("#helpIdPrompt").html("Enter your prompt here");
                
            }



        });
        $('#prompt-generate').submit(function (e) { 
            e.preventDefault();
            // var link = $('#link').val();
            
            if($('#prompt-generate').valid() == false){
                return false;
            }
            if(link == ""){
                $('#generate-image').html(`<p>Loading</p><div class="spinner-border" role="status"></div>`); 
                var prompt = $('#prompt').val();
                var token = $('meta[name="csrf-token"]').attr('content');
                console.log(token);
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate-prompt') }}",
                    data: {
                        prompt : prompt,
                        _token : token
                    },
                    success: function (data) {
                        if(data.status == "error"){
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else if(data.status == "processing"){
                            "i am here"
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else{
                            $('#generate-image').html("Generate Image");
                            $('#img-text').removeAttr('hidden'); 
                            $('#generated-image').attr('src', data['output'][0]);
                        }
                        
                    },
                    error: function (data) {
                        
                    }

                });
            }else{
                $('#generate-image').html(`<div class="spinner-border" role="status"></div>`); 
                var prompt = $('#prompt').val();
                var token = $('meta[name="csrf-token"]').attr('content');
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate-prompt') }}",
                    data: {
                        prompt : prompt,
                        link : link,
                        _token : token
                    },
                    success: function (data) {
                        
                        if(data.status == "error"){
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else if(data.output[0].status == "Processing"){
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else{
                            $('#generate-image').html("Generate Image");
                            $('#img-text').removeAttr('hidden'); 
                            $('#generated-image').attr('src', data['output'][0]);
                        }
                        
                    },
                    error: function (data) {
                        
                    }

                });
            }
           
            
        })
    });
    
  </script>
</body>

</html>