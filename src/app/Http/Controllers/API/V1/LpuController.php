<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LpuRequest;
use App\Models\LPU;
use App\Service\LpuFileService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class LpuController extends Controller
{
    /**
     * @return Application|ResponseFactory|Response
     */
    public function index()
    {
        $list = LPU::select(['id', 'version'])->get();
        return response([
            'success' => true,
            'data' => $list,
        ]);
    }

    /**
     * @param LpuRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function store(LpuRequest $request)
    {
        $requestData = $request->validated();
        if (LpuFileService::checkForm($requestData['lpu'])) {
            try {
                DB::beginTransaction();
                $requestData['file'] = json_encode($requestData['lpu']);
                $lpu = LPU::create($requestData);
                DB::commit();
                return response(['success' => true, 'data' => ['id' => $lpu?->id]]);
            } catch (\Throwable $exception) {
                Log::error('LpuController->store ' . $exception->getMessage(), $requestData);
                DB::rollBack();
            }
        }
        return response(['success' => false]);
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function show($id)
    {
        $lpu = LPU::find($id);
        if ($lpu?->id) {
            return response([
                'success' => true,
                'data' => $lpu
            ]);
        }
        return response(['success' => false]);
    }

    /**
     * @param LpuRequest $request
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function update(LpuRequest $request, $id)
    {
        $requestData = $request->validated();
        $lpu = LPU::find($id);
        if ($lpu?->id && LpuFileService::checkForm($requestData['lpu'])) {
            try {
                DB::beginTransaction();
                $requestData['file'] = json_encode($requestData['lpu']);
                if ($requestData['version'] == $lpu?->version) {
                    $lpu->update($requestData);
                } else {
                    $lpu = $lpu->create($requestData);
                }
                DB::commit();
                return response([
                    'success' => true,
                    'data' => $lpu
                ]);
            } catch (\Throwable $exception) {
                Log::error('LpuController->update ' . $exception->getMessage(), $requestData);
                DB::rollBack();
            }
        }
        return response(['success' => false]);
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function destroy($id)
    {
        $lpu = LPU::find($id);
        if ($lpu?->id) {
            try {
                DB::beginTransaction();
                $lpu->delete();
                DB::commit();
                return response([
                    'success' => true,
                ]);
            } catch (\Throwable $exception) {
                Log::error('LpuController->delete ' . $exception->getMessage(), compact('id'));
                DB::rollBack();
            }
        }
        return response(['success' => false]);
    }
}
