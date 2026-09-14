<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RoletaDiaria;
use App\Models\RoletaRecompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoletaController extends Controller
{
    private function hojeBR(): string
    {
        return now('America/Sao_Paulo')->toDateString();
    }

    public function index()
    {
        $rewards = RoletaRecompensa::active()->ordered()->get();
        $lastSpin = null;

        if (auth()->check()) {
            $lastSpin = RoletaDiaria::where('user_id', auth()->id())
                ->where('spin_date', $this->hojeBR())
                ->with('reward')
                ->first();
        }

        return view('web.roleta.index', compact('rewards', 'lastSpin'));
    }

    public function spin(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Faça login para girar'], 401);
        }

        $already = RoletaDiaria::where('user_id', auth()->id())
            ->where('spin_date', $this->hojeBR())
            ->exists();

        if ($already) {
            return response()->json(['error' => 'Você já girou hoje'], 422);
        }

        $rewards = RoletaRecompensa::active()->ordered()->get();
        if ($rewards->isEmpty()) {
            return response()->json(['error' => 'Nenhum prêmio configurado'], 422);
        }

        // If a guaranteed prize exists, always select it
        $guaranteed = $rewards->firstWhere('garantido', true);
        if ($guaranteed) {
            $selected = $guaranteed;
        } else {
            $totalWeight = $rewards->sum('weight');
            $rand = random_int(1, $totalWeight);
            $cumulative = 0;
            $selected = null;

            foreach ($rewards as $reward) {
                $cumulative += $reward->weight;
                if ($rand <= $cumulative) {
                    $selected = $reward;
                    break;
                }
            }

            if (!$selected) {
                $selected = $rewards->last();
            }
        }

        $segments = $rewards->count();
        $segmentAngle = 360 / $segments;
        $selectedIndex = $rewards->search(function ($r) use ($selected) {
            return $r->id === $selected->id;
        });

        DB::beginTransaction();
        try {
            $spin = RoletaDiaria::create([
                'user_id' => auth()->id(),
                'roleta_recompensa_id' => $selected->id,
                'value' => $selected->value,
                'spin_date' => $this->hojeBR(),
            ]);

            $wallet = auth()->user()->wallet()->firstOrCreate(
                ['user_id' => auth()->id()],
                ['balance' => 0, 'balance_bonus' => 0]
            );
            $wallet->increment('balance', $selected->value);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao processar giro'], 500);
        }

        return response()->json([
            'selectedIndex' => $selectedIndex,
            'segmentAngle' => $segmentAngle,
            'segments' => $segments,
            'reward' => [
                'label' => $selected->label,
                'value' => (float) $selected->value,
                'color' => $selected->color,
            ],
        ]);
    }
}
