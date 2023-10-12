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
    <style>
        canvas {
            border: 1px solid black;
            cursor: crosshair
        }
    </style>
</head>

<body>
  <header>
  </header>
  <main>
    <div class="container-fluid mt-5 shadow-2">
        <div class="row d-flex justify-content-center align-content-center p-2">
            {{-- I want a big center div in bootstrap with  feilds promts and div that will show the images  --}}
            <div class="col-md-8 bg-light mt-2 shadow shadow-2" style="border-style: dashed;">
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
                            <input type="file"  class="form-control" placeholder="Link to your file!!" aria-label="Recipient's username" aria-describedby="button-addon2" name="imageInput" id="imageInput">
                            <button class="btn btn-outline-secondary" id="submit-file">Upload Link</button>
                        </div>
                        <small id="helpId" class="form-text text-muted">Upload your link here</small>
                        <div class="col-md-12 mt-2">
                            <canvas id="imageCanvas"></canvas>
                            <canvas id="maskCanvas"></canvas>
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
    var link;
    var maskLink;
    const imageInput = document.getElementById('imageInput');
    const imageCanvas = document.getElementById('imageCanvas');
    const maskCanvas = document.getElementById('maskCanvas');
    const submitButton = document.getElementById('submit-file');
    let imageContext = imageCanvas.getContext('2d');
    let maskContext = maskCanvas.getContext('2d');
    let isDrawing = false;
    function setBlackBackground() {
        // Set the background color of the mask canvas to black
        maskContext.fillStyle = 'black';
        maskContext.fillRect(0, 0, maskCanvas.width, maskCanvas.height);
    }

    imageCanvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        const x = e.clientX - imageCanvas.getBoundingClientRect().left;
        const y = e.clientY - imageCanvas.getBoundingClientRect().top;
        maskContext.beginPath();
        maskContext.moveTo(x, y);
    });

    imageCanvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            maskContext.strokeStyle = 'white'; // Set brush color to white
            maskContext.lineWidth = 30; // Set brush thickness
            const x = e.clientX - imageCanvas.getBoundingClientRect().left;
            const y = e.clientY - imageCanvas.getBoundingClientRect().top;
            maskContext.lineTo(x, y);
            maskContext.stroke();
        }
    });
    imageCanvas.addEventListener('mouseup', () => {
        isDrawing = false;
        maskContext.closePath();
    });

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    imageCanvas.width = img.width;
                    imageCanvas.height = img.height;
                    maskCanvas.width = img.width;
                    maskCanvas.height = img.height;
                    imageContext.drawImage(img, 0, 0);
                    setBlackBackground(); // Set the black background again
                };
            };
            reader.readAsDataURL(file);
        }
    });


    $('#submit-file').click(function(e){
        e.preventDefault();
        const maskDataURL = maskCanvas.toDataURL('image/png');
        const imageDataURL = imageCanvas.toDataURL('image/png');
        const formData = new FormData();
        const token = $('meta[name="csrf-token"]').attr('content');
        formData.append('image', imageDataURL);
        formData.append('imageMask', maskDataURL);
        console.log(imageDataURL);
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
                    link = data.url;
                    maskLink = data.urlMask;
                    console.log(link);
                    console.log(maskLink);
                },
                error : function(data){
                    console.log(data);
                }
            });
        console.log(maskDataURL);
    });
    $('#generate-image').click(function(e){
        e.preventDefault();
        $('#generate-image').html(`<p>Loading</p><div class="spinner-border" role="status"></div>`); 
        const prompt = $('#prompt').val();
        const token = $('meta[name="csrf-token"]').attr('content');
        const formData = new FormData();
        formData.append('prompt', prompt);
        formData.append('image', link);
        formData.append('imageMask', maskLink);
        $.ajax({
                type : "POST",
                url : "{{ route('generate-prompt') }}",
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data : formData,
                success : function(data){
                    if(data.status == "error"){
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else if(data.status == "Processing"){
                            $('#generate-image').html("Generate Image");
                            $('#error').html(data.message);
                        }else if(data.status == "failed"){
                            console.log("I am here");
                            $('#generate-image').html("Generate Image");
                            $('#error').html("Please upload a valid image");
                        }else{
                            console.log(data);
                            console.log(data.output[0]);
                            $('#generated-image').attr('src', data.output[0]);
                            $('#generated-image').attr('hidden', false);
                            $('#generate-image').html("Generate Image");
                            $('#img-text').attr('hidden', false);
                        }
                },
                error : function(data){
                    console.log(data);
                }
            });
    });
    // submitButton.addEventListener('click', () => {
    //     this.preventDefault();
    //     const maskDataURL = maskCanvas.toDataURL('image/png');
    //     document.querySelector('img').src = maskDataURL;
    //     console.log(maskDataURL);
    //     // You can save or further process the mask image using maskDataURL
    //     // For example, you can send it to your server for storage or processing.
    // });
  </script>
    
</body>

</html>