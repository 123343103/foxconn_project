<?php
/**
 * F3858995
 * 2016/11/1
 */
return [
    /*主页*/
//    '首页' => [
//        [
//            'title'=>'我的审核',
//            'url'   => '/system/inform/index',
//        ],
//        [
//            'title'=>'我的通知',
//            'url'   => '/system/inform/inform-count',
//        ],
//        [
//            'title' => '',
//            'url'   => '/crm/crm-plan-manage/plan-count',
//        ],
//    ],
    /*商品开发与管理*/
    '商品开发计划' => [
        [
            'title' => '商品开发计划',
            'url' => '',
        ],
        [
            'title' => '商品开发需求列表',
            'url' => '/ptdt/product-dvlp/index',
//            'children' => [
//                '/ptdt/product-dvlp/load-product',
//                '/ptdt/product-dvlp/get-product-manager',
//                '/system/verify-record/reviewer',
//            ]
        ],
        [
            'title' => '商品开发需求列表-编辑',
            'url' => '/ptdt/product-dvlp/edit',
        ],
        [
            'title' => '商品开发需求列表-详情',
            'url' => '/ptdt/product-dvlp/view',
        ],
        [
            'title' => '商品开发需求列表-删除',
            'url' => '/ptdt/product-dvlp/delete',
        ],
        [
            'title' => '商品开发需求列表-送审',
            'url' => '/ptdt/product-dvlp/check',
//            'children' => [
//                '/ptdt/product-dvlp/check-view'
//            ]
        ],
        [
            'title' => '新增商品开发需求',
            'url' => '/ptdt/product-dvlp/add',
//            'children' => [
//                '/ptdt/product-dvlp/get-product-type',
//                '/ptdt/product-dvlp/get-develop-dep',
//                '/ptdt/product-dvlp/get-product-manager'
//            ]
        ],
        [
            'title' => '计划草稿列表',
            'url' => '',
        ],
    ],
    '商品开发进程' => [
        [
            'title' => '厂商信息',
            'url' => '/ptdt/firm/index',
//            'children' => [
//                '/ptdt/firm/firm-info',
//                '/ptdt/firm/add-visit-plan'
//            ],
        ],
        [
            'title' => '厂商信息-新增',
            'url' => '/ptdt/firm/create',
//            'children' => [
//                '/ptdt/firm/get-district'
//            ],
        ],
        [
            'title' => '厂商信息-修改',
            'url' => '/ptdt/firm/update',
//            'children' => [
//                '/ptdt/firm/get-district'
//            ],
        ],
        [
            'title' => '厂商信息-详情',
            'url' => '/ptdt/firm/view',
        ],
        [
            'title' => '厂商信息-删除',
            'url' => '/ptdt/firm/delete',
        ],
        [
            'title' => '厂商拜访计划',
            'url' => '/ptdt/visit-plan/index',
        ],
        [
            'title' => '厂商拜访计划-新增',
            'url' => '/ptdt/visit-plan/add',
//            'children' => [
//                '/ptdt/visit-plan/select-com',
//                '/ptdt/visit-plan/firm-info',
//                '/ptdt/visit-plan/add-firm',
//                '/ptdt/visit-plan/firm-sname'
//            ],
        ],
        [
            'title' => '厂商拜访计划-修改',
            'url' => '/ptdt/visit-plan/edit',
        ],
        [
            'title' => '厂商拜访计划-详情',
            'url' => '/ptdt/visit-plan/view',
        ],
        [
            'title' => '厂商拜访计划-删除',
            'url' => '/ptdt/visit-plan/delete',
        ],
        [
            'title' => '厂商拜访履历列表',
            'url' => '/ptdt/visit-resume/index',
//            'children' => [
//                '/ptdt/visit-resume/load-resume',
//            ]
        ],
        [
            'title' => '厂商拜访履历列表-新增',
            'url' => '/ptdt/visit-resume/add',
//            'children' => [
//                '/ptdt/visit-resume/select-firm',
//                '/ptdt/visit-resume/select-plan',
//                '/hr/staff/staff-code-validate',
//            ]
        ],
        [
            'title' => '厂商拜访履历列表-修改',
            'url' => '/ptdt/visit-resume/edit',
//            'children' => [
//                '/ptdt/visit-resume/select-firm',
//                '/ptdt/visit-resume/select-plan',
//                '/hr/staff/staff-code-validate',
//            ]
        ],
        [
            'title' => '厂商拜访履历列表-详情',
            'url' => '/ptdt/visit-resume/view',
//            'children' => [
//                '/ptdt/visit-resume/view-resume',
//                '/ptdt/visit-resume/view-resumes',
//            ]
        ],
        [
            'title' => '厂商拜访履历列表-删除',
            'url' => '/ptdt/visit-resume/delete',
//            'children' => [
//                '/ptdt/visit-resume/delete-child',
//            ]
        ],
        [
            'title' => '厂商拜访履历列表-拜访完成',
            'url' => '/ptdt/visit-resume/visit-complete',
        ],
        [
            'title' => '厂商拜访履历列表-新增谈判',
            'url' => '/ptdt/visit-resume/add-negotiation',
//            'children' => [
//                'url' => '/ptdt/firm-negotiation/create',
//            ]
        ],
        [
            'title' => '谈判履历表',
            'url' => '/ptdt/firm-negotiation/index',
//            'children' => [
//                '/ptdt/visit-plan/get-visit-manager',
//                '/ptdt/product-dvlp/get-product-type',
//                '/hr/staff/get-staff-info',
//                '/ptdt/visit-resume/select-firm',
//                '/ptdt/firm/select-com',
//                '/ptdt/firm-negotiation/load-info',
//                '/ptdt/firm-negotiation/load-dvlp',
//            ]
        ],
        [
            'title' => '谈判履历表-新增',
            'url' => '/ptdt/firm-negotiation/create',
            'children' => [
                '/ptdt/firm-negotiation/select-plan'
            ]
        ],
        [
            'title' => '谈判履历表-删除',
            'url' => '/ptdt/firm-negotiation/delete',
        ],
        [
            'title' => '谈判履历表-修改',
            'url' => '/ptdt/firm-negotiation/update',
        ],
        [
            'title' => '谈判履历表-详情',
            'url' => '/ptdt/firm-negotiation/view',
        ],
        [
            'title' => '谈判履历表-分析',
            'url' => '/ptdt/firm-negotiation/analysis',
        ],
        [
            'title' => '谈判履历表-谈判完成',
            'url' => '/ptdt/firm-negotiation/negotiate',
        ],
        [
            'title' => '厂商呈报列表',
            'url' => '/ptdt/firm-report/index',
//            'children' => [
//                '/ptdt/firm-report/load-report',
//                '/ptdt/firm-report/load-product',
//                '/ptdt/firm-report/check-view',
//                '/system/verify-record/reviewer'
//            ],
        ],
        [
            'title' => '厂商呈报列表-新增',
            'url' => '/ptdt/firm-report/add',
//            'children' => [
//                '/ptdt/firm-report/select-com',
//                '/ptdt/firm-report/firm-info',
//                '/hr/staff/get-staff-info',
//                '/ptdt/firm-report/analysis-com',
//                '/ptdt/firm-report/analysis-report',
//                '/ptdt/firm-negotiation/load-dvlp',
//                '/ptdt/firm-negotiation/dvlp-info',
//                '/ptdt/product-dvlp/get-product-type',
//                '/ptdt/firm-report/add-check',
//                '/ptdt/firm-report/get-visit-manager',
//                '/ptdt/firm-report/load-dvlp'
//            ],
        ],
        [
            'title' => '厂商呈报列表-修改',
            'url' => '/ptdt/firm-report/update',
            'children' => [
                '/ptdt/firm-report/update-check',
            ],
        ],
        [
            'title' => '厂商呈报列表-详情',
            'url' => '/ptdt/firm-report/view',
        ],
        [
            'title' => '厂商呈报列表-删除',
            'url' => '/ptdt/firm-report/delete',
        ],
        [
            'title' => '厂商呈报列表-送审',
            'url' => '/ptdt/firm-report/check',
        ],
        [
            'title' => '厂商呈报列表-呈报分析表',
            'url' => '/ptdt/firm-report/analysis',
        ],

        [
            'title' => '商品认证申请表',
            'url' => '',
        ],
    ],
    '料号管理' => [
//        [
//            'title' => '新增料号申请',
//            'url' => '/ptdt/material-code/add',
//        ],
//        [
//            'title' => '料号申请列表',
//            'url' => '/ptdt/material-code/index',
//        ],
//        [
//            'title' => '料号大分类维护',
//            'url' => '/ptdt/category/index',
//            'children' => [
//                '/ptdt/category/add',
//                '/ptdt/category/edit',
//                '/ptdt/category/view',
//                '/ptdt/category/delete'
//            ]
//        ],
//        [
//            'title' => '分级分类申请',
//            'url' => '',
//        ],
        [
            'title' => '核价资料-列表',
            'url' => '/ptdt/pno-bind-spp/index',
        ],
        [
            'title' => '核价资料-新增',
            'url' => '/ptdt/pno-bind-spp/add',
        ],
        [
            'title' => '核价资料-修改',
            'url' => '/ptdt/pno-bind-spp/edit',
        ],
        [
            'title' => '核价资料-送审',
            'url' => '/ptdt/pno-bind-spp/check',
        ],
        [
            'title' => '核价资料-终止',
            'url' => '/ptdt/pno-bind-spp/stop',
        ],
    ],
    '商店定价' => [
        [
            'title' => '商品定价申请',
            'url' => '/ptdt/partno-price-apply/index',
//            'children' => [
//                '/ptdt/partno-price-apply/create',
//                '/ptdt/partno-price-apply/edit',
//                '/ptdt/partno-price-confirm/edit',
//                '/ptdt/partno-price-apply/view',
//                '/ptdt/partno-price-apply/delete',
//            ]
        ],
        [
            'title' => '商品核价',
            'url' => '/ptdt/partno-price-review/index',
        ],
        [
            'title' => '商品定价表',
            'url' => '/ptdt/partno-price-confirm/index',
//            'children' => [
//                '/ptdt/partno-price-confirm/create',
//                '/ptdt/partno-price-confirm/edit',
//                '/ptdt/partno-price-confirm/view',
//                '/ptdt/partno-price-confirm/delete',
//                '/ptdt/partno-price-confirm/batch-price',
//                '/ptdt/partno-price-confirm/partno-relation'
//            ]
        ],
        [
            'title' => '物流费率维护',
            'url' => '',
        ],
        [
            'title' => '经费费率维护',
            'url' => '',
        ],
    ],
    '商品库管理' => [
        [
            'title' => '商品库列表',
            'url' => '/ptdt/product-library/index',
//            'children' => [
//                '/ptdt/product-library/load-price',
//                '/ptdt/product-library/get-product-type'
//            ]
        ],
        [
            'title' => '商品库修改',
            'url' => '/ptdt/product-library/edit',
        ],
        [
            'title' => '商品库详情',
            'url' => '/ptdt/product-library/view'
        ],
        [
            'title' => '商品库导出',
            'url' => '/ptdt/product-library/export'
        ]
    ],
    '设置' => [
        [
            'title' => '商品经理人管理',
            'url' => '/ptdt/pm/index',
//            'children' => [
//                '/hr/staff/get-staff-info',
//                '/ptdt/pm/edit',
//                '/ptdt/pm/delete',
//            ]
        ],
        [
            'title' => '商品经理人管理-新增',
            'url' => '/ptdt/pm/add',
        ],
        [
            'title' => '商品经理人管理-删除',
            'url' => '/ptdt/pm/delete',
        ],
        [
            'title' => '商品经理人管理-修改',
            'url' => '/ptdt/pm/edit',
        ],
    ],
    /*end商品开发与管理*/

    /*验证中心管理*/
    '商品认证' => [
        [
            'title' => '认证需求',
            'url' => '',
        ],
        [
            'title' => '认证排配',
            'url' => '',
        ],
        [
            'title' => '认证计划',
            'url' => '',
        ],
        [
            'title' => '认证报告单',
            'url' => '',
        ],
    ],
    '商品验证' => [
        [
            'title' => '验证报告单',
            'url' => '',
        ],
        [
            'title' => '验证排配',
            'url' => '',
        ],
        [
            'title' => '验证计划',
            'url' => '',
        ],
    ],
    '商品技术支持' => [
        [
            'title' => '技术支持申请表',
            'url' => '',
        ],
        [
            'title' => '技术支持排配列表',
            'url' => '',
        ],
        [
            'title' => '技术支持记录列表',
            'url' => '',
        ],
    ],
    /*end验证中心管理*/

    /*供应商管理*/
//    '供应商评鉴' => [
//        [
//            'title' => '新增厂商评鉴申请',
//            'url' => '/ptdt/firm-evaluate-apply/add',
//        ],
//        [
//            'title' => '厂商评鉴申请列表',
//            'url' => '/ptdt/firm-evaluate-apply/index',
//            'children' => [
//                '/ptdt/firm-evaluate-apply/add',
//                '/ptdt/firm-evaluate-apply/select-firm',
//                '/ptdt/firm-evaluate-apply/supplier-data',
//                '/ptdt/firm-evaluate-apply/add-product',
//                '/ptdt/firm-evaluate-apply/product-data',
//                '/ptdt/firm-evaluate-apply/find-firm',
//            ]
//        ],
//        [
//            'title' => '新增厂商评鉴',
//            'url' => '/ptdt/firm-evaluate/add',
//        ],
//        [
//            'title' => '厂商评鉴列表',
//            'url' => '/ptdt/firm-evaluate/index',
//            'children' => [
//                '/ptdt/firm-evaluate/load-evaluate',
//                '/ptdt/firm-evaluate/add',
//                '/ptdt/firm-evaluate/select-firm',
//                '/ptdt/firm-evaluate/evaluate-no-pass-firm',
//                '/ptdt/firm-evaluate/purchase-evaluate-judge',
//                '/ptdt/firm-evaluate/manage-evaluate-judge',
//                '/ptdt/firm-evaluate/purchase-evaluate',
//                '/ptdt/firm-evaluate/manage-evaluate',
//                '/ptdt/firm-evaluate/edit-judge',
//                '/ptdt/firm-evaluate/view',
//                '/ptdt/firm-evaluate/delete-main-judge',
//                '/ptdt/firm-evaluate/delete-main',
//                '/ptdt/firm-evaluate/delete-child-judge',
//                '/ptdt/firm-evaluate/delete-child',
//            ]
//        ],
//    ],
//    '供应商申请' => [
//        [
//            'title' => '供应商申请',
//            'url' => '/ptdt/supplier/create',
//        ],
//        [
//            'title' => '供应商申请列表',
//            'url' => '/ptdt/supplier/index',
//            'children' => [
//                '/ptdt/supplier/create',
//                '/ptdt/supplier/view',
//                '/ptdt/supplier/update',
//                '/ptdt/supplier/info-all',
//                '/ptdt/supplier/select-com',
//                '/ptdt/supplier/select-material',
//                '/ptdt/supplier/load-info',
//                '/ptdt/supplier/load-data',
//                '/ptdt/supplier/firm-info',
//                '/ptdt/supplier/material-info',
//
//            ]
//        ],
//    ],
    '供应商资料' => [
        [
            'title' => '供应商-列表',
            'url' => '/spp/supplier/index',
        ],
        [
            'title' => '供应商-新增',
            'url' => '/spp/supplier/add',
        ],
        [
            'title' => '供应商-修改',
            'url' => '/spp/supplier/edit',
        ],
        [
            'title' => '供应商-查看',
            'url' => '/spp/supplier/view',
        ],
        [
            'title' => '供应商-删除',
            'url' => '/spp/supplier/delete',
        ],
        [
            'title' => '供应商-送审',
            'url' => '/spp/supplier/check',
        ],
        [
            'title' => '供应商-抓取数据',
            'url' => '/spp/supplier/get-supplier',
        ],
    ],
    /*end验证中心管理*/

    /*客户关系系统*/
    '活动管理' => [
        [
            'title' => '客户活动报名列表',
            'url' => '/crm/crm-active-apply/index',
//            'children' => [
//                '/crm/crm-active-apply/check-in-info',
//                '/crm/crm-active-apply/pay-info',
//                '/crm/crm-active-apply/message-info',
//            ]
        ],
        [
            'title' => '客户活动报名列表-新增',
            'url' => '/crm/crm-active-apply/add',
//            'children' => [
//                '/crm/crm-active-apply/get-active-name',
//                '/crm/crm-active-apply/get-active-time',
//                '/crm/crm-active-apply/get-customer-info',
//                '/crm/crm-active-apply/get-district',
//            ]
        ],
        [
            'title' => '客户活动报名列表-修改',
            'url' => '/crm/crm-active-apply/edit',
//            'children' => [
//                '/crm/crm-active-apply/get-active-name',
//                '/crm/crm-active-apply/get-active-time',
//                '/crm/crm-active-apply/get-customer-info',
//                '/crm/crm-active-apply/get-district',
//            ]
        ],
        [
            'title' => '客户活动报名列表-详情',
            'url' => '/crm/crm-active-apply/view',
        ],
        [
            'title' => '客户活动报名列表-删除',
            'url' => '/crm/crm-active-apply/delete',
        ],
        [
            'title' => '客户活动报名列表-签到',
            'url' => '/crm/crm-active-apply/check-in',
        ],
        [
            'title' => '客户活动报名列表-缴费',
            'url' => '/crm/crm-active-apply/pay',
        ],
        [
            'title' => '客户活动报名列表-发信息',
            'url' => '/crm/crm-active-apply/send-message',
        ],
        [
            'title' => '客户活动报名列表-发邮件',
            'url' => '/crm/crm-active-apply/send-email',
        ],
        [
            'title' => '客户活动报名列表-导入',
            'url' => '/crm/crm-active-apply/import',
            'children' => [
                '/crm/crm-active-apply/get-progress',
            ]
        ],
        [
            'title' => '客户活动报名列表-导出',
            'url' => '/crm/crm-active-apply/export',
        ],
        [
            'title' => '活动统计列表',
            'url' => '/crm/crm-active-count/index',
        ],
        [
            'title' => '活动统计新增',
            'url' => '/crm/crm-active-count/add',
        ],
        [
            'title' => '活动统计修改',
            'url' => '/crm/crm-active-count/edit',
        ],
        [
            'title' => '活动统计详情',
            'url' => '/crm/crm-active-count/view',
        ],
        [
            'title' => '活动统计删除',
            'url' => '/crm/crm-active-count/delete',
        ],
        [
            'title' => '营销活动行事历',
            'url' => '/crm/crm-active-calendar/index',
        ],
    ],
    '社群营销' => [
        [
            'title' => '社群营销列表',
            'url' => '/crm/crm-community-marketing/index',
//            'children' => [
//                '/crm/crm-community-marketing/get-publish-carriers',
//                '/crm/crm-community-marketing/get-carrier-names'
//            ]
        ],
        [
            'title' => '社群营销-新增',
            'url' => '/crm/crm-community-marketing/create',
//            'children' => [
//                '/crm/crm-community-marketing/get-publish-carriers',
//                '/crm/crm-community-marketing/get-carrier-names'
//            ]
        ],
        [
            'title' => '社群营销-修改',
            'url' => '/crm/crm-community-marketing/edit',
//            'children' => [
//                '/crm/crm-community-marketing/get-publish-carriers',
//                '/crm/crm-community-marketing/get-carrier-names'
//            ]
        ],
        [
            'title' => '社群营销-删除',
            'url' => '/crm/crm-community-marketing/remove'
        ],
        [
            'title' => '社群营销-新增统计',
            'url' => '/crm/crm-community-marketing/new-count'
        ],
        [
            'title' => '社群营销-状态设置',
            'url' => '/crm/crm-community-marketing/status-set'
        ]
    ],
    '媒体资源' => [
        [
            'title' => '媒体资源列表',
            'url' => '/crm/crm-media-resource/index'
        ],
        [
            'title' => '媒体资源-新增',
            'url' => '/crm/crm-media-resource/create',
//            'children' => [
//                '/crm/crm-media-resource/district'
//            ]
        ],
        [
            'title' => '媒体资源-修改',
            'url' => '/crm/crm-media-resource/edit',
//            'children' => [
//                '/crm/crm-media-resource/district'
//            ]
        ],
        [
            'title' => '媒体资源-删除',
            'url' => '/crm/crm-media-resource/remove'
        ],
        [
            'title' => '媒体资源-新增服务内容',
            'url' => '/crm/crm-media-resource/new-service'
        ]
    ],
    '活动相关设置' => [
        [
            'title' => '活动相关设置',
            'url' => '/crm/crm-active-set/index'
        ],
        [
            'title' => '载体-列表',
            'url' => '/crm/crm-carrier/index',
        ],
        [
            'title' => '载体-新增',
            'url' => '/crm/crm-carrier/add',
        ],
        [
            'title' => '载体-修改',
            'url' => '/crm/crm-carrier/edit',
        ],
        [
            'title' => '载体-详情',
            'url' => '/crm/crm-carrier/view',
        ],
        [
            'title' => '载体-删除',
            'url' => '/crm/crm-carrier/delete',
            'children' => [
                '/crm/crm-carrier/delete-carrier',
            ]
        ],
        [
            'title' => '活动类型设置-列表',
            'url' => '/crm/crm-active-type/index',
        ],
        [
            'title' => '活动类型设置-新增',
            'url' => '/crm/crm-active-type/add',
            'children' => [
                '/crm/crm-active-type/validate',
            ]
        ],
        [
            'title' => '活动类型设置-修改',
            'url' => '/crm/crm-active-type/edit',
            'children' => [
                '/crm/crm-active-type/validate',
            ]
        ],
        [
            'title' => '活动类型设置-详情',
            'url' => '/crm/crm-active-type/view',
        ],
        [
            'title' => '活动类型设置-删除',
            'url' => '/crm/crm-active-type/delete',
            'children' => [
                '/crm/crm-active-type/delete-active-type',
            ]
        ],
        [
            'title' => '活动名称管理-列表',
            'url' => '/crm/crm-active-name/index',
        ],
        [
            'title' => '活动名称管理-新增',
            'url' => '/crm/crm-active-name/add',
//            'children' => [
//                '/crm/crm-active-name/get-active-type',
//                '/crm/crm-active-name/validate',
//                '/crm/crm-active-name/get-district',
//                '/hr/staff/staff-code-validate',
//            ]
        ],
        [
            'title' => '活动名称管理-修改',
            'url' => '/crm/crm-active-name/edit',
//            'children' => [
//                '/crm/crm-active-name/get-active-type',
//                '/crm/crm-active-name/validate',
//                '/crm/crm-active-name/get-district',
//                '/hr/staff/staff-code-validate',
//            ]
        ],
        [
            'title' => '活动名称管理-详情',
            'url' => '/crm/crm-active-name/view',
        ],
        [
            'title' => '活动名称管理-删除',
            'url' => '/crm/crm-active-name/delete',
//            'children' => [
//                '/crm/crm-active-name/delete-active-name',
//            ]
        ],
        [
            'title' => '活动名称管理-取消活动',
            'url' => '/crm/crm-active-name/cancel-active',
        ],
        [
            'title' => '活动名称管理-终止活动',
            'url' => '/crm/crm-active-name/stop-active',
        ],
        [
            'title' => '媒体类型-列表',
            'url' => '/crm/crm-media-type/index',
        ],
        [
            'title' => '媒体类型-新增',
            'url' => '/crm/crm-media-type/add',
        ],
        [
            'title' => '媒体类型-修改',
            'url' => '/crm/crm-media-type/edit',
        ],
        [
            'title' => '媒体类型-详情',
            'url' => '/crm/crm-media-type/view',
        ],
        [
            'title' => '媒体类型-删除',
            'url' => '/crm/crm-media-type/delete',
            'children' => [
                '/crm/crm-media-type/delete-media',
            ]
        ],
    ],
    '会员管理' => [
        [
            'title' => '潜在客户列表',
            'url' => '/crm/crm-potential-customer/index',
//            'children' => [
//                '/crm/crm-potential-customer/create',
//                '/crm/crm-potential-customer/edit',
//                '/crm/crm-potential-customer/view',
//                '/crm/crm-potential-customer/delete',
//                '/crm/crm-potential-customer/send-msg',
//                '/crm/crm-potential-customer/send-email',
//                '/crm/crm-potential-customer/remind',
//                '/crm/crm-potential-customer/allotman-select',
//                '/crm/crm-potential-customer/allot',
//                '/crm/crm-potential-customer/claim',
//                '/crm/crm-potential-customer/undo-claim',
//                '/crm/crm-potential-customer/act-info',
//                '/crm/crm-potential-customer/remind-item',
//                '/crm/crm-potential-customer/message-log',
//                '/crm/crm-potential-customer/switch-status',
//                '/crm/crm-potential-customer/visit-log',
//                '/crm/crm-potential-customer/visit-record-create',
//                '/crm/crm-potential-customer/visit-record-edit',
//                '/crm/crm-potential-customer/visit-record-delete',
//                '/crm/crm-potential-customer/select-customer',
//                '/crm/crm-potential-customer/edit-customer',
//                '/crm/crm-potential-customer/add-remind',
//                '/crm/crm-potential-customer/validate',
//                '/crm/crm-potential-customer/import',
//                '/crm/crm-potential-customer/get-progress',
//                '/crm/crm-return-visit/delete',
//                '/crm/crm-member-develop/visit-update',
//                '/crm/crm-member-develop/visit-create',
//                '/crm/crm-member-develop/select-customer',
//            ]
        ],
        [
            'title' => '潜在客户列表-新增',
            'url' => '/crm/crm-potential-customer/create',
        ],
        [
            'title' => '潜在客户列表-修改',
            'url' => '/crm/crm-potential-customer/edit',
        ],
        [
            'title' => '潜在客户列表-详情',
            'url' => '/crm/crm-potential-customer/view',
        ],
        [
            'title' => '潜在客户列表-删除',
            'url' => '/crm/crm-potential-customer/delete',
        ],
        [
            'title' => '潜在客户列表-分配',
            'url' => '/crm/crm-potential-customer/allot',
        ],
        [
            'title' => '潜在客户列表-取消分配',
            'url' => '/crm/crm-potential-customer/undo-allot',
        ],
        [
            'title' => '潜在客户列表-认领',
            'url' => '/crm/crm-potential-customer/claim',
        ],
        [
            'title' => '潜在客户列表-取消认领',
            'url' => '/crm/crm-potential-customer/undo-claim',
        ],
        [
            'title' => '潜在客户列表-转招商开发',
            'url' => '/crm/crm-potential-customer/to-investment',
        ],
        [
            'title' => '潜在客户列表-转销售',
            'url' => '/crm/crm-potential-customer/to-sale',
        ],
        [
            'title' => '潜在客户列表-拜访记录列表',
            'url' => '/crm/crm-potential-customer/visit-log',
        ],
        [
            'title' => '潜在客户列表-新增拜访记录',
            'url' => '/crm/crm-potential-customer/visit-record-create',
        ],
        [
            'title' => '潜在客户列表-修改拜访记录',
            'url' => '/crm/crm-potential-customer/visit-record-edit',
        ],
        [
            'title' => '潜在客户列表-删除拜访记录',
            'url' => '/crm/crm-potential-customer/visit-record-delete',
        ],
        [
            'title' => '潜在客户列表-即时通讯',
            'url' => '/crm/crm-potential-customer/send-message'
        ],
        [
            'title' => '潜在客户列表-导入',
            'url' => '/crm/crm-potential-customer/import'
        ],
        [
            'title' => '潜在客户列表-导出',
            'url' => '/crm/crm-potential-customer/export'
        ],
        [
            'title' => '会员列表',
            'url' => '/crm/crm-member/index',
        ],
        [
            'title' => '会员列表-新增',
            'url' => '/crm/crm-member/create',
        ],
        [
            'title' => '会员列表-修改',
            'url' => '/crm/crm-member/update',
        ],
        [
            'title' => '会员列表-详情',
            'url' => '/crm/crm-member/view',
        ],
        [
            'title' => '会员列表-删除',
            'url' => '/crm/crm-member/delete',
        ],
        [
            'title' => '会员列表-新增回访',
            'url' => '/crm/crm-member/visit-create',
        ],
        [
            'title' => '会员列表-修改回访',
            'url' => '/crm/crm-member/visit-update',
        ],
        [
            'title' => '会员列表-删除回访',
            'url' => '/crm/crm-member/visit-delete',
        ],
        [
            'title' => '会员列表-回访详情',
            'url' => '/crm/crm-member/visit-view',
        ],
        [
            'title' => '会员列表-新增提醒事项',
            'url' => '/crm/crm-member/create-reminders',
        ],
        [
            'title' => '会员列表-修改提醒事项',
            'url' => '/crm/crm-member/update-reminders',
        ],
        [
            'title' => '会员列表-删除提醒事项',
            'url' => '/crm/crm-member/delete-reminders',
        ],
        [
            'title' => '会员列表-活动信息详情',
            'url' => '/crm/crm-member/active-view',
        ],
        [
            'title' => '会员列表-即时通讯',
            'url' => '/crm/crm-member/send-message',
        ],
        [
            'title' => '会员列表-转招商开发',
            'url' => '/crm/crm-member/turn-investment',
        ],
        [
            'title' => '会员列表-转销售',
            'url' => '/crm/crm-member/turn-sales',
        ],
        [
            'title' => '会员列表-批量导入',
            'url' => '/crm/crm-member/import',
        ],
        [
            'title' => '会员列表-批量导出',
            'url' => '/crm/crm-member/export',
        ],
        [
            'title' => '会员开发任务列表',
            'url' => '/crm/crm-member-develop/index',
        ],
        [
            'title' => '会员开发任务列表-新增客户',
            'url' => '/crm/crm-member-develop/create',
        ],
        [
            'title' => '会员开发任务列表-修改客户',
            'url' => '/crm/crm-member-develop/update',
        ],
        [
            'title' => '会员开发任务列表-客户详情',
            'url' => '/crm/crm-member-develop/cust-view',
        ],
        [
            'title' => '会员开发任务列表-新增拜访记录',
            'url' => '/crm/crm-member-develop/visit-create',
        ],
        [
            'title' => '会员开发任务列表-修改拜访记录',
            'url' => '/crm/crm-member-develop/visit-update',
        ],
        [
            'title' => '会员开发任务列表-拜访记录详情',
            'url' => '/crm/crm-member-develop/view',
        ],
        [
            'title' => '会员开发任务列表-删除拜访记录',
            'url' => '/crm/crm-member-develop/delete',
        ],
        [
            'title' => '会员开发任务列表-新增提醒事项',
            'url' => '/crm/crm-member-develop/create-reminders',
        ],
        [
            'title' => '会员开发任务列表-修改提醒事项',
            'url' => '/crm/crm-member-develop/update-reminders',

        ],
        [
            'title' => '会员开发任务列表-删除提醒事项',
            'url' => '/crm/crm-member-develop/delete-reminders',
        ],
        [
            'title' => '会员开发任务列表-即时通讯',
            'url' => '/crm/crm-member-develop/send-message',
        ],
        [
            'title' => '会员开发任务列表-抛至公海',
            'url' => '/crm/crm-member-develop/throw-sea',
        ],
        [
            'title' => '会员开发任务列表-转会员',
            'url' => '/crm/crm-member-develop/turn-member',
        ],
        [
            'title' => '会员开发任务列表-转招商开发',
            'url' => '/crm/crm-member-develop/turn-investment',
        ],
        [
            'title' => '会员开发任务列表-转销售',
            'url' => '/crm/crm-member-develop/turn-sales',
        ],
        [
            'title' => '会员回访记录',
            'url' => '/crm/crm-return-visit/index',
        ],
        [
            'title' => '会员回访记录-新增',
            'url' => '/crm/crm-return-visit/create',
        ],
        [
            'title' => '会员回访记录-修改',
            'url' => '/crm/crm-return-visit/update',
        ],
        [
            'title' => '会员回访记录-详情',
            'url' => '/crm/crm-return-visit/view',
        ],
        [
            'title' => '会员回访记录-删除',
            'url' => '/crm/crm-return-visit/delete',
        ],
    ],
    '招商客户管理' => [
        [
            'title' => '招商会员列表',
            'url' => '/crm/crm-investment-customer/list',
            'children' => [
                '/crm/crm-investment-customer/load-info',
            ]
        ],
        [
            'title' => '招商会员列表-详情',
            'url' => '/crm/crm-investment-customer/view',

        ],
        [
            'title' => '招商会员列表-修改',
            'url' => '/crm/crm-investment-customer/update',

        ],
        [
            'title' => '招商会员列表-新增店铺',
            'url' => '/crm/crm-investment-customer/shop-info',

        ],
        [
            'title' => '招商会员列表-修改店铺',
            'url' => '/crm/crm-investment-customer/shop-edit',

        ],
        [
            'title' => '招商会员列表-删除店铺',
            'url' => '/crm/crm-investment-customer/delete-shop',

        ],
        [
            'title' => '招商会员列表-新增提醒事项',
            'url' => '/crm/crm-investment-customer/reminders',
        ],
        [
            'title' => '招商会员列表-修改提醒事项',
            'url' => '/crm/crm-investment-customer/update-reminders',
        ],
        [
            'title' => '招商会员列表-删除提醒事项',
            'url' => '/crm/crm-investment-customer/delete-reminders',
        ],
        [
            'title' => '招商会员列表-新增拜访记录',
            'url' => '/crm/crm-investment-customer/visit-create',

        ],
        [
            'title' => '招商会员列表-修改拜访记录',
            'url' => '/crm/crm-investment-customer/visit-update',

        ],
        [
            'title' => '招商会员列表-拜访记录详情',
            'url' => '/crm/crm-investment-customer/view-visit',

        ],
        [
            'title' => '招商会员列表-删除拜访记录',
            'url' => '/crm/crm-investment-customer/delete-visit',

        ],
        [
            'title' => '招商会员列表-即时通讯',
            'url' => '/crm/crm-investment-customer/send-message',
        ],
        [
            'title' => '招商会员列表-批量导出',
            'url' => '/crm/crm-investment-customer/export',
        ],

        [
            'title' => '招商会员开发列表',
            'url' => '/crm/crm-investment-dvelopment/index',
            'children' => [
                '/crm/crm-investment-dvelopment/load-info',
                '/crm/crm-investment-dvelopment/select-customer',
            ]
        ],
        [
            'title' => '招商会员开发-新增',
            'url' => '/crm/crm-investment-dvelopment/create',

        ],
        [
            'title' => '招商会员开发-修改',
            'url' => '/crm/crm-investment-dvelopment/update',

        ],
        [
            'title' => '招商会员开发-详情',
            'url' => '/crm/crm-investment-dvelopment/view',

        ],
        [
            'title' => '招商会员开发-新增拜访记录',
            'url' => '/crm/crm-investment-dvelopment/visit-create',

        ],
        [
            'title' => '招商会员开发-修改拜访记录',
            'url' => '/crm/crm-investment-dvelopment/visit-update',

        ],
        [
            'title' => '招商会员开发-拜访记录详情',
            'url' => '/crm/crm-investment-dvelopment/view-visit',

        ],
        [
            'title' => '招商会员开发-删除拜访记录',
            'url' => '/crm/crm-investment-dvelopment/delete-visit',

        ],
        [
            'title' => '招商会员开发-新增店铺',
            'url' => '/crm/crm-investment-dvelopment/shop-info',

        ],
        [
            'title' => '招商会员开发-新增提醒事项',
            'url' => '/crm/crm-investment-dvelopment/reminders',
        ],
        [
            'title' => '招商会员开发-修改提醒事项',
            'url' => '/crm/crm-investment-dvelopment/update-reminders',
        ],
        [
            'title' => '招商会员开发-删除提醒事项',
            'url' => '/crm/crm-investment-dvelopment/delete-reminders',
        ],
        [
            'title' => '招商会员开发-即时通讯',
            'url' => '/crm/crm-investment-dvelopment/send-message',
        ],
        [
            'title' => '招商会员开发-分配员工',
            'url' => '/crm/crm-investment-dvelopment/assign-staff',

        ],
        [
            'title' => '招商会员开发-抛至公海',
            'url' => '/crm/crm-investment-dvelopment/throw-sea',

        ],
        [
            'title' => '招商会员开发-转销售',
            'url' => '/crm/crm-investment-dvelopment/turn-sales',

        ],
        [
            'title' => '招商会员开发-批量导入',
            'url' => '/crm/crm-investment-dvelopment/import',
        ],
        [
            'title' => '招商会员开发-批量导出',
            'url' => '/crm/crm-investment-dvelopment/export',
        ],
    ],
    '基础设置' => [
        [
            'title' => '招商类目负责人设置列表',
            'url' => '/crm/crm-mchpdtype/index',
        ],
        [
            'title' => '招商类目负责人设置-新增',
            'url' => '/crm/crm-mchpdtype/create',
        ],
        [
            'title' => '招商类目负责人设置-修改',
            'url' => '/crm/crm-mchpdtype/update',
        ],
        [
            'title' => '招商类目负责人设置-删除',
            'url' => '/crm/crm-mchpdtype/delete',
        ],
    ],
    '行程管理' => [
        [
            'title' => '拜访计划管理',
            'url' => '/crm/crm-visit-plan/index',
        ],
        [
            'title' => '拜访计划管理-新增',
            'url' => '/crm/crm-visit-plan/create',
        ],
        [
            'title' => '拜访计划管理-修改',
            'url' => '/crm/crm-visit-plan/update',
        ],
        [
            'title' => '拜访计划管理-详情',
            'url' => '/crm/crm-visit-plan/view',
        ],
        [
            'title' => '拜访计划管理-取消计划',
            'url' => '/crm/crm-visit-plan/cancel',
        ],
        [
            'title' => '拜访计划管理-终止计划',
            'url' => '/crm/crm-visit-plan/stop',
        ],
        [
            'title' => '拜访计划管理-导出',
            'url' => '/crm/crm-visit-plan/export',
        ],
        [
            'title' => '拜访记录管理',
            'url' => '/crm/crm-visit-record/index',
        ],
        [
            'title' => '拜访记录管理-新增',
            'url' => '/crm/crm-visit-record/add',
        ],
        [
            'title' => '拜访记录管理-修改',
            'url' => '/crm/crm-visit-record/edit',
        ],
        [
            'title' => '拜访记录管理-详情',
            'url' => '/crm/crm-visit-record/view',
        ],
        [
            'title' => '拜访记录管理-切换明细表',
            'url' => '/crm/crm-visit-record/list',
        ],
        [
            'title' => '拜访记录管理-导出',
            'url' => '/crm/crm-visit-record/export',
        ],
        [
            'title' => '行程日历',
            'url' => '/crm/sale-visit-calendar/index',
        ],
    ],
    '销售数据设置' => [
        [
            'title' => '销售员资料',
            'url' => '/crm/employee-setting/index',
        ],
        [
            'title' => '销售员资料-新增',
            'url' => '/crm/employee-setting/create',
//            'children' => [
//                '/crm/employee-setting/get-staff-info',
//                '/crm/employee-setting/store',
//                '/crm/employee-setting/store-info',
//                '/crm/employee-setting/sale-role',
//                '/crm/employee-setting/leader-role',
//            ]
        ],
        [
            'title' => '销售员资料-修改',
            'url' => '/crm/employee-setting/update',
        ],
        [
            'title' => '销售员资料-详情',
            'url' => '/crm/employee-setting/view',
        ],
        [
            'title' => '销售员资料-删除',
            'url' => '/crm/employee-setting/delete',
        ],
        [
            'title' => '销售角色设置',
            'url' => '/crm/role-setting/index',
        ],
        [
            'title' => '销售角色-新增',
            'url' => '/crm/role-setting/create',
        ],
        [
            'title' => '销售角色-修改',
            'url' => '/crm/role-setting/update',
        ],
        [
            'title' => '销售角色-详情',
            'url' => '/crm/role-setting/view',
        ],
        [
            'title' => '销售角色-删除',
            'url' => '/crm/role-setting/delete',
        ],
        [
            'title' => '销售区域设置',
            'url' => '/crm/area-setting/index',
//            'children' => [
//                '/crm/area-setting/create',
//                '/crm/area-setting/update',
//                '/crm/area-setting/view',
//                '/crm/area-setting/delete',
//            ]
        ],
        [
            'title' => '销售区域-新增',
            'url' => '/crm/area-setting/create',
//            'children' => [
//                '/ptdt/firm/get-district',
//            ]
        ],
        [
            'title' => '销售区域-修改',
            'url' => '/crm/area-setting/update',
        ],
        [
            'title' => '销售区域-详情',
            'url' => '/crm/area-setting/view',
        ],
        [
            'title' => '销售区域-删除',
            'url' => '/crm/area-setting/delete',
        ],
        [
            'title' => '销售点维护',
            'url' => '/crm/store-setting/index',
//            'children' => [
//                '/crm/store-setting/create',
//                '/crm/store-setting/update',
//                '/crm/store-setting/view',
//                '/crm/store-setting/delete',
//            ]
        ],
        [
            'title' => '销售点-新增',
            'url' => '/crm/store-setting/create',
        ],
        [
            'title' => '销售点-修改',
            'url' => '/crm/store-setting/update',
        ],
        [
            'title' => '销售点-详情',
            'url' => '/crm/store-setting/view',
        ],
        [
            'title' => '销售点-删除',
            'url' => '/crm/store-setting/delete',
        ],
    ],
    '销售客户管理' => [
        [
            'title' => '客户管理列表',
            'url' => '/crm/crm-customer-info/index',
//            'children' => [
//                '/crm/crm-customer-info/delete',
//                '/crm/crm-plan-manage/index',
//                '/crm/crm-customer-apply/create',
//                '/crm/crm-visit-info/create',
//                '/crm/crm-customer-info/get-cust-one',
//                '/crm/crm-customer-info/get-manager-staff-info',
//                '/crm/crm-customer-info/get-sale-staff-info',
//                '/crm/crm-customer-info/cancle-person-inch',
//                '/crm/crm-customer-info/update-person-inch',
//                '/crm/crm-customer-info/person-inch',
//                '/crm/crm-customer-info/turn-investment',
//                '/crm/crm-visit-record/add'
//            ],
        ],
        [
            'title' => '客户管理-新增',
            'url' => '/crm/crm-customer-info/create',
        ],
        [
            'title' => '客户管理-修改',
            'url' => '/crm/crm-customer-info/update',
        ],
        [
            'title' => '客户管理-详情',
            'url' => '/crm/crm-customer-info/view',
        ],
        [
            'title' => '客户管理-删除',
            'url' => '/crm/crm-customer-info/delete',
        ],
        [
            'title' => '客户管理-激活',
            'url' => '/crm/crm-customer-info/activation',
        ],
        [
            'title' => '客户管理-账信申请',
            'url' => '/crm/crm-customer-info/credit-create',
        ],
        [
            'title' => '客户管理-新增拜访计划',
            'url' => '/crm/crm-customer-info/plan-create',
        ],
        [
            'title' => '客户管理-新增拜访记录',
            'url' => '/crm/crm-customer-info/record-add',
        ],
        [
            'title' => '客户管理-认领',
            'url' => '/crm/crm-customer-info/person-inch',
        ],
        [
            'title' => '客户管理-分配',
            'url' => '/crm/crm-customer-info/assign',
        ],
        [
            'title' => '客户管理-荐招商',
            'url' => '/crm/crm-customer-info/turn-investment',
        ],
        [
            'title' => '客户管理-抛至公海',
            'url' => '/crm/crm-customer-info/throw-sea',
        ],
        [
            'title' => '客户管理-申请客户代码',
            'url' => '/crm/crm-customer-info/customer-info',
        ],
        [
            'title' => '客户管理-导入',
            'url' => '/crm/crm-customer-info/import',
        ],
        [
            'title' => '客户管理-导出',
            'url' => '/crm/crm-customer-info/export',
        ],


        [
            'title' => '我的客户',
            'url' => '/crm/crm-customer-manage/index',
//            'children' => [
//                '/crm/crm-customer-manage/contact-person',
//                '/crm/crm-customer-manage/update-customer',
//                '/crm/crm-customer-manage/visit-plan',
//                '/crm/crm-customer-manage/customer-company',
//                '/crm/crm-customer-manage/visit-record',
//                '/crm/crm-customer-manage/requirement-product',
//                '/crm/crm-customer-manage/last-sale-order',
//                '/crm/crm-customer-manage/last-sale-quotedprice',
//                '/crm/crm-customer-manage/cust-device',
//                '/crm/crm-customer-manage/cust-main-product',
//                '/crm/crm-customer-manage/cust-main-customer',
//                '/crm/crm-customer-manage/check-link-comp',
//                '/crm/crm-customer-manage/cust-person-inch',
//                '/crm/crm-customer-manage/cust-purchase',
//                '/crm/crm-customer-manage/sale-order',
//                '/crm/crm-customer-manage/sale-quotedprice',
//                '/crm/crm-customer-manage/cost-info',
//                '/crm/crm-customer-manage/cooperation-product',
//                '/crm/crm-customer-manage/project-follow',
//                '/crm/crm-customer-manage/get-manage-staff-info',
//                '/crm/crm-customer-manage/get-sale-staff-info',
//                '/ptdt/firm/get-district',
//                '/crm/crm-customer-info/get-manager-staff-info',
//                '/crm/crm-customer-info/get-sale-staff-info',
//                '/crm/crm-customer-manage/view',
//                '/crm/crm-customer-manage/base-customer',
//                '/crm/crm-customer-manage/company-customer',
//                '/crm/crm-customer-manage/customer-contact-person',
//                '/crm/crm-customer-manage/person-inch',
//                '/crm/crm-customer-manage/visit-plan-info',
//                '/crm/crm-customer-manage/visit-plan-record',
//                '/crm/crm-customer-manage/cust-device-info',
//                '/crm/crm-customer-manage/link-comp',
//                '/crm/crm-customer-manage/cust-oddsitem',
//                '/crm/crm-customer-manage/cust-purchase-info',
//                '/crm/crm-customer-manage/main-product',
//                '/crm/crm-customer-manage/main-customer',
//                '/crm/crm-customer-manage/update-person-inch',
//                '/crm/crm-customer-manage/contact-update',
//                '/crm/crm-customer-manage/contact-create',
//                '/crm/crm-customer-manage/update-device',
//                '/crm/crm-customer-manage/create-device',
//                '/crm/crm-customer-manage/create-cust-oddsitem',
//                '/crm/crm-customer-manage/update-cust-oddsitem',
//                '/crm/crm-customer-manage/update-cust-purchase',
//                '/crm/crm-customer-manage/create-cust-purchase',
//                '/crm/crm-customer-manage/create-link-comp',
//                '/crm/crm-customer-manage/update-link-comp',
//                '/crm/crm-customer-manage/update-main-customer',
//                '/crm/crm-customer-manage/create-main-customer',
//                '/crm/crm-customer-manage/update-main-product',
//                '/crm/crm-customer-manage/create-main-product',
//                '/crm/crm-customer-manage/cancle-person-inch',
//                '/crm/crm-customer-manage/create-visit',
//                '/crm/crm-customer-manage/edit-plan',
//                '/crm/crm-customer-manage/create-info',
//                '/crm/crm-customer-manage/edit-info',
//                '/crm/crm-customer-manage/sys-list',
//                '/crm/crm-customer-manage/module',
//                '/crm/crm-customer-manage/module-delete',
//            ]
        ],

        [
            'title' => '我的客户-详情',
            'url' => '/crm/crm-customer-manage/view'
        ],
        [
            'title' => '我的客户-申请客户代码',
            'url' => '/crm/crm-customer-apply/customer-info'
        ],
        [
            'title' => '我的客户-模块设置',
            'url' => '/crm/crm-customer-manage/module'
        ],
        [
            'title' => '我的客户-模块动态列修改',
            'url' => '/crm/crm-customer-manage/sys-list'
        ],
        [
            'title' => '我的客户-模块删除',
            'url' => '/crm/crm-customer-manage/module-delete'
        ],
        [
            'title' => '我的客户-基本信息修改',
            'url' => '/crm/crm-customer-manage/base-customer'
        ],
        [
            'title' => '我的客户-公司信息修改',
            'url' => '/crm/crm-customer-manage/company-customer'
        ],
        [
            'title' => '我的客户-认证信息修改',
            'url' => '/crm/crm-customer-manage/auth-info'
        ],
        [
            'title' => '我的客户-客户联系人新增',
            'url' => '/crm/crm-customer-manage/customer-contact-person'
        ],
        [
            'title' => '我的客户-设备信息新增',
            'url' => '/crm/crm-customer-manage/cust-device-info'
        ],
        [
            'title' => '我的客户-主要客户新增',
            'url' => '/crm/crm-customer-manage/main-customer'
        ],
        [
            'title' => '我的客户-关联公司新增',
            'url' => '/crm/crm-customer-manage/link-comp'
        ],
        [
            'title' => '我的客户-商机商品新增',
            'url' => '/crm/crm-customer-manage/cust-oddsitem'
        ],
        [
            'title' => '我的客户-采购新增',
            'url' => '/crm/crm-customer-manage/cust-purchase-info'
        ],
        [
            'title' => '我的客户-主营产品新增',
            'url' => '/crm/crm-customer-manage/main-product'
        ],
        [
            'title' => '编码申请列表',
            'url' => '/crm/crm-customer-apply/index',
//            'children' => [
//                '/crm/crm-customer-apply/create',
//                '/system/verify-record/reviewer',
//                '/crm/crm-customer-apply/customre-info',
//                '/crm/crm-customer-apply/delete',
//                '/crm/crm-customer-apply/select-com',
//                '/crm/crm-customer-apply/customer-info',
//                '/crm/crm-customer-apply/view'
//            ]
        ],
        [
            'title' => '客户代码申请-修改',
            'url' => '/crm/crm-credit-apply/update'
        ],
        [
            'title' => '客户代码申请-送审',
            'url' => '/system/verify-record/new-reviewer',
            'children' => [
                '/system/verify-record/reviewer-one',
                '/system/verify-record/reviewer',
                '/crm/crm-customer-apply/reviewer'
            ]
        ],
        [
            'title' => '客户代码申请-取消',
            'url' => '/crm/crm-customer-apply/cannel'
        ],
        [
            'title' => '客户代码申请-导出',
            'url' => '/crm/crm-customer-apply/export'
        ],
        [
            'title' => '客户代码申请-详情',
            'url' => '/crm/crm-customer-apply/view'
        ],
        [
            'title' => '客户资料查询',
            'url' => '',
        ],
    ],
    '信用客户管理' => [
        [
            'title' => '账信申请',
            'url' => '/crm/crm-credit-apply/index',
        ],
        [
            'title' => '账信申请-新增',
            'url' => '/crm/crm-credit-apply/create',
//            'children' => [
//                '/crm/crm-credit-apply/select-customer'
//            ]
        ],
        [
            'title' => '账信申请-修改',
            'url' => '/crm/crm-credit-apply/update',
        ],
        [
            'title' => '账信申请-详情',
            'url' => '/crm/crm-credit-apply/view',
        ],
        [
            'title' => '账信申请-导出',
            'url' => '/crm/crm-credit-apply/export',
        ],
        [
            'title' => '信用客户额度信息',
            'url' => '/crm/crm-credit-limit/index',
//            'children' => [
//                '/crm/crm-credit-limit/limit-details',
//                '/crm/crm-credit-limit/limit-record',
//                '/crm/crm-credit-limit/file-download'
//            ]
        ],
        [
            'title' => '信用客户额度信息-使用明细',
            'url' => '/crm/crm-credit-limit/limit-details',
        ],
        [
            'title' => '信用客户额度信息-还款记录',
            'url' => '/crm/crm-credit-limit/limit-record',
        ],
        [
            'title' => '信用客户额度信息-文件下载',
            'url' => '/crm/crm-credit-limit/file-download',
        ],
        [
            'title' => '信用客户额度信息-详情',
            'url' => '/crm/crm-credit-limit/view',
        ],
        [
            'title' => '信用客户额度信息-冻结',
            'url' => '/crm/crm-credit-limit/freeze',
        ],
        [
            'title' => '信用客户额度信息-导出',
            'url' => '/crm/crm-credit-limit/export',
        ],
        [
            'title' => '批量维护信用额度',
            'url' => '/crm/crm-credit-limit/maintain',
        ],
        [
            'title' => '信用额度类型设置',
            'url' => '/crm/crm-credit-maintain/index',
        ],
        [
            'title' => '信用额度类型设置-新增',
            'url' => '/crm/crm-credit-maintain/create',
        ],
        [
            'title' => '信用额度类型设置-修改',
            'url' => '/crm/crm-credit-maintain/update',
        ],
        [
            'title' => '信用额度类型设置-删除',
            'url' => '/crm/crm-credit-maintain/delete',
        ],
    ],
    'CRM查询' => [
        [
            'title' => '商品定价查询',
            'url' => '/crm/crm-product-price/index',
        ],
        [
            'title' => '客户资料查询',
            'url' => '/crm/crm-all-customer/index',
        ],
        [
            'title' => '个人行程记录查询',
            'url' => '/crm/crm-person-record/index',
        ],
        [
            'title' => '销售客户资料查询',
            'url' => '/crm/crm-all-sale/index',
        ]
    ],
    '商品验证申请' => [
        [
            'title' => '商品验证申请',
            'url' => '',
        ],
        [
            'title' => '商品技术支持申请',
            'url' => '',
        ],
        [
            'title' => '商品技术支持申请查询',
            'url' => '',
        ]
    ],
    '报价单' => [
        [
            'title' => '报价单列表',
            'url' => '/crm/crm-quote-price/index',
//            'children' => [
//                '/crm/crm-quote-price/create',
//                '/crm/crm-quote-price/select-customer',
//                '/crm/crm-quote-price/select-corp',
//                '/crm/crm-quote-price/product-list',
//                '/crm/crm-quote-price/price-info'
//            ]
        ]
    ],
    '客户订单' => [
        [
            'title' => '客户订单列表',
            'url' => '/sale/sale-cust-order/index',
        ],
        [
            'title' => '客户订单-新增',
            'url' => '/sale/sale-cust-order/create',
//            'children' => [
//                '/sale/sale-cust-order/select-customer',
//                '/sale/sale-cust-order/select-product',
//                '/sale/sale-cust-order/select-address',
//            ]
        ],
        [
            'title' => '客户订单-修改',
            'url' => '/sale/sale-cust-order/update',
//            'children' => [
//                '/sale/sale-cust-order/select-customer',
//                '/sale/sale-cust-order/select-product',
//                '/sale/sale-cust-order/select-address',
//            ]
        ],
        [
            'title' => '客户订单-详情',
            'url' => '/sale/sale-cust-order/view',
        ],
        [
            'title' => '客户订单-明细列表',
            'url' => '/sale/sale-cust-order/list',
        ],
        [
            'title' => '客户订单-导出',
            'url' => '/sale/sale-cust-order/export',
        ],
    ],
    /*end客户关系系统*/

    /*客户关系系统*/
    '系统权限设置' => [
        [
            'title' => '用户管理',
            'url' => '/system/user/index',
//            'children' => [
//                '/system/user/get-staff',
//                '/hr/staff/get-info',
//                '/system/user/ajax-validation',
//            ]
        ],
        [
            'title' => '用户管理-新增',
            'url' => '/system/user/create'
        ],
        [
            'title' => '用户管理-重置密码',
            'url' => '/system/user/reset-password'
        ],
        [
            'title' => '用户管理-修改',
            'url' => '/system/user/update'
        ],
        [
            'title' => '用户管理-删除',
            'url' => '/system/user/delete'
        ],
        [
            'title' => '操作角色管理',
            'url' => '/system/authority/role-index',
//            'children' => [
//                '/system/authority/view',
//                '/system/authority/edit',
//                '/system/authority/delete',
//                '/system/authority/add',
//            ]
        ],
//        [
//            'title' => '权限设置',
//            'url' => '',
//        ],
        [
            'title' => '操作日志',
            'url' => '/system/system-log/index',
        ],
        [
            'title' => '公司设置',
            'url' => '/system/company/index',
//            'children' => [
//                '/system/company/create',
//                '/system/company/update',
//                '/system/company/delete',
//            ]
        ]
    ],
    '系统平台设置' => [
        [
            'title' => '公共参数设置列表',
            'url' => '/common/public-data/index',
        ],
        [
            'title' => '公共参数设置详情',
            'url' => '/common/public-data/view',
        ],
        [
            'title' => '公共参数设置新增',
            'url' => '/common/public-data/add',
        ],
        [
            'title' => '公共参数设置修改',
            'url' => '/common/public-data/edit',
        ],
        [
            'title' => '公共参数设置删除',
            'url' => '/common/public-data/delete',
//            'children' => [
//                '/common/public-data/delete-name',
//                '/common/public-data/validate',
//            ]
        ],
        [
            'title' => '单据设定',
            'url' => '',
        ],
        [
            'title' => '单据编码规则',
            'url' => '/system/form-code/index',
//            'children' => [
//                '/system/form-code/edit'
//            ]
        ],
        [
            'title' => '单据类型定义',
            'url' => '',
        ],
        [
            'title' => '单据业务类型定义',
            'url' => '',
        ],
        [
            'title' => '单据业务流程定义',
            'url' => '',
        ],
        [
            'title' => '平台交易相关设置',
            'url' => '/system/transaction/index',
//            'children' => [
//                '/system/transaction/transaction-index',
//                '/system/transaction/create',
//                '/system/transaction/update',
//                '/system/transaction/view',
//                '/system/transaction/delete',
//                '/system/trad-condition/index',
//                '/system/trad-condition/create',
//                '/system/trad-condition/update',
//                '/system/trad-condition/view',
//                '/system/trad-condition/delete',
//                '/system/pay-condition/index',
//                '/system/pay-condition/create',
//                '/system/pay-condition/update',
//                '/system/pay-condition/delete',
//                '/system/pay-condition/view',
//                '/system/payment/index',
//                '/system/payment/create',
//                '/system/payment/update',
//                '/system/payment/view',
//                '/system/payment/delete',
//                '/system/payment-condition/index',
//                '/system/payment-condition/create',
//                '/system/payment-condition/update',
//                '/system/payment-condition/view',
//                '/system/payment-condition/delete',
//                '/system/receipt/index',
//                '/system/receipt/create',
//                '/system/receipt/update',
//                '/system/receipt/view',
//                '/system/receipt/delete',
//                '/system/devcon/index',
//                '/system/devcon/create',
//                '/system/devcon/update',
//                '/system/devcon/view',
//                '/system/devcon/delete',
//                '/system/settlement/index',
//                '/system/settlement/create',
//                '/system/settlement/update',
//                '/system/settlement/view',
//                '/system/settlement/delete',
//                '/system/currency/index',
//                '/system/currency/create',
//                '/system/currency/update',
//                '/system/currency/view',
//                '/system/currency/delete'
//            ]
        ],
        [
            'title' => '数据同步',
            'url' => '/sync/sync/index',
//            'children' => [
//                '/sync/sync/sync'
//            ]
        ],
        [
            'title' => '列表动态列设置',
            'url' => '/system/display-list/index',
//            'children' => [
//                '/system/display-list/get-field',
//                '/system/display-list/edit'
//            ]
        ],
        [
            'title'=>'测试',
            'url'=>'/test/test/index'
        ],
    ],
    '审核流程设置' => [
        [
            'title' => '单据审核流设置',
            'url' => '/system/review-rule/index',
//            'children' => [
//                '/system/review-rule/delete-rule',
//                '/system/review-rule/edit',
//                '/system/verify-record/index',
//                '/system/verify-record/verify-one',
//                '/system/verify-record/verify',
//                '/system/verify-record/verify-all',
//                '/system/verify-record/load-record',
//                '/system/verify-record/count',
//                '/system/verify-record/inform-count',
//                '/system/verify-record/audit-reject',
//                '/system/verify-record/audit-pass'
//            ]
        ],

    ],
    /*end客户关系系统*/

    /*人事资料*/
    '人事信息' => [
        [
            'title' => '人事列表',
            'url' => '/hr/staff/index',
//            'children' => [
//                '/hr/staff/staff-validation',
//                '/hr/staff/insert-excel-staff',
//            ]
        ],
        [
            'title' => '人事列表-新增',
            'url' => '/hr/staff/create',
//            'children' => [
//                '/hr/staff/select-depart',
//            ]
        ],
        [
            'title' => '人事列表-删除',
            'url' => '/hr/staff/delete',
        ],
        [
            'title' => '人事列表-修改',
            'url' => '/hr/staff/update',
//            'children' => [
//                '/hr/staff/select-depart',
//            ]
        ],
        [
            'title' => '人事列表-详情',
            'url' => '/hr/staff/view',
        ],
        [
            'title' => '人事列表-导入',
            'url' => '/hr/staff/insert-excel-staff',
        ],
        [
            'title' => '人事列表-导出',
            'url' => '/hr/staff/export',
        ],
        [
            'title' => '岗位信息',
            'url' => '/hr/staff-title/index',
//            'children' => [
//                '/hr/staff-title/update',
//                '/hr/staff-title/create',
//                '/hr/staff-title/delete',
//                '/hr/staff-title/view',
//            ]
        ],
    ],
    '组织机构' => [
        [
            'title' => '组织列表',
            'url' => '/hr/organization/index',
//            'children' => [
//                '/hr/organization/update',
//                '/hr/organization/create',
//                '/hr/organization/delete',
//            ]
        ],
    ],
    '问卷调查' => [
        [
            'title' => '问卷调查-列表',
            'url' => '/hr/question-survey/index',
        ],
        [
            'title' => '问卷调查-新增',
            'url' => '/hr/question-survey/add',
        ],
        [
            'title' => '问卷调查-答卷列表',
            'url' => '/hr/question-survey/responses-list',
        ],
        [
            'title' => '问卷调查-问卷详情',
            'url' => '/hr/question-survey/view',
        ],
        [
            'title' => '问卷调查-查询',
            'url' => '/hr/question-survey/search',
        ],
        [
            'title' => '问卷调查-新增预览问卷详情',
            'url' => '/hr/question-survey/look',
        ],
    ],
    /*end人事资料*/

    /* start 销售管理*/
    '费用与提成' => [
        [
            'title' => '新增出差申请',
            'url' => '/sale/sale-trip/create-travel-apply'
        ],
        [
            'title' => '新增出差报告',
            'url' => '/sale/sale-trip/create-report'
        ],
        [
            'title' => '新增出差费用报销',
            'url' => '/sale/sale-trip/create-cost'
        ],
        [
            'title' => '出差申请及销单报告列表',
            'url' => '/sale/sale-trip/index',
//            'children' => [
//                '/sale/sale-trip/create-travel-apply',
//                '/sale/sale-trip/create-report',
//                '/sale/sale-trip/create-cost',
//            ]
        ],
        [
            'title' => '出差变更单列表',
            'url' => '/sale/sale-trip/index'
        ],
        [
            'title' => '新增交际费用单',
            'url' => '/sale/sale-interact/index'
        ],
        [
            'title' => '新增报支单',
            'url' => '/sale/sale-interact/index'
        ],
        [
            'title' => '交际费用及报支单列表',
            'url' => '/sale/sale-interact/index'
        ],
        [
            'title' => '销单提成计算',
            'url' => '/sale/sale-commision/index',
        ],
    ],
    '报表管理' => [
        [
            'title' => '报表设计',
            'url' => '/rpt/rpt-manage/index',
        ],
        [
            'title' => '我的报表',
            'url' => '/rpt/my-rpt/index',
        ],
    ],
    '基础数据设置' => [
        [
            'title' => '提成系数设置',
            'url' => ''
        ],
        [
            'title' => '角色提成比率设置',
            'url' => ''
        ],
        [
            'title' => '业务费用类别',
            'url' => '/sale/sale-cost-type/index',
//            'children' => [
//                '/sale/sale-cost-type/create',
//                '/sale/sale-cost-type/update',
//                '/sale/sale-cost-type/view',
//                '/sale/sale-cost-type/delete',
//            ]
        ],
        [
            'title' => '业务费用分类',
            'url' => '/sale/sale-cost-category/index',
//            'children' => [
//                '/sale/sale-cost-category/create',
//                '/sale/sale-cost-category/update',
//                '/sale/sale-cost-category/view',
//                '/sale/sale-cost-category/delete',
//            ]

        ],
        [
            'title' => '业务费用申请额度设置',
            'url' => ''
        ],
        [
            'title' => '资位费用申请标准维护',
            'url' => ''
        ],
    ],
    '移动端菜单' => [
        [
            'title' => '新增拜访计划',
            'url' => 'app/crm/crm-visit-plan/create-visit',
        ],
        [
            'title' => '新增客户',
            'url' => 'app/crm/crm-customer-info/create',
        ],
        [
            'title' => '商品库列表',
            'url' => 'app/ptdt/product-library/index',
        ],
        [
            'title' => '新增临时拜访记录',
            'url' => 'app/crm/crm-visit-info/create-temp',
        ],
        [
            'title' => '新增拜访记录',
            'url' => 'app/crm/crm-visit-info/add',
        ],
        [
            'title' => '定价查询列表',
            'url' => 'app/ptdt/prices/index',
        ],
        [
            'title' => '客户资料列表',
            'url' => 'app/crm/crm-customer-info/index',
        ],
        [
            'title' => '临时拜访列表',
            'url' => 'app/crm/crm-visit-info/index-temp',
        ],
        [
            'title' => '拜访计划列表',
            'url' => 'app/crm/crm-visit-plan/index',
        ],
        [
            'title' => '拜访记录列表',
            'url' => 'app/crm/crm-visit-info/index',
        ],
        [
            "title" => "厂商资料列表",
            "url" => "app/ptdt/firm-info/index",
        ],
        [
            "title" => "新增厂商",
            "url" => "app/ptdt/firm-info/create",
        ],
        [
            "title" => "新增厂商拜访计划",
            "url" => "app/ptdt/firm-visit-plan/add",
        ],
        [
            "title" => "新增拜访履历",
            "url" => "app/ptdt/firm-visit-resume/add",
        ],
        [
            "title" => "厂商拜访计划列表",
            "url" => "app/ptdt/firm-visit-plan/index",
        ],
        [
            "title" => "厂商拜访履历列表",
            "url" => "app/ptdt/firm-visit-resume/index",
        ],
        [
            "title" => "资料认领",
            "url" => "app/crm/crm-customer-info/all-list",
        ],
    ],
    /*end 销售管理*/

    "仓储-入库单" => [
        [
            'title' => '收货通知-列表',
            'url' => '/warehouse/receipt-notice/list',
        ],
        [
            'title' => '收货通知-生成收货单',
            'url' => '/warehouse/receipt-notice/generate-receipt-bill',
        ],
        [
            'title' => '收货通知-取消收货',
            'url' => '/warehouse/receipt-notice/cancel-receipt',
        ],
        [
            'title' => '收货通知-导出',
            'url' => '/warehouse/receipt-notice/export',
        ],
        [
            'title' => '收货单-列表',
            'url' => '/warehouse/receipt-bill/list',
        ],
        [
            'title' => '收货单-入库',
            'url' => '/warehouse/receipt-bill/stock-in',
        ],
        [
            'title' => '收货单-取消入库',
            'url' => '/warehouse/receipt-bill/cancel-stock-in',
        ],
        [
            'title' => '收货单-导出',
            'url' => '/warehouse/receipt-bill/export',
        ],
        [
            'title' => '采购入库-列表',
            'url' => '/warehouse/purchase-stock-in/list',
        ],
        [
            'title' => '采购入库-上架',
            'url' => '/warehouse/purchase-stock-in/put-away',
        ],
        [
            'title' => '采购入库-导出',
            'url' => '/warehouse/purchase-stock-in/export',
        ],
        [
            'title' => '其他入库单-列表',
            'url' => '/warehouse/other-stock-in/list',
        ],
        [
            'title' => '其他入库单-新增',
            'url' => '/warehouse/other-stock-in/add',
        ],
        [
            'title' => '其他入库单-修改',
            'url' => '/warehouse/other-stock-in/edit',
        ],
        [
            'title' => '其他入库单-送审',
            'url' => '/warehouse/other-stock-in/check',
        ],
        [
            'title' => '其他入库单-取消',
            'url' => '/warehouse/other-stock-in/cancel',
        ],
        [
            'title' => '其他入库单-上架',
            'url' => '/warehouse/other-stock-in/put-away',
        ],
        [
            'title' => '其他入库单-导出',
            'url' => '/warehouse/other-stock-in/export',
        ],
    ],
    "仓储-库存管理" => [
        [
            'title' => '出入库查询-列表',
            'url' => '/warehouse/inout-stock-query/index',
        ],
        [
            'title' => '出入库查询-导出',
            'url' => '/warehouse/inout-stock-query/export',
        ],
    ],
    "仓储-库存预警" => [
        [
            "title" => "库存预警人员设置",
            "url" => "/warehouse/set-inventory-warning/index",
        ],
        [
            'title' => '库存预警/报废通知人员-新增',
            'url' => '/warehouse/set-inventory-warning/create',
//            'children' => [
//                '/warehouse/set-inventory-warning/get-staff-info',
//            ]
        ],
        [
            'title' => '库存预警/报废通知人员-编辑',
            'url' => '/warehouse/set-inventory-warning/update',
        ],
        [
            'title' => '库存预警/报废通知人员-详情',
            'url' => '/warehouse/set-inventory-warning/view',
        ],
        /*[
            'title' => '库存预警/报废通知人员-盘点',
            'url' => '/warehouse/set-inventory-warning/inventory',
        ],
        [
            'title' => '库存预警/报废通知人员-送审',
            'url' => '/warehouse/set-inventory-warning/inventory',
        ],*/
        [
            'title' => '库存预警/报废通知人员-发邮件',
            'url' => '/warehouse/set-inventory-warning/sendemail',
        ],
        [
            'title' => '库存预警/报废通知人员-导出',
            'url' => '/warehouse/set-inventory-warning/export',
        ],
    ],
    "仓储-基础信息" => [
        [
            "title" => "库存预警信息查询-列表",
            "url" => "/warehouse/waring-info/index",
        ],
        [
            "title"=>"库存预警信息查询-新增",
            "url"=>"/warehouse/waring-info/create",
        ],
        [
            "title"=>"库存预警信息查询-修改",
            "url"=>"/warehouse/waring-info/edit",
        ],
        [
          "title"=>"库存预警信息查询-刪除",
            "url"=>"/warehouse/waring-info/delete",
        ],
        [
            "title"=>"库存预警信息查询-送審",
            "url"=>"/warehouse/waring-info/reviewer",
        ],
        [
            "title" => "区位信息",
            "url" => "/warehouse/set-part/index",
        ],
        [
            "title" => "收货中心设置-列表",
            "url" => "/warehouse/receipt-center-set/index",
        ],
        [
            "title" => "收货中心设置-新增",
            "url" => "/warehouse/receipt-center-set/add",
        ],
        [
            "title" => "收货中心设置-修改",
            "url" => "/warehouse/receipt-center-set/edit",
        ],
        [
            "title" => "收货中心设置-导出",
            "url" => "/warehouse/receipt-center-set/export",
        ],
        [
            "title" => "收货中心设置-启用",
            "url" => "/warehouse/receipt-center-set/enabled",
        ],
        [
            "title" => "收货中心设置-禁用",
            "url" => "/warehouse/receipt-center-set/disabled",
        ],
    ],
    "仓储-物流管理" => [
        [
            "title" => "物流信息查询",
            "url" => "/warehouse/logistics/index",
        ],
        ["title" => "物流公司信息",
            "url" => "/warehouse/logistics-company/index"],
        ["title" => "车辆信息管理",
            "url" => "/warehouse/vehicle-information/index"],
        ['title' => '调拨单列表',
            'url' => '/warehouse/allocation/index'],
        ["title" => "物流报价信息查询",
            "url" => "/warehouse/logisticsquote/index"],
    ],
    "仓储-异动单" => [
        [
            "title" => "异动单-列表",
            "url" => "/warehouse/warehouse-change/index",
        ],
        [
            "title" => "异动单-新增",
            "url" => "/warehouse/warehouse-change/add",
//            'children' => [
//                "/warehouse/warehouse-change/select-product",
//                "/warehouse/warehouse-change/select-store",
//            ]
        ],
        [
            "title" => "异动单-修改",
            "url" => "/warehouse/warehouse-change/update",
//            'children' => [
//                "/warehouse/warehouse-change/select-product",
//                "/warehouse/warehouse-change/select-store",
//            ]
        ],
        [
            "title" => "异动单-详情",
            "url" => "/warehouse/warehouse-change/view",
        ],
        [
            "title" => "异动单-导出",
            "url" => "/warehouse/warehouse-change/export",
        ],
        [
            "title" => "异动单-删除",
            "url" => "/warehouse/warehouse-change/delete",
        ],
        [
            "title" => "异动单-送审",
            "url" => "/warehouse/warehouse-change/check",
        ],
    ],
    "仓储-仓库标准价格" => [
        [
            "title" => "仓库标准价格查询-列表",
            "url" => "/warehouse/wh-price/index",
        ],
        [
            "title" => "仓库标准价格-新增",
            "url" => "/warehouse/wh-price/create",
        ],
        [
            "title" => "仓库标准价格-修改",
            "url" => "/warehouse/wh-price/update",
        ],
        [
            "title" => "仓库标准价格-禁用/启用",
            "url" => "/warehouse/wh-price/open-close",
        ],
        [
            "title" => "费用信息-列表",
            "url" => "/warehouse/bs-wh-price/index",
        ],
        [
            "title" => "费用信息-新增",
            "url" => "/warehouse/bs-wh-price/create",
        ],
        [
            "title" => "费用信息-修改",
            "url" => "/warehouse/bs-wh-price/update",
        ],
        [
            "title" => "仓库费用确认单-列表",
            "url" => "/warehouse/wh-cost-confirm/index",
        ],
        [
            "title" => "仓库费用确认单-导出",
            "url" => "/warehouse/wh-cost-confirm/export",
        ],

        [
            'title' => '仓库费用确认单-送审',
            "url" => "/warehouse/wh-cost-confirm/check",

        ],
    ],
];
