S1INBOX HP用データファイルコンバート
=====================
 NEMOフロート、APEXフロートのデータファイルを公開ページ用にコンバートする。

* ヘッダーのArgo_IDからWMO番号に書き換え
* プロファイルデータ入れ替えDo_calc_corr(uM/kg)のデータを入れ替え
* ただし入れ替え前のデータと比較して999.999の場合には入れ替えない
* Bpha(deg)データを追加
 等・・・

使い方
------
###VBスクリプトを用意してあります  ###
    >>rename.vbs  
 _Xammp等ブラウザアクセスできるフォルダにrename(元ファイル）,dec(デコードファイル）,public(公開ファイル）ディレクトリを作成してデータを置きます_

ライセンス
----------
 一応...  
Copyright &copy; 2014 JAMSTEC  
Distributed under the [MIT License][mit].  
 
[MIT]: http://www.opensource.org/licenses/mit-license.php

