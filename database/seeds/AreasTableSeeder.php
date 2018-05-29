<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Zhiyi\Plus\Models\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Seeder need all regions.
     *
     * @var array
     */
    protected static $regions;

    /**
     * Create the seeder instance.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct()
    {
        if (empty(static::$regions) && config('app.env') !== 'testing') {
            static::$regions = json_decode(
                file_get_contents(MEDZ_GBT2260_RAW_PATH), true
            );
        }
    }

    /**
     * The seeder handler.
     *
     * @return void
     * @author Seven Du <shiweidu@outloo.com>
     */
    public function run()
    {
        $output = $this->command->getOutput();
        $china = new Area();
        $china->name = '中国';
        $china->pid = 0;
        $china->extends = 3;
        $china->save();

        $output->progressStart(count(
            array_keys(static::$regions)
        ));

        foreach (static::$regions as $code => $name) {
            $provinceRegionCode = substr($code, 0, 2).'0000';
            $cityRegionCode = substr($code, 0, 4).'00';
            $countyRrgionCode = (string) $code;

            $provinceName = static::$regions[$provinceRegionCode] ?? null;
            $cityName = static::$regions[$cityRegionCode] ?? null;
            $countyName = static::$regions[$countyRrgionCode] ?? null;

            $cityName = $cityRegionCode === $provinceRegionCode ? null : $cityName;
            $countyName = (
                ($countyRrgionCode === $provinceRegionCode)
                || ($countyRrgionCode === $cityRegionCode)
            ) ? null : $countyName;

            $province = null;
            if ($provinceName) {
                $province = Area::where('name', $provinceName)->where('pid', $china->id)->first();
                if (! $province) {
                    $province = new Area();
                    $province->name = $provinceName;
                    $province->pid = $china->id;
                    $province->save();
                    $output->progressAdvance(1);
                }
            }

            $city = null;
            if ($province && $cityName) {
                $city = Area::where('name', $cityName)->where('pid', $province->id)->first();
                if (! $city) {
                    $city = new Area();
                    $city->name = $cityName;
                    $city->pid = $province->id;
                    $city->save();
                    $output->progressAdvance(1);
                }
            }

            if ($city && $countyName) {
                $county = Area::where('name', $countyName)->where('pid', $city->id)->first();
                if (! $county) {
                    $county = new Area();
                    $county->name = $countyName;
                    $county->pid = $city->id;
                    $county->save();
                    $output->progressAdvance(1);
                }
            } elseif ($province && $countyName) {
                $county = Area::where('name', $countyName)->where('pid', $province->id)->first();
                if (! $county) {
                    $county = new Area();
                    $county->name = $countyName;
                    $county->pid = $province->id;
                    $county->save();
                    $output->progressAdvance(1);
                }
            }
        }

        $output->progressFinish();
        $output->newLine();
    }
}
