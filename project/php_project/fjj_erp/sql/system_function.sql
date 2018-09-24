DELETE FROM  system_function;
/*
商品開發相關功能 start
*/
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(1,'商品开发列表','ptdt','product-dvlp',1,'商品开发计划','/ptdt/product-dvlp/index',null);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
 VALUE(2,'新增商品开发需求','ptdt','product-dvlp',2,'商品开发计划','/ptdt/product-dvlp/add',1);

 INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(3,'修改商品开发需求','ptdt','product-dvlp',3,'商品开发计划','/ptdt/product-dvlp/edit',1);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(4,'删除商品开发需求','ptdt','product-dvlp',1,'商品开发计划','/ptdt/product-dvlp/delete',1);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(5,'查看商品开发需求','ptdt','product-dvlp',4,'商品开发计划','/ptdt/product-dvlp/view',1);
/*
商品開發相關功能 end
*/

/*
系统操作日志查看 start
*/
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(6,'系统操作日志查看','system','log',5,'系统操作日志查看','/system/log/index',null);
/*
系统操作日志查看 end
*/

--厂商拜访计划相关功能 start
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(7,'厂商拜访计划列表','ptdt','visit-plan',7,'厂商拜访计划','/ptdt/visit-plan/index',null);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(8,'新增厂商拜访计划','ptdt','visit-plan',8,'厂商拜访计划','/ptdt/visit-plan/add',7);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(9,'修改厂商拜访计划','ptdt','visit-plan',9,'厂商拜访计划','/ptdt/visit-plan/edit',7);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(10,'删除厂商拜访计划','ptdt','visit-plan',7,'厂商拜访计划','/ptdt/visit-plan/delete',7);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(11,'查看厂商拜访计划','ptdt','visit-plan',10,'厂商拜访计划','/ptdt/visit-plan/view',7);
--厂商拜访计划相关功能 end

/*
廠商拜訪履歷相關功能 start
*/
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(12,'廠商拜訪履歷列表','ptdt','visit-resume',12,'廠商拜訪履歷','/ptdt/visit-resume/index',null);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(13,'新增廠商拜訪履歷','ptdt','visit-resume',13,'廠商拜訪履歷','/ptdt/visit-resume/add',1);

 INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(14,'修改廠商拜訪履歷','ptdt','visit-resume',14,'廠商拜訪履歷','/ptdt/visit-resume/edit',1);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(15,'删除廠商拜訪履歷主表','ptdt','visit-resume',12,'廠商拜訪履歷','/ptdt/visit-resume/delete-main',1);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(16,'删除廠商拜訪履歷子表','ptdt','visit-resume',12,'廠商拜訪履歷','/ptdt/visit-resume/delete-child',1);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
  VALUE(17,'查看廠商拜訪履歷','ptdt','visit-resume',15,'廠商拜訪履歷','/ptdt/visit-resume/view',1);
/*
廠商拜訪履歷相關功能 end
*/

/*厂商呈报相关功能 - start - */
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(18,'厂商呈报列表','ptdt','firm-report',18,'厂商呈报列表','/ptdt/firm-report/index',null);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(19,'新增厂商呈报','ptdt','firm-report',19,'厂商呈报列表','/ptdt/firm-report/add',18);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(20,'修改厂商呈报','ptdt','firm-report',20,'厂商呈报列表','/ptdt/firm-report/update',18);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(21,'删除厂商拜访计划','ptdt','firm-report',18,'厂商呈报列表','/ptdt/firm-report/delete',18);
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(22,'查看厂商呈报','ptdt','firm-report',21,'厂商呈报列表','/ptdt/firm-report/view',18);

/*厂商呈报相关功能 - end - */
/*客户资料相关功能-start-*/
INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(23,'客户资料列表','crm','crm-customer-info',23,'客户资料列表','/crm/crm-customer-info/index',null);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(24,'新增客户资料','crm','crm-customer-info',24,'客户资料列表','/crm/crm-customer-info/create',23);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(25,'修改客户资料','crm','crm-customer-info',25,'客户资料列表','/crm/crm-customer-info/update',23);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(25,'删除客户资料','crm','crm-customer-info',23,'客户资料列表','/crm/crm-customer-info/delete',23);

INSERT INTO system_function(`function_id`,`function_name`,`system_module`,`module_controller`,`page_id`,`function_parent`,`url`,`parent_id`)
   VALUE(26,'查看客户资料','crm','crm-customer-info',25,'客户资料列表','/crm/crm-customer-info/view',23);
/*客户资料相关功能-end-*/