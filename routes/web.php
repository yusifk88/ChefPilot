<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    return redirect("https://chefpilot.live/download");

});

Route::get('/res/{ulid}', [ItemsController::class, 'publicPost'])->name('recipe.publicPost');


Route::get("/.well-known/assetlinks.json", function () {

    return json_decode('[
  {
    "relation": ["delegate_permission/common.handle_all_urls"],
    "target": {
      "namespace": "android_app",
      "package_name": "com.chefpilot.app",
      "sha256_cert_fingerprints": ["71:1E:D6:44:B5:31:4D:7D:DE:35:3B:61:FC:2E:67:BE:F3:74:17:C4:76:A6:35:DC:88:8B:50:AD:97:58:C1:AD"]
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
