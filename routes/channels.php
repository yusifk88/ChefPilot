<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('recipesEvents_{id}', function ($user, $id) {
   // return (int) $user->id === (int) $id;

    return true;
});
