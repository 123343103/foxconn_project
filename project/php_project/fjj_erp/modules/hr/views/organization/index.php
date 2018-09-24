<?php


use yii\helpers\Url;

use app\assets\TreeAsset;
//use app\assets\goods\type\IndexAsset;
TreeAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\Search\OrganizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['homeLike'] = ['label'=>'人事信息','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'组织机构'];
$this->title = '人事资料';
?>
    <div class="content">
        <div id="tree"></div>
    </div>

<script>
    $(function(){
            var tree = [
                <?= $tree ?>
            ];
            $('#tree').treeview({
                data: tree,         // data is not optional
                levels: 2,
                selected: false,
                enableLinks: true,
                highlightSelected: false,
                searchResultBackColor: "#DEDEDE",
            });
            $('#treeview-disabled').treeview({
                data: tree
            });



    });
    function orgBelow(id) {
        $.fancybox.open({
            href: '<?= Url::to(['create'])?>?pid=' + id,
            type: 'iframe',
            padding : [],
            fitToView	: false,
            width		: 760,
            height		: 450,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    }
    function orgUpd(id) {
        $.fancybox.open({
            href: '<?= Url::to(['update'])?>?id=' + id,
            type: 'iframe',
            padding : [],
            fitToView	: false,
            width		: 760,
            height		: 450,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    }
    function orgDel(id){
        var url = '<?= Url::to(['delete']) ?>';
            option = {
                alert: "请点击选择一条信息",
                confirm:"确定要删除吗?"
            }
                layer.confirm(option.confirm,
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": id},
                            url: url,
                            success: function (msg) {
                                if( msg.flag === 1){
                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                }else{
                                    layer.alert(msg.msg,{icon:2})
                                }
                            },
                            error :function(msg){
                                layer.alert(msg.msg,{icon:2})
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
    }
</script>