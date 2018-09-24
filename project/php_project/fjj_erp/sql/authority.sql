DELETE  FROM auth_item WHERE type = 2 ;
DELETE FROM auth_item_child;
/*
商品開發相關權限 start
*/
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/add',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/index',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/edit',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/view',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/delete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/load-product',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/get-product-type',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/get-develop-dep',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/product-dvlp/get-product-manager',2);

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/add','/ptdt/product-dvlp/get-product-type');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/add','/ptdt/product-dvlp/get-develop-dep');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/add','/ptdt/product-dvlp/get-product-manager');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/edit','/ptdt/product-dvlp/get-product-type');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/edit','/ptdt/product-dvlp/get-develop-dep');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/edit','/ptdt/product-dvlp/get-product-manager');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/index','/ptdt/product-dvlp/get-product-manager');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/index','/ptdt/product-dvlp/get-develop-dep');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/product-dvlp/index','/ptdt/product-dvlp/load-product');
/*
商品開發相關權限 end
*/

/*
厂商拜访计划相关权限 start
*/

INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/add',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/index',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/edit',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/view',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/delete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/add-resume',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-plan/get-visit-manager',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm/firm-info',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/add',2);


INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/add','/ptdt/visit-plan/add-resume');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/add','/ptdt/visit-plan/get-visit-manager');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/add','/ptdt/firm/firm-info');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/edit','/ptdt/visit-plan/add-resume');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/edit','/ptdt/visit-plan/get-visit-manager');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/edit','/ptdt/firm/firm-info');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-plan/index','/ptdt/visit-resume/add');
/*
厂商拜访计划相关权限 end
*/

/*
操作日志查看相关权限 start
*/
INSERT INTO auth_item(`name`,`type`) VALUE('/system/log/index',2);
/*
操作日志查看相关权限 end
*/

/*
廠商拜訪履歷相關權限 start
*/


INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/index',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/edit',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/view',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/delete-main',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/delete-child',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/visit-complete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/load-resume',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/find-firm',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/visit-resume/get-visit-manager',2);

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/add','/ptdt/firm/select-com');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/add','/ptdt/firm/firm-info');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/add','/ptdt/visit-resume/select-plan');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/add','/ptdt/visit-resume/get-visit-manager');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/edit','/ptdt/visit-resume/select-plan');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/edit','/ptdt/visit-resume/get-visit-manager');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/visit-resume/index','/ptdt/firm-negotiation/create');
/*
廠商拜訪履歷相關權限 end
*/

/*厂商呈报相关权限 start*/
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/index',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/add',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/update',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/view',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/delete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/check',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/analysis',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/load-report',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/load-product',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/complete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/select-com',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/firm-info',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/analysis-com',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/analysis-report',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/ptdt/firm-report/add-evaluate',2);

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/add','/ptdt/firm-report/select-com');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/add','/ptdt/firm-report/firm-info');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/add','/ptdt/firm-report/analysis-com');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/add','/ptdt/firm-report/analysis-report');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/update','/ptdt/firm-report/select-com');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/update','/ptdt/firm-report/firm-info');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/update','/ptdt/firm-report/analysis-com');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/update','/ptdt/firm-report/analysis-report');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/index','/ptdt/firm-report/load-report');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/index','/ptdt/firm-report/load-product');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/index','/ptdt/firm-report/complete');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/ptdt/firm-report/index','/ptdt/firm-report/add-evaluate');
/*厂商呈报相关权限 end*/

/*销售客户关系管理相关权限 start*/
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/index',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/create',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/update',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/view',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/delete',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/get-district-salearea',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/select-sname',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/get-country',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/get-district',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/get-all-district',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/salearea',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/industry-type',2);
INSERT INTO auth_item(`name`,`type`) VALUE('/crm/crm-customer-info/get-cust-one',2);


INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/create','/crm/crm-customer-info/get-district-salearea');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/create','/crm/crm-customer-info/select-sname');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/create','/ptdt/firm/get-district');

INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/update','/crm/crm-customer-info/get-district-salearea');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/update','/crm/crm-customer-info/select-sname');
INSERT INTO auth_item_child(`parent`,`child`) VALUE('/crm/crm-customer-info/update','/ptdt/firm/get-district');

/*客户关系管理相关权限 end*/

