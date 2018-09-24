<?php
//公司

use yii\helpers\Url;
use app\assets\TreeAsset;
TreeAsset::register($this);

$this->params['homeLike'] = ['label'=>'系统平台设置','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'公司设置'];
$this->title = '公司设置';
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
    function companyView(id) {
        $.fancybox.open({
            href: '<?= Url::to(['view'])?>?id=' + id,
            type: 'iframe',
            padding : [],
            fitToView	: false,
            width		: 700,
            height		: 600,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    }
    function companyBelow(id) {
        $.fancybox.open({
            href: '<?= Url::to(['create'])?>?pid=' + id,
            type: 'iframe',
            padding : [],
            fitToView	: false,
            width		: 700,
            height		: 600,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    }
    function companyUpd(id) {
        $.fancybox.open({
            href: '<?= Url::to(['update'])?>?id=' + id,
            type: 'iframe',
            padding : [],
            fitToView	: false,
            width		: 700,
            height		: 600,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
        });
    }

    function companyDel(id){
                layer.confirm('确定删除公司？',
                    {
                        btn:['確定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": id},
                            url: "<?= Url::to(['delete'])?>",
                            success: function (msg) {
                                if( msg.flag === 1){
                                    parent.$.fancybox.close();
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