<?php
/**
 * User: F3859386
 * Date: 2017/1/3
 * Time: 16:35
 */
use yii\helpers\Url;

$product = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-goods.png') . '>&nbsp;';
$firm = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-book.png') . '>&nbsp;';
$shield = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-shield.png') . '>&nbsp;';
$people = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-people.png') . '>&nbsp;';
$setting = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-setting.png') . '>&nbsp;';
$sale = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-setting.png') . '>&nbsp;';
$purchase = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-setting.png') . '>&nbsp;';
$report = '<img src=' . Url::to('@web/img/layout/icon-background.png') . '><img src=' . Url::to('@web/img/layout/icon-setting.png') . '>&nbsp;';

return [
    $product . '商品管理' => [
//        '<span>开发计划</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '新增商品需求',
//                    'url' => '/ptdt/product-dvlp/add'],
//                ['title' => '商品需求列表',
//                    'url' => '/ptdt/product-dvlp/index'],
//                ['title' => '商品开发计划',
//                    'url' => ''],
//                ['title' => '计划草稿列表',
//                    'url' => ''],
//            ],
//        ],
//        '<span>开发进程</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '厂商信息',
//                    'url' => '/ptdt/firm/index'],
//                ['title' => '拜访计划',
//                    'url' => '/ptdt/visit-plan/index'],
//                ['title' => '拜访履历表',
//                    'url' => '/ptdt/visit-resume/index'],
//                ['title' => '谈判履历表',
//                    'url' => '/ptdt/firm-negotiation/index'],
//                ['title' => '呈报列表',
//                    'url' => '/ptdt/firm-report/index'],
//                ['title' => '商品认证列表',
//                    'url' => ''],
//            ],
//        ],
        '<span>料号管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
//                ['title' => '料号申请',
//                    'url' => '/ptdt/material-code/add'],
//                ['title' => '料号申请列表',
//                    'url' => '/ptdt/material-code/index'],
//                ['title' => '料号大分类维护',
//                    'url' => '/ptdt/category/index'],
//                ['title' => '分级分类申请',
//                    'url' => '/ptdt/material-code/add'],
                ['title' => '核价资料',
                    'url' => '/ptdt/pno-bind-spp/index'],
            ],
        ],
//        '<span>定价管理</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '定价列表',
//                    'url' => '/ptdt/partno-price-confirm/index'],
//                ['title' => '新增定价',
//                    'url' => ''],
//                ['title' => '定价申请列表',
//                    'url' => '/ptdt/partno-price-apply/index'],
//                ['title' => '定价申请',
//                    'url' => ''],
//                ['title' => '商品核价',
//                    'url' => '/ptdt/partno-price-review/index'],
//                ['title' => '物流费率维护',
//                    'url' => ''],
//                ['title' => '经费费率维护',
//                    'url' => '']
//            ],
//        ],
        '<span>商品库管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '商品列表',
                    'url' => '/ptdt/product-list/index'],
                ['title' => '发布新商品',
                    'url' => '/ptdt/goods-released/index'],
                ['title' => '类别管理',
                    'url' => '/ptdt/category-manage/index'],
            ],
        ],
//        '<span>设置</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '商品经理人管理',
//                    'url' => '/ptdt/pm/index'],
//            ],
//        ]
    ],
//    $shield . '商品验证中心' => [
//        '<span>商品认证</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '认证需求列表',
//                    'url' => ''],
//                ['title' => '认证排配列表',
//                    'url' => ''],
//                ['title' => '认证计划列表',
//                    'url' => ''],
//                ['title' => '认证报告单列表',
//                    'url' => ''],
//            ],
//        ],
//        '<span>商品验证</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '验证报告单',
//                    'url' => ''],
//                ['title' => '验证排配',
//                    'url' => ''],
//                ['title' => '验证计划',
//                    'url' => ''],
//            ],
//        ],
//        '<span>商品技术支持</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '技术支持申请表',
//                    'url' => ''],
//                ['title' => '技术支持排配列表',
//                    'url' => ''],
//                ['title' => '技术支持记录列表',
//                    'url' => ''],
//            ],
//        ],
//    ],
    $firm . '供应商管理' => [
//        '<span>供应商评鉴</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '厂商评鉴申请',
//                    'url' => '/ptdt/firm-evaluate-apply/add'],
//                ['title' => '厂商评鉴申请列表',
//                    'url' => '/ptdt/firm-evaluate-apply/index'],
//                ['title' => '新增厂商评鉴',
//                    'url' => '/ptdt/firm-evaluate/add'],
//                ['title' => '厂商评鉴管理列表',
//                    'url' => '/ptdt/firm-evaluate/index'],
//            ],
//        ],
//        '<span>供应商申请</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '供应商申请',
//                    'url' => '/ptdt/supplier/create'],
//                ['title' => '供应商申请列表',
//                    'url' => '/ptdt/supplier/index'],
//            ],
//        ],
        '<span>供应商资料</span> <i class=\'icon-caret-down\'></i>' => [
            [
//                ['title' => '供应商资料库',
//                    'url' => '/ptdt/supplier-lib/index'],
                ['title' => '供应商申请',
                    'url' => '/spp/supplier/add'],
                ['title' => '供应商列表',
                    'url' => '/spp/supplier/index'],
            ],
        ],
    ],
    $people . '客户关系管理' => [
        '<span>活动管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '报名列表',
                    'url' => '/crm/crm-active-apply/index'],
                ['title' => '活动统计列表',
                    'url' => '/crm/crm-active-count/index'],
                ['title' => '活动行事历',
                    'url' => '/crm/crm-active-calendar/index'],
                ['title' => '网络社区营销',
                    'url' => '/crm/crm-community-marketing/index'],
                ['title' => '媒体资源列表',
                    'url' => '/crm/crm-media-resource/index'],
                ['title' => '活动相关设置',
                    'url' => '/crm/crm-active-set/index'],
            ],
        ],
        '<span>会员管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '潜在客户列表',
                    'url' => '/crm/crm-potential-customer/index'],
                ['title' => '会员列表',
                    'url' => '/crm/crm-member/index'],
                ['title' => '会员回访记录',
                    'url' => '/crm/crm-return-visit/index'],
                ['title' => '会员开发任务列表',
                    'url' => '/crm/crm-member-develop/index'],
            ],
        ],
        '<span>招商客戶管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '招商会员列表',
                    'url' => '/crm/crm-investment-customer/list'],
                ['title' => '招商会员开发',
                    'url' => '/crm/crm-investment-dvelopment/index'],
                ['title' => '招商类目负责人设置',
                    'url' => '/crm/crm-mchpdtype/index'],
            ],
        ],
        '<span>行程管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '拜访计划管理',
                    'url' => '/crm/crm-visit-plan/index'],
                ['title' => '拜访记录管理',
                    'url' => '/crm/crm-visit-record/index'],
                ['title' => '行程日历',
                    'url' => '/crm/sale-visit-calendar/index'],

            ],
        ],
        '<span>销售客户管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '客户管理',
                    'url' => '/crm/crm-customer-info/index'],
                ['title' => '我的客户',
                    'url' => '/crm/crm-customer-manage/index'],
                ['title' => '客户代码申请列表',
                    'url' => '/crm/crm-customer-apply/index'],
            ],
        ],
        '<span>信用客户管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
//                ['title' => '账信申请',
//                    'url' => '/crm/crm-credit-apply/create'],
                ['title' => '账信申请列表',
                    'url' => '/crm/crm-credit-apply/index'],
//                ['title' => '账信查询列表',
//                    'url' => '/crm/crm-credit-apply/list'],
                ['title' => '信用额度查询',
                    'url' => '/crm/crm-credit-limit/index'],
//                ['title' => '批量维护信用额度',
//                    'url' => '/crm/crm-credit-limit/maintain'],
//                ['title' => '信用额度类型设置',
//                    'url' => '/crm/crm-credit-maintain/index'],
            ],
        ],
        //       '<span>商品验证申请</span> <i class=\'icon-caret-down\'></i>' => [
        //      [
        //        ['title' => '商品验证申请',
        //             'url' => ''],
        //         ['title' => '商品验证申请',
        //            'url' => ''],
        //         ['title' => '商品技术支持申请',
        //             'url' => ''],
        //         ['title' => '商品技术支持申请查询',
        //             'url' => ''],
        //    ],
        //  ],
//        '<span>报价单</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '新增报价单', 'url' => '/crm/crm-quote-price/create']
//            ]
//        ],
        '<span>基础数据设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '销售员资料',
                    'url' => '/crm/employee-setting/index'],
                ['title' => '销售角色设置',
                    'url' => '/crm/role-setting/index'],
                ['title' => '销售区域设置',
                    'url' => '/crm/area-setting/index'],
                ['title' => '销售点维护',
                    'url' => '/crm/store-setting/index'],
            ],
        ],
        '<span>查询</span> <i class=\'icon-caret-down\'></i>' => [
            [
//                ['title' => '商品定价查询', 'url' => '/crm/crm-product-price/index'],
                ['title' => '客户资料查询', 'url' => '/crm/crm-all-customer/index'],
                ['title' => '个人行程记录查询', 'url' => '/crm/crm-person-record/index'],
                ['title' => '销售客户资料查询', 'url' => '/crm/crm-all-sale/index']
            ]
        ],
//        '<span>基础设置</span> <i class=\'icon-caret-down\\'></i>'=>[
//            [
//                ['title'=>'招商类目负责人设置',
//                    'url'=>'/crm/crm-mchpdtype/index'],
//            ],
//        ],
    ],
    $sale . '销售管理' => [
        '<span>订单管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '新增需求单',
                    'url' => '/sale/sale-cust-order/create'],
                ['title' => '需求单查询',
                    'url' => '/sale/sale-cust-order/index'],
                ['title' => '报价单查询',
                    'url' => '/sale/sale-quoted-order/index'],
                ['title' => '交易订单查询',
                    'url' => '/sale/sale-trade-order/index'],
            ],
        ],
        '<span>退款管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '退款列表',
                    'url' => '/sale/ord-refund/index'],
            ],
        ],
//        '<span>费用与提成</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '新增出差申请',
//                    'url' => '/sale/sale-trip/create-travel-apply'],
//                ['title' => '新增出差报告',
//                    'url' => '/sale/sale-trip/create-report'],
//                ['title' => '新增出差费用报销',
//                    'url' => '/sale/sale-trip/create-cost'],
//                ['title' => '出差申请及销单报告列表',
//                    'url' => '/sale/sale-trip/index'],
//                ['title' => '出差变更单列表',
//                    'url' => '/sale/sale-trip/index'],
//                ['title' => '新增交际费用单',
//                    'url' => '/sale/sale-interact/index'],
//                ['title' => '新增报支单',
//                    'url' => '/sale/sale-interact/index'],
//                ['title' => '交际费用及报支单列表',
//                    'url' => '/sale/sale-interact/index'],
//                ['title' => '销单提成计算',
//                    'url' => '/sale/sale-commision/index'],
//            ],
//        ],
//        '<span>基础数据设置</span> <i class=\'icon-caret-down\'></i>' => [
//            [
//                ['title' => '提成系数设置',
//                    'url' => ''],
//                ['title' => '角色提成比率设置',
//                    'url' => ''],
//                ['title' => '业务费用类别',
//                    'url' => '/sale/sale-cost-type/index'],
//                ['title' => '业务费用分类',
//                    'url' => '/sale/sale-cost-category/index'],
//                ['title' => '业务费用申请额度设置',
//                    'url' => ''],
//                ['title' => '资位费用申请标准维护',
//                    'url' => ''],
//            ],
//        ],
        '<span>收款管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '银行流水列表',
                    'url' => '/sale/sale-bank-sta/index'],
            ],
        ],
    ],
    $purchase . '采购管理' => [
        '<span>商品请购</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '新增请购单',
                    'url' => '/purchase/purchase-apply/create'],
                ['title' => '请购单列表',
                    'url' => '/purchase/purchase-apply/index'],
            ],
        ],
        '<span>商品采购</span> <i class=\'icon-caret-down\'></i>' => [
            [
//                ['title' => '新增采购单',
//                    'url' => '/purchase/purchase/create'],
                ['title' => '采购单列表',
                    'url' => '/purchase/purchase-notify/index'],
//                ['title' => '采购通知列表',
//                    'url' => '/purchase/purchase-notify/index'],
                ['title' => '采购前置工作',
                    'url' => '/purchase/purchase-before-work/index'
                ],
            ],
        ],
    ],
    $setting . '仓储物流管理' => [
        '<span>入库单</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '收货通知列表',
                    'url' => '/warehouse/receipt-notice/list'],
                ['title' => '收货单列表',
                    'url' => '/warehouse/receipt-bill/list'],
                ['title' => '采购入库列表',
                    'url' => '/warehouse/purchase-stock-in/list'],
                ['title' => '其他入库单',
                    'url' => '/warehouse/other-stock-in/list'],
            ],
        ],
        '<span>拣货单</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '出货通知列表',
                    'url' => '/warehouse/shipment-notify/index'],
                ['title' => '拣货单列表',
                    'url' => '/warehouse/picking-list/index'],
            ],
        ],
        '<span>出库单</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '销售出库列表',
                    'url' => '/warehouse/sale-out-stock/index'],
                ['title' => '其他出库单',
                    'url' => '/warehouse/other-out-stock/index'],
            ],
        ],
        '<span>库存管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '商品库存查询',
                    'url' => '/warehouse/product-stock-query/index'],
                ['title' => '报废单查询',
                    'url' => '/warehouse/inv-changeh/index'],
                ['title' => '仓库异动列表',
                    'url' => '/warehouse/warehouse-change/index'],
                ['title' => '调拨单列表',
                    'url' => '/warehouse/allocation/index'],
                ['title' => '盘点单',
                    'url' => '/warehouse/check-list/index'],
                ['title' => '出入库流水查询',
                    'url' => '/warehouse/inout-stock-query/index'],
            ],
        ],
        "<span>库存预警</span> <i class='icon-caret-down'></i>" => [
            [
                ["title" => "库存预警人员设置",
                    "url" => "/warehouse/set-inventory-warning/index"],
                ["title" => "库存预警信息查询",
                    "url" => "/warehouse/waring-info/index"],
            ],

        ],
        "<span>仓库标准价格</span> <i class='icon-caret-down'></i>" => [
            [
                ["title" => "仓库标准价格设置",
                    "url" => "/warehouse/wh-price/index"],
                ["title" => "费用名称设置",
                    "url" => "/warehouse/bs-wh-price/index"],
                ["title" => "仓库费用确认单",
                "url" => "/warehouse/wh-cost-confirm/index"],
            ],

        ],
        "<span>物流管理</span> <i class='icon-caret-down'></i>" => [
            [
                ["title" => "物流信息查询",
                    "url" => "/warehouse/logistics/index"],
//                ["title" => "物流公司信息",
//                    "url" => "/warehouse/logistics-company/index"],
//                ["title" => "车辆信息管理",
//                    "url" => "/warehouse/vehicle-information/index"],
                ["title" => "物流报价信息查询",
                    "url" => "/warehouse/logisticsquote/index"],
                ["title" => "物流订单查询",
                    "url" => "/warehouse/logisticsorder/index"],
                ["title" => "运费试算",
                    "url" => "/warehouse/freight-calculation/index"]
            ],
        ],
        "<span>基础信息</span> <i class='icon-caret-down'></i>" => [
            [
                ["title" => "仓库信息",
                    "url" => "/warehouse/set-warehouse/index"],
                ["title" => "区位信息",
                    "url" => "/warehouse/set-part/index"],
                ["title" => "储位信息",
                    "url" => "/warehouse/storage/index"],
                ["title" => "出仓费用设置",
                    "url" => "/warehouse/set-outcost/index"],
                ["title" => "收货中心设置",
                    "url" => "/warehouse/receipt-center-set/index"],
            ],
        ],
    ],
    $setting . '人事资料' => [
        '<span>人事信息</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '人事资料',
                    'url' => '/hr/staff/index'],
                ['title' => '岗位信息',
                    'url' => '/hr/staff-title/index'],
            ],
        ],
        '<span>组织机构</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '组织机构',
                    'url' => '/hr/organization/index'],
            ],
        ],
        '<span>问卷调查</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '问卷调查列表',
                    'url' => '/hr/question-survey/index'],
                ['title' => '新增问卷调查',
                    'url' => '/hr/question-survey/add'],
            ],
        ],
    ],
    $report . '报表管理' => [
        '<span>报表设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                [
                    'title' => '报表设置',
                    'url' => '/rpt/rpt-manage/index'
                ],
            ],
        ],
        '<span>我的报表</span> <i class=\'icon-caret-down\'></i>' => [
            [
                [
                    'title' => '我的报表',
                    'url' => '/rpt/my-rpt/index'
                ],
            ],
        ],
        '<span>销售订单查询报表</span> <i class=\'icon-caret-down\'></i>' => [
            [
                [
                    'title' => '销售订单明细查询报表',
                    'url' => '/rpt/order-detail-rpt/index'
                ],
                [
                    'title' => '销售订单汇总查询报表',
                    'url' => '/rpt/order-summary-rpt/index'
                ],
                [
                    'title' => '订单出货情况查询报表',
                    'url' => '/rpt/order-shipment-rpt/index'
                ]

            ],
        ],
        '<span>仓储物流报表</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '库存报表',
                    'url' => '/rpt/stock/index'],
                ['title' => '库龄报表',
                    'url' => '/system/authority/role-index'],
                ['title' => '进出仓明细报表',
                    'url' => '/system/system-log/index'],
                ['title' => '费用报表',
                    'url' =>'/system/system-log/index']
            ],
        ],
    ],
    $setting . '系统平台设置' => [
        '<span>CRM权限设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '用户管理',
                    'url' => '/system/user/index'],
                ['title' => '操作角色管理',
                    'url' => '/system/authority/role-index'],
                ['title' => '操作日志',
                    'url' => '/system/system-log/index'],

            ],
        ],
        '<span>系统设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '公共数据字典',
                    'url' => '/common/public-data/index'],
//                ['title' => '单据设定',
//                    'url' => ''],
                ['title' => '单据编码规则',
                    'url' => '/system/form-code/index'],
//                ['title' => '单据业务类型定义',
//                    'url' => ''],
//                ['title' => '单据业务流程定义',
//                    'url' => ''],
                ['title' => '系统动态列设置',
                    'url' => '/system/display-list/index'],
            ],
        ],
        '<span>审核流程设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '单据审核流设置',
                    'url' => '/system/review-rule/index']
            ],
        ],
        '<span>基础数据设置</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '交易相关设置',
                    'url' => '/system/transaction/index'],
                ['title' => '数据同步',
                    'url' => '/sync/sync/index'],
                ['title' => '公司设置',
                    'url' => '/system/company/index'],
                ['title' => '测试',
                    'url' => '/test/test/index'],
                ['title' => '数据查询',
                    'url' => '/system/sync-check/check'],
                ["title" => "超比例设置",
                    "url" => "/warehouse/set-proportion/index"],
            ]
        ],
        '<span>平台管理</span> <i class=\'icon-caret-down\'></i>' => [
            [
                ['title' => '用户管理',
                    'url' => '/system/user-management/index'],
                ['title' => '用户角色设置',
                    'url' => '/system/user-role-management/user-index'],
                ['title' => '菜单管理',
                    'url' => '/system/menu-power/index'],
                ['title' => '操作管理',
                    'url' => '/system/system-operation/index'],
            ]
        ],
    ],
];