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
use Illuminate\Console\OutputStyle;

class AreasTableSeeder extends Seeder
{
    /**
     * Seeder need all regions.
     *
     * @var array
     */
    protected static $regions = [];

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

            $province = $this->advance($output, (bool) $provinceName, $china->id, (string) $provinceName);
            $city = $this->advance($output, $province && $cityName, $province->id ?? 0, (string) $cityName);
            $county = $this->advance($output, $city && $countyName, $city->id ?? 0, (string) $countyName);
            $this->advance($output, ! $county && $province && $countyName, $province->id ?? 0, (string) $countyName);
        }

        $output->progressFinish();
        $output->newLine();
    }

    private function advance(OutputStyle $output, bool $condition, int $parentId, string $name)
    {
        if (! $condition) {
            return null;
        }

        $area = Area::where('name', $name)->where('pid', $parentId)->first();
        if (! $area) {
            $area = new Area();
            $area->name = $name;
            $area->pid = $parentId;
            $area->save();
            $output->progressAdvance(1);
        }

        return $area;
    }
}
