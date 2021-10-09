<?php

namespace App\Overrides;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler;

class LFMConfigHandler extends ConfigHandler
{
    public function userField()
    {
        return auth('admin')->user()->id;
    }
}
