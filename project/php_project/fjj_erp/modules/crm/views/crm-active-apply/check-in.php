<?php
/**
 * User: F1677929
 * Date: 2017/3/8
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JqueryUIAsset::register($this);
?>
<h1 class="head-first">签到</h1>
<?php ActiveForm::begin([
    'id'=>'check_in_form',
    'action'=>Url::to(['check-in','id'=>$data['applyData']['acth_id']])
])?>
<div class="mb-20">
    <label class="width-80">是否签到</label>
    <select id="check_in_select" class="width-100" name="CrmActiveCheckIn[actcin_ischeckin]">
        <option value="1" <?=$data['checkInData'][0]['actcin_ischeckin']=='1'?'selected':''?>>是</option>
        <?php if($data['applyData']['acth_ischeckin']=='0'){?>
            <option value="0" <?=$data['checkInData'][0]['actcin_ischeckin']=='0'?'selected':''?>>否</option>
        <?php }?>
    </select>
</div>
<div id="yes_check_in_div" class="mb-20 pl-20 pr-20"
    <?php if($data['applyData']['acth_ischeckin']=='0'&&$data['checkInData'][0]['actcin_ischeckin']=='0'){?>
        style="display:none;"
    <?php }?>
>
    <table class="table-small" style="width:100%;font-size:12px;">
        <thead>
        <tr>
            <th style="width:30px;">序号</th>
            <th style="width:150px;">签到人</th>
            <th style="width:150px;">手机号码</th>
            <th style="width:150px;">职位</th>
            <th style="width:180px;">备注</th>
            <th><a id="add_check_in_person">+添加</a></th>
        </tr>
        </thead>
        <tbody id="check_in_tbody">
        <?php if($data['checkInData'][0]['actcin_ischeckin']=='1'){?>
            <?php foreach($data['checkInData'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="required:true,validType:['length[0,20]','tdSame']" name="checkInArr[<?=$key?>][CrmActiveCheckIn][actcin_name]" value="<?=$val['actcin_name']?>" placeholder="必填"></td>
                    <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:['mobile','length[0,20]']" name="checkInArr[<?=$key?>][CrmActiveCheckIn][actcin_phone]" value="<?=$val['actcin_phone']?>"></td>
                    <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:'length[0,20]'" name="checkInArr[<?=$key?>][CrmActiveCheckIn][actcin_position]" value="<?=$val['actcin_position']?>"></td>
                    <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:'length[0,200]'" name="checkInArr[<?=$key?>][CrmActiveCheckIn][actcin_remark]" value="<?=$val['actcin_remark']?>"></td>
                    <td><a onclick='resetCheckInPerson(this)'>重置</a><?=$key==0?'':"&nbsp;&nbsp;<a onclick='deleteCheckInPerson(this)'>删除</a>"?></td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td>1</td>
                <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="required:true,validType:['length[0,20]','tdSame']" name="checkInArr[0][CrmActiveCheckIn][actcin_name]" value="<?=$data['applyData']['acth_name']?>" placeholder="必填"></td>
                <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:['mobile','length[0,20]']" name="checkInArr[0][CrmActiveCheckIn][actcin_phone]" value="<?=$data['applyData']['acth_phone']?>"></td>
                <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:'length[0,20]'" name="checkInArr[0][CrmActiveCheckIn][actcin_position]" value="<?=$data['applyData']['acth_position']?>"></td>
                <td><input type="text" style="width:100%;" class="text-center easyui-validatebox" data-options="validType:'length[0,200]'" name="checkInArr[0][CrmActiveCheckIn][actcin_remark]"></td>
                <td><a onclick='resetCheckInPerson(this)'>重置</a></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<div id="no_check_in_div"
    <?php if(!($data['applyData']['acth_ischeckin']=='0'&&$data['checkInData'][0]['actcin_ischeckin']=='0')){?>
        style="display:none;"
    <?php }?>
>
    <div class="mb-20">
        <label class="width-80 vertical-top">未到原因</label>
        <textarea style="width:650px;height:80px;" class="easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmActiveCheckIn[actcin_nocause]"><?=$data['checkInData'][0]['actcin_nocause']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-80 vertical-top">备注</label>
        <textarea style="width:650px;height:80px;" class="easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmActiveCheckIn[actcin_remark]"><?=$data['checkInData'][0]['actcin_remark']?></textarea>
    </div>
</div>
<div class="text-center">
    <button class="button-blue mr-20" type="submit">确定</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end();?>
<script>
    //重置签到人
    function resetCheckInPerson(obj){
        $(obj).parents('tr').find('input').val('');
    }

    //删除签到人
    function deleteCheckInPerson(obj){
        $(obj).parents("tr").remove();
        $("#check_in_tbody").find("tr").each(function(index){
            $(this).find("td:first").text(index+1);
        })
    }

    $(function(){
        //是否签到切换
        $("#check_in_select").change(function(){
            $("#yes_check_in_div,#no_check_in_div").toggle();
        });

        //添加签到人
        var i=100;
        $("#add_check_in_person").click(function(){
            var tr="<tr>";
            tr+="<td></td>";
            tr+="<td><input class='text-center easyui-validatebox' data-options='validType:[\"length[0,20]\",\"tdSame\"]' style='width:100%;' name='checkInArr["+i+"][CrmActiveCheckIn][actcin_name]'></td>";
            tr+="<td><input class='text-center easyui-validatebox' data-options='validType:[\"mobile\",\"length[0,20]\"]' style='width:100%;' name='checkInArr["+i+"][CrmActiveCheckIn][actcin_phone]'></td>";
            tr+="<td><input class='text-center easyui-validatebox' data-options='validType:\"length[0,20]\"' style='width:100%;' name='checkInArr["+i+"][CrmActiveCheckIn][actcin_position]'></td>";
            tr+="<td><input class='text-center easyui-validatebox' data-options='validType:\"length[0,200]\"' style='width:100%;' name='checkInArr["+i+"][CrmActiveCheckIn][actcin_remark]'></td>";
            tr+="<td><a onclick='resetCheckInPerson(this)'>重置</a>&nbsp;&nbsp;<a onclick='deleteCheckInPerson(this)'>删除</a></td>";
            $("#check_in_tbody").append(tr);
            $.parser.parse($("#check_in_tbody").find("tr:last"));//easyui解析
            $("#check_in_tbody").find("tr").each(function(index){
                $(this).find("td:first").text(index+1);
            });
            i++;
        });

        //ajax提交表单
        ajaxSubmitForm($("#check_in_form"),'',
            function(data){
                parent.layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        parent.$("#apply_table").datagrid('reload');
                        parent.$("#check_in_info_tab").datagrid('reload');
                        parent.$.fancybox.close();
                    }
                })
            }
        );

        //取消隐藏框验证
        $("button[type='submit']").click(function(){
            $("#yes_check_in_div:hidden,#no_check_in_div:hidden").find("input,textarea").validatebox({novalidate:true});
        });
    })
</script>