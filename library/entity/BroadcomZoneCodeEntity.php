<?php

/**
 * 数据库应用类-*
 * @author Kinsama
 * @version 2020-03-23
 */
class BroadcomZoneCodeEntity
{

    public static function getProvinceList()
    {
        return array(
            "11" => "北京",
            "12" => "天津",
            "13" => "河北",
            "14" => "山西",
            "15" => "内蒙古",
            "21" => "辽宁",
            "22" => "吉林",
            "23" => "黑龙江",
            "31" => "上海",
            "32" => "江苏",
            "33" => "浙江",
            "34" => "安徽",
            "35" => "福建",
            "36" => "江西",
            "37" => "山东",
            "41" => "河南",
            "42" => "湖北",
            "43" => "湖南",
            "44" => "广东",
            "45" => "广西",
            "46" => "海南",
            "50" => "重庆",
            "51" => "四川",
            "52" => "贵州",
            "53" => "云南",
            "54" => "西藏",
            "61" => "陕西",
            "62" => "甘肃",
            "63" => "青海",
            "64" => "宁夏",
            "65" => "新疆"
        );
    }

    public static function getCityList()
    {
        return array(
            "11" => array(
                "1" => "东城",
                "2" => "西城",
                "3" => "朝阳",
                "4" => "丰台",
                "5" => "石景山",
                "6" => "海淀",
                "7" => "门头沟",
                "8" => "房山",
                "9" => "通州",
                "10" => "顺义",
                "11" => "昌平",
                "12" => "大兴",
                "13" => "怀柔",
                "14" => "平谷",
                "15" => "密云",
                "16" => "延庆"
            ),
            "12" => array(
                "1" => "和平",
                "2" => "河东",
                "3" => "河西",
                "4" => "南开",
                "5" => "河北",
                "6" => "红桥",
                "7" => "东丽",
                "8" => "西青",
                "9" => "津南",
                "10" => "北辰",
                "11" => "武清",
                "12" => "宝坻",
                "13" => "滨海新区",
                "14" => "宁河",
                "15" => "静海",
                "16" => "蓟州"
            ),
            "13" => array(
                "1" => "石家庄",
                "2" => "唐山",
                "3" => "秦皇岛",
                "4" => "邯郸",
                "5" => "邢台",
                "6" => "保定",
                "7" => "张家口",
                "8" => "承德",
                "9" => "沧州",
                "10" => "廊坊",
                "11" => "衡水"
            ),
            "14" => array(
                "1" => "太原",
                "2" => "大同",
                "3" => "阳泉",
                "4" => "长治",
                "5" => "晋城",
                "6" => "朔州",
                "7" => "晋中",
                "8" => "运城",
                "9" => "忻州",
                "10" => "临汾",
                "11" => "吕梁"
            ),
            "15" => array(
                "1" => "呼和浩特",
                "2" => "包头",
                "3" => "乌海",
                "4" => "赤峰",
                "5" => "通辽",
                "6" => "鄂尔多斯",
                "7" => "呼伦贝尔",
                "8" => "巴彦淖尔",
                "9" => "乌兰察布",
                "10" => "兴安",
                "11" => "锡林郭勒",
                "12" => "阿拉善"
            ),
            "21" => array(
                "1" => "沈阳",
                "2" => "大连",
                "3" => "鞍山",
                "4" => "抚顺",
                "5" => "本溪",
                "6" => "丹东",
                "7" => "锦州",
                "8" => "营口",
                "9" => "阜新",
                "10" => "辽阳",
                "11" => "盘锦",
                "12" => "铁岭",
                "13" => "朝阳",
                "14" => "葫芦岛"
            ),
            "22" => array(
                "1" => "长春",
                "2" => "吉林",
                "3" => "四平",
                "4" => "辽源",
                "5" => "通化",
                "6" => "白山",
                "7" => "松原",
                "8" => "白城",
                "9" => "延边"
            ),
            "23" => array(
                "1" => "哈尔滨",
                "2" => "齐齐哈尔",
                "3" => "鸡西",
                "4" => "鹤岗",
                "5" => "双鸭山",
                "6" => "大庆",
                "7" => "伊春",
                "8" => "佳木斯",
                "9" => "七台河",
                "10" => "牡丹江",
                "11" => "黑河",
                "12" => "绥化",
                "13" => "大兴安岭"
            ),
            "31" => array(
                "1" => "黄浦",
                "2" => "徐汇",
                "3" => "长宁",
                "4" => "静安",
                "5" => "普陀",
                "6" => "虹口",
                "7" => "杨浦",
                "8" => "闵行",
                "9" => "宝山",
                "10" => "嘉定",
                "11" => "浦东新区",
                "12" => "金山",
                "13" => "松江",
                "14" => "青浦",
                "15" => "奉贤",
                "16" => "崇明"
            ),
            "32" => array(
                "1" => "南京",
                "2" => "无锡",
                "3" => "徐州",
                "4" => "常州",
                "5" => "苏州",
                "6" => "南通",
                "7" => "连云港",
                "8" => "淮安",
                "9" => "盐城",
                "10" => "扬州",
                "11" => "镇江",
                "12" => "泰州",
                "13" => "宿迁"
            ),
            "33" => array(
                "1" => "杭州",
                "2" => "宁波",
                "3" => "温州",
                "4" => "嘉兴",
                "5" => "湖州",
                "6" => "绍兴",
                "7" => "金华",
                "8" => "衢州",
                "9" => "舟山",
                "10" => "台州",
                "11" => "丽水"
            ),
            "34" => array(
                "1" => "合肥",
                "2" => "芜湖",
                "3" => "蚌埠",
                "4" => "淮南",
                "5" => "马鞍山",
                "6" => "淮北",
                "7" => "铜陵",
                "8" => "安庆",
                "9" => "黄山",
                "10" => "滁州",
                "11" => "阜阳",
                "12" => "宿州",
                "13" => "六安",
                "14" => "亳州",
                "15" => "池州",
                "16" => "宣城"
            ),
            "35" => array(
                "1" => "福州",
                "2" => "厦门",
                "3" => "莆田",
                "4" => "三明",
                "5" => "泉州",
                "6" => "漳州",
                "7" => "南平",
                "8" => "龙岩",
                "9" => "宁德"
            ),
            "36" => array(
                "1" => "南昌",
                "2" => "景德镇",
                "3" => "萍乡",
                "4" => "九江",
                "5" => "新余",
                "6" => "鹰潭",
                "7" => "赣州",
                "8" => "吉安",
                "9" => "宜春",
                "10" => "抚州",
                "11" => "上饶"
            ),
            "37" => array(
                "1" => "济南",
                "2" => "青岛",
                "3" => "淄博",
                "4" => "枣庄",
                "5" => "东营",
                "6" => "烟台",
                "7" => "潍坊",
                "8" => "济宁",
                "9" => "泰安",
                "10" => "威海",
                "11" => "日照",
                "12" => "莱芜",
                "13" => "临沂",
                "14" => "德州",
                "15" => "聊城",
                "16" => "滨州",
                "17" => "菏泽"
            ),
            "41" => array(
                "1" => "郑州",
                "2" => "开封",
                "3" => "洛阳",
                "4" => "平顶山",
                "5" => "安阳",
                "6" => "鹤壁",
                "7" => "新乡",
                "8" => "焦作",
                "9" => "濮阳",
                "10" => "许昌",
                "11" => "漯河",
                "12" => "三门峡",
                "13" => "南阳",
                "14" => "商丘",
                "15" => "信阳",
                "16" => "周口",
                "17" => "驻马店",
                "18" => "济源"
            ),
            "42" => array(
                "1" => "武汉",
                "2" => "黄石",
                "3" => "十堰",
                "4" => "宜昌",
                "5" => "襄阳",
                "6" => "鄂州",
                "7" => "荆门",
                "8" => "孝感",
                "9" => "荆州",
                "10" => "黄冈",
                "11" => "咸宁",
                "12" => "随州",
                "13" => "恩施",
                "14" => "仙桃",
                "15" => "潜江",
                "16" => "天门",
                "17" => "神农"
            ),
            "43" => array(
                "1" => "长沙",
                "2" => "株洲",
                "3" => "湘潭",
                "4" => "衡阳",
                "5" => "邵阳",
                "6" => "岳阳",
                "7" => "常德",
                "8" => "张家界",
                "9" => "益阳",
                "10" => "郴州",
                "11" => "永州",
                "12" => "怀化",
                "13" => "娄底",
                "14" => "湘西"
            ),
            "44" => array(
                "1" => "广州",
                "2" => "韶关",
                "3" => "深圳",
                "4" => "珠海",
                "5" => "汕头",
                "6" => "佛山",
                "7" => "江门",
                "8" => "湛江",
                "9" => "茂名",
                "10" => "肇庆",
                "11" => "惠州",
                "12" => "梅州",
                "13" => "汕尾",
                "14" => "河源",
                "15" => "阳江",
                "16" => "清远",
                "17" => "东莞",
                "18" => "中山",
                "19" => "潮州",
                "20" => "揭阳",
                "21" => "云浮"
            ),
            "45" => array(
                "1" => "南宁",
                "2" => "柳州",
                "3" => "桂林",
                "4" => "梧州",
                "5" => "北海",
                "6" => "防城港",
                "7" => "钦州",
                "8" => "贵港",
                "9" => "玉林",
                "10" => "百色",
                "11" => "贺州",
                "12" => "河池",
                "13" => "来宾",
                "14" => "崇左"
            ),
            "46" => array(
                "1" => "海口",
                "2" => "三亚",
                "3" => "三沙",
                "4" => "儋州",
                "5" => "五指山",
                "6" => "琼海",
                "7" => "文昌",
                "8" => "万宁",
                "9" => "东方",
                "10" => "定安",
                "11" => "屯昌",
                "12" => "澄迈",
                "13" => "临高",
                "14" => "白沙",
                "15" => "昌江",
                "16" => "乐东",
                "17" => "陵水",
                "18" => "保亭",
                "19" => "琼中"
            ),
            "50" => array(
                "1" => "万州",
                "2" => "涪陵",
                "3" => "渝中",
                "4" => "大渡口",
                "5" => "江北",
                "6" => "沙坪坝",
                "7" => "九龙坡",
                "8" => "南岸",
                "9" => "北碚",
                "10" => "綦江",
                "11" => "大足",
                "12" => "渝北",
                "13" => "巴南",
                "14" => "黔江",
                "15" => "长寿",
                "16" => "江津",
                "17" => "合川",
                "18" => "永川",
                "19" => "南川",
                "20" => "璧山",
                "21" => "铜梁",
                "22" => "潼南",
                "23" => "荣昌",
                "24" => "开州",
                "25" => "梁平",
                "26" => "武隆",
                "27" => "城口",
                "28" => "丰都",
                "29" => "垫江",
                "30" => "忠县",
                "31" => "云阳",
                "32" => "奉节",
                "33" => "巫山",
                "34" => "巫溪",
                "35" => "石柱",
                "36" => "秀山",
                "37" => "酉阳",
                "38" => "彭水"
            ),
            "51" => array(
                "1" => "成都",
                "2" => "自贡",
                "3" => "攀枝花",
                "4" => "泸州",
                "5" => "德阳",
                "6" => "绵阳",
                "7" => "广元",
                "8" => "遂宁",
                "9" => "内江",
                "10" => "乐山",
                "11" => "南充",
                "12" => "眉山",
                "13" => "宜宾",
                "14" => "广安",
                "15" => "达州",
                "16" => "雅安",
                "17" => "巴中",
                "18" => "资阳",
                "19" => "阿坝",
                "20" => "甘孜",
                "21" => "凉山"
            ),
            "52" => array(
                "1" => "贵阳",
                "2" => "六盘水",
                "3" => "遵义",
                "4" => "安顺",
                "5" => "毕节",
                "6" => "铜仁",
                "7" => "黔西南",
                "8" => "黔东南",
                "9" => "黔南"
            ),
            "53" => array(
                "1" => "昆明",
                "2" => "曲靖",
                "3" => "玉溪",
                "4" => "保山",
                "5" => "昭通",
                "6" => "丽江",
                "7" => "普洱",
                "8" => "临沧",
                "9" => "楚雄",
                "10" => "红河",
                "11" => "文山",
                "12" => "西双版纳",
                "13" => "大理",
                "14" => "德宏",
                "15" => "怒江",
                "16" => "迪庆"
            ),
            "54" => array(
                "1" => "拉萨",
                "2" => "日喀则",
                "3" => "昌都",
                "4" => "林芝",
                "5" => "山南",
                "6" => "那曲",
                "7" => "阿里"
            ),
            "61" => array(
                "1" => "西安",
                "2" => "铜川",
                "3" => "宝鸡",
                "4" => "咸阳",
                "5" => "渭南",
                "6" => "延安",
                "7" => "汉中",
                "8" => "榆林",
                "9" => "安康",
                "10" => "商洛"
            ),
            "62" => array(
                "1" => "兰州",
                "2" => "嘉峪关",
                "3" => "金昌",
                "4" => "白银",
                "5" => "天水",
                "6" => "武威",
                "7" => "张掖",
                "8" => "平凉",
                "9" => "酒泉",
                "10" => "庆阳",
                "11" => "定西",
                "12" => "陇南",
                "13" => "临夏",
                "14" => "甘南"
            ),
            "63" => array(
                "1" => "西宁",
                "2" => "海东",
                "3" => "海北",
                "4" => "黄南",
                "5" => "海南",
                "6" => "果洛",
                "7" => "玉树",
                "8" => "海西"
            ),
            "64" => array(
                "1" => "银川",
                "2" => "石嘴山",
                "3" => "吴忠",
                "4" => "固原",
                "5" => "中卫"
            ),
            "65" => array(
                "1" => "乌鲁木齐",
                "2" => "克拉玛依",
                "3" => "吐鲁番",
                "4" => "哈密",
                "5" => "昌吉",
                "6" => "博尔塔拉",
                "7" => "巴音郭楞",
                "8" => "阿克苏",
                "9" => "克孜勒苏",
                "10" => "喀什",
                "11" => "和田",
                "12" => "伊犁",
                "13" => "塔城",
                "14" => "阿勒泰",
                "15" => "石河子",
                "16" => "阿拉尔",
                "17" => "图木舒克",
                "18" => "五家渠",
                "19" => "铁门关"
            )
        );
    }
}
?>