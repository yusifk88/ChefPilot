<x-mail::message>

<div style="text-align: center; margin-bottom: 60px;">
<h1 style="color: #2d3436; margin: 0; font-size: 28px; line-height: 1.2;">Howdy, {{$user->name}}!</h1>
<p style="color: #636e72; font-size: 16px; margin-top: 10px;">
I went through what you’ve got in the kitchen and put together some seriously tasty recipes for you to try
today. 🍲
I think they turned out great—definitely give them a shot and let me know what you think! And hey, if
they're a hit, don't forget to share the love (and the food) with your friends.
Happy cooking!
</p>
</div>

@foreach($recipes as $item)
<a href="https://google.com">
<table border="0" cellpadding="0" cellspacing="0" width="100%"
style="border: 1px solid rgba(2,209,12,0.29); border-radius: 12px; overflow: hidden; margin-top: 25px">
<tr>
<td>
<img
src="{{isset($item?->photos[0]) ? $item->photos[0]->url : 'https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.png'}}"
alt="Mediterranean Bowl"
class="recipe-image"
style="width: 100%; height: 300px; object-fit: cover;">
</td>
</tr>
<tr>
<td style="padding: 25px;">
<div style="display: flex; gap: 8px; margin-bottom: 15px;">
<span class="color-{{strtolower($item->difficulty)}}"
style="padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase;">{{$item->difficulty}}</span>
<span
style="padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-left: 5px;">Prep:{{$item->estimatedTimeMinutes}} Mins</span>

</div>

<h2 style="color: #2d3436; margin: 0 0 10px 0; font-size: 22px;">{{$item->name}}</h2>
<p style="color: #636e72; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
{{$item->description}}
</p>

@if($item->ulid)
<x-mail::button :url="route('recipe.publicPost',$item->ulid)">
View Recipe
</x-mail::button>
@endif

</td>
</tr>
</table>
</a>
@endforeach

<!-- Secondary Suggestion -->
<div style="margin-top: 40px; text-align: center;">
<h3 style="color: #2d3436; font-size: 18px;">Not feeling it?</h3>
<p style="color: #636e72; margin-bottom: 15px;">We have 3 other matches waiting in your dashboard.</p>
</div>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
