
DELETE FROM bs_form;
DELETE FROM bs_form_code_max;
DELETE FROM bs_form_code_rule;

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_requirement' ,'商品開發需求表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(1,'pd_requirement','PD','REQ',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(1,1,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_visit_plan' ,'廠商拜訪計畫表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(2,'pd_visit_plan','PD','PVP',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(2,2,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm' ,'廠商信息表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(3,'pd_firm','PD','PF',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(3,3,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_visit_resume' ,'廠商拜访履历主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(4,'pd_visit_resume','PD','MAIN',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(4,4,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_visit_resume_child' ,'廠商拜访履历子表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(5,'pd_visit_resume_child','PD','CHILD',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(5,5,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_negotiation' ,'廠商談判履历主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(6,'pd_negotiation','PD','N',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(6,6,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_negotiation_child' ,'廠商談判履历子表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(7,'pd_negotiation_child','PD','NC',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(7,7,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm_report' ,'廠商呈報主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(8,'pd_firm_report','PD','FR',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(8,8,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm_report_child' ,'廠商呈報子表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(9,'pd_firm_report_child','PD','FRC',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(9,9,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm_evaluate_apply' ,'廠商评鉴申请主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(10,'pd_firm_evaluate_apply','FEA','MAIN',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(10,10,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_supplier' ,'供應商申请表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(11,'pd_supplier','PD','SU',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(11,11,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm_evaluate' ,'厂商评鉴主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(12,'pd_firm_evaluate','FE','MAIN',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(12,12,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_firm_evaluate_child' ,'厂商评鉴子表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(13,'pd_firm_evaluate_child','FE','CHILD',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(13,13,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('pd_material_code' ,'料号表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(14,'pd_material_code','PD','LH',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(14,14,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_bs_customer_info' ,'客户关系信息表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(15,'crm_bs_customer_info','CRM','CI',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(15,15,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_visit_plan' ,'客户拜访计划表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(16,'crm_visit_plan','CV','PLAN',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(16,16,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_visit_info' ,'客户拜访记录主表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(17,'crm_visit_info','CV','INFO',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(17,17,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_visit_info_child' ,'客户拜访记录子表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(18,'crm_visit_info_child','CV','IC',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(18,18,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_bs_member' ,'会员表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(19,'crm_bs_member','CM','',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(19,19,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('system_verifyrecord' ,'单据审核');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(20,'system_verifyrecord','VR','H',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(20,20,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_bs_acttype' ,'活动类型表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(21,'crm_bs_acttype','CRM','AT',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(21,21,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_bs_act' ,'活动名称表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(22,'crm_bs_act','CRM','AN',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(22,22,'00001');

INSERT INTO bs_form(`form_id`,`form_name`) VALUE('crm_act_h' ,'活动报名表');
INSERT INTO bs_form_code_rule(`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`)
VALUE(23,'crm_act_h','CRM','AA',1,1,1,'00001');
INSERT INTO bs_form_code_max(`number_id`,`code_rule_id`,`current_number`) VALUE(23,23,'00001');

INSERT INTO `bs_form` (`form_id`, `form_name`, `form_pid`) VALUES ('crm_customer_apply', '客戶编码申请表', '100000000');
INSERT INTO `bs_form_code_rule` (`rule_id`, `form_id`, `rule_one`, `rule_two`, `rule_three`, `rule_four`, `rule_five`, `serial_number_start`,`rule_one_index`, `rule_two_index`, `rule_three_index`, `start_index`, `field_index`) VALUES (25, 'crm_customer_apply', 'CRM', 'CA',1,3,4,'00001',1,2,3,4,5);
INSERT INTO `bs_form_code_max` (`number_id`,`code_rule_id`,`current_number`) VALUE(25,25,'00001');

INSERT INTO `bs_form` (`form_id`,`form_name`,`form_pid`) VALUE('crm_bs_media_type' ,'媒体类型表','100000000');
INSERT INTO `bs_form_code_rule` (`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`,`rule_one_index`,`rule_two_index`,`rule_three_index`,`start_index`,`field_index`) VALUE(26,'crm_bs_media_type','CB','MT',1,3,4,'00001',1,2,3,4,5);
INSERT INTO `bs_form_code_max` (`number_id`,`code_rule_id`,`current_number`) VALUE(26,26,'00001');

INSERT INTO `bs_form` (`form_id`,`form_name`,`form_pid`) VALUE('crm_bs_carrier' ,'载体表','100000000');
INSERT INTO `bs_form_code_rule` (`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`,`rule_one_index`,`rule_two_index`,`rule_three_index`,`start_index`,`field_index`) VALUE(27,'crm_bs_carrier','CB','C',1,3,4,'00001',1,2,3,4,5);
INSERT INTO `bs_form_code_max` (`number_id`,`code_rule_id`,`current_number`) VALUE(27,27,'00001');

INSERT INTO `bs_form` (`form_id`,`form_name`,`form_pid`) VALUE('crm_act_count' ,'活动统计主表','100000000');
INSERT INTO `bs_form_code_rule` (`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`,`rule_one_index`,`rule_two_index`,`rule_three_index`,`start_index`,`field_index`) VALUE(28,'crm_act_count','CA','C',1,3,4,'00001',1,2,3,4,5);
INSERT INTO `bs_form_code_max` (`number_id`,`code_rule_id`,`current_number`) VALUE(28,28,'00001');

INSERT INTO `bs_form` (`form_id`,`form_name`,`form_pid`) VALUE('crm_act_count_child' ,'活动统计子表','100000000');
INSERT INTO `bs_form_code_rule` (`rule_id`,`form_id`,`rule_one`,`rule_two`,`rule_three`,`rule_four`,`rule_five`,`serial_number_start`,`rule_one_index`,`rule_two_index`,`rule_three_index`,`start_index`,`field_index`) VALUE(29,'crm_act_count_child','CA','CC',1,3,4,'00001',1,2,3,4,5);
INSERT INTO `bs_form_code_max` (`number_id`,`code_rule_id`,`current_number`) VALUE(29,29,'00001');