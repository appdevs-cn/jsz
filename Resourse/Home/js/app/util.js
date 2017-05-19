//五星直选单式过滤
var DirectFilter = function( data, len, filter )
{
    var s = new Array();
    if(data=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    var r = []
    var f = []
    data.sort()
    for (var i=0;i<data.length;i++){
        var str_temp = data[i].toString();
        if(filter=="common")
        {
            if(str_temp.length==len){
                if (jQuery.inArray(str_temp,r)==-1){
                    r.push(str_temp);
                }else{
                    f.push(str_temp);
                }
            }else{
                f.push(str_temp);
            }
        }
        else if(filter=="hhFilter")
        {
            if(str_temp.length==len)
            {
                str_1 = str_temp.slice(0,1);
                str_2 = str_temp.slice(1,2);
                str_3 = str_temp.slice(2,3);
                if( str_1 == str_2 && str_2 == str_3 && str_3 == str_1 )
                {
                    f.push(str_temp);
                }
                else
                {
                    if (jQuery.inArray(str_temp,r)==-1){
                        r.push(str_temp);
                    }else{
                        f.push(str_temp);
                    }
                }
            }
            else
            {
                f.push(str_temp);
            }
        }
        else if(filter=="2xzxFilter")
        {
            if(str_temp.length==len)
            {
                str_1 = str_temp.slice(0,1);
                str_2 = str_temp.slice(1,2);
                str = (str_1 > str_2 && str_1 != str_2) ? (str_2 + str_1) : "";
                str = (str_1 < str_2 && str_1 != str_2) ? (str_1 + str_2) : "";
                if (jQuery.inArray(str,r)==-1 && str_1 != str_2){
                    r.push(str_temp);
                }else{
                    f.push(str_temp);
                }
            }
            else
            {
                f.push(str_temp);
            }
        }
        else if(filter=="z3Filter")
        {
            if(str_temp.length==len)
            {
                str_1 = str_temp.slice(0,1);
                str_2 = str_temp.slice(1,2);
                str_3 = str_temp.slice(2,3);
                if( str_1 == str_2 && str_2 == str_3 && str_3 == str_1 )
                {
                    f.push(str_temp);
                }
                else
                {
                    if( (str_1 == str_2 || str_2 == str_3 || str_3 == str_1))
                    {
                        if (jQuery.inArray(str,r)==-1){
                            r.push(str_temp);
                        }else{
                            f.push(str_temp);
                        }
                    }
                    else
                    {
                        f.push(str_temp);
                    }
                }
            }
            else
            {
                f.push(str_temp);
            }
        }
        else if(filter=="z6Filter")
        {
            if(str_temp.length==len)
            {
                str_1 = str_temp.slice(0,1);
                str_2 = str_temp.slice(1,2);
                str_3 = str_temp.slice(2,3);
                if( str_1 != str_2 && str_2  != str_3 && str_3  != str_1 )
                {
                    if (jQuery.inArray(str,r)==-1){
                        r.push(str_temp);
                    }else{
                        f.push(str_temp);
                    }
                }
                else
                {
                    f.push(str_temp);
                }
            }
            else
            {
                f.push(str_temp);
            }
        }

    }
    s['filter'] = f.join(" ");
    s['result'] = r.join(" ");
    return s;
}

//11x5单式过滤
var XwDirectFilter = function( data, len, filter )
{
    if(filter=="common" && len==2)
    {
        return xwerxing(data)
    }
    if(filter=="common" && len==3)
    {
        return xwsanxing(data)
    }
    if(filter=="common" && len==4)
    {
        return xwrenxuan4(data)
    }
    if(filter=="common" && len==5)
    {
        return xwrenxuan5(data)
    }
    if(filter=="common" && len==6)
    {
        return xwrenxuan6(data)
    }
    if(filter=="common" && len==7)
    {
        return xwrenxuan7(data)
    }
    if(filter=="common" && len==8)
    {
        return xwrenxuan8(data)
    }
}

//11选5 2星
var xwerxing = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==2){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];

            if( str_1 != str_2){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

//11选5 3星
var xwsanxing = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==3){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];

            if( str_1 != str_2 && str_2  != str_3 && str_3  != str_1 ){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
};

//11选5 4星
var xwrenxuan4 = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==4){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];
            var str_4 = str_temp[3];

            if( str_1 != str_2 && str_1 != str_3 && str_1 != str_4 && str_2 != str_3 && str_2 != str_4 && str_3 != str_4){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

//11选5 5星
var xwrenxuan5 = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==5){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];
            var str_4 = str_temp[3];
            var str_5 = str_temp[4];

            if( str_1 != str_2 && str_1 != str_3 && str_1 != str_4 && str_1 != str_5 && str_2 != str_3 && str_2 != str_4 && str_2 != str_5 && str_3 != str_4 && str_3 != str_5 && str_4 != str_5){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

//11选5 6星
var xwrenxuan6 = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==6){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];
            var str_4 = str_temp[3];
            var str_5 = str_temp[4];
            var str_6 = str_temp[5];

            if( str_1 != str_2 && str_1 != str_3 && str_1 != str_4 && str_1 != str_5 && str_1 != str_6 && str_2 != str_3 && str_2 != str_4 && str_2 != str_5 && str_2 != str_6 && str_3 != str_4 && str_3 != str_5 && str_3 != str_6 && str_4 != str_5 && str_4 != str_6 && str_5 != str_6){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

//11选5 7星
var xwrenxuan7 = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==7){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];
            var str_4 = str_temp[3];
            var str_5 = str_temp[4];
            var str_6 = str_temp[5];
            var str_7 = str_temp[6];

            if( str_1 != str_2 && str_1 != str_3 && str_1 != str_4 && str_1 != str_5 && str_1 != str_6 && str_1 != str_7
                && str_2 != str_3 && str_2 != str_4 && str_2 != str_5 && str_2 != str_6 && str_2 != str_7
                && str_3 != str_4 && str_3 != str_5 && str_3 != str_6 && str_3 != str_7
                && str_4 != str_5 && str_4 != str_6 && str_4 != str_7 && str_5 != str_6 && str_5 != str_7 && str_6 != str_7){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

//11选5 8星
var xwrenxuan8 = function(arr)
{
    var r = [];
    var f = [];
    var s = new Array();
    if(arr=="")
    {
        s['filter'] = "";
        s['result'] = "";
        return s;
    }
    arr.sort();
    for (var i=0;i<arr.length;i++){
        var str_temp = arr[i].split(" ");

        if(str_temp.length==8){
            var str_1 = str_temp[0];
            var str_2 = str_temp[1];
            var str_3 = str_temp[2];
            var str_4 = str_temp[3];
            var str_5 = str_temp[4];
            var str_6 = str_temp[5];
            var str_7 = str_temp[6];
            var str_8 = str_temp[7];

            if( str_1 != str_2 && str_1 != str_3 && str_1 != str_4 && str_1 != str_5 && str_1 != str_6 && str_1 != str_7 && str_1 != str_8
                && str_2 != str_3 && str_2 != str_4 && str_2 != str_5 && str_2 != str_6 && str_2 != str_7 && str_2 != str_8
                && str_3 != str_4 && str_3 != str_5 && str_3 != str_6 && str_3 != str_7 && str_3 != str_8
                && str_4 != str_5 && str_4 != str_6 && str_4 != str_7 && str_4 != str_8
                && str_5 != str_6 && str_5 != str_7 && str_5 != str_8
                && str_6 != str_7 && str_6 != str_8 && str_7 != str_8){
                if (jQuery.inArray(str_temp.join(" "),r)==-1){
                    r.push(str_temp.join(" "));
                } else {
                    f.push(str_temp.join(" "));
                }
            }else{
                f.push(str_temp.join(" "));
            }


        }else{
            f.push(str_temp.join(" "));
        }
    }
    s['filter'] = f.join(",");
    s['result'] = r.join(",");
    return s;
}

function SetFilterFunc(func,data,len,filter)
{
    return window[func](data,len,filter)
}

window.filterArray = function(receiveArray) {
    var arrResult = new Array(); //定义一个返回结果数组.
    var filterResult = new Array();
    var result = new Array();
    if (receiveArray.length == 0) {
        result['filter'] = "";
        result['result'] = "";
        return result
    }
    for (var i = 0; i < receiveArray.length; ++i) {
        if (check(arrResult, receiveArray[i]) == -1) {
            //在这里做i元素与所有判断相同与否
            arrResult.push(receiveArray[i]);
            //　添加该元素到新数组。如果if内判断为false（即已添加过），
            //则不添加。
        } else {
            filterResult.push(receiveArray[i]);
        }
    }
    result['filter'] = filterResult;
    result['result'] = arrResult;
    return result;
}

function check(receiveArray, checkItem) {
    var index = -1; //　函数返回值用于布尔判断
    for (var i = 0; i < receiveArray.length; ++i) {
        if (receiveArray[i] == checkItem) {
            index = i;
            break;
        }
    }
    return index;
}
