<?php
//データベースnendoカラムのSとHの和暦を漢字に変換後(.と~以降の値がある場合、それ以降を削除)
$jc = 'S98~H33';

$dot = '.';
$nami = '~';

$jcen = array('S','H');
$jcarray = array('昭和','平成');
$jcstr = str_replace($jcen, $jcarray, $jc);

//.の値がある場合(.)以降を削除
if(strpos($jcstr,$dot)){
$jcstr = substr($jcstr, 0 ,strcspn($jcstr, $dot));
$jcresult = $jcstr."年他";

echo $jcresult;
}
//~の値がある場合(~)以降を削除
elseif(strpos($jcstr,$nami)){
$jcstr = substr($jcstr, 0 ,strcspn($jcstr, $nami));
$jcresult = $jcstr."年他";

echo $jcresult;
}else{
	echo $jcstr;
}
?>