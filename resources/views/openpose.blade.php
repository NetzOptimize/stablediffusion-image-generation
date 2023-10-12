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
        @import url('https://fonts.googleapis.com/css2?family=Mooli&display=swap');
        canvas {
            border: 1px solid black;
            cursor: crosshair
        }

        .posebutton{
            height: 50%;
            width: 50%;
        }
        .data-img{
            height: 708px;
            width: 337px;
        }

        .mooli{
            font-family: 'Mooli',sans-serif;
        }
        @media (max-width: 768px) {
            .col-md-6{
                width: 26%;
            }
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
            <div class="col-md-9 bg-light mt-2 shadow shadow-2" style="border-style: dashed;">
                <div class="bg-white shadow shadow-1 rounded-2 d-flex justify-content-center mt-4 mb-2" >
                    <img src="https://stablediffusionapi.com//storage/themes/October2022/Kh4QdnAqdueh6PeJpCQD.png" height="40" width="40"  class="img-fluid rounded rounded-2">
                    <span><h3>Image to Image - Stable Diffusion API Demo</h3></span>
                    
                </div>
                <form id="prompt-generate" enctype="multipart/form-data" >
                    @csrf
                        <h4 class="mooli">Select a pose:</h4>
                        <div class="col-md-12 text-center">
                        <div class="btn-group justify-content-center" role="group" aria-label="Basic radio toggle button group" style="display: inline ">
                            <input type="radio" class="btn-check " name="pose" value="pose1.jpg" id="btnradio1" autocomplete="off" >
                            <label class="btn btn-outline-dark" for="btnradio1"><img class="rounded posebutton"  src="{{ asset('poses/pose1.jpg') }}" alt=""></label>
                            
                            <input type="radio" class="btn-check " name="pose" value="pose2.png" id="btnradio2" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio2"><img class="rounded posebutton "  src="{{ asset('poses/pose2.png') }}" alt=""></label>
                            
                            <input type="radio" class="btn-check " name="pose" value="pose3.jpg" id="btnradio3" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio3"><img class="rounded posebutton "  src="{{ asset('poses/pose3.jpg') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose4.jpg" id="btnradio4" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio4"><img class="rounded posebutton "  src="{{ asset('poses/pose4.jpg') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose5.png" id="btnradio5" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio5"><img class="rounded posebutton "  src="{{ asset('poses/pose5.png') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose6.jpg" id="btnradio6" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio6"><img class="rounded posebutton "  src="{{ asset('poses/pose6.jpg') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose7.png" id="btnradio7" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio7"><img class="rounded posebutton "  src="{{ asset('poses/pose7.png') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose8.jpg" id="btnradio8" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio8"><img class="rounded posebutton "  src="{{ asset('poses/pose8.jpg') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose9.jpg" id="btnradio9" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio9"><img class="rounded posebutton "  src="{{ asset('poses/pose9.jpg') }}" alt=""></label>

                            <input type="radio" class="btn-check " name="pose" value="pose10.jpg" id="btnradio10" autocomplete="off">
                            <label class="btn btn-outline-dark" for="btnradio10"><img class="rounded posebutton "  src="{{ asset('poses/pose10.jpg') }}" alt=""></label>

                        </div>
                        
                    </div>
                    <h4 class="mooli">Select a category:</h4>
                    <div style="display: inline-flex;"  >
                        <input type="radio" class="btn-check" name="category" value="1" id="btn-check-2-outlined" autocomplete="off">
                        <label class="btn btn-outline-secondary me-2"  for="btn-check-2-outlined">Anime</label><br>

                        <input type="radio" class="btn-check" name="category" value="2" id="btn-check-5-outlined" autocomplete="off">
                        <label class="btn btn-outline-secondary me-2"  for="btn-check-5-outlined">Realistic</label><br>
                    </div>
                    <div class="mb-3">
                        <div class="col-md-12 mt-2">
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
                    <div class="row ">
                        <div class="col-md-12" id="generated-Images" style="display: flex,width:26%;">
                            
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
        
    
    $('#generate-image').click(function(e){
        e.preventDefault();
        form = $('#prompt-generate');
        formData = new FormData(form[0]);
        
        const prompt = formData.get('prompt');
        const pose = formData.get('pose');
        const category = formData.get('category');
        const token = $('meta[name="csrf-token"]').attr('content');
        console.log(category);

        if(pose == null){
            $('#error').text('Please select a pose');
            return;
        }
        if(category == null){
            $('#error').text('Please select a category');
            return;
        }
        if(prompt == ''){
            $('#error').text('Please enter a prompt');
            return;
        }
        else{
            $('#error').text('');
        }
        $('#generate-image').html(`<p>Loading</p><div class="spinner-border" role="status"></div>`);
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
                    if(data.status == "success"){
                        
                        $('#generate-image').html(`Generate Image`);
                        $('#generated-Images').empty();
                        for(let i = 0; i < data.output.length; i++){
                            $('#generated-Images').append('<img src="'+data.output[i]+'" class="img-fluid data-img" alt="">');
                        }
                    }else if(data.status == "processing"){
                        $('#generate-image').html(`<p>Processing</p><div class="spinner-border" role="status"></div>`);
                        $('#generated-Images').empty();
                        eta = Math.ceil(data.eta*1000)+10000;
                        console.log(eta);
                        
                        setTimeout(() => {
                            fetchData = new FormData();
                            fetchData.append('id', data.id);
                            fetchData.append('fetch_result',data.fetch_result);
                            console.log('Fetch Data:', fetchData.get('id'));
                            console.log('Fetch Data:', fetchData.get('fetch_result'));
                            console.log('Token:', token);
                            console.log('jQuery Version:', $.fn.jquery);
                            $.ajax({
                                type: "POST",
                                url: "{{route('generate-prompt')}}",
                                data: {
                                    "id": data.id,
                                    "fetch_result": data.fetch_result
                                },
                                headers: {
                                    'X-CSRF-TOKEN': token
                                },
                                success: function (pdata) {
                                    if(pdata.status == "success"){
                                        $('#generate-image').html(`Generate Image`);
                                        $('#generated-Images').empty();
                                        for(let i = 0; i < pdata.output.length; i++){
                                            $('#generated-Images').append('<img src="'+pdata.output[i]+'" class="img-fluid data-img" alt="">');
                                        }
                                    }else{
                                        
                                        $('#generate-image').html(`Generate Image`);
                                        $('#generated-Images').empty();
                                        $('#generated-Images').append('<p>Something went wrong</p>');
                                    }
                                },
                                error: function (xhr, textStatus, errorThrown) {
                                    // Handle the error here and provide feedback to the user.
                                    console.error("AJAX Request Error: " + textStatus, errorThrown);
                                }
                            });

                        }, eta);
                        

                    }
                },
                error : function(data){
                    console.log(data);
                }
            });

        
    });
});
  </script>
    
</body>

</html>