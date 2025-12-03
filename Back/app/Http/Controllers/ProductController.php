<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ptt05v01;
use App\Models\pam22e09;

class ProductController extends Controller
{
    // Получение всех КНВ для изделия
    public function getCards(Request $request)
    {
        $code_detail = preg_replace('/[^А-я\d]/ui', '', $request->productID);
        return ptt05v01::select()->where('code_detail', $code_detail)->orderBy('date_of_create', 'DESC')->get();
    }

    // Метод поиска изделий
    public function searchProducts(Request $request)
    {
        $query = $request->all();
        $data = [];

        if (array_key_exists('query', $query)) {
            $query = trim($query['query']); // Обрезаем лишние пробелы
            $query = preg_replace('/[^А-я\d]/ui', '', $query) ?? ''; // Оставляем только буквы и цифры
            $data = pam22e09::where('c006', 'ILIKE', "%$query%")
                ->orWhere('p003', 'ILIKE', "%$query%")
                ->orWhere('p0081', 'ILIKE', "%$query%")
                ->get();
        } else {
            $c006 = preg_replace('/[^А-я\d]/ui', '', $query['designation']) ?? ''; // Оставляем только буквы и цифры
            $p003 = preg_replace('/[^\d]/ui', '', $query['p003']) ?? ''; // Оставляем только цифры
            $p0081 = $query['Name'] ?? '';
            $query = ($c006) ? "c006 ILIKE '%{$c006}%' AND " : '';
            $query .= ($p003) ? "p003 ILIKE '%{$p003}%' AND " : '';
            $query .= ($p0081) ? "p0081 ILIKE '%{$p0081}%'" : '';
            if ($c006 || $p003 || $p0081) {
                if (mb_substr($query, -4) == 'AND ') { // Обрезаем последний AND, если есть
                    $query = mb_substr($query, 0, -4);
                }
                $data = pam22e09::whereRaw($query)->get();
            } else {
                $data = [];
            }
        }

        $result = [];
        foreach ($data as $str) {
            $result[] = [
                'designation' => $str->p006,
                'p003' => preg_replace("/^(\d{4})(\d{6})(\d{3})(\d{2})/u", "$1.$2.$3-$4", $str->p003), // Форматирование в нужный вид
                'name' => $str->p0081
            ];
        }
        return $result;
    }
}
