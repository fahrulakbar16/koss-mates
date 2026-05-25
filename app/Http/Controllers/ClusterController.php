<?php

namespace App\Http\Controllers;

use App\Actions\Cluster\DeleteClusterAction;
use App\Actions\Cluster\GetClustersAction;
use App\Actions\Cluster\StoreClusterAction;
use App\Actions\Cluster\UpdateClusterAction;
use App\Actions\BoardingHouse\DeleteBoardingHouseAction;
use App\Actions\BoardingHouse\StoreBoardingHouseAction;
use App\Actions\BoardingHouse\UpdateBoardingHouseAction;
use App\Actions\Cluster\GetClusterById;
use App\Http\Requests\Cluster\StoreClusterRequest;
use App\Http\Requests\Cluster\UpdateClusterRequest;
use App\Http\Requests\BoardingHouse\StoreBoardingHouseRequest;
use App\Http\Requests\BoardingHouse\UpdateBoardingHouseRequest;
use App\Models\Cluster;
use App\Models\BoardingHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // abort_unless(Gate::allows('clusters.view'), 403, 'Anda tidak memiliki akses untuk melihat data cluster');

        $result = app(GetClustersAction::class)->execute($request, Auth::user());
        $clusters = $result['clusters'];

        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Penyewa');
        })->get();

        return Inertia::render('Admin/Clusters/Index', [
            'clusters' => $clusters,
            'owners' => $owners,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // abort_unless(Gate::allows('clusters.create'), 403, 'Anda tidak memiliki akses untuk menambah cluster');

        return Inertia::render('Admin/Clusters/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClusterRequest $request)
    {
        // abort_unless(Gate::allows('clusters.create'), 403, 'Anda tidak memiliki akses untuk menambah cluster');

        app(StoreClusterAction::class)->execute($request->validated());

        return redirect()->route('dashboard')->with('success', 'Cluster berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cluster $cluster)
    {
        // abort_unless(Gate::allows('clusters.edit'), 403, 'Anda tidak memiliki akses untuk mengubah cluster');

        return Inertia::render('Admin/Clusters/Edit', [
            'cluster' => $cluster
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClusterRequest $request, Cluster $cluster)
    {
        // abort_unless(Gate::allows('clusters.edit'), 403, 'Anda tidak memiliki akses untuk mengubah cluster');

        app(UpdateClusterAction::class)->execute($cluster, $request->validated());

        return redirect()->route('dashboard')->with('success', 'Cluster berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cluster $cluster)
    {
        // abort_unless(Gate::allows('clusters.delete'), 403, 'Anda tidak memiliki akses untuk menghapus cluster');

        try {
            app(DeleteClusterAction::class)->execute($cluster);

            return redirect()->back()->with('success', 'Cluster berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus cluster: ' . $e->getMessage());
        }
    }
}
