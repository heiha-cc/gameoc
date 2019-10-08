/**

 @Name：layuiAdmin Echarts集成
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：GPL-2

 */


layui.define(function (exports) {


    //区块轮播切换
    layui.use(['admin', 'carousel'], function () {
        var $ = layui.$
            , admin = layui.admin
            , carousel = layui.carousel
            , element = layui.element
            , device = layui.device();

        //轮播切换

        carousel.render({
            elem: '#test1'
            , width: '100%'
            , arrow: 'none'
            , interval: 5000
            , autoplay: false
        });
        element.render('progress');
    });


//折线图
    layui.use(['carousel', 'echarts'], function () {
        var $ = layui.$
            , carousel = layui.carousel
            , echarts = layui.echarts;


        var getData = function () {
            var regData = [];
            $.ajax({
                type: 'post',
                url: 'gameonline',
                dataType: 'json',
                async: false,
                success: function (res) {
                    if (res.code === 0) {
                        regData[0] = res.data.dates;
                        regData[1] = res.data.numbers;
                        regData[2] = res.data.numbers2;
                        //标准折线图
                        var echnormline = [], normline = [
                            {
                                title: {
                                    text: '在线人数统计',
                                    x: 'center',
                                    textStyle: {
                                        fontSize: 16
                                    }
                                },
                                tooltip: {
                                    trigger: 'axis'
                                },
                                legend: {
                                    data: ['', '']
                                },
                                toolbox: {
                                    show: true,
                                    feature: {
                                        mark: {show: true},
                                        dataView: {show: true, readOnly: false},
                                        magicType: {show: true, type: ['line', 'bar']},
                                        restore: {show: true},
                                        saveAsImage: {show: true}
                                    }
                                },
                                // calculable : true, //所有点都突出显示
                                xAxis: [{
                                    type: 'category',
                                    boundaryGap: false,
                                    data: regData[0],
                                }],
                                yAxis: [{
                                    type: 'value'
                                }],
                                series: [{
                                    name: '大厅在线人数',
                                    type: 'line',
                                    data: regData[1],
                                    markPoint: {
                                        data: [
                                            {type: 'max', name: '最大值'},
                                            {type: 'min', name: '最小值'}
                                        ]
                                    },
                                    markLine: {
                                        data: [
                                            //{type : 'average', name: '平均值'}
                                        ]
                                    }
                                },
                                    {
                                        name: '游戏在线人数',
                                        type: 'line',
                                        data: regData[2],
                                        markPoint: {
                                            data: [
                                                {type: 'max', name: '最大值'},
                                                {type: 'min', name: '最小值'}
                                            ]
                                        },
                                        markLine: {
                                            data: [
                                                // {type : 'average', name: '平均值'}
                                            ]
                                        }
                                    }
                                ]
                            },
                        ]
                            , elemnormline = $('#hall').children('div')
                            , rendernormline = function (index) {
                            echnormline[index] = echarts.init(elemnormline[index], layui.echartsTheme);
                            echnormline[index].setOption(normline[index]);
                            window.onresize = echnormline[index].resize;
                        };
                        if (!elemnormline[0]) return;
                        rendernormline(0);
                    }
                }
            });
        };
         getData();
    });

    exports('gameonline', {})
});