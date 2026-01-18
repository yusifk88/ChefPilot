<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;

Route::get('/res/{ulid}', [ItemsController::class, 'publicPost'])->name('recipe.publicPost');


Route::get("/.well-known/assetlinks.json", function () {

    return json_decode('[
  {
    "relation": ["delegate_permission/common.handle_all_urls"],
    "target": {
      "namespace": "android_app",
      "package_name": "com.chefpilot.app",
      "sha256_cert_fingerprints": ["12:08:41:8D:3D:E4:17:2F:44:59:9A:44:A7:8F:CD:E0:11:99:A6:61:9B:D2:26:83:AC:B4:C9:22:E6:F9:80:4C"]
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
