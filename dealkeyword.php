<?php

		$filePath = $_FILES['file']['name'];

		$siteUrl = $_POST['url'];
		
		$emp = strstr($siteUrl,'，');
		
		if($emp){
			$siteUrl = explode('，',$siteUrl);
			
		}else{
			$siteUrl = explode(',',$siteUrl);
			
		}
		
		include "Bdk.class.php";
		
		include("runtime.php");
		
		$runtime= new runtime;
		
		$runtime->start();
		
		$baidu = new BaiduKeyWordRank();
		
		$fileString = fopen($filePath, 'r');
		
		$keyWordlineNum = 0;
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<title>百度关键词查询</title>
			</head>
			<style>
				table{border:1px solid #09F7F7;border-top:none;}
				tr{border:none}
				td{border-top:none;border-left:none;border-bottom:none;border-right-color:#09F7F7;word-break:break-all;}
				.blue{background-color:#8CBB44}
			</style>
			<script src='jquery-2.0.0.js' type='text/javascript'></script>
			<script src='hover.js' type='text/javascript'></script>
			<body>
				<table width='0' border='1' cellspacing='0' cellpadding='0' style='text-align: left;border-top:1px solid #09F7F7'>
					<tr style='font-size:14px'>
								<td width='150'>关键词</td>
								<td width='100'>收录数</td>
								<!--<td width='100'>推广数</td>-->
								";
								foreach($siteUrl as $k=>$v){
									echo "<td width='200'>".$v."自然排名</td>";
								}
								foreach($siteUrl as $k=>$v){
									echo "<td width='200'>".$v."关键词文章收录数</td>";
								}
								
								echo "</tr></table>";
		
		while(!feof($fileString)) {
			$keyWordline = fgets($fileString);
			
			$keyWordline = ltrim($keyWordline);
			
			$keyWordline = rtrim($keyWordline);
			
			//$keyWordline = str_replace(" ","+",$keyWordline);
			
			if($keyWordline){
				$baidu->keyWordlineNum = $baidu->keyWordlineNum +1;
				
				$returncontts = $baidu->KeyWrodReturn($keyWordline,$siteUrl);
				
				while(empty($returncontts['shoulu'])){
					sleep(30);
					
					$baidu->rankNum = 0;
					
					$returncontts = $baidu->KeyWrodReturn($keyWordline,$siteUrl);
				}
				
				$tuiGuangAll = $returncontts['TuiAll'];
				
				if(empty($tuiGuangAll)){
					$tuiGuangAll = 0;
				}
			echo "<table width='0' border='1'style='border-top:none' cellspacing='0' cellpadding='0' style='text-align: left;'><tr style='font-size:14px'>
						<td width='150'>".$keyWordline."</td>
						<td width='100'>".$returncontts['shoulu']."</td>
						<!--<td width='100'>$tuiGuangAll</td>-->";
						
						if($returncontts['zipai']=='无结果'){
							foreach($siteUrl as $k=>$v){
								echo "<td width='200'>无结果</td>";
							}
						}else{
							foreach($returncontts['zipai'] as $k=>$v){
							echo "<td width='200'>".$v['ziran']."</td>";
                           }
						}
						if($returncontts['wenzhangNum']=='无结果'){
							foreach($siteUrl as $k=>$v){
								echo "<td width='200'>无结果</td>";
							}
						}else{						
							foreach($returncontts['wenzhangNum'] as $k=>$v){
							echo "<td width='200'>".$v['wenzhangNum']."</td>";
                           }
						}
						
						
						
						
						
					echo "</tr></table></body></html>";
			}
		}
	
	$runtime->stop();
	
	echo "<span>成功查询：".$baidu->keyWordlineNum."个</span><br />";
	echo "<span>页面执行时间：".$runtime->spent()."秒</span>";
?>