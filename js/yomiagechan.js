$(function yomiage(){

    if(! window.speechSynthesis) alert('読み上げ非対応みたい');
    // 音声準備
    $voise        = null;
    $voiseName    = 'Google 日本語';
    $voices       = speechSynthesis.getVoices();
    $synthes      = new SpeechSynthesisUtterance();
    textList      = []; //テキストを入れておく配列
    initFlag      = false; //音声読み込みを複数回行わないように制御

    // SpeechSynthesisUtterance()に時間かかるから様子みる
    $repeat  = setInterval(function() {
        if($synthes){
            $voices  = speechSynthesis.getVoices();
            // $voicesの中身を見てみる F12
            //$.map($voices, function(n, i){console.log(n.name)});
            clearInterval($repeat);
        }
    }, 300);

    function initialize(){
        if( initFlag ) return;
        initFlag = true;

        if(! $voices.length){alert('音声ロード中みたい…'); return;}
        if(! $voices.lenght) $voices = speechSynthesis.getVoices();
        // $voices から $voiseNameを探す
        $voise = $.grep($voices, function(n, i){return n.name == $voiseName})[0];
        if($voise) $synthes.voice = $voise;  // 音声の設定
        speechSynthesis.cancel();            // 停止
        $synthes.rate  = 1.0;                // 速度    0.1-10
        $synthes.pitch = 1.0;                // ピッチ  0.0-2.0
        $synthes.lang  = "ja-JP";            // 日本語に設定
    }

    // 読み上げ
    function say(){
        //読み上げテキストの入った配列が空なら実行しない
        if( textList.length === 0 ) return;
        $synthes.text  = textList.shift();   // 配列から破壊的に取り出す
        console.log( "Browser said : " , $synthes.text );
        speechSynthesis.speak( $synthes );   // 喋れ
        $synthes.onend = say //読み上げが終わったら、もう一度 say method を実行する
    }

    // ボタン動作
    $("#read").click(function(){
        initialize();
        if( textList.length !== 0 ) return;

        let $newsList = $(".article");
        $newsList.each( function(){
            let text = $(this).children(".news-title").text();
            //読み上げテキストを一旦配列に入れる
            textList.push( text );
        });

        say();
    });
});