<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::transaction(function () {
            DB::select('SET NOCOUNT ON; EXEC sp_get_users')->chunk(100, function ($results) {
                foreach ($results as $result) {
                    // Process each result
                    // not need to process
                }
            });
        });
        return $query::all();
    }
}
