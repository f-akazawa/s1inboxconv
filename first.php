<?php

//ファイルを開く
//モード[r]の読み込み専用
if (! ($fp = fopen ( "$_GET[f]", "r" ))) {
   echo "ファイルが開けません。";
}

// デコードファイル読み込んで配列に保存
// 10行目から必要(配列なので９から使う）
$decode = file("$_GET[dec]");

// テスト用出力ファイル
if(! $wfp = fopen("temp.txt","w")){
	echo "書き込みファイル開けません";
}


//echo substr($decode[9],11,6);// P
//echo substr($decode[9],20,6);// T
//echo substr($decode[9],29,6);// S
//echo substr($decode[9],84,5);// 差し替える数値

//ファイルの読み込みと3つの値を確認する
//１行ずつファイルを読み込んで、表示する。
if($fp){
	if(!feof($fp)){
		// ヘッダは修正済み、書き出すだけ
		$header = fgets($fp,4096);
		fwrite($wfp, $header);
	}
}
while (! feof ($fp)) {
   $load = fgets ($fp, 4096);

// プロファイルデータ追加

// 19文字目が9かそれ以外を判定
$check = substr($load,18,6);
if($check == '99.999'){
	// 45文字目から９９．９９を入れる
	$addword = wordwrap($load,45," 99.99 ",true);
	//echo $addword;
}else{// ３つの数値のマッチを確認した上で差し込み

	for($i=9; $i<=130; $i++){// １０行目から１３０行目までが必要なデータ
		$dp = substr($decode[$i],11,6);// decode file のP値　以下同
		$dt = substr($decode[$i],20,6);
		$ds = substr($decode[$i],29,6);
		$bpha = substr($decode[$i],83,7);// 全部マッチした場合に差し替えるBpha値。前後にスペース付き

		$op = substr($load,0,6); // 公開用ファイルのP値　以下同
		$ot = substr($load,9,6);
		$os = substr($load,18,6);

		if($op == $dp && $ot == $dt && $os == $ds){
			$addword = wordwrap($load,43,$bpha,true);
		}
	}// end of for loop

}// プロファイルデータ追加ここまで





// echo $addword;

// 2だったら０に直すところの判定。これもプロファイルデータ入れ替え
$check2 = substr($load,42,1);
//echo $check2;
if($check2 == '2'){
	$addword = substr_replace($addword, "0", 42,1);
}

// プロファイルデータ追加の部分まででうまく行っているので一旦テンポラリファイルに書き出す
fwrite($wfp,$addword);

}// end of while
//ファイルを閉じる
fclose ($fp);
fclose ($wfp);

////////////////////////////////////////////////////////////////////////////////////////////////
// ２つめの処理
// テンポラリファイルを開いて、プロファイルデータの入れ替えをする
///////////////////////////////////////////////////////////////////////////////////////////////
if(!($fp= fopen("temp.txt","r"))){
	echo "temp file open error!!";
}

if (! ($wfp = fopen ( "$_GET[n]", "w" ))) {
	echo "ファイルが開けません。";
}


if($fp){
	if(!feof($fp)){
		// ヘッダは修正済みなので書き出すだけ
		$header = fgets($fp,4096);
		fwrite($wfp, $header);
	}
}
while(! feof ($fp)){
	$second = fgets ($fp, 4096);

	// プロファイルデータ入れ替え
	$odocalc = substr($second,34,7);// 999.999以外かつ、下の$ocheckが０以外の時に差し替える値
	$ocheck = substr($second,42,1);// ０か１で１の場合に差し替え
	$opp = substr($second,0,6);// P値
	$ott = substr($second,9,6);// T値
	$oss = substr($second,18,6);// S値

	var_dump($addword);

	for($i=9;$i<=130;$i++){

		$dpp = substr($decode[$i],11,6);// デコードファイルのP値（確認用）
		$dtt = substr($decode[$i],20,6);// デコードファイルのT値
		$dss = substr($decode[$i],29,6);// デコードファイルのS値
		$docalc = substr($decode[$i],184,7);// マッチした時に差し替えるDO_calc_corr(uM/kg)値
		if ($dpp == $opp && $dtt == $ott && $dss == $oss){// 差し替えに使って良い行かチェック
			// odocalcとocheckの値を確認して差し替えるかチェックする
			if($odocalc == '999.999' && $ocheck == '0'){
				// 差し替えない
				$addword2 = $second;
				break;
			}else{
				// 差し替える
				$addword2 = str_replace($odocalc,$docalc,$second);
				break;
			}
		}else{
			// P,T,S値がマッチしない時は使わない行なので上書き
			$addword2 = $second;
		}

	}
	//var_dump($addword2);
	fwrite($wfp,$addword2);

}// end of while

fclose($fp);
fclose($wfp);
?>