<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use App\modules\CSVCreator;
use App\Models\costs;
use App\Models\users_sum_costs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserSumCostShipped;

class CostsController extends Controller
{
    public function insert_or_update()
    {
        // Делается запись в БД
        $data = [['Что-то сделать', 500, 1], ['Что-то сделать', 500, 2]];
        $insert_to_base = [];
        $users_id = [];

        foreach ($data as $datum) {
            $user_id = $datum[2];
            $insert_to_base[$user_id]['cost'] = $datum[0];
            $insert_to_base[$user_id]['price'] = $datum[1];
            $insert_to_base[$user_id]['user_id'] = $user_id;
            $users_id[] = $user_id;
        }

        costs::insert($insert_to_base);

        //Берутся изменения в заказах
        $user = users::with('costs')
            ->withSum('costs', 'price')
            ->withCount('costs')
            ->whereIn('id', $users_id)
            ->get();

        // Перебор коллекций, дабы взять нужные данные
        $users_sum_costs_insert = [];

        foreach ($user as $datum) {
            $users_sum_costs_insert [$datum->id] = "($datum->id, $datum->costs_count, $datum->costs_sum_price)";
        }
        $users_sum_costs_insert  = implode(',', $users_sum_costs_insert );

        //Пишется в сводную таблицу изменения
        DB::insert("insert into users_sum_costs(`user_id`,`count`,`sum`)
                      values $users_sum_costs_insert 
                      on duplicate key update `count` = values(`count`), `sum` = values(`sum`)");

        return $this->mail_report();

    }

    public function csv_report()
    {
        $user_count_sum_costs = users::with('costs')->withSum('costs', 'price')
            ->withCount('costs')
            ->orderBy('costs_sum_price', 'desc')
            ->get();


        $CSVCreator = new CSVCreator();
        $CSVCreator->create($user_count_sum_costs);

    }

    public function mail_report()
    {
        $user_sum_costs = users_sum_costs::with('user')->get();
        $user_data = [];

        foreach ($user_sum_costs as $user)
        {
            $user_data[] = ['name' => $user->user->name, 'count' => $user->count, 'sum' => $user->sum];
        }

        Mail::to('duircianos@yandex.ru')->send(new UserSumCostShipped($user_data));
    }
}
