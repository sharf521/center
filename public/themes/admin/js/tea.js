/**
 * Created by Administrator on 2016/10/27.
 */

function drawChart(title,subtext,id,datas)
{
    var myChart = echarts.init(document.getElementById(id));
    var option = {
        title: {
            text: title,
            subtext: subtext
        },
        tooltip : {
            trigger: 'item',
            formatter: "推荐{c}人"
        },
        calculable: false,
        series: [{
            name: '树图',
            type: 'tree',
            orient: 'horizontal',  // vertical horizontal
            rootLocation: {x: 50, y: 'center'}, // 根节点位置  {x: 100, y: 'center'}
            symbolSize: 15,
            layerPadding: 100,
            nodePadding: 5,
            roam: 'move',
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        position: 'right',
                        formatter: "{b}",
                        textStyle: {
                            color: '#000',
                            fontSize: 5
                        }
                    },
                    lineStyle: {
                        color: '#999',
                        type: 'curve' // 'curve'|'broken'|'solid'|'dotted'|'dashed'
                    }
                },
                emphasis: {
                    label: {
                        show: true
                    }
                }
            },
            data: datas
        }]
    };
    myChart.setOption(option);
}
