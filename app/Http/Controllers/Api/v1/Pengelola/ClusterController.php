<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Cluster\DeleteClusterAction;
use App\Actions\Cluster\GetClusterById;
use App\Actions\Cluster\GetClustersAction;
use App\Actions\Cluster\StoreClusterAction;
use App\Actions\Cluster\UpdateClusterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cluster\StoreClusterRequest;
use App\Http\Requests\Cluster\UpdateClusterRequest;
use App\Http\Resources\BoardingHouse\ClusterResource;
use App\Http\Resources\Cluster\ClusterDetailResource;
use App\Models\Cluster;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClusterController extends Controller
{
    use ApiResponse;


    /**
     * Menampilkan daftar cluster.
     */
    public function index(Request $request)
    {
        $cluster = app(GetClustersAction::class)->execute($request, Auth::user());

        return $this->apiResponse(ClusterResource::collection($cluster['clusters'])->response()->getData(true), 'Data cluster berhasil diambil');
    }

    /**
     * Menyimpan cluster baru ke database.
     */
    public function store(StoreClusterRequest $request)
    {
        app(StoreClusterAction::class)->execute($request->validated(), Auth::user());

        return $this->apiResponse(null, 'Cluster berhasil ditambahkan');
    }

    /**
     * Menampilkan detail cluster.
     */
    public function show($id)
    {
        $cluster = app(GetClusterById::class)->execute($id, Auth::user());

        return $this->apiResponse(new ClusterDetailResource($cluster), 'Data cluster berhasil diambil');
    }


    /**
     * Memperbarui data cluster di database.
     */
    public function update(UpdateClusterRequest $request, $clusterId)
    {
        $cluster = Cluster::findOrFail($clusterId);
        app(UpdateClusterAction::class)->execute($cluster, $request->validated());

        return $this->apiResponse(null, 'Cluster berhasil diperbarui');
    }

    /**
     * Menghapus cluster dari database.
     */
    public function destroy($clusterId)
    {
        $cluster = Cluster::findOrFail($clusterId);
        app(DeleteClusterAction::class)->execute($cluster);

        return $this->apiResponse(null, 'Cluster berhasil dihapus');
    }
}
