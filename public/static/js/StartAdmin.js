var CODE_SUCCESS = 200;
var access_token = getCookie("access_token");
var PostBase = {
    access_token: access_token,
    plat: "admin",
    version: "10000"
};
//写cookies
function setCookie(name, value) {
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
}
//读取cookies
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

    if (arr = document.cookie.match(reg))

        return unescape(arr[2]);
    else
        return null;
}
//删除cookies
function delCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}
console.log("%c\n\n\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\n                  _oo0oo_                   \n                 088888880                  \n                 88\" . \"88                  \n                 (| -_- |)                  \n                  0\\ = /0                   \n               ___/'---'\\___                \n             .' \\\\\\|   |/// '.              \n            / \\\\\\||| : |||/// \\             \n           /_ ||||||-:-|||||| _\\            \n          |   | \\\\\\\\ - //// |   |           \n          | \\_|  ''\\---/''  |_/ |           \n           \\  .-\\__  '-'  __/-.  /          \n        ___'. .'  /--.--\\  '. .'___         \n     .\"\"  '< '.___\\_<|>_/___.' >'  \"\".      \n    | | : '-  \\'.:'\\ _ /':.'/ - ' : | |     \n    \\  \\ '_.   \\_ __\\ /__ _/   .-' /  /     \n     '-____'.___  \\_____/  ___.'____-'      \n                  '=---='                   \n^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^\n     Powered By Hamm Email:admin@hamm.cn    \n\t\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\t\n", "background-color:yellow;color:orangered;");
console.log("%c\n\n\n  Wish you fuck your bugs\n\n\n", "font-size:20px;color:orangered;font-weight:bold;");

const isDebug = true;
echoGroupStart("System initialize start");
echo("System initializeing");
echoGroupEnd("System has been initialized");
function echo(msg, color = "#000", fontSize = "14") {
    if (isDebug) {
        console.log("%c " + msg, "font-size:" + fontSize + "px;color:" + color + ";font-weight:bold;");
    }
}

function echoGroupStart(msg, color = "#000") {
    if (isDebug) {
        console.group("%c " + msg, "font-size:16px;color:" + color + ";font-weight:bold;");
    }
}
function echoGroupEnd(msg, color = "#000") {
    if (isDebug) {
        console.groupEnd();
        console.log("%c " + msg, "font-size:16px;color:" + color + ";font-weight:bold;");
    }
}

function isChinese(obj) {
    var reg = /^[\u0391-\uFFE5]+$/;
    if (obj != "" && !reg.test(obj)) {
        return false;
    } else {
        return true;
    }
}

function checkZm(zm) {
    var zmReg = /^[a-zA-Z]*$/;
    if (zm != "" && !zmReg.test(zm)) {
        return false;
    } else {
        return true;
    }
}

function checkInt(obj) {
    var reg = /^[0-9]+$/;
    if (obj != "" && !reg.test(obj)) {
        return false;
    } else {
        return true;
    }
}

function checkZmOrInt(zmnum) {
    var zmnumReg = /^[0-9a-zA-Z]*$/;
    if (zmnum != "" && !zmnumReg.test(zmnum)) {
        return false;
    } else {
        return true;
    }
}

function checkEmail(obj) {
    //对电子邮件的验证
    var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    if (!myreg.test(obj)) {
        return false;
    } else {
        return true;
    }
}

function checkMobile(mobile) {
    if (mobile.length == 0) {
        return false;
    }
    if (mobile.length != 11) {
        return false;
    }

    if (!(/^1[34578]\d{9}$/.test(mobile))) {
        return false;
    } else {
        return true;
    }
}

function checkIDCard(idCard) {
    var checkFlag = new IDCard(idCard);
    if (!checkFlag.IsValid()) {
        return false;
    } else {
        return true;
    }
}

function checkDouble(value) {
    var reg = /^(-?\d+).(-?\d+)?$/
    if (reg.test(value)) {
        return true;
    } else {
        return false;
    }
}

function checkDateTime(value) {
    var reg = /^(?:19|20)[0-9][0-9]-(?:(?:0[1-9])|(?:1[0-2]))-(?:(?:[0-2][1-9])|(?:[1-3][0-1])) (?:(?:[0-2][0-3])|(?:[0-1][0-9])):[0-5][0-9]:[0-5][0-9]$/;
    if (reg.test(value)) {
        return true;
    } else {
        return false;
    }
}

function checkTime(value, type = "H:i:s") {
    var reg = /^((?:[0-2][0-3])|(?:[0-1][0-9])|(?:[0-9])):([0-5][0-9]|[0-9]):([0-5][0-9]|[0-9])$/;
    switch (type) {
        case 'H:i:s':
            reg = /^((?:[0-2][0-3])|(?:[0-1][0-9])|(?:[0-9])):([0-5][0-9]|[0-9]):([0-5][0-9]|[0-9])$/;
            break;
        case 'H:i':
            reg = /^((?:[0-2][0-3])|(?:[0-1][0-9])|(?:[0-9])):([0-5][0-9]|[0-9])$/;
            break;
        default:
    }
    if (reg.test(value)) {
        return true;
    } else {
        return false;
    }
}

function checkDate(value, type = "Y-m-d") {
    var reg = /^(?:19|20)[0-9][0-9]-(?:(?:[1-9])|(?:0[1-9])|(?:1[0-2]))-(?:(?:[1-9])|(?:[0-2][1-9])|(?:[1-3][0-1]))$/;
    switch (type) {
        case 'Y-m-d':
            reg = /^(?:19|20)[0-9][0-9]-(?:(?:[1-9])|(?:0[1-9])|(?:1[0-2]))-(?:(?:[1-9])|(?:[0-2][1-9])|(?:[1-3][0-1]))$/;
            break;
        case 'm-d':
            reg = /^(?:(?:[1-9])|(?:0[1-9])|(?:1[0-2]))-(?:(?:[1-9])|(?:[0-2][1-9])|(?:[1-3][0-1]))$/;
            break;
        default:
    }
    if (reg.test(value)) {
        return true;
    } else {
        return false;
    }
}

function checkTrainNo(value) {
    var reg = /^(C|D|G|Z|T|K|L)([0-9][0-9]|[0-9][0-9][0-9]|[0-9][0-9][0-9][0-9])$/;
    if (reg.test(value.toUpperCase())) {
        return true;
    } else {
        return false;
    }
}

function IDCard(CardNo) {
    this.Valid = false;
    this.ID15 = '';
    this.ID18 = '';
    this.Local = '';
    if (CardNo != null) this.SetCardNo(CardNo);
}

// 设置身份证号码，15位或者18位
IDCard.prototype.SetCardNo = function (CardNo) {
    this.ID15 = '';
    this.ID18 = '';
    this.Local = '';
    CardNo = CardNo.replace(" ", "");
    var strCardNo;
    if (CardNo.length == 18) {
        pattern = /^\d{17}(\d|x|X)$/;
        if (pattern.exec(CardNo) == null) return;
        strCardNo = CardNo.toUpperCase();
    } else {
        pattern = /^\d{15}$/;
        if (pattern.exec(CardNo) == null) return;
        strCardNo = CardNo.substr(0, 6) + '19' + CardNo.substr(6, 9)
        strCardNo += this.GetVCode(strCardNo);
    }
    this.Valid = this.CheckValid(strCardNo);
}

// 校验身份证有效性
IDCard.prototype.IsValid = function () {
    return this.Valid;
}

// 返回生日字符串，格式如下，1981-10-10
IDCard.prototype.GetBirthDate = function () {
    var BirthDate = '';
    if (this.Valid) BirthDate = this.GetBirthYear() + '-' + this.GetBirthMonth() + '-' + this.GetBirthDay();
    return BirthDate;
}

// 返回生日中的年，格式如下，1981
IDCard.prototype.GetBirthYear = function () {
    var BirthYear = '';
    if (this.Valid) BirthYear = this.ID18.substr(6, 4);
    return BirthYear;
}

// 返回生日中的月，格式如下，10
IDCard.prototype.GetBirthMonth = function () {
    var BirthMonth = '';
    if (this.Valid) BirthMonth = this.ID18.substr(10, 2);
    if (BirthMonth.charAt(0) == '0') BirthMonth = BirthMonth.charAt(1);
    return BirthMonth;
}

// 返回生日中的日，格式如下，10
IDCard.prototype.GetBirthDay = function () {
    var BirthDay = '';
    if (this.Valid) BirthDay = this.ID18.substr(12, 2);
    return BirthDay;
}

// 返回性别，1：男，0：女
IDCard.prototype.GetSex = function () {
    var Sex = '';
    if (this.Valid) Sex = this.ID18.charAt(16) % 2;
    return Sex;
}

// 返回15位身份证号码
IDCard.prototype.Get15 = function () {
    var ID15 = '';
    if (this.Valid) ID15 = this.ID15;
    return ID15;
}

// 返回18位身份证号码
IDCard.prototype.Get18 = function () {
    var ID18 = '';
    if (this.Valid) ID18 = this.ID18;
    return ID18;
}

// 返回所在省，例如：上海市、浙江省
IDCard.prototype.GetLocal = function () {
    var Local = '';
    if (this.Valid) Local = this.Local;
    return Local;
}

IDCard.prototype.GetVCode = function (CardNo17) {
    var Wi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1);
    var Ai = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    var cardNoSum = 0;
    for (var i = 0; i < CardNo17.length; i++) cardNoSum += CardNo17.charAt(i) * Wi[i];
    var seq = cardNoSum % 11;
    return Ai[seq];
}

IDCard.prototype.CheckValid = function (CardNo18) {
    if (this.GetVCode(CardNo18.substr(0, 17)) != CardNo18.charAt(17)) return false;
    if (!this.IsDate(CardNo18.substr(6, 8))) return false;
    var aCity = {
        11: "北京",
        12: "天津",
        13: "河北",
        14: "山西",
        15: "内蒙古",
        21: "辽宁",
        22: "吉林",
        23: "黑龙江 ",
        31: "上海",
        32: "江苏",
        33: "浙江",
        34: "安徽",
        35: "福建",
        36: "江西",
        37: "山东",
        41: "河南",
        42: "湖北 ",
        43: "湖南",
        44: "广东",
        45: "广西",
        46: "海南",
        50: "重庆",
        51: "四川",
        52: "贵州",
        53: "云南",
        54: "西藏 ",
        61: "陕西",
        62: "甘肃",
        63: "青海",
        64: "宁夏",
        65: "新疆",
        71: "台湾",
        81: "香港",
        82: "澳门",
        91: "国外"
    };
    if (aCity[parseInt(CardNo18.substr(0, 2))] == null) return false;
    this.ID18 = CardNo18;
    this.ID15 = CardNo18.substr(0, 6) + CardNo18.substr(8, 9);
    this.Local = aCity[parseInt(CardNo18.substr(0, 2))];
    return true;
}

IDCard.prototype.IsDate = function (strDate) {
    var r = strDate.match(/^(\d{1,4})(\d{1,2})(\d{1,2})$/);
    if (r == null) return false;
    var d = new Date(r[1], r[2] - 1, r[3]);
    return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[2] && d.getDate() == r[3]);
}