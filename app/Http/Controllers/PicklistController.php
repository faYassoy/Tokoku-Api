<?php

namespace App\Http\Controllers;

use App\Models\AdCategory;
use App\Models\Corporate;
use App\Models\Cube;
use App\Models\CubeType;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use App\Models\World;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PicklistController extends Controller
{
    public function category()
    {
        return ProductCategory::get(['id as value', 'name as label']);
    }

}
