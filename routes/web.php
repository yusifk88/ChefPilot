<?php

use App\Http\Controllers\ItemsController;
use App\Http\Controllers\Social\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    return redirect("https://chefpilot.live/download");

});

Route::get('/res/{ulid}', [ItemsController::class, 'publicPost'])->name('recipe.publicPost');
Route::get('/p/{ulid}' , [PostController::class, "publicPost"])->name('post.publicPost');

Route::get("/.well-known/assetlinks.json", function () {

    return json_decode('[
  {
    "relation": ["delegate_permission/common.handle_all_urls"],
    "target": {
      "namespace": "android_app",
      "package_name": "live.chefpilot.app",
      "sha256_cert_fingerprints": ["71:1E:D6:44:B5:31:4D:7D:DE:35:3B:61:FC:2E:67:BE:F3:74:17:C4:76:A6:35:DC:88:8B:50:AD:97:58:C1:AD","12:08:41:8D:3D:E4:17:2F:44:59:9A:44:A7:8F:CD:E0:11:99:A6:61:9B:D2:26:83:AC:B4:C9:22:E6:F9:80:4C"]
    }
  }
]');

});


Route::get("/.well-known/apple-app-site-association", function () {

    return json_decode(
        '{
  "applinks": {
    "apps": [],
    "details": [
      {
        "appID": "TEAMID.com.myapp.app",
        "paths": ["*"]
      }
    ]
  }
}'
    );

});
