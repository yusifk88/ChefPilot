<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$recipe->name}}</title>

    <meta property="og:title" content="{{$recipe->name}}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{route("recipe.publicPost",$recipe->ulid)}}" />
    <meta property="og:image" content="{{$recipe->photos[0]->url}}" />
    <meta property="og:description" content="{{$recipe->description}}">
    <meta name="twitter:title" content="{{$recipe->name}}">
    <meta name="twitter:description" content="{{$recipe->description}}">
    <meta name="twitter:image" content="{{$recipe->photos[0]->url}}">
    <meta name="twitter:card" content="summary_large_image">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        /* The Fade-out Mask */
        .fade-mask {
            mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
        }

        .recipe-image {
            height: 45vh;
            width: 100%;
            object-fit: cover;
        }

        .cta-button {
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .cta-button:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="max-w-md mx-auto bg-white shadow-2xl min-h-screen relative">

<!-- Recipe Hero Image -->
<div class="relative">
    <img
        src="{{$recipe->photos[0]->url}}"
        alt="Pan-Seared Lemon Garlic Salmon"
        class="recipe-image"
        onerror="this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.png'"
    >
{{--    <div class="absolute top-4 left-4">--}}
{{--        <button class="bg-white/90 backdrop-blur p-2 rounded-full shadow-lg">--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />--}}
{{--            </svg>--}}
{{--        </button>--}}
{{--    </div>--}}
</div>

<!-- Content Section -->
<div class="p-6 -mt-8 bg-white rounded-t-3xl relative z-10">
    <div class="flex justify-between items-start mb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded {{$recipe->difficulty=='Easy' ? 'bg-green-50 text-green-500' : 'bg-orange-50 text-orange-500'}}">{{$recipe->difficulty}}</span>
            <h1 class="text-3xl font-bold text-gray-900 mt-2 leading-tight">{{$recipe->name}}</h1>
        </div>
       <div>
           <small>
               Prep:{{$recipe->estimatedTimeMinutes}}mins
           </small>
       </div>
    </div>

    <!-- Stats Bar -->
    <div class="flex justify-between py-4 border-y border-gray-100 mb-6">
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase font-medium">Protein</p>
            <p class="font-bold text-gray-800">{{$recipe->nutrition["protein"]}}</p>
        </div>
        <div class="text-center border-x border-gray-100 px-8">
            <p class="text-xs text-gray-500 uppercase font-medium">carbohydrates</p>
            <p class="font-bold text-gray-800">{{$recipe->nutrition["carbohydrates"]}}</p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-500 uppercase font-medium">Calories</p>
            <p class="font-bold text-gray-800">{{$recipe->nutrition["calories"]}}</p>
        </div>
    </div>

    <!-- Fading Description Section -->
    <div class="relative">
        <div class="fade-mask text-gray-600 leading-relaxed mb-4">
          {{$recipe->description}}

        </div>

        <!-- Overlay for the fade-out effect button area -->
        <div class=" bottom-0 left-0 right-0 pt-2 pb-4 text-center bg-green-50 rounded-3xl">
            <div class="bg-gradient-to-t from-white via-white/95 to-transparent h-40 absolute bottom-0 left-0 right-0 -z-10"></div>

            <p class="text-sm font-semibold text-green-500 mb-6 px-4">Download ChefPilot to see the full recipe and discover 1,000+ more.</p>

            <div class="flex flex-col gap-3 px-6">
                <!-- App Store Button -->
                <a href="https://apps.apple.com/us/app/chefpilot/id6759763909" class="cta-button flex items-center justify-center gap-3 bg-black text-white py-3 px-6 rounded-xl hover:bg-gray-800 shadow-lg">
                    <svg class="w-6 h-6" viewBox="0 0 384 512" fill="currentColor">
                        <path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 21.8-88.5 21.8-11.4 0-51.1-20.8-83.6-20.1-42.9.6-82.7 24.4-104.6 63.3-44.9 77.7-11.5 191.7 31.2 255.2 20.9 30 45.9 63.5 77.3 62.6 31.1-.8 42.1-19.8 81.3-19.8 38.9 0 49.4 19.8 82 19.2 32.5-.5 54.4-30.2 74.3-59.5 21.3-31.1 30.1-61.1 30.4-62.7-.8-.3-58.6-22.5-59.7-88.6zm-58.5-174.9c15.2-18.4 25.1-44.1 22.3-69.7-22.1 1.2-48.8 15.3-64.5 33.6-14 16.2-26.3 42.1-23.1 67.2 24.6 1.9 49.8-12.7 65.3-31.1z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[10px] uppercase leading-none">Download on the</p>
                        <p class="text-lg font-semibold leading-tight">App Store</p>
                    </div>
                </a>

                <!-- Google Play Button -->
                <a href="https://play.google.com/store/apps/details?id=live.chefpilot.app&pcampaignid=web_share" class="cta-button flex items-center justify-center gap-3 bg-black text-white py-3 px-6 rounded-xl hover:bg-gray-800 shadow-lg">
                    <svg class="w-6 h-6" viewBox="0 0 512 512" fill="currentColor">
                        <path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[10px] uppercase leading-none">Get it on</p>
                        <p class="text-lg font-semibold leading-tight">Google Play</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Extra space for scrolling on small screens -->
<div class="h-12"></div>

</body>
</html>
