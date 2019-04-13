var request=require("request");
var async=require("async");
var cheerio=require("cheerio");
var http=require("http");
const md5=require("./routes/md5");
var async=require("async");
var mysql=require("./routes/mysql");
var buf=new Buffer(65535);
var CronJob = require('cron').CronJob;
new CronJob('1 * * * * *', function() {
    go();
}, null, true, 'America/Los_Angeles')

function go() {
    var arr=[];
    var arrs=[];
    async.series([
        function (next) {
            mysql.query("select * from news", function (err, result) {
                if (err) {
                    console.log(err);
                } else {
                    for (var i = 0; i < result.length; i++) {
                        save(result[i].title, 4);
                    }
                }
                next();
            })
        },
        function (next) {
            request("http://tech.ifeng.com/listpage/803/1/list.shtml", function (err, head, body) {
                var $ = cheerio.load(body);
                $(".zheng_list h2 .t_css").each(function (index, obj) {
                    var newobj = {};
                    newobj.url = $(obj).attr("href");
                    newobj.text = $(obj).text();
                    diff(newobj.text, 4, function () {
                        save(newobj.text, 4)
                    }, function () {
                        arr.push(newobj);
                    })


                })
                next();
            })
        },
        function (next) {
            var i = 0;
            async.eachSeries(arr, function (item, next1) {


                request(item.url, function (err, head, body) {
                    i++
                    var $ = cheerio.load(body);
                    var con = $("#main_content").text();

                    var newarr = [];
                    newarr.push(item.text);
                    newarr.push(con);
                    arrs.push(newarr);
                    console.log(arrs.length);
                    next1()

                })

            }, function () {
                next();
            })


        },
        function (next) {
            mysql.query("insert into news (title,con) values ?", [arrs], function (err) {
                if (err) {
                    console.log(err);
                } else {
                    console.log("ok")

                }
                next();
            })
        }
    ], function () {
        console.log("end");
    })
}


function save(str,num){
    var result=md5(str);
    for(var i=0;i<result.length;i+=num){
        buf[(parseInt(result.substr(i,num),16))]=1;
    }
}
function diff(str,num,same,no){
    var result=md5(str);
    var flag=true;
    for(var i=0;i<result.length;i+=num){
        if(buf[parseInt(result.substr(i,num),16)]!=1){
            flag=false;
            break;
        }
    }
    if(flag){
        if(same){
            same();
        }
    }else{
        if(no){
            no();
        }
    }

}