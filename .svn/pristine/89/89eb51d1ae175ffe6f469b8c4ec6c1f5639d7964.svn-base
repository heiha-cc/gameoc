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

        carousel.render({
            elem: '#test2'
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
                url: 'hall',
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
                                    text: '大厅在线人数',
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
                                    name: 'IOS人数',
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
                                        name: '安卓人数',
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
        var getData2 = function () {
            var orderData = [];
            $.ajax({
                type: 'post',
                url: 'game',
                dataType: 'json',
                async: false,
                success: function (res2) {
                    if (res2.code === 0) {
                        orderData[0] = res2.data.dates;
                        orderData[1] = res2.data.numbers;
                        orderData[2] = res2.data.numbers2;

                        //标准折线图
                        var echnormline2 = [], normline2 = [
                            {
                                title: {
                                    text: '游戏内在线人数',
                                    x: 'center',
                                    textStyle: {
                                        fontSize: 16
                                    }
                                },
                                tooltip: { //提示框
                                    trigger: 'axis'
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
                                xAxis: [{ //X轴
                                    type: 'category',
                                    boundaryGap: false,
                                    data: orderData[0]
                                }],
                                yAxis: [{  //Y轴
                                    type: 'value'
                                }],
                                series: [{ //内容
                                    name: 'IOS人数',
                                    type: 'line',
                                    data: orderData[1],
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
                                },
                                    { //内容
                                        name: '安卓人数',
                                        type: 'line',
                                        data: orderData[2],
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
                                    },
                                ]
                            }
                        ]
                            , elemnormline2 = $('#game').children('div')
                            , rendernormline2 = function (index) {
                            echnormline2[index] = echarts.init(elemnormline2[index], layui.echartsTheme);
                            echnormline2[index].setOption(normline2[index]);
                            window.onresize = echnormline2[index].resize;
                        };
                        if (!elemnormline2[0]) return;
                        rendernormline2(0);
                    }
                }
            });
        };
         getData();
        getData2();
    });

    exports('online', {})
});