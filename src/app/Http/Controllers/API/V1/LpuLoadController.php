<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\LPU;
use App\Service\LpuFileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 *
 */
class LpuLoadController extends Controller
{

    /**
     * @param $id
     * @return Response|StreamedResponse
     */
    public function download($id)
    {
        $lpu = LPU::find($id);
        if ($lpu?->file) {
            $path = "/lpu/" .  $lpu?->version . '[' . $lpu?->id .  ']';
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            Storage::put($path, $lpu?->file);
            return Storage::download($path);
        } else {
            return response()->noContent();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function upload(Request $request)
    {
        $file = $request->file('file')->get();
        if ($file) {
            try {
                DB::beginTransaction();
                if (LpuFileService::checkForm(json_decode($file, true))) {
                    $lpu = LPU::create([
                        'file' => $file,
                        'version' => Carbon::now()->format('y-m-d H:i')
                    ]);
                    DB::commit();
                    return response([
                        'success' => true,
                        'data' => $lpu,
                        ]);
                }
            } catch (\Throwable $exception) {
                Log::error('LpuLoadController->upload ' . $exception->getMessage());
                DB::rollBack();
            }
        }
        return response(['success' => false]);
    }

    public function downloadForm()
    {
        $path = "/lpu/lpu.json";
        if (Storage::exists($path)) {
            return Storage::download($path);
        }
        return response()->noContent();
    }
}
