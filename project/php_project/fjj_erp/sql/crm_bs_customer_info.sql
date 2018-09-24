
alter table erp.crm_bs_customer_info modify column cust_cource  bigint;

alter table erp.crm_bs_customer_info modify column cust_businesstype  bigint;

alter table erp.crm_bs_customer_info add column compsum_cur bigint not null comment '年营业额幣別' after member_compsum;

alter table erp.crm_bs_customer_info add column pruchaseqty_cur bigint not null comment '年采购额幣別' after cust_pruchaseqty;

ALTER table crm_bs_customer_info add crm_ps_fu bigint comment '职位职能';

alter table crm_bs_customer_info add COLUMN HV_INV_TYPE  bigint(20)  DEFAULT NULL COMMENT '具備发票类型';

ALTER TABLE `crm_bs_customer_info`
ADD COLUMN `yn_pay`  int(1) NULL DEFAULT 0 COMMENT '是否可購買。0不能購買(20171009)' AFTER `official_receipts_cur`;
