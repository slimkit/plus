<?php

use App\Models\Area;
use cn\GB2260;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return voids
     */
    public function run()
    {
        $area = new Area();
        $area->name = '中国';
        $area->id = 100000;
        $area->pid = 0;
        $area->extends = '3';
        $area->save();
        $chinaAreas = GB2260::getData();
        $pid = 0;
        foreach ($chinaAreas as $key => $value) {
            $a = GB2260::parse($key);
            $arr_a = explode(' ', $a);
            if ($arr_a[0]) {
                $res = $area
                       ->where('name', $arr_a[0])
                       ->where('pid', $area->id)
                       ->first();
                if (!$res) {
                    $res = new Area();
                    $res->name = $arr_a[0];
                    $res->pid = $area->id;
                    $res->id = $key;
                    $res->save();
                }
            }
            if (isset($arr_a[1])) {
                $res_sec = $area
                       ->where('name', $arr_a[1])
                       ->where('pid', $res->id)
                       ->first();
                if (!$res_sec) {
                    $res_sec = new Area();
                    $res_sec->name = $arr_a[1];
                    $res_sec->pid = $res->id;
                    $res_sec->id = $key;
                    $res_sec->save();
                }
            }
            if (isset($arr_a[2])) {
                $res_thr = $area
                       ->where('name', $arr_a[2])
                       ->where('pid', $res_sec->id)
                       ->first();
                if (!$res_thr) {
                    $res_thr = new Area();
                    $res_thr->name = $arr_a[2];
                    $res_thr->pid = $res_sec->id;
                    $res_thr->id = $key;
                    $res_thr->save();
                }
            }
        }
    }
}
