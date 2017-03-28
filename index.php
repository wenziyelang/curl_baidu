<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>百度关键词查询</title>
        <style>
            li {height:50px;list-style:none outside none;margin:0; padding:0;}
            .but{background:url("../but.gif") repeat-x scroll 50% top #CDE4F2;border:1px solid #C5E2F2;cursor:pointer;height:30px;margin-bottom:5px;margin-left:5px;width:80px;}
            input{color:#22AC38;height:25px;width:300px;background:none repeat scroll 0 0 #FFFFFF;border:1px solid #94C6E1;font-weight:bold;margin-bottom:5px;padding:5px;}

        </style>
    </head>

    <body>

        <form action="dealkeyword.php" method="post" enctype="multipart/form-data">
            <ul>
                <li>
                    <span>文件地址：</span><input type="file" name="file" />
                    <span>&nbsp;&nbsp;网站地址：</span><input type="text" name="url">
                </li>
                <li>
                    <input type="submit" name="submit" value="搜索" class="but" style="width:200px;">
                </li>
            </ul>
        </form>

        <h2 style="color:red">注意：</h2><br/>
        1、打开wamp程序，程序第一次启动需要开启php_curl扩展，左击桌面右下角的绿色的wamp，php-php.ini，打开后搜索extension=php_curl.dll，把前面的;去掉，然后点击wamp重启所有服务 <br />
        2、选择文件地址，一般把关键词放到keywords目录下的key.txt中，一行一个关键词<br/>
        3、网站地址可以输入多个，用逗号隔开。例如：bailitop.com,tiandaoedu.com,jjl.cn<br />
        4、联系方式: <br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email：wenziyelang@foxmail.com<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tel:13720095654<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;座机：2512<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ:260288701
    </body>
</html>