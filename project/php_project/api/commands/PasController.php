<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\FpPrice;
use Faker\Factory;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PasController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $faker=Factory::create("zh_CN");
        $query=FpPrice::find();
        $count=$query->count();
        foreach ($query->batch(100) as $k=>$rows){
            foreach ($rows as $row){
                FpPas::getDb()->createCommand("INSERT INTO pdt.fp_pas (part_no, area, bu, material, supplier_code, supplier_name_shot, supplier_name, customer, bs_model, quote_currency, quote_price, rmb_price, exchange_rate, effective_date, expiration_date, delivery_address, model, payment_terms, trading_terms, unite, min_order, currency, buy_price, min_price, ws_upper_price, ws_lower_price, min_num, max_num, gross_profit, gross_profit_margin, pre_tax_profit, pre_tax_profit_rate, after_tax_profit, after_tax_profit_margin, flag, num_area, upper_limit_profit, lower_limit_profit, upper_limit_profit_margin, lower_limit_profit_margin, pre_ws_lower_price, price_fd, CHECK_DATE, pas_date, limit_day) VALUES ('{$row->part_no}', '深圳', 'MTC', '锌合金件', 'VCN0027920', '和鑫盛', '{$faker->company}', '天防', '', 'RMB', '2.20000', '2.20000', '1.00000', '2016-01-01 00:00:00', '2018-12-23 00:00:00', '龙华', '/', '', '', 'PCS', '', 'RMB', 2.2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, '2016-01-01', '2017-12-23 11:32:09', '')")->execute();
            }
            echo round($k*100*100/$count,2)."%\r\n";
            flush();
        }
        echo "100%\r\n";
    }
}
