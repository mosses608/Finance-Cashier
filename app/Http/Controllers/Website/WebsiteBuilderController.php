<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class WebsiteBuilderController extends Controller
{
    public function websiteBuilder($encryptedId)
    {
        try {
            $decryptedCompanyId = Crypt::decrypt($encryptedId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'Your website is not yet built, please be patient!');
    }
}
