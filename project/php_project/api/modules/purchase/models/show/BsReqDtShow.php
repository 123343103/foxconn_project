<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 11:26
 */

namespace app\modules\purchase\models\show;


use app\modules\purchase\models\BsReqDt;

class BsReqDtShow extends BsReqDt
{
  public function fields()
  {
      $fields = parent::fields();
      //请购id
      $fields['req_dt_id'] = function () {
          return $this->req_dt_id;
      };
      //请购数量
      $fields['req_nums'] = function () {
          return $this->req_nums;
      };
      //请购部门
      $fields['spp_dpt_id']=function (){
          return $this->req['spp_dpt_id'];
      };
      //申请人
      $fields['app_id']=function (){
          return $this->req['app_id'];
      };
      //请购单价
      $fields['req_price'] = function () {
          return $this->req_price;
      };
      //请购单号
      $fields['req_no'] = function () {
          return $this->req['req_no'];
      };
      //请购单状态
      $fields['req_status'] = function () {
          return $this->req['req_status'];
      };
      //请购明细ID
      $fields['req_id'] = function () {
          return $this->req['req_id'];
      };
      //申请日期
      $fields['app_date']=function ()
      {
          return $this->req['app_date'];
      };
      //采购区域
      $fields['area_id']=function ()
      {
        return $this->req['area_id'];
      };
      //料号ID
      $fields['prt_pkid'] = function () {
          return $this->prt_pkid;
      };
      //供应商代码
      $fields['spp_code'] = function () {
          return $this->bsSupplier['spp_code'];
      };
      //供应商名称
      $fields['spp_fname']=function (){
          return $this->bsSupplier['spp_fname'];
      };
      //料号
      $fields['part_no'] = function () {
          return $this->bsPartno['part_no'];
      };
      //商品名称
      $fields['pdt_name'] = function () {
          return $this->bsProduct['pdt_name'];
      };
      //单位
      $fields['unit']=function (){
          return $this->bsPubdata['bsp_svalue'];
      };
      //品牌名
      $fields['brand_name_cn']=function (){
          return $this->bsBrank['brand_name_cn'];
      };
      return $fields;
  }
}