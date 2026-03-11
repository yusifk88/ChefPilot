<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$post->user->name}} . Chefpilot</title>

    <meta property="og:title" content="{{$post->user->name}}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{route("post.publicPost",$post->ulid)}}" />
    <meta property="og:image" content="{{$post->recipe->photos[0]->url}}" />
    <meta property="og:description" content="{{$post->caption}}">
    <meta name="twitter:title" content="{{$post->caption}}">
    <meta name="twitter:description" content="{{$post->caption}}">
    <meta name="twitter:image" content="{{$post->recipe->photos[0]->url}}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Font configuration (Inter) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-green-50 text-gray-800 pb-24 md:pb-0">


<!-- Main Content -->
<main class="max-w-4xl mx-auto px-4 pt-6 md:pt-10 flex flex-col md:flex-row gap-8">

    <!-- Left Column: The Shared Post -->
    <div class="flex-1">
        <div class="bg-white rounded-3xl shadow-xl shadow-green-900/5 overflow-hidden border border-green-100/50">

            <!-- User Header -->
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="{{$post->user->image_url}}"
                             alt="Sarah Jenkins"
                             class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                        <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white p-0.5 rounded-full border-2 border-white flex items-center justify-center">
                            <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 leading-tight">{{$post->user->name}}</h3>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span>•</span>
                            <span>{{$post->created_at->diffForHumans()}}</span>
                        </div>
                    </div>
                </div>
{{--                <button class="text-gray-400 hover:text-emerald-600 transition-colors">--}}
{{--                    <i data-lucide="share-2" class="w-5 h-5"></i>--}}
{{--                </button>--}}
            </div>

            <!-- Post Text Content -->
            <div class="px-6 pb-4">
                <p class="text-gray-700 leading-relaxed text-lg">
                    {!!  $post->caption !!}
                </p>
            </div>

            <!-- CORE FEATURE: Distinct Recipe Card -->
            <div class="px-4 pb-6">
                <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 cursor-pointer transition-all hover:shadow-md">

                    <!-- Recipe Image -->
                    <div class="aspect-video w-full overflow-hidden relative">
                        <img src="{{$post->recipe->photos[0]->url}}"
                             onerror="this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.png'"
                             alt="{{$post->recipe->name}}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

{{--                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-emerald-800 flex items-center gap-1 shadow-sm">--}}
{{--                            <i data-lucide="chef-hat" class="w-3 h-3"></i>--}}
{{--                            Generated Recipe--}}
{{--                        </div>--}}

                        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>

{{--                        <div class="absolute bottom-4 left-4 text-white">--}}
{{--                            <div class="flex items-center gap-1 text-xs font-medium bg-black/30 backdrop-blur-md w-fit px-2 py-0.5 rounded-md mb-2 border border-white/20">--}}
{{--                                <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>--}}
{{--                                4.8--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                    <!-- Recipe Details -->
                    <div class="p-5">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 group-hover:text-emerald-700 transition-colors mb-2">
                            {{$post->recipe->name}}
                        </h2>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{$post->recipe->description}}

                        </p>

                        <div class="flex items-center gap-4 text-sm font-medium text-gray-500 border-t border-emerald-100 pt-4">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-4 h-4 text-emerald-500"></i>
                                {{$post->recipe->estimatedTimeMinutes}} min
                            </div>
                            <div class="flex items-center gap-1.5">
                               <small>Cal: {{$post->recipe->nutrition["calories"]}}</small>

                               <small>PRT:{{$post->recipe->nutrition["protein"]}}</small>
                                <small>CABs:{{$post->recipe->nutrition["carbohydrates"]}}</small>

                                <small>FAT:{{$post->recipe->nutrition["fat"]}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interaction Bar -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <button id="like-btn" class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition-colors group">
                        <i data-lucide="heart" class="w-6 h-6 group-hover:fill-red-500 transition-all"></i>
                        <span id="like-count" class="font-semibold">{{$post->likes_count}}</span>
                    </button>
                    <button class="flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                        <span class="font-semibold">{{$post->comments_count}}</span>
                    </button>
                </div>
{{--                <span class="text-xs text-gray-400 font-medium">12k views</span>--}}
            </div>

            <!-- Comments Preview Section (Teaser) -->
            <div class="bg-gray-50 px-6 py-6 border-t border-gray-200 relative">
                <!-- Blur Overlay & CTA -->
                <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-gray-50 via-gray-50/90 to-transparent flex flex-col items-center justify-end pb-6">
                    <div class="text-center px-4">
                        <p class="text-gray-600 font-medium mb-3">Join the conversation</p>
                        <a href="https://chefpilot.live/download" class="bg-white text-emerald-600 border border-emerald-200 px-6 py-2 rounded-full font-bold shadow-sm hover:bg-emerald-50 transition-colors text-sm">
                            Open in App
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Mobile Bottom Floating CTA -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 pb-6 shadow-[0_-4px_20px_rgba(0,0,0,0.1)] z-50">
    <div class="flex items-center gap-4">
        <div class="bg-emerald-600 w-10 h-10 rounded-lg flex items-center justify-center shrink-0">
            <img src="https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/chefpilot_icon.png">
        </div>
        <div class="flex-1">
            <p class="font-bold text-gray-900 text-sm">Get the full experience</p>
            <p class="text-xs text-gray-500">Save recipes & create your own</p>
        </div>
        <button class="bg-black text-white px-5 py-2.5 rounded-full font-bold text-sm shrink-0">
            Open App
        </button>
    </div>
</div>

<!-- Scripts for Interactions -->
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Simple Mobile Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Simple Like Button Interaction
    const likeBtn = document.getElementById('like-btn');
    const likeCount = document.getElementById('like-count');
    const likeIcon = likeBtn.querySelector('svg'); // gets the lucide svg
    let isLiked = false;

    likeBtn.addEventListener('click', () => {
        isLiked = !isLiked;
        if (isLiked) {
            likeBtn.classList.remove('text-gray-500');
            likeBtn.classList.add('text-red-500');
            likeIcon.setAttribute('fill', 'currentColor');
            likeCount.textContent = '1244';
        } else {
            likeBtn.classList.add('text-gray-500');
            likeBtn.classList.remove('text-red-500');
            likeIcon.setAttribute('fill', 'none');
            likeCount.textContent = '1243';
        }
    });
</script>
</body>
</html>
