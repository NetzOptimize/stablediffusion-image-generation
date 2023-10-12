<?php

namespace App\Http\Controllers;

use Google\Service\SecurityCommandCenter\Process;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromptController extends Controller
{
    public function fetch_data($jsonResponse){
        // dd($jsonResponse);
        
        $key = env('STABLE_KEY');
        $fetch_result = $jsonResponse->fetch_result;
        $client = new Client();
            $response = $client->post($fetch_result,[
                'json' => [
                    "key"=> $key,
                    "request_id"=> $jsonResponse->id,
                ],
            
            ]);
            $apiResponse = $response->getBody()->getContents();
            $processResponse = json_decode($apiResponse);
            if($processResponse->status == 'processing'){
                sleep($jsonResponse->eta+1);
                $this->fetch_data($jsonResponse);
            }
            return response()->json(json_decode($apiResponse));
    }
    public function generate(Request $request){
        
        $key = env('STABLE_KEY');
        $prompt = $request->prompt;
        $pose = isset($request->pose) ? $request->pose : null;
        $link = isset($request->link) ? $request->link : null;
        $fetch_result = isset($request->fetch_result) ? $request->fetch_result : null;
        
        $category = array(
            "1" => "anything-v4",
            "2" => "analog-diffusion",
        );

        $maskLink = isset($request->imageMask) ? $request->imageMask : null;
        
        if($fetch_result){
            $client = new Client();
            $response = $client->post($fetch_result,[
                'json' => [
                    "key"=> $key,
                    "request_id"=> $request->id,
                ],
            ]);
            $apiResponse = $response->getBody()->getContents();
            return response()->json(json_decode($apiResponse));
        }


        if($link){
           
            if($maskLink){
                $client = new Client();
                $response = $client->post('https://stablediffusionapi.com/api/v3/inpaint',[
                    'json' => [
                            "key"=> $key,
                            "prompt"=> $prompt,
                            "negative_prompt"=> "((out of frame)), ((extra fingers)), NSFW , Nudity ,mutated hands, ((poorly drawn hands)), ((poorly drawn face)), (((mutation))), (((deformed))), (((tiling))), ((naked)), ((tile)), ((fleshpile)), ((ugly)), (((abstract))), blurry, ((bad anatomy)), ((bad proportions)), ((extra limbs)), cloned face, (((skinny))), glitchy, ((extra breasts)), ((double torso)), ((extra arms)), ((extra hands)), ((mangled fingers)), ((missing breasts)), (missing lips), ((ugly face)), ((fat)), ((extra legs))",
                            "init_image"=> $link,
                            "mask_image"=> $maskLink,
                            "width"=> "512",
                            "height"=> "512",
                            "samples"=> "1",
                            "num_inference_steps"=> "30",
                            "safety_checker"=> "yes",
                            "enhance_prompt"=> "yes",
                            "guidance_scale"=> 7.5,
                            "strength"=> 0.7,
                            "seed"=> null,
                            "webhook"=> null,
                            "track_id"=> null

                    ]
                    ]); 

            }else{
            
            $client = new Client();
            $response = $client->post('https://stablediffusionapi.com/api/v3/img2img',[
                'json' => [
                    "key"=> $key,
                    "prompt"=> $prompt,
                    "negative_prompt"=> "((out of frame)), ((extra fingers)), NSFW , Nudity ,mutated hands, ((poorly drawn hands)), ((poorly drawn face)), (((mutation))), (((deformed))), (((tiling))), ((naked)), ((tile)), ((fleshpile)), ((ugly)), (((abstract))), blurry, ((bad anatomy)), ((bad proportions)), ((extra limbs)), cloned face, (((skinny))), glitchy, ((extra breasts)), ((double torso)), ((extra arms)), ((extra hands)), ((mangled fingers)), ((missing breasts)), (missing lips), ((ugly face)), ((fat)), ((extra legs))",
                    "init_image"=> $link,
                    "width"=> "512",
                    "height"=> "512",
                    "samples"=> "1",
                    "num_inference_steps"=> "30",
                    "safety_checker"=> "yes",
                    "enhance_prompt"=> "no",
                    "guidance_scale"=> 7.5,
                    "strength"=> 0.7,
                    "seed"=> null,
                    "webhook"=> null,
                    "track_id"=> null
                ],
            
            ]);
        }
        }else{

            if($pose){

                // dd($category[$request->category]);
                $client = new Client();
                $response = $client->post('https://stablediffusionapi.com/api/v5/controlnet',[
                    'json' => [
                        "key"=> $key,
                        "controlnet_model"=> "openpose",
                        "controlnet_type" =>"openpose",
                        "model_id"=> $category[$request->category],
                        "auto_hint"=> "yes",
                        "guess_mode" => "no",
                        "prompt"=> $prompt,
                        "negative_prompt"=> "(((Nudity)))",
                        "init_image"=>asset('poses/'.$pose),
                        "mask_image"=> null,
                        "width"=> "512",
                        "height"=> "512",
                        "samples"=> "4",
                        "scheduler"=> "UniPCMultistepScheduler",
                        "num_inference_steps"=> "30",
                        "safety_checker"=> "no",
                        "enhance_prompt"=> "yes",
                        "guidance_scale"=> 7.5,
                        "controlnet_conditioning_scale"=> 0.5,
                        "strength"=> 0.55,
                        "lora_model"=> "more_details",
                        "clip_skip"=> "2",
                        "tomesd"=> "yes",
                        "use_karras_sigmas"=> "yes",
                        "vae"=> null,
                        "lora_strength"=> null,
                        "embeddings_model"=> null,
                        "seed"=> null,
                        "webhook"=> null,
                        "track_id"=> null,
                    ],
                
                ]
                );
            }else{
                
                
                $client = new Client();
                $response = $client->post('https://stablediffusionapi.com/api/v3/text2img',[
                    'json' => [
                        "key" => $key,
                        "prompt"=> $prompt,
                        "negative_prompt"=> "((out of frame)), ((extra fingers)), mutated hands, NSFW , Nudity  ((poorly drawn hands)), ((poorly drawn face)), (((mutation))), (((deformed))), (((tiling))), ((naked)), ((tile)), ((fleshpile)), ((ugly)), (((abstract))), blurry, ((bad anatomy)), ((bad proportions)), ((extra limbs)), cloned face, (((skinny))), glitchy, ((extra breasts)), ((double torso)), ((extra arms)), ((extra hands)), ((mangled fingers)), ((missing breasts)), (missing lips), ((ugly face)), ((fat)), ((extra legs))",
                        "width"=> "512",
                        "height"=> "512",
                    "samples"=> "1",
                    "num_inference_steps"=> "20",
                    "safety_checker"=> "no",
                    "enhance_prompt"=> "no",
                    "seed"=> null,
                    "guidance_scale"=> 7.5,
                    "webhook"=> null,
                    "track_id"=> null,
                ],
            ]);
        }
        }

        
        $apiResponse = $response->getBody()->getContents();
        $jsonResponse = json_decode($apiResponse);
        if($jsonResponse->status == 'processing'){
            // dd($jsonResponse->eta+10);
            sleep($jsonResponse->eta+10);
            return $this->fetch_data($jsonResponse);
        }
        return response()->json(json_decode($apiResponse));
    }

    public function upload(Request $request){
        if($request->has('image') && $request->has('imageMask')){
            // the image and imageMask are base64 encoded need to decode them
            $image = $request->image;
            $imageMask = $request->imageMask;
            $image = str_replace('data:image/png;base64,', '', $image);
            $imageMask = str_replace('data:image/png;base64,', '', $imageMask);
            $image = str_replace(' ', '+', $image);
            $imageMask = str_replace(' ', '+', $imageMask);
            $imageName = time() . '.png';
            $imageMaskName = time() . '_mask.png';
            Storage::disk('public')->put('uploads/'.$imageName,base64_decode($image));
            Storage::disk('public')->put('uploads/'.$imageMaskName,base64_decode($imageMask));
            return response()->json(['success' => true,
            'url' => asset('uploads/'.$imageName),
            'urlMask' => asset('uploads/'.$imageMaskName)
            ]);

        }
        if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->put('uploads/'.$filename,file_get_contents($file));
            return response()->json(['success' => true,
            'file' => $filename,
            'url' => asset('uploads/'.$filename)
            ]);
            
        }
        return response()->json(['success' => false,
    ]);
    }
}
