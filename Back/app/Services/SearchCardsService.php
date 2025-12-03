<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\ptt05v01;
use App\Models\ptt05v03;

class SearchCardsService
{
    public function search(array $data)
    {
        if (isset($data['query'])) {
            $cards = $this->commonSearch($data['query']);
        } else {
            $cards = $this->advancedSearch($data['search']);
        }

        return $cards;
    }

    private function commonSearch(string $query)
    {
        $creationDate = ptt05v03::select('id_v01', DB::raw('MAX(updated_at) AS original_create_date'))->groupBy('id_v01');

        $cards = ptt05v01::select(
            'ptt05v01.id_v01',
            'ptt05v01.designation',
            'ptt05v01.code_detail',
            'pam00.pam22e09.p0081 as name',
            'ptt05v01.workshop',
            'ptt05v01.cipher_main_td',
            'ptt05v01.create_service_number as norm',
            'ptt05v01.date_of_create as created_date',
            'original_create_date as updated_date',
            'ptt05status.value as status',
        );

        $this->findByDesignation($cards, $query);
        $this->findByCode($cards, $query);
        $this->findByProductName($cards, $query);

        $cards
            ->leftJoin('pam00.pam22e09', 'pam00.pam22e09.c006', '=', 'ptt05v01.designation')
            ->leftJoin('ptt05status', 'ptt05status.id_status', '=', 'ptt05v01.id_status')
            ->leftJoinSub($creationDate, 'creation_date', function ($join) {
                $join->on('creation_date.id_v01', '=', 'ptt05v01.id_v01');
            });

        return $cards;
    }
    // косяк
    private function advancedSearch(array $data)
    {
        [
            'designation' => $designation,
            'code_detail' => $code_detail,
            'name' => $name,
            'workshop' => $workshop,
            'cipher_main_td' => $cipher_main_td,
            'cipher_of_the_reference_tp' => $cipher_of_the_reference_tp,
            'norm' => $norm,
            'dateCreatedFrom' => $dateCreatedFrom,
            'dateCreatedTo' => $dateCreatedTo,
            'dateEditedFrom' => $dateEditedFrom,
            'dateEditedTo' => $dateEditedTo,
        ] = $data;

        $cards = ptt05v01::select(
            'ptt05v01.id_v01',
            'ptt05v01.designation',
            'ptt05v01.code_detail',
            'pam00.pam22e09.p0081 as name',
            'ptt05v01.workshop',
            'ptt05v01.cipher_main_td',
            'ptt05v01.create_service_number as norm',
            'ptt05v01.date_of_create as created_date',
            'original_create_date as updated_date',
            'ptt05status.value as status',
        );

        $cards
            ->leftJoin('pam00.pam22e09', 'pam00.pam22e09.c006', '=', 'ptt05v01.designation')
            ->leftJoin('ptt05status', 'ptt05status.id_status', '=', 'ptt05v01.id_status');

        $this->filterByDesignation($cards, $designation);
        $this->filterByCode($cards, $code_detail);
        $this->filterByProductName($cards, $name);
        $this->filterByWorkshop($cards, $workshop);
        $this->filterByMainTP($cards, $cipher_main_td);
        $this->filterByReferenceTP($cards, $cipher_of_the_reference_tp);
        $this->filterByNormSetter($cards, $norm);
        $this->filterByCreationDate($cards, $dateCreatedFrom, $dateCreatedTo);
        $this->filterByEditDate($cards, $dateEditedFrom, $dateEditedTo);

        return $cards;
    }

    protected function findByDesignation($query, $designation)
    {
        if ($designation) {
            $query->orWhere('ptt05v01.designation', 'ILIKE', "%$designation%");
        }
    }

    protected function findByCode($query, $code)
    {
        if ($code) {
            $query->orWhere('ptt05v01.code_detail', 'ILIKE', "%$code%");
        }
    }

    protected function findByProductName($query, $productName)
    {
        if ($productName) {
            $query->orWhere('pam00.pam22e09.p0081', 'ILIKE', "%$productName%");
        }
    }

    protected function filterByCreationDate($query, $createdFrom, $createdTo)
    {
        if ($createdFrom) {
            $query->where('ptt05v01.date_of_create', '>=', $createdFrom);
        }
        if ($createdTo) {
            $query->where('ptt05v01.date_of_create', '<=', $createdTo);
        }
    }

    protected function filterByEditDate($query, $editedFrom, $editedTo)
    {
        $creationDate = ptt05v03::select('id_v01', DB::raw('MAX(updated_at) AS original_create_date'))
            ->groupBy('id_v01');

        if ($editedFrom || $editedTo) {
            $creationDate
                ->addSelect('updated_at')
                ->whereBetween('updated_at', [
                    $editedFrom ? $editedFrom : date('Y-m-d H:i:s', 0),
                    $editedTo ? $editedTo : now(),
                ])
                ->groupBy('updated_at');
        }

        $query
            ->leftJoinSub($creationDate, 'creation_date', function ($join) {
                $join->on('creation_date.id_v01', '=', 'ptt05v01.id_v01');
            });

        if ($editedFrom || $editedTo) {
            $query->where(function ($query) {
                $query->whereNotNull('creation_date.id_v01');
            });
        }
    }

    protected function filterByDesignation($query, $designation)
    {
        if ($designation) {
            $query->where('ptt05v01.designation', 'ILIKE', "%$designation%");
        }
    }

    protected function filterByCode($query, $code)
    {
        if ($code) {
            $query->where('ptt05v01.code_detail', 'ILIKE', "%$code%");
        }
    }

    protected function filterByProductName($query, $productName)
    {
        if ($productName) {
            $query->where('pam00.pam22e09.p0081', 'ILIKE', "%$productName%");
        }
    }

    protected function filterByWorkshop($query, $workshop)
    {
        if ($workshop) {
            $query->where(DB::raw('CAST(workshop AS text)'), 'ILIKE', "%$workshop%");
        }
    }

    protected function filterByMainTP($query, $mainTP)
    {
        if ($mainTP) {
            $query->where('ptt05v01.cipher_main_td', 'ILIKE', "%$mainTP%");
        }
    }

    protected function filterByReferenceTP($query, $referenceTP)
    {
        if ($referenceTP) {
            $query->where('ptt05v02.cipher_of_the_reference_tp', 'ILIKE', "%$referenceTP%");
        }
    }

    protected function filterByNormSetter($query, $normSetter)
    {
        if ($normSetter) {
            $query->where('ptt05v01.create_service_number', 'ILIKE', "%$normSetter%");
        }
    }
}
