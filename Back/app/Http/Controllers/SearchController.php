<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchCardsRequest;
use App\Http\Resources\CardSearchResource;
use App\Http\Resources\OperationSearchResource;
use App\Http\Resources\ProductSearchResource;
use App\Http\Resources\ProfessionSearchResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\pam22e09;
use App\Models\pkt50_v;
use App\Models\pak65e045;
use App\Models\pak01e01;
use App\Services\SearchCardsService;

class SearchController extends Controller
{
    // Метод поиска наименования оборудования по его коду
    public function searchHardware(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');

            $query = trim($query);
            $query = preg_replace('/\./ui', '', $query);
            if (strlen($query) === 0) return [];

            $data = pak01e01::select('p451', 'p501')
                ->where('p451', 'ILIKE', "%$query%")
                ->orWhere('p501', 'LIKE', "%$query%")
                ->get();

            $data->each(function ($item, $key) {
                $item->p501 = preg_replace("/^(\d{2})(\d{4})(\d{5})/u", "$1.$2.$3", mb_substr($item->p501, 4));
            });
            return $data;
        }
    }

    // Метод общего поиска изделий
    public function searchProduct(Request $request)
    {
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');
        $perPage = $request->input('per_page', 10);
        if ($request->has('query')) {
            $query = trim($request->input('query', ''));
            $query = preg_replace('/[^А-я\d]/ui', '', $query);
            $products = pam22e09::query()
                ->where('c006', 'ILIKE', "%$query%")
                ->orWhere('p003', 'ILIKE', "%$query%")
                ->orWhere('p0081', 'ILIKE', "%$query%");
            // Log::info(json_encode($products));
        } else if ($request->has('search')) {
            $search = $request->input('search', []);
            $c006 = trim($search['c006'] ?? '');
            $p003 = trim($search['p003'] ?? '');
            $p0081 = trim($search['p0081'] ?? '');

            $c006 = preg_replace('/[^А-я\d]/ui', '', $c006);
            $p003 = preg_replace('/[^\d]/ui', '', $p003);

            $products = pam22e09::where([
                ['c006', 'ILIKE', "%$c006%"],
                ['p003', 'ILIKE', "%$p003%"],
                ['p0081', 'ILIKE', "%$p0081%"],
            ]);
        } else {
            abort(401);
        }
        if ($request->has('sort_by')) {
            $products->orderBy($sortBy, $sortDirection);
        }
        $data = ProductSearchResource::collection($products->paginate($perPage));
        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $perPage,
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // Метод общего поиска КНВ
    public function searchCards(SearchCardsRequest $request, SearchCardsService $service)
    {
        $validated = $request->validated();

        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');
        $perPage = $request->input('per_page', 10);

        $cards = $service->search($validated);

        if ($request->has('sort_by')) {
            $cards->orderBy($sortBy, $sortDirection);
        }
        $data = CardSearchResource::collection($cards->paginate($perPage));

        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $perPage,
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    // Метод поиска операции по ее шифру
    public function searchOperation(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');

            $query = trim($query);
            $query = ltrim($query, '0'); // Удаление ведущих нулей
            if (strlen($query) === 0) return [];

            if (is_numeric($query)) {
                $data = pak65e045::where('p018', '=', "$query");
            } else {
                $data = pak65e045::where('p014', 'ILIKE', "%$query%");
            }

            $data = $data->get();

            return response()->json(OperationSearchResource::collection($data));
        }
    }

    // Метод поиска профессии по шифру
    public function searchProfession(Request $request)
    {
        if ($request->has('query')) {
            $query = $request->input('query');

            $query = trim($query);
            if (strlen($query) === 0) return [];

            // Список "разрешенных" типов профессий
            $allowProfessions = [
                1 => 'Рабочий',
                2 => 'Инженер'
            ];
            $kategoryProfArray = array_keys($allowProfessions);
            $data = pkt50_v::where('profcode70', 'LIKE', "%$query%")
                ->orWhere('snm_as_scaption', 'ILIKE', "%$query%")
                ->whereIn('kategoryprof', $kategoryProfArray)
                ->orderBy('kategoryprof', 'ASC')
                ->get();

            return response()->json(ProfessionSearchResource::collection($data));
        }
    }

    // Метод поиска логов по табельному номеру из файла
    public function searchLogs(Request $request)
    {
        $tabNum = $request->tabNum;
        if (Storage::exists('logs/' . $tabNum . '.log')) {
            $logs = Storage::get('logs/' . $tabNum . '.log');
            $logs = explode("{__}:", $logs);

            $logs = array_reverse($logs);

            $data = [];
            for ($i = 0; $i < count($logs); $i++) {
                $str = explode(" ", trim($logs[$i]), 3);
                if (count($str) != 3) {
                    continue;
                };
                $date = $str[0];
                $time = mb_substr($str[1], 0, -1);
                $action = $str[2];

                $data[] = [
                    'date' => $date,
                    'time' => $time,
                    'action' => $action
                ];
            }

            return $data;
        }
        return 'not found';
    }
}
